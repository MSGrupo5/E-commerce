@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<h2 class="text-2xl font-bold mb-6">Tu Carrito</h2>

<table class="w-full bg-white shadow-md rounded-lg">
    <thead>
        <tr class="bg-gray-200">
            <th class="p-2">Producto</th>
            <th class="p-2">Precio</th>
            <th class="p-2">Cantidad</th>
            <th class="p-2">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="p-2">Laptop Gamer</td>
            <td class="p-2">$1200</td>
            <td class="p-2">1</td>
            <td class="p-2">$1200</td>
        </tr>
    </tbody>
</table>

<div class="mt-6 text-right">
    <button class="bg-violet-600 text-white px-6 py-2 rounded">Finalizar compra</button>
</div>
@endsection