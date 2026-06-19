<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/productos', [ProductController::class, 'index'])->name('products.index');

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('productos', App\Http\Controllers\Admin\ProductController::class)
            ->parameters(['productos' => 'product'])
            ->only(['index', 'destroy']);

        Route::get('usuarios', [App\Http\Controllers\Admin\UserController::class, 'index'])
            ->name('users.index');

        Route::patch('usuarios/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])
            ->name('users.toggle');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.add');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/confirmacion/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
});

Route::prefix('panel')
    ->middleware('auth')
    ->name('seller.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('productos', App\Http\Controllers\Seller\ProductController::class)->parameters(['productos' => 'product']);
        Route::get('pedidos', [App\Http\Controllers\Seller\OrderController::class, 'index'])->name('orders');
    });

Route::get('/search', [ProductController::class, 'search'])
    ->name('products.search');

Route::get('/productos/{product}', [ProductController::class, 'show'])
    ->name('products.show');

Route::middleware('auth')->prefix('profile/catalog')->name('profile.catalog.')->group(function () {
    Route::post('/', [UserCatalogController::class, 'store'])->name('store');
    Route::patch('/{product}', [UserCatalogController::class, 'update'])->name('update');
    Route::delete('/{product}', [UserCatalogController::class, 'destroy'])->name('destroy');
});

Route::get('/carrito', [CartController::class, 'index'])
    ->name('cart.index')
    ->middleware('auth');

require __DIR__ . '/auth.php';

Route::get('/error', function () {
    return view('errors.404');
});
