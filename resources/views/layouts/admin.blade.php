<!doctype html>
<html lang="es" class="h-full bg-dark-bg">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusTech - Panel de Administración</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=oxanium:wght@400;600;700&family=plus+jakarta+sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body x-data="{ mobilemenuopen: false, currentuserrole: 'superadmin' }"
    class="bg-background text-text font-jakarta text-body font-normal antialiased min-h-screen flex relative overflow-hidden">

    <div x-show="mobilemenuopen" @click="mobilemenuopen = false"
        class="fixed inset-0 bg-background/80 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

    <aside :class="mobilemenuopen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-surface border-r border-border flex flex-col shrink-0 transition-transform duration-300 ease-in-out">

        <div class="h-16 flex items-center justify-between px-6 border-b border-border">
            <div class="flex items-center gap-2 text-primary font-oxanium font-bold text-h4 tracking-wide">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                    <rect x="4" y="4" width="16" height="16" rx="2" />
                    <rect x="9" y="9" width="6" height="6" />
                    <path d="m9 1v3m6-3v3m-9 16v3m6-3v3m-11-13h3m-3 6h3m16-6h3m-3 6h3" />
                </svg>
                <span>Nexus<span class="text-text">Tech</span></span>
            </div>
            <button @click="mobilemenuopen = false" class="lg:hidden text-muted hover:text-text focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 18 12-12m0 12l-12-12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 py-4 overflow-y-auto">
            <ul class="space-y-1">
                <li>
                    <a href="/dashboard"
                        class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->is('dashboard*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if (request()->is('dashboard*'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="9" rx="1" />
                            <rect x="14" y="3" width="7" height="5" rx="1" />
                            <rect x="14" y="12" width="7" height="9" rx="1" />
                            <rect x="3" y="16" width="7" height="5" rx="1" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="/catalog"
                        class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->is('catalog*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if (request()->is('catalog*'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" />
                            <rect x="9" y="9" width="6" height="6" />
                        </svg>
                        <span>Catálogo de Hardware</span>
                    </a>
                </li>

                <li>
                    <a href="/orders"
                        class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->is('orders*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if (request()->is('orders*'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path
                                d="m2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43h-17.3" />
                        </svg>
                        <span>Pedidos</span>
                    </a>
                </li>

                <li>
                    <a href="/clients"
                        class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->is('clients*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if (request()->is('clients*'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                            <path d="m16 21v-2a4 4 0 0 0-4-4h-4a4 4 0 0 0-4 4v2" />
                            <circle cx="8" cy="7" r="4" />
                            <path d="m22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="m16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <span>Clientes</span>
                    </a>
                </li>

                <li>
                    <a href="/roles"
                        class="flex items-center px-6 py-3 gap-3 text-h6 transition-colors relative {{ request()->is('roles*') ? 'text-primary bg-primary/10 font-medium' : 'text-muted hover:text-text hover:bg-background/50' }}">
                        @if (request()->is('roles*'))
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-r-sm"></div>
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                            viewbox="0 0 24 24">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="m19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51v.1a2 2 0 0 1-4 0v-.1a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51v-.09a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1h.09a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                        </svg>
                        <span>Roles y Configuración</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-border mt-auto">
            <form method="post" action="/logout" class="block w-full">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 gap-3 text-h6 text-error hover:bg-error/10 rounded-md transition-colors focus:outline-none text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentcolor" stroke-width="2" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 21h-6a2 2 0 0 1-2-2v-14a2 2 0 0 1 2-2h6m4 16 5-5-5-5m5 5h-12" />
                    </svg>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>

    </aside>

    <div class="flex-1 flex flex-col min-w-0 w-full">

        <header class="h-16 bg-surface border-b border-border flex items-center justify-between px-4 lg:px-6 shrink-0">
            <div class="flex items-center flex-1 gap-4">
                <button @click="mobilemenuopen = true"
                    class="lg:hidden text-muted hover:text-text p-1 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m3.75 6.75h16.5m-16.5 5.25h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="relative w-full max-w-xl hidden sm:block">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted" fill="none"
                        stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    <input type="text" placeholder="buscar..."
                        class="w-full bg-background border border-border rounded-md pl-10 pr-4 py-2 text-small text-text focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors placeholder:text-muted" />
                </div>
                <button class="sm:hidden text-muted hover:text-text focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center gap-4 lg:gap-6 ml-4">

                <div x-data="{ dropdownopen: false }" class="relative">
                    <div @click="dropdownopen = !dropdownopen"
                        class="flex items-center gap-3 pl-4 lg:pl-6 border-l border-border cursor-pointer select-none">
                        <div
                            class="w-8 h-8 lg:w-9 lg:h-9 rounded-full bg-background flex items-center justify-center overflow-hidden border border-border">
                            <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?q=80&w=200&auto=format&fit=crop"
                                alt="admin avatar" class="w-full h-full object-cover" />
                        </div>
                        <div class="hidden md:flex flex-col">
                            <span class="text-small font-medium leading-none mb-1">Alex Mercer</span>
                            <span x-text="currentuserrole"
                                class="text-label text-muted leading-none capitalize"></span>
                        </div>
                    </div>

                    <div x-show="dropdownopen" @click.away="dropdownopen = false"
                        class="absolute right-0 mt-2 w-56 bg-surface border border-border rounded-lg shadow-2xl z-50 p-1"
                        x-cloak style="display: none;">

                        <button @click="currentuserrole = 'superadmin'; dropdownopen = false"
                            :class="currentuserrole === 'superadmin' ? 'bg-primary/10 text-primary' :
                                'text-text hover:bg-background'"
                            class="w-full flex items-center gap-2 px-2 py-2 text-small rounded-md text-left transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewbox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="m19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51v.1a2 2 0 0 1-4 0v-.1a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51v-.09a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1h.09a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                            </svg>
                            <span>SuperAdmin</span>
                        </button>

                        <button @click="currentuserrole = 'gestor de catálogo'; dropdownopen = false"
                            :class="currentuserrole === 'gestor de catálogo' ? 'bg-accent/10 text-accent' :
                                'text-text hover:bg-background'"
                            class="w-full flex items-center gap-2 px-2 py-2 text-small rounded-md text-left transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewbox="0 0 24 24">
                                <rect x="4" y="4" width="16" height="16" rx="2" />
                                <rect x="9" y="9" width="6" height="6" />
                            </svg>
                            <span>Gestor de Catálogo</span>
                        </button>

                        <button @click="currentuserrole = 'técnico de armado'; dropdownopen = false"
                            :class="currentuserrole === 'técnico de armado' ? 'bg-warning/10 text-warning' :
                                'text-text hover:bg-background'"
                            class="w-full flex items-center gap-2 px-2 py-2 text-small rounded-md text-left transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewbox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="m19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51v.1a2 2 0 0 1-4 0v-.1a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51v-.09a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h.09a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1h.09a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                            </svg>
                            <span>Técnico de Armado</span>
                        </button>

                        <button @click="currentuserrole = 'atención al cliente'; dropdownopen = false"
                            :class="currentuserrole === 'atención al cliente' ? 'bg-success/10 text-success' :
                                'text-text hover:bg-background'"
                            class="w-full flex items-center gap-2 px-2 py-2 text-small rounded-md text-left transition-colors focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewbox="0 0 24 24">
                                <path d="m16 21v-2a4 4 0 0 0-4-4h-4a4 4 0 0 0-4 4v2" />
                                <circle cx="8" cy="7" r="4" />
                            </svg>
                            <span>Atención al Cliente</span>
                        </button>

                        <div class="h-px bg-border my-1"></div>

                        <a href="/profile"
                            class="flex items-center gap-2 px-2 py-2 text-small text-text hover:bg-background rounded-md transition-colors">
                            <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="2"
                                viewbox="0 0 24 24">
                                <path d="m19 21v-2a4 4 0 0 0-4-4h-6a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                            <span>Mi Perfil</span>
                        </a>

                        <form method="post" action="/logout" class="block w-full">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2 px-2 py-2 text-small text-error hover:bg-error/10 rounded-md text-left transition-colors focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewbox="0 0 24 24">
                                    <path d="m9 21h-6a2 2 0 0 1-2-2v-14a2 2 0 0 1 2-2h6m4 16 5-5-5-5m5 5h-12" />
                                </svg>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 lg:p-6 bg-background">
            <div class="max-w-[1440px] mx-auto w-full">
                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>
