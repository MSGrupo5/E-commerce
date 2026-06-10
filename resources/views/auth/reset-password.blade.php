<x-guest-layout>

    <div class="-my-4 -mx-6 p-8 bg-surface border border-border rounded-lg">

        <!-- Header -->

        <div class="text-center space-y-3 mb-8">

            <h2 class="font-oxanium text-h2 text-text font-bold tracking-tight">

                {{ __('Restablecer Contraseña') }}

            </h2>

            <p class="font-jakarta text-body text-muted leading-relaxed">

                {{ __('Por favor, ingresá tu nueva contraseña técnica a continuación.') }}

            </p>

        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">

            @csrf

            <!-- Token de Restablecimiento (Obligatorio para Laravel) -->

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Correo Electrónico -->

            <div>

                <x-input-label for="email" :value="__('Correo Electrónico')"

                    class="font-jakarta text-label text-text font-semibold mb-2 block" />

                <x-text-input id="email"

                    class="block w-full bg-background border-border text-text placeholder:text-muted rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out"

                    type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />

                <x-input-error :messages="$errors->get('email')" class="mt-2 font-jakarta text-small" />

            </div>

            <!-- Nueva Contraseña -->

            <div>

                <x-input-label for="password" :value="__('Nueva Contraseña')"

                    class="font-jakarta text-label text-text font-semibold mb-2 block" />

                <x-text-input id="password"

                    class="block w-full bg-background border-border text-text placeholder:text-muted rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out"

                    type="password" name="password" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2 font-jakarta text-small" />

            </div>

            <!-- Confirmar Nueva Contraseña -->

            <div>

                <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')"

                    class="font-jakarta text-label text-text font-semibold mb-2 block" />

                <x-text-input id="password_confirmation"

                    class="block w-full bg-background border-border text-text placeholder:text-muted rounded-lg shadow-sm focus:ring-primary focus:border-primary transition duration-150 ease-in-out"

                    type="password" name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 font-jakarta text-small" />

            </div>

            <!-- Botón de Acción de Ancho Completo -->

            <div>

                <x-primary-button

                    class="w-full justify-center py-3.5 font-jakarta text-small font-bold uppercase tracking-widest bg-primary hover:opacity-90 active:opacity-80 focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface rounded-lg shadow-lg transition-all duration-150 ease-in-out">

                    {{ __('Actualizar Contraseña') }}

                </x-primary-button>

            </div>

        </form>

    </div>

</x-guest-layout>
