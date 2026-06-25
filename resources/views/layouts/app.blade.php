<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getlocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Marketo') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/images/marketo_icono_solo.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-jakarta bg-background text-text antialiased min-h-screen flex flex-col justify-between">

    {{-- ─── Barra superior de marca ────────────────────────────────── --}}
    <div class="h-[3px] w-full bg-primary sticky top-0 z-[51]"></div>

    {{-- ─── Header ──────────────────────────────────────────────────── --}}
    <header class="w-full sticky top-[3px] z-50 bg-surface border-b border-border/30">

        <div class="max-w-screen-xl mx-auto px-4 md:px-8 h-14 md:h-16 flex items-center justify-between gap-6">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="focus:outline-none">
                <x-app.logo />
            </a>

            {{-- Buscador (desktop) --}}
            <div class="hidden md:block flex-1 max-w-xl"
                x-data="searchSuggest('{{ route('products.suggestions') }}', '{{ request('search') }}')"
                @click.outside="close()">
                <form action="{{ route('products.index') }}" method="get" class="relative" autocomplete="off" @submit="close()">
                    <svg class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-muted shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                    </svg>
                    <input type="text" name="search" x-model="query"
                        @input="search()"
                        @keydown.down.prevent="move(1)"
                        @keydown.up.prevent="move(-1)"
                        @keydown.enter="if (highlighted >= 0) { $event.preventDefault(); chooseHighlighted(); }"
                        @keydown.escape="close()"
                        @focus="show = suggestions.length > 0"
                        placeholder="Buscar productos, vendedores..."
                        class="w-full rounded-2xl border border-border bg-background pl-10 pr-4 py-2.5 text-sm text-text placeholder-muted outline-none transition focus:border-primary/50 focus:ring-2 focus:ring-primary/20">

                    <div x-show="show" x-cloak x-transition.opacity.duration.100ms
                        class="absolute left-0 right-0 top-full mt-2 z-50 max-h-96 overflow-y-auto rounded-2xl border border-border bg-surface shadow-2xl">
                        <template x-for="(item, index) in suggestions" :key="item.id">
                            <a :href="item.url"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm transition-colors"
                                :class="highlighted === index ? 'bg-primary/10 text-primary' : 'text-text hover:bg-background'">
                                <img :src="item.image_url" alt="" class="h-8 w-8 rounded-lg object-cover border border-border shrink-0" onerror="this.style.display='none'">
                                <span class="flex-1 truncate" x-text="item.name"></span>
                                <span class="text-xs text-muted shrink-0" x-text="'$' + item.price_formatted"></span>
                            </a>
                        </template>
                    </div>
                </form>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center gap-3 md:gap-5 ml-auto">

                {{-- Favoritos (solo auth) --}}
                @auth
                <a href="{{ route('favorites.index') }}"
                    class="flex items-center gap-2 text-muted hover:text-text transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                    <span class="hidden md:inline text-sm font-medium">Favoritos</span>
                </a>
                @endauth

                {{-- Carrito --}}
                <a href="{{ route('cart.index') }}"
                    class="flex items-center gap-2 text-muted hover:text-text transition-colors relative group">
                    <div class="relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0z" />
                        </svg>
                        @php
                            $cartCount = auth()->check()
                                ? (auth()->user()->cart?->items()->sum('quantity') ?? 0)
                                : collect(session('cart.items', []))->sum('quantity');
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-primary text-background text-[9px] font-bold flex items-center justify-center">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </div>
                    <span class="hidden md:inline text-sm font-medium">Carrito</span>
                </a>

                {{-- Usuario (desktop) --}}
                <div class="hidden md:block">
                    @auth
                        <x-dropdown align="right" width="52" content-classes="py-1 bg-surface border border-border">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 hover:text-text text-muted transition-colors focus:outline-none">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center text-primary font-semibold text-sm">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-medium capitalize">{{ auth()->user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-border">
                                    <p class="text-xs font-semibold text-text">{{ auth()->user()->name }} {{ auth()->user()->apellido }}</p>
                                    <p class="text-xs text-muted truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <x-dropdown-link :href="route('profile.edit')" class="text-text hover:bg-background hover:text-primary">
                                    Mi perfil
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('favorites.index')" class="text-text hover:bg-background hover:text-primary">
                                    Mis favoritos
                                </x-dropdown-link>
                                @unless(auth()->user()->isAdmin())
                                    <x-dropdown-link :href="route('seller.dashboard')" class="text-text hover:bg-background hover:text-primary">
                                        Panel de vendedor
                                    </x-dropdown-link>
                                @endunless
                                @if(auth()->user()->isAdmin())
                                    <x-dropdown-link :href="route('admin.dashboard')" class="text-text hover:bg-background hover:text-primary">
                                        Panel de administración
                                    </x-dropdown-link>
                                @endif
                                <div class="border-t border-border"></div>
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-error hover:bg-error/10">
                                        Cerrar sesión
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-muted hover:text-text transition-colors">
                                Ingresar
                            </a>
                            <a href="{{ route('register') }}"
                                class="bg-primary text-background px-4 py-2 rounded-xl text-sm font-semibold hover:bg-primary/90 transition-colors">
                                Registrarse
                            </a>
                        </div>
                    @endauth
                </div>

                {{-- Menú mobile --}}
                <div class="block md:hidden">
                    <x-dropdown align="right" width="52" content-classes="py-1 bg-surface border border-border">
                        <x-slot name="trigger">
                            <button class="text-muted hover:text-text p-1 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <a href="{{ route('products.index') }}" class="block px-4 py-2 text-sm text-text hover:bg-background">Catálogo</a>
                            <a href="{{ route('cart.index') }}" class="block px-4 py-2 text-sm text-text hover:bg-background">
                                Carrito
                                @if($cartCount > 0)
                                    <span class="ml-1 text-primary font-semibold">({{ $cartCount }})</span>
                                @endif
                            </a>
                            @auth
                                @unless(auth()->user()->isAdmin())
                                    <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 text-sm text-text hover:bg-background">Panel de vendedor</a>
                                @endunless
                                <a href="{{ route('favorites.index') }}" class="block px-4 py-2 text-sm text-text hover:bg-background">Mis favoritos</a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-text hover:bg-background">Mi perfil</a>
                                <div class="border-t border-border"></div>
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-error hover:bg-error/10">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            @else
                                <div class="border-t border-border"></div>
                                <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-primary hover:bg-primary/10">Iniciar Sesión</a>
                                <a href="{{ route('register') }}" class="block px-4 py-2 text-sm text-text hover:bg-background">Registrarse</a>
                            @endauth
                        </x-slot>
                    </x-dropdown>
                </div>

            </div>
        </div>

        {{-- Buscador mobile --}}
        <div class="block md:hidden px-4 pb-3"
            x-data="searchSuggest('{{ route('products.suggestions') }}', '{{ request('search') }}')"
            @click.outside="close()">
            <form action="{{ route('products.index') }}" method="get" class="relative" autocomplete="off" @submit="close()">
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-muted shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607z" />
                </svg>
                <input type="text" name="search" x-model="query"
                    @input="search()"
                    @keydown.down.prevent="move(1)"
                    @keydown.up.prevent="move(-1)"
                    @keydown.enter="if (highlighted >= 0) { $event.preventDefault(); chooseHighlighted(); }"
                    @keydown.escape="close()"
                    @focus="show = suggestions.length > 0"
                    placeholder="Buscar productos..."
                    class="w-full rounded-2xl border border-border bg-background pl-9 pr-3 py-2 text-sm text-text placeholder-muted outline-none transition focus:border-primary/50 focus:ring-2 focus:ring-primary/20">

                <div x-show="show" x-cloak x-transition.opacity.duration.100ms
                    class="absolute left-0 right-0 top-full mt-2 z-50 max-h-80 overflow-y-auto rounded-2xl border border-border bg-surface shadow-2xl">
                    <template x-for="(item, index) in suggestions" :key="item.id">
                        <a :href="item.url"
                            class="flex items-center gap-3 px-3 py-2 text-sm transition-colors"
                            :class="highlighted === index ? 'bg-primary/10 text-primary' : 'text-text hover:bg-background'">
                            <img :src="item.image_url" alt="" class="h-7 w-7 rounded-lg object-cover border border-border shrink-0" onerror="this.style.display='none'">
                            <span class="flex-1 truncate" x-text="item.name"></span>
                            <span class="text-xs text-muted shrink-0" x-text="'$' + item.price_formatted"></span>
                        </a>
                    </template>
                </div>
            </form>
        </div>
    </header>

    {{-- ─── Toast de carrito ──────────────────────────────────────── --}}
    @if(session('cart_success') && !request()->routeIs('cart.*'))
        <div x-data="{ show: true }" x-show="show" x-cloak
            x-init="setTimeout(() => show = false, 4500)"
            class="fixed top-20 right-4 z-[100] max-w-sm w-full rounded-2xl border border-primary/30 bg-surface px-5 py-4 shadow-2xl">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-primary/20">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <p class="text-sm text-text font-medium flex-1">{{ session('cart_success') }}</p>
                <button type="button" @click="show = false" class="ml-auto text-muted hover:text-text transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- ─── Toast de error ─────────────────────────────────────────── --}}
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-cloak
            x-init="setTimeout(() => show = false, 4500)"
            class="fixed top-20 right-4 z-[100] max-w-sm w-full rounded-2xl border border-error/30 bg-surface px-5 py-4 shadow-2xl">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-error/20">
                    <svg class="h-5 w-5 text-error" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>
                <p class="text-sm text-text font-medium flex-1">{{ session('error') }}</p>
                <button type="button" @click="show = false" class="ml-auto text-muted hover:text-text transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <x-ui.loader />

    {{-- ─── Contenido principal ────────────────────────────────────── --}}
    <main class="flex-1 w-full max-w-screen-xl mx-auto px-4 md:px-8 py-6 md:py-10">
        {{ $slot }}
    </main>

    {{-- ─── Footer ──────────────────────────────────────────────────── --}}
    <footer class="bg-surface border-t border-border">
        <div class="max-w-screen-xl mx-auto px-4 md:px-8 py-8 md:py-12">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8 md:mb-12">

                <div class="col-span-2 md:col-span-1">
                    <div class="mb-4">
                        <x-app.logo />
                    </div>
                    <p class="text-xs md:text-sm text-muted leading-relaxed">
                        El marketplace donde cualquiera puede comprar y vender. Miles de productos, vendedores reales.
                    </p>
                </div>

                <div>
                    <p class="text-text text-[13px] font-semibold mb-4 tracking-wider uppercase">Explorar</p>
                    <div class="space-y-2 md:space-y-3">
                        <a href="{{ route('products.index') }}" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Todos los productos</a>
                        <a href="{{ route('products.index') }}?category=perifericos" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Periféricos</a>
                        <a href="{{ route('products.index') }}?category=monitores" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Monitores</a>
                        <a href="{{ route('products.index') }}?category=audio" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Audio</a>
                    </div>
                </div>

                <div>
                    <p class="text-text text-[13px] font-semibold mb-4 tracking-wider uppercase">Vender</p>
                    <div class="space-y-2 md:space-y-3">
                        @unless(auth()->user()?->isAdmin())
                            <a href="{{ route('seller.dashboard') }}" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Panel de vendedor</a>
                            <a href="{{ route('seller.productos.create') }}" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Publicar producto</a>
                        @endunless
                        <a href="{{ route('register') }}" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Registrarse como vendedor</a>
                    </div>
                </div>

                <div>
                    <p class="text-text text-[13px] font-semibold mb-4 tracking-wider uppercase">Mi cuenta</p>
                    <div class="space-y-2 md:space-y-3">
                        <a href="{{ route('profile.edit') }}" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Mi perfil</a>
                        <a href="{{ route('cart.index') }}" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Mi carrito</a>
                        @guest
                            <a href="{{ route('login') }}" class="block text-xs md:text-sm text-muted hover:text-text transition-colors">Iniciar sesión</a>
                        @endguest
                    </div>
                </div>

            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-6 md:pt-8 border-t border-border">
                <p class="text-xs text-muted">
                    © {{ date('Y') }} Marketo. Todos los derechos reservados.
                </p>
                <div class="flex gap-4 md:gap-6">
                    <a href="#" class="text-xs text-muted hover:text-text transition-colors">Términos y Condiciones</a>
                    <a href="#" class="text-xs text-muted hover:text-text transition-colors">Política de Privacidad</a>
                </div>
            </div>

        </div>
    </footer>

</body>

</html>
