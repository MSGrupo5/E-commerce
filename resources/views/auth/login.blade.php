@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Iniciar sesión</h2>
    <form>
        <input type="email" placeholder="Correo" class="w-full border p-2 mb-4 rounded">
        <input type="password" placeholder="Contraseña" class="w-full border p-2 mb-4 rounded">
        <button class="bg-violet-600 text-white px-4 py-2 rounded w-full">Entrar</button>
    </form>
</div>
@endsection