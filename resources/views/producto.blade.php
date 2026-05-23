@extends('layouts.app')

@section('title', 'Detalle de Producto')

@section('content')
<div class="flex flex-col md:flex-row gap-6">
    <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" class="w-full md:w-1/2 rounded-lg shadow-lg">
    <div class="md:w-1/2">
        <h2 class="text-3xl font-bold mb-4">{{ $producto['nombre'] }}</h2>
        <p class="text-gray-700 mb-4">{{ $producto['descripcion'] }}</p>
        <p class="text-2xl font-bold text-violet-600 mb-4">${{ $producto['precio'] }}</p>
        <button class="bg-violet-600 text-white px-6 py-2 rounded">Agregar al carrito</button>
    </div>
</div>
@endsection