<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/', function () {
    $productos = [
        ['nombre' => 'Laptop', 'precio' => 1200, 'imagen' => 'https://via.placeholder.com/150'],
        ['nombre' => 'Celular', 'precio' => 800, 'imagen' => 'https://via.placeholder.com/150'],
        ['nombre' => 'Auriculares', 'precio' => 150, 'imagen' => 'https://via.placeholder.com/150'],
    ];
    return view('catalogo', compact('productos'));
});