<x-guest-layout>
    <div class="w-full mx-auto" style="max-width: 440px;">

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <a href="{{ route('home') }}" class="mb-6 flex justify-center w-full focus:outline-none">
                <x-app.logo :large="true" />
            </a>
            <h1 class="text-h3 font-bold text-text text-center mb-1.5 font-oxanium">Creá tu cuenta</h1>
            <p class="text-muted text-h6 text-center font-jakarta">Al registrarte podés comprar <strong class="text-text">y vender</strong> en Marketo</p>
        </div>

        {{-- Toggle login/register --}}
        <div class="flex rounded-xl p-1 mb-8 bg-surface border border-border">
            <a href="{{ route('login') }}"
                class="flex-1 py-2.5 rounded-lg text-small font-semibold text-center transition-all bg-transparent text-muted hover:text-text">
                Iniciar Sesión
            </a>
            <div class="flex-1 py-2.5 rounded-lg text-small font-semibold text-center bg-primary text-background">
                Registrarse
            </div>
        </div>

        {{-- Formulario --}}
        <div class="rounded-2xl p-6 bg-surface border border-border">
            <form method="post" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="name" class="block text-small font-semibold text-muted mb-1.5">Nombre</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="w-full bg-background border border-border rounded-xl px-4 py-3 text-text text-h6 outline-none focus:border-primary transition-all"
                            placeholder="Juan">
                        <x-input-error :messages="$errors->get('name')" class="mt-1 text-error text-label" />
                    </div>

                    <div>
                        <label for="apellido" class="block text-small font-semibold text-muted mb-1.5">Apellido</label>
                        <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" required
                            class="w-full bg-background border border-border rounded-xl px-4 py-3 text-text text-h6 outline-none focus:border-primary transition-all"
                            placeholder="Pérez">
                        <x-input-error :messages="$errors->get('apellido')" class="mt-1 text-error text-label" />
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-small font-semibold text-muted mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-background border border-border rounded-xl px-4 py-3 text-text text-h6 outline-none focus:border-primary transition-all"
                        placeholder="tuemail@gmail.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-error text-label" />
                </div>

                <div>
                    <label for="password" class="block text-small font-semibold text-muted mb-1.5">Contraseña</label>
                    <div x-data="{ show: false }" class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="w-full bg-background border border-border rounded-xl pl-4 pr-12 py-3 text-text text-h6 outline-none focus:border-primary transition-all"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-text transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="show" style="display:none" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                                <line x1="3" y1="3" x2="21" y2="21"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-error text-label" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-small font-semibold text-muted mb-1.5">Confirmar Contraseña</label>
                    <div x-data="{ show: false }" class="relative">
                        <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                            class="w-full bg-background border border-border rounded-xl pl-4 pr-12 py-3 text-text text-h6 outline-none focus:border-primary transition-all"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-text transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg x-show="show" style="display:none" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>
                                <line x1="3" y1="3" x2="21" y2="21"/>
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-error text-label" />
                </div>

                {{-- Nota marketplace --}}
                <div class="rounded-xl border border-primary/20 bg-primary/5 px-4 py-3">
                    <p class="text-xs text-primary/80 leading-relaxed">
                        <strong class="text-primary">¡Vendé desde el primer día!</strong>
                        Al crear tu cuenta obtenés acceso automático al panel de vendedor.
                    </p>
                </div>

                <button type="submit"
                    class="w-full py-4 mt-1 rounded-xl bg-primary text-background text-body font-bold hover:bg-primary/90 transition-colors cursor-pointer">
                    Crear Cuenta en Marketo
                </button>

                <p class="text-center text-muted text-h6 pt-1">
                    ¿Ya tenés cuenta?
                    <a href="{{ route('login') }}" class="text-primary font-semibold hover:opacity-80 transition-opacity">
                        Iniciá Sesión
                    </a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
