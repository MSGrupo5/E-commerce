<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-text">Mi Panel</h2>
    </x-slot>

    <div class="flex flex-col gap-6 lg:flex-row lg:gap-8">

        <aside class="w-full shrink-0 lg:w-64">
            <div class="rounded-[32px] border border-border bg-surface p-3 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                <nav class="flex flex-row gap-1 lg:flex-col">
                    <a href="{{ route('seller.dashboard') }}"
                        class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all {{ request()->routeIs('seller.dashboard') ? 'bg-primary/10 text-primary border-primary/30' : 'text-muted hover:text-text hover:bg-background border-transparent' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="7" height="9" rx="1" />
                            <rect x="14" y="3" width="7" height="5" rx="1" />
                            <rect x="14" y="12" width="7" height="9" rx="1" />
                            <rect x="3" y="16" width="7" height="5" rx="1" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('seller.productos.index') }}"
                        class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all {{ request()->routeIs('seller.productos.*') ? 'bg-primary/10 text-primary border-primary/30' : 'text-muted hover:text-text hover:bg-background border-transparent' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" />
                            <rect x="9" y="9" width="6" height="6" />
                        </svg>
                        <span>Mis Productos</span>
                    </a>

                    <a href="{{ route('seller.orders') }}"
                        class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all {{ request()->routeIs('seller.orders') ? 'bg-primary/10 text-primary border-primary/30' : 'text-muted hover:text-text hover:bg-background border-transparent' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                        <span>Pedidos</span>
                    </a>
                </nav>
            </div>
        </aside>

        <div class="min-w-0 flex-1">
            <div class="rounded-[32px] border border-border bg-surface p-6 sm:p-8 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-cloak class="mb-6 rounded-2xl border border-success/30 bg-success/10 px-5 py-4">
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
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-cloak class="mb-6 rounded-2xl border border-error/30 bg-error/10 px-5 py-4">
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
        </div>

    </div>
</x-app-layout>
