{{-- resources/views/components/ui/product-card.blade.php --}}
{{-- Props: $product (App\Models\Product), $isFavorite (bool) --}}
@props(['product', 'isFavorite' => false])
@php
    $image = $product->image_url ?? '';
    $categoryName = optional($product->category)->name ?? 'Sin categoría';
    $isOutOfStock = (isset($product->stock) ? intval($product->stock) : 0) <= 0;
    $badge = $product->badge ?? null;
    $originalPrice = $product->original_price ?? null;
    $priceUsd = isset($usdToArs) && $usdToArs > 0
        ? number_format($product->price / $usdToArs, 0, ',', '.')
        : null;
@endphp

<div class="w-full max-w-full sm:max-w-[260px] h-full flex flex-col rounded-[32px] border border-border bg-surface shadow-[0_24px_64px_rgba(0,0,0,0.25)] overflow-hidden transition-all duration-300 hover:border-primary/40 hover:-translate-y-0.5">
    <div class="relative overflow-hidden bg-background shrink-0">
        <a href="{{ Route::has('products.show') ? route('products.show', $product) : url('/products/'.$product->id) }}" class="block h-[170px] sm:h-[180px]">
            <div class="absolute inset-0 bg-gradient-to-b from-[#11121a]/40 to-transparent pointer-events-none"></div>
            <img src="{{ $image }}" alt="{{ $product->name }}" class="w-full h-full object-contain object-center transition-transform duration-500 hover:scale-105" onerror="this.style.display='none'" />
        </a>

        @if($badge)
            <span class="absolute top-4 left-4 rounded-full border border-accent/25 bg-accent/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-accent">
                {{ $badge }}
            </span>
        @endif

        <div class="absolute top-4 right-4">
            @php
                $favBtnClasses = $isFavorite ? 'bg-error/15 border border-error/30 text-error' : 'bg-surface/90 border border-border text-muted';
            @endphp
            @auth
                <form action="{{ route('favorites.toggle', $product) }}" method="POST">
                    @csrf
                    <button type="submit" title="{{ $isFavorite ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl transition hover:bg-error/10 hover:border-error/30 hover:text-error {{ $favBtnClasses }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                            fill="{{ $isFavorite ? 'currentColor' : 'none' }}"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" title="Iniciar sesión para guardar favoritos"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-surface/90 border border-border text-muted hover:border-error/30 hover:text-error hover:bg-error/10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </a>
            @endauth
        </div>
    </div>

    <div class="flex flex-1 flex-col gap-4 p-4 sm:p-5">
        <div class="space-y-2">
            <p class="inline-flex max-w-full truncate rounded-full border border-border bg-background/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-muted">
                {{ $categoryName }}
            </p>
            <a href="{{ Route::has('products.show') ? route('products.show', $product) : url('/products/'.$product->id) }}" class="block h-11 text-base font-semibold text-text leading-snug line-clamp-2 hover:text-primary transition-colors">
                {{ $product->name }}
            </a>
            @if($product->relationLoaded('seller') && $product->seller)
                <p class="truncate text-xs text-muted mt-1">
                    por
                    <a href="{{ route('products.index', ['seller' => $product->seller->id]) }}" class="text-text/70 font-medium hover:text-primary transition-colors">
                        {{ $product->seller->name }}
                    </a>
                </p>
            @endif
            <p class="h-8 text-xs leading-snug text-muted/80 line-clamp-2">
                {{ $product->description ?? 'Sin descripción disponible.' }}
            </p>
        </div>

        <div class="mt-auto space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <div class="flex items-end gap-2">
                        <span class="text-xl font-bold text-primary">${{ number_format($product->price, 0, ',', '.') }}</span>
                        @if($originalPrice)
                            <span class="text-sm text-muted line-through">${{ number_format($originalPrice, 0, ',', '.') }}</span>
                        @endif
                    </div>
                    @if($priceUsd)
                        <p class="text-[11px] text-muted/70">≈ USD {{ $priceUsd }}</p>
                    @endif
                    <p class="text-xs text-muted">
                        {{ $isOutOfStock ? 'Agotado' : 'En stock' }}
                    </p>
                </div>
            </div>

            @if(!$isOutOfStock)
                @if(Route::has('cart.add'))
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full rounded-2xl bg-primary px-4 py-3 text-sm font-semibold text-background transition hover:bg-primary/90">
                            Agregar al carrito
                        </button>
                    </form>
                @else
                    <button type="button" class="w-full rounded-2xl bg-primary/30 px-4 py-3 text-sm font-semibold text-muted cursor-not-allowed">
                        Agregar al carrito
                    </button>
                @endif
            @else
                <button disabled class="w-full rounded-2xl bg-border px-4 py-3 text-sm font-semibold text-muted cursor-not-allowed">
                    Sin stock
                </button>
            @endif
        </div>
    </div>
</div>
