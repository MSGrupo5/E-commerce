<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-text">
            Mi perfil
        </h2>
    </x-slot>

    <div x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'info' }" class="flex flex-col gap-6 lg:flex-row lg:gap-8">

        {{-- Sidebar / Tab navigation --}}
        <aside class="w-full shrink-0 lg:w-64">
            <div class="rounded-[32px] border border-border bg-surface p-3 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                <nav class="flex flex-row gap-1 lg:flex-col">
                    <button type="button" @click="tab = 'info'"
                        :class="tab === 'info'
                            ? 'bg-primary/10 text-primary border-primary/30'
                            : 'text-muted hover:text-text hover:bg-background border-transparent'"
                        class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <span>Mis datos</span>
                    </button>

                    <button type="button" @click="tab = 'password'"
                        :class="tab === 'password'
                            ? 'bg-primary/10 text-primary border-primary/30'
                            : 'text-muted hover:text-text hover:bg-background border-transparent'"
                        class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <span>Cambiar contraseña</span>
                    </button>

                    <button type="button" @click="tab = 'catalog'"
                        :class="tab === 'catalog'
                            ? 'bg-primary/10 text-primary border-primary/30'
                            : 'text-muted hover:text-text hover:bg-background border-transparent'"
                        class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                        </svg>
                        <span>Mi catálogo</span>
                    </button>

                    <button type="button" @click="tab = 'delete'"
                        :class="tab === 'delete'
                            ? 'bg-error/10 text-error border-error/30'
                            : 'text-muted hover:text-error hover:bg-error/5 border-transparent'"
                        class="flex w-full items-center gap-3 rounded-2xl border px-4 py-3 text-sm font-medium transition-all">
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <span>Eliminar cuenta</span>
                    </button>
                </nav>
            </div>
        </aside>

        {{-- Content panels --}}
        <div class="min-w-0 flex-1">
            <div x-show="tab === 'info'" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-[32px] border border-border bg-surface p-6 sm:p-8 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <div x-show="tab === 'password'" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-[32px] border border-border bg-surface p-6 sm:p-8 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div x-show="tab === 'delete'" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-[32px] border border-border bg-surface p-6 sm:p-8 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

            <div x-show="tab === 'catalog'" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-[32px] border border-border bg-surface p-6 sm:p-8 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">
                    @include('profile.partials.catalog-manager')
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
