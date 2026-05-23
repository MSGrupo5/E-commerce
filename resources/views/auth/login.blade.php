@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-center">Iniciar sesión</h2>

    <form action="/login" method="POST" class="space-y-4">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
            <input type="email" id="email" name="email" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-violet-500 focus:border-violet-500">
        </div>

        <!-- Contraseña -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="password" name="password" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-violet-500 focus:border-violet-500">
        </div>

        <!-- Botón de login -->
        <div>
            <button type="submit"
                    class="w-full bg-violet-600 text-white py-2 px-4 rounded-lg hover:bg-violet-700">
                Iniciar sesión
                
            </button>
        </div>
    </form>

    <!-- Enlace a registro -->
    <p class="text-center text-sm text-gray-600 mt-4">
        ¿No tienes cuenta?
        <a href="/register" class="text-blue-600 font-semibold hover:underline">Crear una nueva cuenta</a>
    </p>
</div>
@endsection