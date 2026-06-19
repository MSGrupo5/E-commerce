@extends('layouts.admin')

@section('content')
    <div class="mb-8">
        <h1 class="text-h3 font-oxanium font-bold text-text mb-1">
            Bienvenido, {{ auth()->user()->name }}
        </h1>
        <p class="text-muted text-small">Panel de administración · Marketo</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
        <x-ui.stat-card
            label="Productos"
            :value="$totalProductos"
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>'
        />
        <x-ui.stat-card
            label="Pedidos"
            :value="$totalPedidos"
            icon='<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>'
        />
        <x-ui.stat-card
            label="Usuarios"
            :value="$totalUsuarios"
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>'
        />
    </div>

    <div>
        <h2 class="text-h5 font-oxanium font-semibold text-text mb-4">Accesos rápidos</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.productos.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-background font-semibold text-small hover:bg-primary/90 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
                Gestionar Productos
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-surface border border-border text-text font-medium text-small hover:bg-background transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"/>
                </svg>
                Gestionar Usuarios
            </a>
        </div>
    </div>
@endsection
