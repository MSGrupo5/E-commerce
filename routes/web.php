<?php

use Illuminate\Support\Facades\Route;

// Catálogo de productos
Route::get('/', function () {
    $productos = [
        ['id' => 1, 'nombre' => 'Laptop Gamer', 'precio' => 1200, 'imagen' => 'https://via.placeholder.com/150'],
        ['id' => 2, 'nombre' => 'Mouse RGB', 'precio' => 50, 'imagen' => 'https://via.placeholder.com/150'],
        ['id' => 3, 'nombre' => 'Auriculares Pro', 'precio' => 150, 'imagen' => 'https://via.placeholder.com/150'],
    ];
    return view('catalogo', compact('productos'));
});

// Detalle de producto
Route::get('/producto/{id}', function ($id) {
    $productos = [
        1 => ['nombre' => 'Laptop Gamer', 'precio' => 1200, 'imagen' => 'https://via.placeholder.com/400', 'descripcion' => 'Laptop potente para gaming y trabajo.'],
        2 => ['nombre' => 'Mouse RGB', 'precio' => 50, 'imagen' => 'https://via.placeholder.com/400', 'descripcion' => 'Mouse ergonómico con luces RGB.'],
        3 => ['nombre' => 'Auriculares Pro', 'precio' => 150, 'imagen' => 'https://via.placeholder.com/400', 'descripcion' => 'Auriculares con sonido envolvente.'],
    ];

    // Buscar el producto por ID
    $producto = $productos[$id] ?? null;

    if (!$producto) {
        abort(404); // Si no existe, mostrar error 404
    }

    return view('producto', compact('producto'));
});

// Carrito de compras
Route::get('/carrito', function () {
    $carrito = [
        ['nombre' => 'Laptop Gamer', 'precio' => 1200, 'cantidad' => 1],
        ['nombre' => 'Mouse RGB', 'precio' => 50, 'cantidad' => 2],
    ];
    return view('carrito', compact('carrito'));
});


// Login
Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function () {
    // Simulación de login
    return redirect('/')->with('success', 'Inicio de sesión correcto (simulado)');
});

// Registro
Route::get('/register', function () {
    return view('auth.register');
});

Route::post('/register', function () {
    // Simulación de registro
    return redirect('/')->with('success', 'Usuario registrado correctamente (simulado)');
});