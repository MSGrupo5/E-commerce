{{-- resources/views/components/ui/product-card.blade.php --}}
{{-- Props: $product (App\Models\Product), $isFavorite (bool) --}}
@props(['product', 'isFavorite' => false])
@php
    $image = $product->image_url;
    $categoryName = optional($product->category)->name ?? 'Sin categoría';
    $isOutOfStock = (isset($product->stock) ? intval($product->stock) : 0) <= 0;
    $badge = $product->badge ?? null;
    $originalPrice = $product->original_price ?? null;
@endphp

<div class="w-full max-w-full sm:max-w-[260px] rounded-[32px] border border-border bg-surface shadow-[0_24px_64px_rgba(0,0,0,0.25)] overflow-hidden transition-all duration-300 hover:border-primary/40 hover:-translate-y-0.5">
    <div class="relative overflow-hidden bg-background">
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
            @if(Route::has('favorites.toggle'))
                <form action="{{ route('favorites.toggle', $product) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl transition hover:bg-primary/10 {{ $favBtnClasses }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                </form>
            @else
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-surface/90 border border-border text-muted cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <div class="space-y-4 p-4 sm:p-5">
        <div class="space-y-2">
            <p class="inline-flex rounded-full border border-border bg-background/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.22em] text-muted">
                {{ $categoryName }}
            </p>
            <a href="{{ Route::has('products.show') ? route('products.show', $product) : url('/products/'.$product->id) }}" class="block text-base font-semibold text-text leading-snug hover:text-primary transition-colors">
                {{ $product->name }}
            </a>
            @if($product->relationLoaded('seller') && $product->seller)
                <p class="text-xs text-muted mt-1">
                    por
                    <a href="{{ route('products.index', ['seller' => $product->seller->id]) }}" class="text-text/70 font-medium hover:text-primary transition-colors">
                        {{ $product->seller->name }}
                    </a>
                </p>
            @endif
        </div>

        <div class="flex items-center justify-between gap-3">
            <div class="space-y-1">
                <div class="flex items-end gap-2">
                    <span class="text-xl font-bold text-primary">${{ number_format($product->price, 0, ',', '.') }}</span>
                    @if($originalPrice)
                        <span class="text-sm text-muted line-through">${{ number_format($originalPrice, 0, ',', '.') }}</span>
                    @endif
                </div>
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
