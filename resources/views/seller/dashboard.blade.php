<x-seller-layout>
    <div class="mb-8">
        <h1 class="text-h3 font-oxanium font-bold text-text mb-1">
            Bienvenido, {{ auth()->user()->name }}
        </h1>
        <p class="text-muted text-small">Panel de vendedor · Marketo</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-10">
        <x-ui.stat-card
            label="Productos publicados"
            :value="$productsCount"
            icon='<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/>'
        />
        <x-ui.stat-card
            label="Pedidos recibidos"
            :value="$salesCount"
            icon='<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>'
        />
    </div>

    <div>
        <h2 class="text-h5 font-oxanium font-semibold text-text mb-4">Accesos rápidos</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('seller.productos.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-background font-semibold text-small hover:bg-primary/90 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nuevo Producto
            </a>
            <a href="{{ route('seller.productos.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-background border border-border text-text font-medium text-small hover:border-primary/40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
                Gestionar Productos
            </a>
            <a href="{{ route('seller.orders') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-background border border-border text-text font-medium text-small hover:border-primary/40 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="8" cy="21" r="1" />
                    <circle cx="19" cy="21" r="1" />
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                </svg>
                Ver Pedidos
            </a>
        </div>
    </div>
</x-seller-layout>
