<?php

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
});

Route::get('/app', function () {
    return view('layouts.app', ['slot' => '']);
});

Route::get('login', function () {
    return view('auth.login');
});

Route::get('/search', [ProductController::class, 'search'])
    ->name('products.search');

Route::get('/productos/{product}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get('/catalog', function () {
    return redirect()->route('products.index');
})->name('catalog');

Route::middleware('auth')->prefix('profile/catalog')->name('profile.catalog.')->group(function () {
    Route::post('/', [UserCatalogController::class, 'store'])->name('store');
    Route::patch('/{product}', [UserCatalogController::class, 'update'])->name('update');
    Route::delete('/{product}', [UserCatalogController::class, 'destroy'])->name('destroy');
});

require __DIR__ . '/auth.php';
