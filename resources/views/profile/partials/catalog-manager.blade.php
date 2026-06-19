@php
    $categories = \App\Models\Category::all();
    $userProducts = auth()->user()->products()->with('category')->latest()->get();
@endphp

<section x-data="{ editing: null, storeUrl: '{{ route('profile.catalog.store') }}' }">
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-text">
            Mi catálogo
        </h2>
        <p class="mt-1 text-sm text-muted">
            Administrá los productos que publicás en la tienda.
        </p>
    </header>

    {{-- Success messages --}}
    @if (session('catalog-status') === 'product-created')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="mb-4 rounded-2xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">
            Producto creado correctamente.
        </p>
    @elseif (session('catalog-status') === 'product-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="mb-4 rounded-2xl border border-success/20 bg-success/5 px-4 py-3 text-sm text-success">
            Producto actualizado correctamente.
        </p>
    @elseif (session('catalog-status') === 'product-deleted')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="mb-4 rounded-2xl border border-error/20 bg-error/5 px-4 py-3 text-sm text-error">
            Producto eliminado.
        </p>
    @endif

    {{-- Product form --}}
    <div class="mb-8 rounded-2xl border border-border bg-background p-5">
        <h3 class="mb-4 text-sm font-semibold text-text" x-text="editing ? 'Editar producto' : 'Nuevo producto'"></h3>

        <form method="post" x-bind:action="editing ? storeUrl + '/' + editing : storeUrl" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="_method" x-bind:value="editing ? 'PATCH' : 'POST'">

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <x-input-label for="cat-name" value="Nombre del producto" />
                    <x-text-input id="cat-name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="cat-category" value="Categoría" />
                    <select id="cat-category" name="category_id" required
                        class="mt-1 block w-full rounded-2xl border border-border bg-surface px-4 py-3 text-sm text-text shadow-sm transition focus:border-primary/50 focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="" class="bg-surface">Seleccionar categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" class="bg-surface" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                </div>

                <div>
                    <x-input-label for="cat-price" value="Precio" />
                    <x-text-input id="cat-price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price')" required placeholder="0.00" />
                    <x-input-error class="mt-2" :messages="$errors->get('price')" />
                </div>

                <div>
                    <x-input-label for="cat-stock" value="Stock" />
                    <x-text-input id="cat-stock" name="stock" type="number" min="0" class="mt-1 block w-full" :value="old('stock')" required placeholder="0" />
                    <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                </div>
            </div>

            <div>
                <x-input-label for="cat-description" value="Descripción" />
                <textarea id="cat-description" name="description" rows="3"
                    class="mt-1 block w-full rounded-2xl border border-border bg-surface px-4 py-3 text-sm text-text placeholder-muted shadow-sm transition focus:border-primary/50 focus:outline-none focus:ring-2 focus:ring-primary/20">{{ old('description') }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <div>
                <x-input-label for="cat-image" value="Imagen del producto" />
                <input id="cat-image" name="image" type="file" accept="image/jpeg,image/png,image/jpg,image/webp"
                    class="mt-1 block w-full text-sm text-muted file:mr-4 file:rounded-2xl file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary hover:file:bg-primary/20" />
                <p class="mt-1 text-xs text-muted">Formatos: jpg, png, webp — Máx. 2MB</p>
                <x-input-error class="mt-2" :messages="$errors->get('image')" />
            </div>

            <div class="flex items-center gap-3">
                <x-primary-button x-text="editing ? 'Actualizar producto' : 'Publicar producto'"></x-primary-button>
                <button type="button" x-show="editing" @click="editing = null; document.getElementById('cat-name').closest('form').reset()" class="rounded-2xl border border-border bg-surface px-6 py-3 text-sm font-semibold text-text transition hover:bg-background">
                    Cancelar
                </button>
            </div>
        </form>
    </div>

    {{-- Product list --}}
    @if ($userProducts->isEmpty())
        <div class="rounded-2xl border border-border bg-background p-8 text-center">
            <svg class="mx-auto mb-3 h-10 w-10 text-muted" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
            </svg>
            <p class="text-sm text-muted">Todavía no publicaste productos.</p>
            <p class="text-xs text-muted mt-1">Completá el formulario de arriba para agregar tu primer producto.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($userProducts as $product)
                <div class="flex items-center gap-4 rounded-2xl border border-border bg-background p-4">
                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-surface">
                        @if ($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover" onerror="this.style.display='none'" />
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-text">{{ $product->name }}</p>
                        <p class="text-xs text-muted">
                            {{ optional($product->category)->name ?? 'Sin categoría' }} ·
                            ${{ number_format($product->price, 0, ',', '.') }} ·
                            <span class="{{ $product->inStock() ? 'text-success' : 'text-error' }}">
                                {{ $product->inStock() ? $product->stock . ' en stock' : 'Agotado' }}
                            </span>
                        </p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <button type="button"
                            @click="editing = {{ $product->id }}; $nextTick(() => { document.getElementById('cat-name').value = '{{ $product->name }}'; document.getElementById('cat-description').value = '{{ $product->description }}'; document.getElementById('cat-price').value = '{{ $product->price }}'; document.getElementById('cat-stock').value = '{{ $product->stock }}'; document.getElementById('cat-category').value = '{{ $product->category_id }}'; })"
                            class="rounded-xl border border-border bg-surface p-2 text-muted transition hover:text-text hover:bg-background">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </button>
                        <form method="post" action="{{ route('profile.catalog.destroy', $product) }}" onsubmit="return confirm('¿Eliminar este producto?')">
                            @csrf
                            @method('delete')
                            <button type="submit" class="rounded-xl border border-border bg-surface p-2 text-muted transition hover:text-error hover:bg-error/5">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</section>