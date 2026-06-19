<x-app-layout>
    <div class="space-y-6">

        <section x-data="{ mobileFilterOpen: false }" @keydown.escape.window="mobileFilterOpen = false" class="space-y-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-1">
                    <h1 class="text-2xl font-oxanium font-bold text-text">
                        {{ request('search') ? 'Resultados para "' . request('search') . '"' : 'Catálogo' }}
                    </h1>
                    <p class="text-sm text-muted">Explorá los productos disponibles y elegí lo que más te guste.</p>
                </div>
                <span class="rounded-full border border-border bg-surface px-3 py-2 text-sm font-medium text-text self-start sm:self-auto">
                    {{ $products->total() }} {{ $products->total() === 1 ? 'producto' : 'productos' }}
                </span>
            </div>

            {{-- Filtro mobile --}}
            <div class="lg:hidden border-t border-border pt-4">
                <button type="button" @click="mobileFilterOpen = !mobileFilterOpen"
                    class="inline-flex w-full items-center justify-between rounded-2xl border border-border bg-surface px-4 py-3 text-left text-sm font-medium text-text transition hover:border-primary/50 hover:bg-primary/5">
                    <span>Filtrar por categoría</span>
                    <svg class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="mobileFilterOpen ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-cloak x-show="mobileFilterOpen" x-transition class="mt-3">
                    <x-ui.category-filter :categories="$categories" :categorySlug="$categorySlug" :frontOnly="false" />
                </div>
            </div>
        </section>

        @isset($activeSeller)
            <div class="rounded-2xl border border-primary/20 bg-primary/5 px-4 py-3 flex items-center justify-between">
                <p class="text-sm text-text">
                    Estás viendo los productos de <span class="font-semibold text-primary">{{ $activeSeller->name }}</span>
                </p>
                <a href="{{ route('products.index') }}" class="text-sm text-muted hover:text-text transition-colors shrink-0 ml-4">
                    &times; Ver todos
                </a>
            </div>
        @endisset
        <div class="grid gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:items-start">

            <aside class="hidden lg:block">
                <x-ui.category-filter :categories="$categories" :categorySlug="$categorySlug" :frontOnly="false" />
            </aside>

            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-items-stretch">
                    @forelse ($products as $product)
                        <x-ui.product-card :product="$product" :isFavorite="$favoriteIds->contains($product->id)" />
                    @empty
                        <div class="col-span-full rounded-3xl border border-border bg-surface p-12 text-center">
                            @if(request('search'))
                                <p class="text-base font-medium text-text mb-2">
                                    No se encontraron productos para <span class="text-primary">"{{ request('search') }}"</span>
                                </p>
                                <a href="{{ route('products.index') }}" class="text-sm font-medium text-primary hover:underline">
                                    &larr; Ver todos los productos
                                </a>
                            @else
                                <p class="text-base font-medium text-text">No hay productos disponibles en este momento.</p>
                            @endif
                        </div>
                    @endforelse
                </div>

                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
