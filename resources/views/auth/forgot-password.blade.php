<x-guest-layout>
    <div class="w-full mx-auto" style="max-width: 440px;">
        
        <div class="flex flex-col items-center mb-8">
            <a href="{{ route('home') }}" class="mb-6">
                <x-app.logo />
            </a>
            <h1 class="text-h3 font-bold text-text text-center mb-1.5 font-oxanium">Recuperar contraseña</h1>
            <p class="text-muted text-h6 text-center font-jakarta">Te ayudamos a recuperar el acceso</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="rounded-2xl p-6 bg-surface border border-border">
            <p class="text-muted text-small font-jakarta mb-6">
                Ingresá tu email y te enviaremos un link para restablecer tu contraseña.
            </p>

            <form method="post" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-small font-semibold text-muted mb-1.5 font-jakarta">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full bg-surface border border-border rounded-xl px-4 text-text text-h6 outline-none font-jakarta focus:border-primary transition-all" 
                           style="padding-top: 13px; padding-bottom: 13px;" placeholder="tuemail@gmail.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-error text-label" />
                </div>

                <button type="submit" class="w-full py-4 mt-2 rounded-xl bg-primary text-background text-body font-bold hover:opacity-90 transition-all cursor-pointer border-none font-jakarta flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentcolor" stroke-width="2" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                    </svg>
                    Enviar link
                </button>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-muted text-h6 font-jakarta hover:text-text transition-colors cursor-pointer border-none bg-transparent">
                        &larr; Volver al login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>