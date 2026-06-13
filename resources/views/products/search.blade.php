<x-app-layout>
    <div class="space-y-6 px-4 sm:px-6 lg:px-0">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div class="space-y-2">
                <a href="{{ route('products.index') }}" class="text-sm font-medium text-primary hover:underline">&larr; Volver al catálogo</a>
                <h1 class="text-2xl font-semibold text-text">
                    Resultados de búsqueda para <span class="text-primary">"{{ $query }}"</span>
                </h1>
                <p class="text-sm text-muted">{{ $products->total() }} {{ Str::plural('producto', $products->total()) }} encontrados</p>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-items-stretch">
                @foreach ($products as $product)
                    <x-ui.product-card :product="$product" :isFavorite="false" />
                @endforeach
            </div>

            <div class="flex justify-center">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <div class="rounded-3xl border border-border bg-surface p-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-background">
                    <svg class="h-8 w-8 text-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </div>
                <p class="text-base font-medium text-text">No se encontraron productos para <span class="text-primary">"{{ $query }}"</span></p>
                <p class="mt-1 text-sm text-muted">Intenta con otros términos de búsqueda.</p>
            </div>
        @endif
    </div>
</x-app-layout>
