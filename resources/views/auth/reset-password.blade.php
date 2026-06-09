<x-guest-layout>

    <!-- Encabezado del Formulario -->

    <div class="mb-6 text-center">

        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">

            {{ __('Restablecer Contraseña') }}

        </h2>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">

            {{ __('Por favor, ingresá tu nueva contraseña técnica a continuación.') }}

        </p>

    </div>


    <form method="POST" action="{{ route('password.store') }}">

        @csrf


        <!-- Token de Restablecimiento (Obligatorio para Laravel) -->

        <input type="hidden" name="token" value="{{ $request->route('token') }}">


        <!-- Correo Electrónico -->

        <div>

            <x-input-label for="email" :value="__('Correo Electrónico')" class="font-medium text-gray-700 dark:text-gray-300" />

            <x-text-input id="email" class="block mt-1 w-full bg-gray-50 dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>


        <!-- Nueva Contraseña -->

        <div class="mt-4">

            <x-input-label for="password" :value="__('Nueva Contraseña')" class="font-medium text-gray-700 dark:text-gray-300" />

            <x-text-input id="password" class="block mt-1 w-full border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" type="password" name="password" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>


        <!-- Confirmar Nueva Contraseña -->

        <div class="mt-4">

            <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" class="font-medium text-gray-700 dark:text-gray-300" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

        </div>


        <!-- Botón de Acción de Ancho Completo -->

        <div class="mt-6">

            <x-primary-button class="w-full justify-center py-2.5 text-sm font-semibold tracking-wider uppercase transition duration-150 ease-in-out bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900 rounded-lg shadow-md">

                {{ __('Actualizar Contraseña') }}

            </x-primary-button>

        </div>

    </form>

</x-guest-layout>
