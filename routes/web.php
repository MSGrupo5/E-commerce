<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController;
use Illuminate\Support\Facades\Route;

// ─── Catálogo público ────────────────────────────────────────────────────────

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/sugerencias', [ProductController::class, 'suggestions'])->name('products.suggestions');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// ─── Carrito (público: guests y usuarios autenticados) ───────────────────────

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito', [CartController::class, 'store'])->name('cart.add');

Route::middleware('auth')->group(function () {
    Route::patch('/carrito/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/carrito/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Favoritos
    Route::get('/favoritos', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favoritos/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});

// ─── Checkout (requiere autenticación) ──────────────────────────────────────

Route::middleware(['auth', 'redirect.if.admin'])->prefix('pedido')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/{order}/confirmacion', [CheckoutController::class, 'confirmacion'])->name('confirmacion');
    Route::patch('/{order}/comprobante', [CheckoutController::class, 'comprobante'])->name('comprobante');
});

// ─── Perfil ──────────────────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Panel vendedor ──────────────────────────────────────────────────────────

Route::prefix('panel')
    ->middleware(['auth', 'redirect.if.admin'])
    ->name('seller.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('productos', App\Http\Controllers\Seller\ProductController::class)
            ->parameters(['productos' => 'product']);
        Route::patch('productos/{product}/toggle-activo', [App\Http\Controllers\Seller\ProductController::class, 'toggleActivo'])
            ->name('productos.toggle-activo');
        Route::get('pedidos', [OrderController::class, 'index'])->name('orders');
        Route::patch('pedidos/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('compras', [App\Http\Controllers\Seller\ComprasController::class, 'index'])->name('compras');
    });

// ─── Panel administrador ─────────────────────────────────────────────────────

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('productos', App\Http\Controllers\Admin\ProductController::class)
            ->parameters(['productos' => 'product'])
            ->only(['index', 'destroy']);

        Route::get('usuarios', [UserController::class, 'index'])
            ->name('users.index');

        Route::patch('usuarios/{user}/toggle', [UserController::class, 'toggleStatus'])
            ->name('users.toggle');

        Route::get('categorias', [CategoryController::class, 'index'])
            ->name('categorias.index');
        Route::post('categorias', [CategoryController::class, 'store'])
            ->name('categorias.store');
        Route::patch('categorias/{category}', [CategoryController::class, 'update'])
            ->name('categorias.update');
        Route::delete('categorias/{category}', [CategoryController::class, 'destroy'])
            ->name('categorias.destroy');
    });

require __DIR__.'/auth.php';

Route::get('/error', fn () => view('errors.404'));
