@extends('seller.layout')

@section('seller-content')
    <div class="mb-6">
        <a href="{{ route('seller.productos.index') }}" class="inline-flex items-center gap-1 text-muted hover:text-text text-small transition-colors mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Volver a mis productos
        </a>
        <h1 class="text-h3 font-oxanium font-bold text-text">
            {{ isset($product) ? 'Editar Producto' : 'Nuevo Producto' }}
        </h1>
    </div>

    <div class="max-w-2xl rounded-3xl border border-border bg-surface p-6 md:p-8">
        <form action="{{ isset($product) ? route('seller.productos.update', $product) : route('seller.productos.store') }}" method="POST">
            @csrf
            @if(isset($product))
                @method('PATCH')
            @endif

            <div class="space-y-5">
                <div>
                    <label for="name" class="block text-small font-medium text-text mb-1.5">Nombre del producto</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required
                        class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted"
                        placeholder="Ej: RTX 5090">
                    @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-small font-medium text-text mb-1.5">Descripción</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted"
                        placeholder="Describí el producto...">{{ old('description', $product->description ?? '') }}</textarea>
                    @error('description') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-small font-medium text-text mb-1.5">Precio ($)</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price', $product->price ?? '') }}" required
                            class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted"
                            placeholder="0.00">
                        @error('price') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="stock" class="block text-small font-medium text-text mb-1.5">Stock</label>
                        <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $product->stock ?? '') }}" required
                            class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted"
                            placeholder="0">
                        @error('stock') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="category_id" class="block text-small font-medium text-text mb-1.5">Categoría</label>
                    <select id="category_id" name="category_id" required
                        class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="image" class="block text-small font-medium text-text mb-1.5">URL de imagen</label>
                    <input type="url" id="image" name="image" value="{{ old('image', $product->image ?? '') }}"
                        class="w-full bg-background border border-border rounded-xl px-4 py-2.5 text-text text-small focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted"
                        placeholder="https://ejemplo.com/imagen.jpg">
                    @error('image') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-primary text-text font-medium text-small hover:bg-primary/90 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                        </svg>
                        {{ isset($product) ? 'Guardar cambios' : 'Publicar producto' }}
                    </button>
                    <a href="{{ route('seller.productos.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-surface border border-border text-text font-medium text-small hover:bg-background transition-colors">
                        Cancelar
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
