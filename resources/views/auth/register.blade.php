@extends('layouts.app')

@section('title', 'Crear Cuenta - NexusTech')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Header Card -->
        <div class="bg-linear-to-r from-violet-600 to-violet-700 text-white rounded-t-2xl p-8 text-center shadow-lg">
            <h1 class="text-3xl font-bold mb-2">NexusTech</h1>
            <p class="text-violet-100 text-sm">Únete a nuestra comunidad gaming</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow-xl rounded-b-2xl p-8 border-t-4 border-violet-600">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Crear cuenta</h2>
            <p class="text-gray-600 text-sm mb-6">Completa el formulario para registrarte</p>

            <form action="/register" method="POST" class="space-y-5">
                @csrf

                <!-- Nombre Completo -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nombre completo
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required
                           placeholder="Juan Pérez"
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-100 text-gray-900 placeholder-gray-400">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Correo electrónico
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required
                           placeholder="correo@example.com"
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-100 text-gray-900 placeholder-gray-400">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="Mínimo 8 caracteres"
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-100 text-gray-900 placeholder-gray-400">
                    <p class="text-gray-500 text-xs mt-1">Usa mayúsculas, minúsculas y números</p>
                    @error('password')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Confirmar contraseña
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           placeholder="Repite tu contraseña"
                           class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg transition-colors duration-200 focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-100 text-gray-900 placeholder-gray-400">
                </div>

                <!-- Botón Registrarse -->
                <button type="submit"
                        class="w-full bg-linear-to-r from-violet-600 to-violet-700 hover:from-violet-700 hover:to-violet-800 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 active:scale-95 shadow-lg mt-6">
                    Crear cuenta
                </button>
            </form>

            <!-- Link Login -->
            <div class="mt-6 text-center border-t pt-6">
                <p class="text-gray-600 text-sm">
                    ¿Ya tienes cuenta? 
                    <a href="/login" class="text-violet-600 hover:text-violet-700 font-semibold transition-colors">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </div>

        <!-- Security Badge -->
        <div class="mt-4 flex items-center justify-center text-xs text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
            </svg>
            Tu información está segura y encriptada
        </div>
    </div>
</div>
@endsection