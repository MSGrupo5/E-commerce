@extends('seller.layout')

@section('seller-content')
    <div class="mb-8">
        <h1 class="text-h3 font-oxanium font-bold text-text mb-2">
            Bienvenido, {{ auth()->user()->name }}
        </h1>
        <p class="text-muted text-body">Panel de Vendedor de NexusTech</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
        <div class="rounded-3xl border border-border bg-surface p-6 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="2" y="3" width="20" height="14" rx="2" />
                    <path d="M8 21h8" />
                    <path d="M12 17v4" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Productos publicados</p>
                <p class="text-h3 font-bold text-text font-oxanium">{{ $totalProductos }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-border bg-surface p-6 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
            </div>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-muted">Pedidos recibidos</p>
                <p class="text-h3 font-bold text-text font-oxanium">{{ $totalPedidos }}</p>
            </div>
        </div>
    </div>

    <div>
        <h2 class="text-h4 font-oxanium font-bold text-text mb-4">Accesos rápidos</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('seller.productos.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-text font-medium text-small hover:bg-primary/90 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nuevo Producto
            </a>
            <a href="{{ route('seller.productos.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-surface border border-border text-text font-medium text-small hover:bg-background transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="16" rx="2" />
                    <rect x="9" y="9" width="6" height="6" />
                </svg>
                Gestionar Productos
            </a>
            <a href="{{ route('seller.orders') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-surface border border-border text-text font-medium text-small hover:bg-background transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
                Ver Pedidos
            </a>
        </div>
    </div>
@endsection
