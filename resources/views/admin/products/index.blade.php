@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-h3 font-oxanium font-bold text-text">Productos</h1>
            <p class="text-muted text-body">Total: {{ $products->total() }}</p>
        </div>

        <form method="GET" action="{{ route('admin.productos.index') }}" class="flex items-center gap-3">
            <select name="category_id"
                onchange="this.form.submit()"
                class="rounded-xl border border-border bg-surface px-4 py-2.5 text-small text-text outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/30 transition-colors">
                <option value="">Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            @if(request('category_id'))
                <a href="{{ route('admin.productos.index') }}"
                    class="inline-flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-muted hover:text-text hover:bg-surface border border-border text-small transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <div class="rounded-3xl border border-border bg-surface overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-small min-w-[640px]">
                <thead class="sticky top-0 z-10 bg-surface">
                    <tr class="border-b border-border text-muted text-[11px] uppercase tracking-[0.15em]">
                        <th class="px-5 py-4 font-semibold">Producto</th>
                        <th class="px-5 py-4 font-semibold">Categoría</th>
                        <th class="px-5 py-4 font-semibold">Precio</th>
                        <th class="px-5 py-4 font-semibold">Stock</th>
                        <th class="px-5 py-4 font-semibold">Vendedor</th>
                        <th class="px-5 py-4 font-semibold text-right">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($products as $product)
                        <tr class="hover:bg-background/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="w-10 h-10 rounded-lg object-cover bg-background shrink-0"
                                        onerror="this.style.display='none'">
                                    <span class="font-medium text-text truncate">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-muted">{{ optional($product->category)->name }}</td>
                            <td class="px-5 py-4 text-text font-medium">${{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <span class="{{ $product->inStock() ? 'text-success' : 'text-error' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-muted truncate">{{ optional($product->seller)->name ?? '—' }}</td>
                            <td class="px-5 py-4 text-right">
                                <form action="{{ route('admin.productos.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar «{{ $product->name }}»?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-error hover:bg-error/10 text-[12px] font-medium transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-muted">No hay productos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $products->withQueryString()->links() }}
    </div>
@endsection
