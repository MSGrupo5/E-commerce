<!doctype html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusTech - Mi Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=oxanium:wght@400;600;700&family=plus+jakarta+sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body x-data="{ mobileMenuOpen: false }" class="bg-background text-text font-jakarta text-body font-normal antialiased min-h-screen flex relative overflow-hidden">

    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" class="fixed inset-0 bg-background/80 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-surface border-r border-border flex flex-col shrink-0 transition-transform duration-300 ease-in-out">

        <div class="h-16 flex items-center justify-between px-6 border-b border-border">
            <a href="{{ route('seller.dashboard') }}" class="flex items-center gap-2 text-primary font-oxanium font-bold text-h4 tracking-wide">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                </svg>
                <span>Nexus<span class="text-text">Tech</span></span>
            </a>
            <button @click="mobileMenuOpen = false" class="lg:hidden text-muted hover:text-text focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 py-4 overflow-y-auto">
            <p class="px-6 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted mb-3">Mi Panel</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('seller.dashboard') }}" class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->routeIs('seller.dashboard') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if(request()->routeIs('seller.dashboard'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="9" rx="1" />
                            <rect x="14" y="3" width="7" height="5" rx="1" />
                            <rect x="14" y="12" width="7" height="9" rx="1" />
                            <rect x="3" y="16" width="7" height="5" rx="1" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.productos.index') }}" class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->routeIs('seller.productos.*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if(request()->routeIs('seller.productos.*'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" />
                            <rect x="9" y="9" width="6" height="6" />
                        </svg>
                        <span>Mis Productos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('seller.orders') }}" class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->routeIs('seller.orders') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if(request()->routeIs('seller.orders'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                        <span>Pedidos</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-border mt-auto">
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="flex items-center w-full px-4 py-2 gap-3 text-h6 text-muted hover:text-text hover:bg-background/50 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>Mi Perfil</span>
                </a>
                <a href="{{ route('home') }}" class="flex items-center w-full px-4 py-2 gap-3 text-h6 text-muted hover:text-text hover:bg-background/50 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="m2.25 12 8.954-8.955a1.126 1.126 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span>Ir a la Tienda</span>
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 gap-3 text-h6 text-error hover:bg-error/10 rounded-md transition-colors text-left focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 w-full">
        <header class="h-16 bg-surface border-b border-border flex items-center justify-between px-4 lg:px-6 shrink-0">
            <div class="flex items-center gap-4">
                <button @click="mobileMenuOpen = true" class="lg:hidden text-muted hover:text-text p-1 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="hidden sm:flex items-center gap-2 text-muted text-small">
                    <a href="{{ route('home') }}" class="hover:text-text transition-colors">Tienda</a>
                    <span>/</span>
                    <span class="text-text">Mi Panel</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-small text-muted hidden sm:block">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-6 bg-background">
            <div class="max-w-[1440px] mx-auto w-full">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-cloak class="mb-6 rounded-2xl border border-success/30 bg-success/10 px-5 py-4 shadow-2xl">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-success/20">
                                <svg class="h-5 w-5 text-success" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                            <p class="text-sm text-success font-medium">{{ session('success') }}</p>
                            <button type="button" @click="show = false" class="ml-auto text-success/60 hover:text-success transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-cloak class="mb-6 rounded-2xl border border-error/30 bg-error/10 px-5 py-4 shadow-2xl">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-error/20">
                                <svg class="h-5 w-5 text-error" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                            </div>
                            <p class="text-sm text-error font-medium">{{ session('error') }}</p>
                            <button type="button" @click="show = false" class="ml-auto text-error/60 hover:text-error transition-colors">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('seller-content')
            </div>
        </main>
    </div>
</body>
</html>
