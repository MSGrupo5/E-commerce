<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Catálogo de productos
        </h2>
    </x-slot>

    <div class="space-y-6 px-4 sm:px-6 lg:px-0">
        {{-- Page header / summary --}}
        <section x-data="{ mobileFilterOpen: false }" @keydown.escape.window="mobileFilterOpen = false" class="space-y-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-2">
                    <p class="text-sm text-muted">Explora los productos disponibles y elige lo que más te guste.</p>
<!--                     <h1 class="text-2xl font-semibold text-text">Catálogo de productos</h1>
 -->                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full border border-border bg-surface px-3 py-2 text-sm font-medium text-text">
                        {{ $products->total() }} productos
                    </span>
                </div>
            </div>

            {{-- Mobile category filter --}}
            <div class="lg:hidden">
                <div class="border-t border-border pt-4">
                    <button type="button" @click="mobileFilterOpen = !mobileFilterOpen" class="inline-flex w-full items-center justify-between rounded-2xl border border-border bg-surface px-4 py-3 text-left text-sm font-medium text-text transition hover:border-primary/50 hover:bg-primary/5">
                        <span>Filtrar por categoría</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" :class="mobileFilterOpen ? 'rotate-180' : ''">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-cloak x-show="mobileFilterOpen" x-transition class="mt-3">
                        <x-ui.category-filter :frontOnly="true" />
                    </div>
                </div>
            </div>
        </section>

        {{-- Main layout: sidebar + product grid --}}
        <div class="grid gap-4 lg:grid-cols-[280px_minmax(0,1fr)] lg:items-start">
            <aside class="hidden lg:block">
                <x-ui.category-filter :frontOnly="true" />
            </aside>

            <div class="space-y-4">
                {{-- Product cards list --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 justify-items-stretch">
                    @forelse ($products as $product)
                        <x-ui.product-card :product="$product" :isFavorite="false" />
                    @empty
                        <div class="col-span-full rounded-3xl border border-border bg-surface p-8 text-center">
                            <p class="text-base font-medium text-text">No hay productos disponibles en este momento.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination controls --}}
                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
