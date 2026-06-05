<!doctype html>
<html lang="es" class="h-full bg-dark-bg">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nexuspc - panel de administración</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=oxanium:wght@400;600;700&family=plus+jakarta+sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<form action="{{ route('admin') }}" method="get">

    <body class="h-full font-sans antialiased text-text-main bg-dark-bg" x-data="{ mobilemenuopen: false, notificationsopen: false, profileopen: false }">

        <div class="flex h-screen w-full overflow-hidden">

            <div x-show="mobilemenuopen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-dark-bg/80 backdrop-blur-sm z-40 lg:hidden"
                @click="mobilemenuopen = false" style="display: none;">
            </div>

            <aside
                class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-dark-card border-r border-dark-border flex flex-col flex-shrink-0 transform transition-transform duration-300 ease-in-out"
                :class="mobilemenuopen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

                <div class="h-16 flex items-center justify-between px-6 border-b border-dark-border">
                    <div
                        class="flex items-center gap-2 text-brand-primary font-display font-bold text-xl tracking-wide">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" />
                            <path d="M9 9h6v6h-6zM9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 15h3M1 9h3M1 15h3" />
                        </svg>
                        <span class="font-bold" style="font-family: 'oxanium', sans-serif;">nexus<span
                                class="text-text-main">pc</span></span>
                    </div>
                    <button class="lg:hidden text-text-muted hover:text-text-main" @click="mobilemenuopen = false">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 py-4 overflow-y-auto">
                    <ul class="space-y-1">
                        <li>
                            <a href="#"
                                class="flex items-center px-6 py-3 gap-3 text-sm transition-colors relative {{ request()->is('admin/dashboard*') ? 'text-brand-primary bg-brand-primary/10 font-medium' : 'text-text-muted hover:text-text-main hover:bg-dark-card/50' }}">
                                @if (request()->is('admin/dashboard*'))
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-primary rounded-r-sm"></div>
                                @endif
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M3 3h7v9H3zm11 0h7v5h-7zm0 9h7v9h-7zM3 16h7v5H3z" />
                                </svg>
                                dashboard
                            </a>
                        </li>

                        <li>
                            <a href="#"
                                class="flex items-center px-6 py-3 gap-3 text-sm transition-colors relative {{ request()->is('admin/catalog*') ? 'text-brand-primary bg-brand-primary/10 font-medium' : 'text-text-muted hover:text-text-main hover:bg-dark-card/50' }}">
                                @if (request()->is('admin/catalog*'))
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-primary rounded-r-sm"></div>
                                @endif
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="4" y="4" width="16" height="16" rx="2" />
                                    <path d="M9 9h6v6h-6z" />
                                </svg>
                                catálogo de hardware
                            </a>
                        </li>

                        <li>
                            <a href="#"
                                class="flex items-center px-6 py-3 gap-3 text-sm transition-colors relative {{ request()->is('admin/orders*') ? 'text-brand-primary bg-brand-primary/10 font-medium' : 'text-text-muted hover:text-text-main hover:bg-dark-card/50' }}">
                                @if (request()->is('admin/orders*'))
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-primary rounded-r-sm"></div>
                                @endif
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <circle cx="9" cy="21" r="1" />
                                    <circle cx="20" cy="21" r="1" />
                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
                                </svg>
                                pedidos
                            </a>
                        </li>

                        <li>
                            <a href="#"
                                class="flex items-center px-6 py-3 gap-3 text-sm transition-colors relative {{ request()->is('admin/clients*') ? 'text-brand-primary bg-brand-primary/10 font-medium' : 'text-text-muted hover:text-text-main hover:bg-dark-card/50' }}">
                                @if (request()->is('admin/clients*'))
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-primary rounded-r-sm"></div>
                                @endif
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                                clientes
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>

            <div class="flex-1 flex flex-col min-w-0 w-full">

                <header
                    class="h-16 bg-dark-card border-b border-dark-border flex items-center justify-between px-4 lg:px-6 flex-shrink-0">

                    <div class="flex items-center flex-1 gap-4">
                        <button class="lg:hidden text-text-muted hover:text-text-main p-1"
                            @click="mobilemenuopen = true">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="relative w-full max-w-xl hidden sm:block">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-muted"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8" />
                                <path d="M21 21l-4.35-4.35" />
                            </svg>
                            <input type="text" placeholder="buscar componentes, nro de serie o pedidos..."
                                class="w-full bg-dark-bg border border-dark-border rounded-md pl-10 pr-4 py-2 text-sm text-text-main focus:outline-none focus:ring-1 focus:ring-brand-primary focus:border-brand-primary transition-colors placeholder:text-text-muted">
                        </div>
                    </div>

                    <div class="flex items-center gap-4 lg:gap-6 ml-4">

                        <div class="relative">
                            <button
                                class="relative text-text-muted hover:text-text-main transition-colors outline-none"
                                @click="notificationsopen = !notificationsopen"
                                @click.away="notificationsopen = false">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" />
                                </svg>
                                <span
                                    class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-dark-card"></span>
                            </button>

                            <div x-show="notificationsopen"
                                class="absolute right-0 mt-2 w-80 bg-dark-card border border-dark-border rounded-lg shadow-xl z-50 overflow-hidden"
                                style="display: none;">
                                <div
                                    class="flex items-center justify-between px-4 py-3 border-b border-dark-border bg-dark-bg/30">
                                    <h3 class="font-medium text-text-main">notificaciones</h3>
                                    <span
                                        class="text-xs bg-brand-primary/20 text-brand-primary px-2 py-0.5 rounded-full font-medium">3
                                        nuevas</span>
                                </div>
                                <div class="p-2 border-t border-dark-border text-center bg-dark-bg/10">
                                    <button
                                        class="text-xs text-brand-primary font-medium hover:underline w-full py-1">marcar
                                        todas como leídas</button>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="flex items-center gap-3 pl-4 lg:pl-6 border-l border-dark-border cursor-pointer outline-none"
                                @click="profileopen = !profileopen" @click.away="profileopen = false">
                                <div
                                    class="w-8 h-8 lg:w-9 lg:h-9 rounded-full bg-dark-bg flex items-center justify-center overflow-hidden border border-dark-border">
                                    <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?q=80&w=200&auto=format&fit=crop"
                                        alt="admin avatar" class="w-full h-full object-cover">
                                </div>
                            </div>

                            <div x-show="profileopen"
                                class="absolute right-0 mt-2 w-56 bg-dark-card border border-dark-border rounded-lg shadow-xl z-50 p-1"
                                style="display: none;">
                                <div class="px-2 py-2 border-b border-dark-border mb-1">
                                    <p class="text-xs text-text-muted">sesión activa</p>
                                    <p class="text-sm font-medium text-text-main">administrador</p>
                                </div>
                                <a href="#"
                                    class="flex items-center gap-2 px-2 py-2 text-sm text-text-main hover:bg-dark-bg/50 rounded-md outline-none">
                                    mi perfil
                                </a>
                                <div class="h-px bg-dark-border my-1"></div>

                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-2 py-2 text-sm text-red-500 hover:bg-red-500/10 rounded-md text-left outline-none">
                                        cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-6 bg-dark-bg">
                    <div class="max-w-[1440px] mx-auto w-full">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

    </body>
</form>

</html>
