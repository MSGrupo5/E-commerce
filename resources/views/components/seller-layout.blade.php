<x-app-layout>

    <div class="flex flex-col gap-5 lg:flex-row lg:gap-8">

        {{-- Sidebar --}}
        <aside class="w-full shrink-0 lg:w-56">
            <div class="rounded-2xl border border-border bg-surface p-2 shadow-[0_8px_32px_rgba(0,0,0,0.2)]">

                {{-- Info del vendedor — sólo desktop --}}
                <div class="hidden lg:flex items-center gap-3 px-3 py-3 mb-1 border-b border-border/50">
                    <div class="w-8 h-8 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center text-primary font-semibold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-text truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-muted">Vendedor</p>
                    </div>
                </div>

                {{-- Navegación --}}
                <nav class="flex flex-row lg:flex-col gap-1 mt-0 lg:mt-1">
                    <a href="{{ route('seller.dashboard') }}"
                        class="flex flex-1 lg:flex-none items-center justify-center lg:justify-start gap-2 lg:gap-3 rounded-xl border px-3 py-2.5 lg:px-4 lg:py-3 text-xs sm:text-sm font-medium transition-all
                            {{ request()->routeIs('seller.dashboard') ? 'bg-primary/10 text-primary border-primary/30' : 'text-muted hover:text-text hover:bg-background border-transparent' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="9" rx="1" />
                            <rect x="14" y="3" width="7" height="5" rx="1" />
                            <rect x="14" y="12" width="7" height="9" rx="1" />
                            <rect x="3" y="16" width="7" height="5" rx="1" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('seller.productos.index') }}"
                        class="flex flex-1 lg:flex-none items-center justify-center lg:justify-start gap-2 lg:gap-3 rounded-xl border px-3 py-2.5 lg:px-4 lg:py-3 text-xs sm:text-sm font-medium transition-all
                            {{ request()->routeIs('seller.productos.*') ? 'bg-primary/10 text-primary border-primary/30' : 'text-muted hover:text-text hover:bg-background border-transparent' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <span class="hidden xs:inline sm:inline">Productos</span>
                        <span class="xs:hidden sm:hidden">Prod.</span>
                    </a>

                    <a href="{{ route('seller.orders') }}"
                        class="flex flex-1 lg:flex-none items-center justify-center lg:justify-start gap-2 lg:gap-3 rounded-xl border px-3 py-2.5 lg:px-4 lg:py-3 text-xs sm:text-sm font-medium transition-all
                            {{ request()->routeIs('seller.orders') ? 'bg-primary/10 text-primary border-primary/30' : 'text-muted hover:text-text hover:bg-background border-transparent' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                        <span>Pedidos</span>
                    </a>

                    <a href="{{ route('seller.compras') }}"
                        class="flex flex-1 lg:flex-none items-center justify-center lg:justify-start gap-2 lg:gap-3 rounded-xl border px-3 py-2.5 lg:px-4 lg:py-3 text-xs sm:text-sm font-medium transition-all
                            {{ request()->routeIs('seller.compras') ? 'bg-primary/10 text-primary border-primary/30' : 'text-muted hover:text-text hover:bg-background border-transparent' }}">
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z"/>
                        </svg>
                        <span>Compras</span>
                    </a>
                </nav>
            </div>
        </aside>

        {{-- Contenido --}}
        <div class="min-w-0 flex-1">
            <div class="rounded-2xl sm:rounded-[32px] border border-border bg-surface p-4 sm:p-6 lg:p-8 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">

                <x-ui.alert type="success" :message="session('success')" />
                <x-ui.alert type="error"   :message="session('error')" />

                {{ $slot }}
            </div>
        </div>

    </div>

</x-app-layout>
