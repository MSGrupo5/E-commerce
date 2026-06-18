@extends('seller.layout')

@section('seller-content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-h3 font-oxanium font-bold text-text">Mis Productos</h1>
            <p class="text-muted text-small">Gestioná tu catálogo de productos</p>
        </div>
        <a href="{{ route('seller.productos.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-text font-medium text-small hover:bg-primary/90 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Nuevo Producto
        </a>
    </div>

    @if($products->isEmpty())
        <div class="rounded-3xl border border-border bg-surface p-12 text-center">
            <svg class="w-16 h-16 text-muted/40 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <rect x="4" y="4" width="16" height="16" rx="2" />
                <rect x="9" y="9" width="6" height="6" />
            </svg>
            <p class="text-h5 font-oxanium font-semibold text-text mb-1">No tenés productos todavía</p>
            <p class="text-muted text-small mb-6">Publicá tu primer producto para empezar a vender.</p>
            <a href="{{ route('seller.productos.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-text font-medium text-small hover:bg-primary/90 transition-colors">
                Publicar Producto
            </a>
        </div>
    @else
        <div class="overflow-x-auto rounded-2xl border border-border">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface border-b border-border">
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Producto</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Categoría</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Precio</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Stock</th>
                        <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="border-b border-border last:border-b-0 hover:bg-background/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-10 h-10 rounded-lg object-cover border border-border">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-surface border border-border flex items-center justify-center text-muted">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <rect x="4" y="4" width="16" height="16" rx="2" />
                                                <rect x="9" y="9" width="6" height="6" />
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="text-text text-small font-medium">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-muted text-small">{{ $product->category?->name ?? 'Sin categoría' }}</td>
                            <td class="px-4 py-3 text-text text-small font-medium">${{ number_format($product->price, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1 text-small {{ $product->inStock() ? 'text-success' : 'text-error' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $product->inStock() ? 'bg-success' : 'bg-error' }}"></span>
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('seller.productos.edit', $product) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-small font-medium hover:bg-primary/20 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                        Editar
                                    </a>
                                    <form action="{{ route('seller.productos.destroy', $product) }}" method="POST" onsubmit="return confirm('¿Eliminar este producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-error/10 text-error text-small font-medium hover:bg-error/20 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
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
@endsection
