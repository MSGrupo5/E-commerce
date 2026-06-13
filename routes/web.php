<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/productos', [ProductController::class, 'index'])->name('products.index'); // Ruta para mostrar la lista de productos

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
});

// Route::get('/app', function () {
//     return view('layouts.app', ['slot' => '']); // tambien me parece una ruta innecesaria.
// });

Route::get('login', function () {
    return view('auth.login');
});

Route::get('/search', [ProductController::class, 'search'])
    ->name('products.search');

Route::get('/productos/{product}', [ProductController::class, 'show'])
    ->name('products.show');

// Route::get('/catalog', function () {
//     return redirect()->route('products.index');    me parece que esta de mas
// })->name('catalog');

// Rutas para el catálogo del usuario (agregar, editar, eliminar productos del catálogo personal)
Route::middleware('auth')->prefix('profile/catalog')->name('profile.catalog.')->group(function () {
    Route::post('/', [UserCatalogController::class, 'store'])->name('store');
    Route::patch('/{product}', [UserCatalogController::class, 'update'])->name('update');
    Route::delete('/{product}', [UserCatalogController::class, 'destroy'])->name('destroy');
});

require __DIR__ . '/auth.php';
