<x-seller-layout>
    <div x-data="{ modal: false, deleteUrl: '', productName: '' }">

    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-h3 font-oxanium font-bold text-text">Mis Productos</h1>
            <p class="text-muted text-small">Gestioná tu catálogo de productos</p>
        </div>
        <a href="{{ route('seller.productos.create') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-background font-semibold text-small hover:bg-primary/90 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Producto
        </a>
    </div>

    @if($products->isEmpty())
        <x-ui.empty-state
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>'
            title="No tenés productos todavía"
            description="Publicá tu primer producto para empezar a vender en Marketo."
        >
            <a href="{{ route('seller.productos.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-background font-semibold text-small hover:bg-primary/90 transition-colors">
                Publicar Producto
            </a>
        </x-ui.empty-state>
    @else
        <div class="overflow-x-auto rounded-2xl border border-border -mx-1">
            <table class="w-full text-left min-w-[520px]">
                <thead>
                    <tr class="bg-background border-b border-border">
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Producto</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted hidden md:table-cell">Categoría</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Precio</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Stock</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted hidden sm:table-cell">Estado</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="border-b border-border last:border-b-0 hover:bg-background/40 transition-colors group">
                            <td class="px-4 py-3">
                                <a href="{{ route('seller.productos.edit', $product) }}" class="flex items-center gap-3">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}"
                                            alt="{{ $product->name }}"
                                            class="w-9 h-9 rounded-lg object-cover border border-border shrink-0"
                                            onerror="this.style.display='none'">
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-background border border-border flex items-center justify-center text-muted shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="text-text text-small font-medium line-clamp-1 max-w-[120px] sm:max-w-none group-hover:text-primary transition-colors">{{ $product->name }}</span>
                                </a>
                            </td>

                            <td class="px-4 py-3 text-muted text-small hidden md:table-cell">
                                {{ $product->category?->name ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-text text-small font-semibold whitespace-nowrap">
                                ${{ number_format($product->price, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 text-small {{ $product->inStock() ? 'text-success' : 'text-error' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->inStock() ? 'bg-success' : 'bg-error' }}"></span>
                                    {{ $product->stock }}
                                </span>
                            </td>

                            <td class="px-4 py-3 hidden sm:table-cell">
                                <form action="{{ route('seller.productos.toggle-activo', $product) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium transition-colors
                                            {{ $product->is_active
                                                ? 'bg-success/10 text-success hover:bg-success/20'
                                                : 'bg-muted/10 text-muted hover:bg-muted/20' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-success' : 'bg-muted' }}"></span>
                                        {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                    </button>
                                </form>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('seller.productos.edit', $product) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-small font-medium hover:bg-primary/20 transition-colors">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        </svg>
                                        Editar
                                    </a>
                                    <button type="button"
                                        @click="deleteUrl = '{{ route('seller.productos.destroy', $product) }}'; productName = '{{ addslashes($product->name) }}'; modal = true"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-error/10 text-error text-small font-medium hover:bg-error/20 transition-colors">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    @endif

    {{-- Formulario oculto para confirmar eliminación --}}
    <form id="delete-form" method="POST" :action="deleteUrl" x-ref="deleteForm">
        @csrf
        @method('DELETE')
    </form>

    {{-- Modal de confirmación --}}
    <div
        x-show="modal"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        @keydown.escape.window="modal = false">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="modal = false"></div>

        {{-- Card --}}
        <div
            x-show="modal"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative w-full max-w-md rounded-3xl border border-border bg-surface p-6 shadow-[0_32px_80px_rgba(0,0,0,0.5)]">

            {{-- Ícono --}}
            <div class="flex justify-center mb-5">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-error/10 border border-error/20">
                    <svg class="h-7 w-7 text-error" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </div>
            </div>

            {{-- Texto --}}
            <div class="text-center space-y-2 mb-6">
                <h3 class="text-lg font-semibold text-text">¿Estás seguro?</h3>
                <p class="text-sm text-muted leading-relaxed">
                    Estás por eliminar
                    <span class="font-medium text-text" x-text="'«' + productName + '»'"></span>.
                    Esta acción no se puede deshacer.
                </p>
            </div>

            {{-- Botones --}}
            <div class="flex gap-3">
                <button type="button"
                    @click="modal = false"
                    class="flex-1 rounded-2xl border border-border bg-background px-4 py-3 text-sm font-medium text-text hover:bg-surface transition-colors">
                    Cancelar
                </button>
                <button type="button"
                    @click="$refs.deleteForm.submit()"
                    class="flex-1 rounded-2xl bg-error px-4 py-3 text-sm font-semibold text-white hover:bg-error/90 transition-colors">
                    Confirmar
                </button>
            </div>

        </div>
    </div>

    </div>
</x-seller-layout>
