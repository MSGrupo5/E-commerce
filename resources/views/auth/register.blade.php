<x-guest-layout>
    <div class="w-full mx-auto" style="max-width: 440px;">

        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentcolor" stroke-width="2" viewbox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m13 2l-10 12h9l-2 8 10-12h-9l2-8z" />
                </svg>
                <span class="text-text font-bold text-h4 uppercase font-oxanium"
                    style="letter-spacing: 2px;">nexustech</span>
            </div>

            <h1 class="text-h3 font-bold text-text text-center mb-1.5 font-oxanium">Creá tu cuenta</h1>
            <p class="text-muted text-h6 text-center font-jakarta">Accedé a todos los beneficios NexusTech</p>
        </div>

        <div class="flex rounded-xl p-1 mb-8 bg-surface border border-border">
            <a href="{{ route('login') }}"
                class="flex-1 py-2.5 rounded-lg text-small font-semibold text-center transition-all bg-transparent text-muted hover:text-text font-jakarta">Iniciar
                Sesión</a>
            <div
                class="flex-1 py-2.5 rounded-lg text-small font-semibold text-center transition-all bg-primary text-text font-jakarta">
                Registrarse</div>
        </div>

        <div class="rounded-2xl p-6 bg-surface border border-border">
            <form method="post" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name"
                        class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Nombre</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full bg-surface border border-border rounded-xl px-4 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all"
                        style="padding-top: 13px; padding-bottom: 13px;" placeholder="Juan">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-error text-label" />
                </div>

                <div>
                    <label for="apellido"
                        class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Apellido</label>
                    <input id="apellido" type="text" name="apellido" value="{{ old('apellido') }}" required
                        class="w-full bg-surface border border-border rounded-xl px-4 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all"
                        style="padding-top: 13px; padding-bottom: 13px;" placeholder="Perez">
                    <x-input-error :messages="$errors->get('apellido')" class="mt-2 text-error text-label" />
                </div>

                <div>
                    <label for="email"
                        class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full bg-surface border border-border rounded-xl px-4 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all"
                        style="padding-top: 13px; padding-bottom: 13px;" placeholder="tuemail@gmail.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-label" />
                </div>

                <div>
                    <label for="password"
                        class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Contraseña</label>
                    <div x-data="{ show: false }" class="relative">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="w-full bg-surface border border-border rounded-xl pl-4 pr-12 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all"
                            style="padding-top: 13px; padding-bottom: 13px;" placeholder="••••••••">

                        <button type="button" @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-transparent border-none p-0 cursor-pointer text-muted hover:text-text transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentcolor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewbox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z" />
                                <circle cx="12" cy="12" r="3" />
                                <line x1="3" y1="3" x2="21" y2="21" />
                            </svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none"
                                stroke="currentcolor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                viewbox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-label" />
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Confirmar
                        Contraseña</label>
                    <div x-data="{ show: false }" class="relative">
                        <input id="password_confirmation" :type="show ? 'text' : 'password'"
                            name="password_confirmation" required
                            class="w-full bg-surface border border-border rounded-xl pl-4 pr-12 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all"
                            style="padding-top: 13px; padding-bottom: 13px;" placeholder="••••••••">

                        <button type="button" @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-transparent border-none p-0 cursor-pointer text-muted hover:text-text transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentcolor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewbox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z" />
                                <circle cx="12" cy="12" r="3" />
                                <line x1="3" y1="3" x2="21" y2="21" />
                            </svg>
                            <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none"
                                stroke="currentcolor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" viewbox="0 0 24 24">
                                <path d="m2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-error text-label" />
                </div>

                <button type="submit"
                    class="w-full py-4 mt-2 rounded-xl bg-primary text-text text-body font-bold hover:opacity-90 transition-all cursor-pointer border-none font-jakarta">
                    Crear Cuenta
                </button>

                <p class="text-center text-muted text-h6 font-jakarta pt-2">
                    ¿Ya tenés cuenta?
                    <a href="{{ route('login') }}"
                        class="text-primary font-semibold hover:opacity-80 transition-opacity bg-transparent border-none p-0 cursor-pointer font-jakarta">
                        Iniciá Sesión
                    </a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
