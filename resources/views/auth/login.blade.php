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
            <h1 class="text-h3 font-bold text-text text-center mb-1.5 font-oxanium">Bienvenido de vuelta
            </h1>
            <p class="text-muted text-h6 text-center font-jakarta">Iniciá sesión para continuar
            </p>
        </div>

        <div class="flex rounded-xl p-1 mb-8 bg-surface border border-border">
            <a href="{{ route('login') }}"
                class="flex-1 py-2.5 rounded-lg text-small font-semibold text-center transition-all bg-primary text-text font-jakarta">iniciar
                sesión</a>
            <a href="{{ route('register') }}"
                class="flex-1 py-2.5 rounded-lg text-small font-semibold text-center transition-all bg-transparent text-muted hover:text-text font-jakarta">registrarse</a>
        </div>

        <div class="rounded-2xl p-6 bg-surface border border-border">
            <form method="post" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email"
                        class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-surface border border-border rounded-xl px-4 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all"
                        style="padding-top: 13px; padding-bottom: 13px;" placeholder="tu@email.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-label" />
                </div>

                <div>
                    <label for="password"
                        class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Contraseña</label>
                    <div class="relative" x-data="{ show: false }">
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required
                            class="w-full bg-surface border border-border rounded-xl px-4 pr-12 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all"
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
                        
                        </input>    
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-error text-label" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative">
                            <input id="remember_me" type="checkbox" name="remember" class="peer sr-only">
                            <div
                                class="w-4 h-4 rounded border border-border bg-background 
                                peer-checked:bg-primary peer-checked:border-primary transition-all duration-200 group-hover:border-primary">
                            </div>
                            <svg class="absolute inset-0 w-4 h-4 text-text opacity-0 peer-checked:opacity-100 
                                transition-opacity duration-200 pointer-events-none"
                                viewbox="0 0 16 16" fill="none" stroke="currentcolor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m3 8l3.5 3.5l6.5-6.5" />
                            </svg>
                        </div>
                        <span
                            class="text-small text-muted font-jakarta group-hover:text-text transition-colors select-none">
                            Recordarme
                        </span>
                    </label>

                    <a href="{{ route('password.request') }}"
                        class="text-small text-primary font-medium hover:opacity-80 font-jakarta">
                        Olvidé mi contraseña
                    </a>
                </div>

                <button type="submit"
                    class="w-full py-4 rounded-xl bg-primary text-text text-body font-bold hover:opacity-90 transition-all cursor-pointer border-none font-jakarta">
                    Iniciar Sesión
                </button>

                <p class="text-center text-muted text-h6 font-jakarta pt-2">
                    ¿No tenés cuenta?
                    <a href="{{ route('register') }}" class="text-primary font-semibold hover:opacity-80">Registrate
                        Gratis
                    </a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
