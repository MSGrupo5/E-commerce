@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<h2 class="text-xl font-semibold mb-6">Productos</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($productos as $producto)
        <div class="bg-white shadow-md rounded-lg p-4">
            <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="w-full h-40 object-cover rounded">
            <h3 class="text-lg font-bold mt-2">{{ $producto['nombre'] }}</h3>
            <p class="text-gray-600">${{ $producto['precio'] }}</p>
            <button class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Agregar al carrito</button>
        </div>
    @endforeach
</div>
@endsection