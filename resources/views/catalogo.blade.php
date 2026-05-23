@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<h2 class="text-2xl font-bold mb-6">Nueva colección gaming</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($productos as $producto)
        <div class="bg-white shadow-lg rounded-lg p-4 hover:scale-105 transition">
            <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="w-full h-40 object-cover rounded">
            <h3 class="text-lg font-semibold mt-2">{{ $producto['nombre'] }}</h3>
            <p class="text-gray-700 font-bold">${{ $producto['precio'] }}</p>
            <span class="text-sm text-green-600">Envío gratis</span>
            <button class="bg-violet-600 text-white px-4 py-2 rounded mt-2 w-full">Agregar al carrito</button>
        </div>
    @endforeach
</div>
@endsection