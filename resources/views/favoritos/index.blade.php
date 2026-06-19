<x-app-layout>
    <div class="space-y-6">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="space-y-1">
                <h1 class="text-2xl font-oxanium font-bold text-text">Mis favoritos</h1>
                <p class="text-sm text-muted">Productos que guardaste para más adelante.</p>
            </div>
            @if($favorites->isNotEmpty())
                <span class="rounded-full border border-border bg-surface px-3 py-2 text-sm font-medium text-text self-start sm:self-auto">
                    {{ $favorites->count() }} {{ $favorites->count() === 1 ? 'producto' : 'productos' }}
                </span>
            @endif
        </div>

        <x-ui.alert type="success" :message="session('success')" />

        @if($favorites->isNotEmpty())
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-items-stretch">
                @foreach($favorites as $product)
                    <x-ui.product-card :product="$product" :isFavorite="$favoriteIds->contains($product->id)" />
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 sm:py-28 text-center space-y-6">
                <div class="flex items-center justify-center w-24 h-24 rounded-3xl bg-surface border border-border">
                    <svg class="w-10 h-10 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h2 class="text-xl font-semibold text-text">No tenés favoritos aún</h2>
                    <p class="text-sm text-muted">Explorá el catálogo y guardá los productos que más te gusten.</p>
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
