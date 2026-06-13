<x-app-layout>
    <div class="space-y-6 px-4 sm:px-6 lg:px-0">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="space-y-2">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-primary hover:underline">&larr; Volver al catálogo</a>
                <h1 class="text-3xl font-semibold text-text sm:text-4xl">Detalle del producto</h1>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] items-start">
            <div class="rounded-[32px] border border-border bg-surface p-4 sm:p-6">
                <div class="overflow-hidden rounded-[28px] bg-background">
                    <img
                        src="{{ $product->image }}"
                        alt="{{ $product->name }}"
                        class="h-[420px] w-full object-contain object-center"
                        onerror="this.src='https://via.placeholder.com/720x420?text=Sin+imagen'"
                    />
                </div>
            </div>

            <div class="space-y-6 rounded-[32px] border border-border bg-surface p-6 sm:p-8">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between gap-4">
                        <span class="inline-flex items-center rounded-full border border-border bg-background/80 px-3 py-2 text-sm font-semibold uppercase tracking-[0.2em] text-muted">
                            {{ optional($product->category)->name ?? 'Sin categoría' }}
                        </span>

                        <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-border bg-background/90 px-4 py-3 text-sm font-semibold text-text transition hover:border-primary/70 hover:text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                            Favorito
                        </button>
                    </div>

                    <div class="space-y-2">
                        <h2 class="text-4xl font-semibold text-text sm:text-5xl">{{ $product->name }}</h2>
                        <p class="text-sm text-muted">SKU #{{ $product->id }}</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-3xl font-bold text-primary">${{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="mt-1 text-sm text-muted">Precio final</p>
                        </div>
                        <div class="rounded-3xl border border-border bg-background px-4 py-3 text-sm font-semibold {{ $product->stock > 0 ? 'text-primary' : 'text-error' }}">
                            {{ $product->stock > 0 ? $product->stock . ' unidades disponibles' : 'Agotado' }}
                        </div>
                    </div>
                </div>

                <div class="space-y-3 rounded-3xl border border-border bg-background/80 p-5">
                    <h3 class="text-lg font-semibold text-text">Descripción</h3>
                    <p class="text-sm leading-relaxed text-muted">{{ $product->description ?? 'Este producto no tiene una descripción disponible.' }}</p>
                </div>

                <div class="space-y-3">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full rounded-2xl bg-primary px-5 py-4 text-base font-semibold text-background transition hover:bg-primary/90">
                                Agregar al carrito
                            </button>
                        </form>
                    @else
                        <button type="button" disabled class="w-full rounded-2xl bg-border px-5 py-4 text-base font-semibold text-muted cursor-not-allowed">
                            Sin stock
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
