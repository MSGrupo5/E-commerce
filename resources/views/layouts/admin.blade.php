<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketo — Panel de Administración</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ sidebarOpen: false }"
    class="bg-background text-text font-jakarta antialiased h-screen flex relative overflow-hidden">

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
        class="fixed inset-0 bg-background/80 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

    <x-ui.loader />

    {{-- ─── Sidebar ─────────────────────────────────────────────────── --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-surface border-r border-border/30 flex flex-col shrink-0 transition-transform duration-300 ease-in-out">

        {{-- Logo --}}
        <div class="h-16 flex items-center justify-between px-5 border-b border-border">
            <a href="{{ route('home') }}" class="focus:outline-none">
                <x-app.logo />
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-muted hover:text-text focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 18 12-12m0 12L6 6" />
                </svg>
            </button>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 py-4 overflow-y-auto">
            <p class="px-6 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-muted/60">Administración</p>
            <ul class="space-y-0.5 px-3">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center px-3 py-2.5 gap-3 text-sm rounded-xl transition-colors relative
                            {{ request()->routeIs('admin.dashboard') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/60' }}">
                        @if(request()->routeIs('admin.dashboard'))
                            <div class="absolute left-0 top-2 bottom-2 w-0.5 -ml-3 bg-primary rounded-full"></div>
                        @endif
                        <svg class="w-4.5 h-4.5 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="9" rx="1" />
                            <rect x="14" y="3" width="7" height="5" rx="1" />
                            <rect x="14" y="12" width="7" height="9" rx="1" />
                            <rect x="3" y="16" width="7" height="5" rx="1" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.productos.index') }}"
                        class="flex items-center px-3 py-2.5 gap-3 text-sm rounded-xl transition-colors relative
                            {{ request()->routeIs('admin.productos.*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/60' }}">
                        @if(request()->routeIs('admin.productos.*'))
                            <div class="absolute left-0 top-2 bottom-2 w-0.5 -ml-3 bg-primary rounded-full"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <span>Productos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categorias.index') }}"
                        class="flex items-center px-3 py-2.5 gap-3 text-sm rounded-xl transition-colors relative
                            {{ request()->routeIs('admin.categorias.*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/60' }}">
                        @if(request()->routeIs('admin.categorias.*'))
                            <div class="absolute left-0 top-2 bottom-2 w-0.5 -ml-3 bg-primary rounded-full"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        <span>Categorías</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-3 py-2.5 gap-3 text-sm rounded-xl transition-colors relative
                            {{ request()->routeIs('admin.users.*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/60' }}">
                        @if(request()->routeIs('admin.users.*'))
                            <div class="absolute left-0 top-2 bottom-2 w-0.5 -ml-3 bg-primary rounded-full"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span>Usuarios</span>
                    </a>
                </li>
            </ul>

            <div class="mt-6 px-3">
                <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.2em] text-muted/60">Tienda</p>
                <a href="{{ route('home') }}"
                    class="flex items-center px-3 py-2.5 gap-3 text-sm rounded-xl text-muted hover:text-text hover:bg-background/60 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span>Ver tienda</span>
                </a>
            </div>
        </nav>

        {{-- Logout --}}
        <div class="p-3 border-t border-border">
            <div class="flex items-center gap-3 px-3 py-2 mb-1">
                <div class="w-7 h-7 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center text-primary font-semibold text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-medium text-text truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-muted truncate">Administrador</p>
                </div>
            </div>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center w-full px-3 py-2 gap-3 text-sm text-error hover:bg-error/10 rounded-xl transition-colors focus:outline-none text-left">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ─── Área principal ──────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0 w-full">

        {{-- Header --}}
        <header class="h-14 bg-surface border-b border-border/30 flex items-center justify-between px-4 lg:px-6 shrink-0">
            <button @click="sidebarOpen = true" class="lg:hidden text-muted hover:text-text p-1 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5m-16.5 5.25h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <div class="flex items-center gap-2 ml-auto">
                <span class="text-xs text-muted hidden sm:inline">Panel de administración</span>
                <div class="w-px h-4 bg-border hidden sm:block"></div>
                <span class="text-xs font-medium text-primary">Marketo</span>
            </div>
        </header>

        <main class="flex-1 min-h-0 overflow-x-hidden overflow-y-auto px-4 lg:px-6 pt-4 lg:pt-6 pb-12 bg-background">
            <div class="max-w-[1440px] mx-auto w-full">
                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>
