<x-app-layout>
    <div class="space-y-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-semibold text-text">Mi carrito</h1>
            @if($items->isNotEmpty())
                <span class="text-sm text-muted">
                    {{ $items->count() }} {{ $items->count() === 1 ? 'producto' : 'productos' }}
                </span>
            @endif
        </div>

        @if($items->isNotEmpty())

        @php
            $total = $items->sum(fn($item) => $item->quantity * $item->product->price);
            $totalUsd = (isset($usdToArs) && $usdToArs > 0) ? $total / $usdToArs : null;
        @endphp

        <div class="grid gap-6 lg:grid-cols-3 lg:items-start">

            {{-- Lista de items --}}
            <div class="lg:col-span-2 space-y-3">

                @foreach($items as $item)
                @php $subtotal = $item->quantity * $item->product->price; @endphp

                <div class="flex gap-4 rounded-3xl border border-border bg-surface p-4 sm:p-5">

                    {{-- Imagen --}}
                    <a href="{{ route('products.show', $item->product) }}" class="shrink-0">
                        <img src="{{ $item->product->image_url }}"
                            alt="{{ $item->product->name }}"
                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover bg-background"
                            onerror="this.style.display='none'"/>
                    </a>

                    {{-- Contenido --}}
                    <div class="flex-1 min-w-0 flex flex-col gap-3">

                        {{-- Nombre + eliminar --}}
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <a href="{{ route('products.show', $item->product) }}"
                                    class="text-sm sm:text-base font-semibold text-text hover:text-primary transition-colors line-clamp-2">
                                    {{ $item->product->name }}
                                </a>
                                <div class="mt-0.5">
                                    <p class="text-sm text-muted">
                                        ${{ number_format($item->product->price, 0, ',', '.') }} c/u
                                    </p>
                                    @if(isset($usdToArs) && $usdToArs > 0)
                                        <p class="text-xs text-muted/60">
                                            ≈ USD {{ number_format($item->product->price / $usdToArs, 0, ',', '.') }} c/u
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @auth
                                <form method="POST" action="{{ route('cart.destroy', $item) }}" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center justify-center w-8 h-8 rounded-xl text-muted hover:text-error hover:bg-error/10 transition-colors"
                                        title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            @endauth
                        </div>

                        {{-- Cantidad + subtotal --}}
                        <div class="flex flex-wrap items-center justify-between gap-3">

                            @auth
                                {{-- Incrementador +/- con Alpine.js --}}
                                <div x-data="{ qty: {{ $item->quantity }} }">
                                    <form method="POST" action="{{ route('cart.update', $item) }}" x-ref="form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                        <input type="hidden" name="quantity" :value="qty">
                                        <div class="flex items-center gap-1">
                                            <button type="button"
                                                @click="qty > 1 && (qty--, $nextTick(() => $refs.form.submit()))"
                                                :disabled="qty <= 1"
                                                class="flex items-center justify-center w-8 h-8 rounded-xl border border-border bg-background text-text hover:border-primary hover:text-primary transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                                                </svg>
                                            </button>
                                            <span x-text="qty" class="w-8 text-center text-sm font-bold text-text tabular-nums select-none"></span>
                                            <button type="button"
                                                @click="qty < {{ $item->product->stock }} && (qty++, $nextTick(() => $refs.form.submit()))"
                                                :disabled="qty >= {{ $item->product->stock }}"
                                                class="flex items-center justify-center w-8 h-8 rounded-xl border border-border bg-background text-text hover:border-primary hover:text-primary transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <span class="text-sm text-muted">Cant.: <strong class="text-text">{{ $item->quantity }}</strong></span>
                            @endauth

                            <div class="text-right">
                                <p class="text-base sm:text-lg font-semibold text-text">
                                    ${{ number_format($subtotal, 0, ',', '.') }}
                                </p>
                                @if(isset($usdToArs) && $usdToArs > 0)
                                    <p class="text-xs text-muted/60">
                                        ≈ USD {{ number_format($subtotal / $usdToArs, 0, ',', '.') }}
                                    </p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                @endforeach

                @error('quantity')
                    <p class="text-sm text-error px-1">{{ $message }}</p>
                @enderror

                <div class="pt-1">
                    <a href="{{ route('products.index') }}"
                        class="inline-flex items-center gap-2 text-sm text-muted hover:text-primary transition-colors">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                        Seguir comprando
                    </a>
                </div>

            </div>

            {{-- Resumen del pedido --}}
            <div class="lg:sticky lg:top-24">
                <div class="rounded-3xl border border-border bg-surface p-6 space-y-5">

                    <h2 class="text-lg font-semibold text-text">Resumen del pedido</h2>

                    <ul class="space-y-2">
                        @foreach($items as $item)
                            <li class="flex items-start justify-between gap-3 text-sm text-muted">
                                <span class="truncate">{{ $item->product->name }} × {{ $item->quantity }}</span>
                                <span class="shrink-0">${{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-t border-border pt-5 space-y-3">
                        <div class="flex items-end justify-between gap-2">
                            <span class="text-base font-semibold text-text">Total</span>
                            <div class="text-right">
                                <span class="font-oxanium text-3xl sm:text-4xl font-bold text-primary leading-none block">
                                    ${{ number_format($total, 0, ',', '.') }}
                                </span>
                                @if($totalUsd !== null)
                                    <span class="text-xs text-muted/70 mt-0.5 block">≈ USD {{ number_format($totalUsd, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('checkout.index') }}"
                            class="flex items-center justify-center gap-2 w-full rounded-2xl bg-primary px-5 py-4 text-sm font-semibold text-background hover:bg-primary/90 transition-colors">
                            Finalizar compra
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @else
                        <div class="space-y-3">
                            <p class="text-xs text-muted text-center leading-relaxed">
                                Para finalizar tu compra necesitás iniciar sesión o registrarte.
                            </p>
                            <a href="{{ route('login') }}?intended={{ urlencode(route('checkout.index')) }}"
                                class="flex items-center justify-center gap-2 w-full rounded-2xl bg-primary px-5 py-3.5 text-sm font-semibold text-background hover:bg-primary/90 transition-colors">
                                Iniciar sesión para comprar
                            </a>
                            <a href="{{ route('register') }}"
                                class="flex items-center justify-center w-full rounded-2xl border border-border bg-background px-5 py-3.5 text-sm font-medium text-text hover:border-primary/40 transition-colors">
                                Crear cuenta gratis
                            </a>
                        </div>
                    @endauth

                </div>
            </div>

        </div>

        @else

        {{-- Estado vacío --}}
        <div class="flex flex-col items-center justify-center py-20 sm:py-28 text-center space-y-6">
            <div class="flex items-center justify-center w-24 h-24 rounded-3xl bg-surface border border-border">
                <svg class="w-10 h-10 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0z"/>
                </svg>
            </div>
            <div class="space-y-2">
                <h2 class="text-xl font-semibold text-text">Tu carrito está vacío</h2>
                <p class="text-sm text-muted">Explorá el catálogo y sumá los productos que más te gusten.</p>
            </div>
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center gap-2 bg-primary text-background font-semibold text-sm px-6 py-3 rounded-2xl hover:bg-primary/90 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Ver catálogo
            </a>
        </div>

        @endif

    </div>
</x-app-layout>
