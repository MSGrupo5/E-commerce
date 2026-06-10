<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getlocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NexusTech') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=oxanium:wght=700&family=plus+jakarta+sans:wght=400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-jakarta bg-background text-text antialiased min-h-screen flex flex-col justify-between">

    <header class="w-full sticky top-0 z-50 bg-surface border-b border-border">

        <div class="max-w-screen-xl mx-auto px-4 md:px-8 h-14 md:h-16 flex items-center justify-between gap-6">

            <a href="/app" class="flex items-center gap-2 shrink-0 focus:outline-none">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2"
                    viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m3.75 13.5l10.5-11.25l-2.25 9h7.5l-10.5 11.25l2.25-9h-7.5z" />
                </svg>
                <span class="font-oxanium font-bold text-base md:text-lg tracking-widest text-text">
                    NexusTech
                </span>
            </a>

            <div class="hidden md:block flex-1 max-w-xl">
                <form action="/search" method="get"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-background border border-border">
                    <svg class="w-4 h-4 text-muted" fill="none" stroke="currentColor" stroke-width="2"
                        viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21l-5.197-5.197m0 0a7.5 7.5 0 10-10.518-10.518 7.5 7.5 0 0010.518 10.518z" />
                    </svg>
                    <input type="text" name="q" placeholder="Buscar laptops, smartphones, auriculares..."
                        class="bg-transparent text-sm outline-none w-full text-text placeholder-muted">
                </form>
            </div>

            <div class="flex items-center gap-4 md:gap-6 ml-auto">

                <a href="/favorites"
                    class="flex items-center gap-2 text-muted hover:text-text transition-colors relative group">
                    <div class="relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733c-2.589 0-4.688 2.015-4.688 4.5 0 5.852 7.005 11.583 11.048 12.68a1.201 1.201 0 00.704 0c4.043-1.107 11.048-6.828 11.048-12.68z" />
                        </svg>
                        <span
                            class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-primary text-background text-[9px] font-bold flex items-center justify-center">0</span>
                    </div>
                    <span class="hidden md:inline text-sm font-medium">Favoritos</span>
                </a>

                <a href="/cart"
                    class="flex items-center gap-2 text-muted hover:text-text transition-colors relative group">
                    <div class="relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                        </svg>
                        <span
                            class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-primary text-background text-[9px] font-bold flex items-center justify-center">0</span>
                    </div>
                    <span class="hidden md:inline text-sm font-medium">Carrito</span>
                </a>

                <div class="hidden md:block">
                    @if (auth()->check())
                        <x-dropdown align="right" width="48"
                            content-classes="py-1 bg-surface border border-border">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center gap-2 hover:text-text text-muted transition-colors focus:outline-none">
                                    <div
                                        class="w-8 h-8 rounded-full bg-surface border border-primary flex items-center justify-center text-primary">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                            viewbox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium capitalize">{{ auth()->user()->name }}</span>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')" class="bg-surface text-text hover:bg-background">
                                    {{ __('mi perfil') }}
                                </x-dropdown-link>
                                <a href="/orders"
                                    class="block px-4 py-2 text-sm leading-5 text-text hover:bg-background transition duration-150 ease-in-out">
                                    Mis pedidos
                                </a>
                                <div class="border-t border-border"></div>
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventdefault(); this.closest('form').submit();"
                                        class="text-error hover:bg-error/10">
                                        {{ __('cerrar sesión') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-muted hover:text-text transition-colors">Ingresar</a>
                            <a href="{{ route('register') }}"
                                class="bg-primary text-background px-4 py-2 rounded-xl text-sm font-medium hover:opacity-90 transition-opacity">Registrarse</a>
                        </div>
                    @endif
                </div>

                <div class="block md:hidden">
                    <x-dropdown align="right" width="48" content-classes="py-1 bg-surface border border-border">
                        <x-slot name="trigger">
                            <button class="text-muted hover:text-text p-1 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                    viewbox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <a href="/catalog"
                                class="block px-4 py-2 text-sm text-text hover:bg-background transition-colors">Catálogo</a>
                            <a href="/favorites"
                                class="block px-4 py-2 text-sm text-text hover:bg-background transition-colors">Mis
                                favoritos</a>
                            <a href="/orders"
                                class="block px-4 py-2 text-sm text-text hover:bg-background transition-colors">Mis
                                pedidos</a>
                            <div class="border-t border-border"></div>
                            @if (auth()->check())
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-error hover:bg-error/10 transition-colors">Cerrar
                                        Sesión</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                    class="block px-4 py-2 text-sm text-primary hover:bg-primary/10 transition-colors">Iniciar
                                    Sesión</a>
                            @endif
                        </x-slot>
                    </x-dropdown>
                </div>

            </div>
        </div>

        <div class="block md:hidden px-4 pb-3">
            <form action="/search" method="get"
                class="flex items-center gap-2 px-3 py-2 rounded-lg bg-background border border-border">
                <svg class="w-3.5 h-3.5 text-muted" fill="none" stroke="currentColor" stroke-width="2"
                    viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21l-5.197-5.197m0 0a7.5 7.5 0 10-10.518-10.518 7.5 7.5 0 0010.518 10.518z" />
                </svg>
                <input type="text" name="q" placeholder="buscar productos..."
                    class="bg-transparent text-sm outline-none w-full text-text placeholder-muted">
            </form>
        </div>
    </header>

    <main class="flex-1 w-full max-w-screen-xl mx-auto px-4 md:px-8 py-6 md:py-10">
        @yield('content')
    </main>

    <footer class="bg-background border-t border-border">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8 md:py-12">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8 md:mb-12">

                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2"
                            viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m3.75 13.5l10.5-11.25l-2.25 9h7.5l-10.5 11.25l2.25-9h-7.5z" />
                        </svg>
                        <span class="font-oxanium font-bold text-base md:text-lg tracking-widest text-text">
                            TechStore
                        </span>
                    </div>
                    <p class="text-xs md:text-sm text-muted leading-relaxed mb-4">
                        Tu destino tech premium. Tecnología de vanguardia con la mejor experiencia de compra.
                    </p>
                </div>

                <div>
                    <p class="text-text text-[13px] font-semibold mb-4 tracking-wider uppercase">productos</p>
                    <div class="space-y-2 md:space-y-3">
                        <a href="/catalog"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Laptops</a>
                        <a href="/catalog"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Smartphones</a>
                        <a href="/catalog"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Auriculares</a>
                        <a href="/catalog"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Monitores</a>
                    </div>
                </div>

                <div>
                    <p class="text-text text-[13px] font-semibold mb-4 tracking-wider uppercase">Mi cuenta</p>
                    <div class="space-y-2 md:space-y-3">
                        <a href="/profile"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Mi perfil</a>
                        <a href="/orders"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Mis
                            pedidos</a>
                        <a href="/favorites"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Mis
                            favoritos</a>
                    </div>
                </div>

                <div>
                    <p class="text-text text-[13px] font-semibold mb-4 tracking-wider uppercase">Ayuda</p>
                    <div class="space-y-2 md:space-y-3">
                        <a href="/support"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Centro de
                            Ayuda</a>
                        <a href="/shipping"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Envíos y
                            Devoluciones</a>
                        <a href="/warranty"
                            class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Garantías</a>
                    </div>
                </div>

            </div>

            <div
                class="flex flex-col md:flex-row items-center justify-between gap-4 pt-6 md:pt-8 border-t border-border">
                <p class="text-xs text-muted">
                    © 2026 TechStore. Todos los derechos reservados.
                </p>
                <div class="flex gap-4 md:gap-6">
                    <a href="/terms" class="text-xs text-muted hover:text-text transition-colors">Términos y
                        Condiciones</a>
                    <a href="/privacy" class="text-xs text-muted hover:text-text transition-colors">Política de
                        Privacidad</a>
                </div>
            </div>

        </div>
    </footer>

</body>

</html>
