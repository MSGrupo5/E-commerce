---
name: laravel-ecommerce
description: >
  Usar SIEMPRE que se trabaje en este proyecto Laravel 12. Cubre convenciones,
  estructura, modelos, rutas, controladores, vistas Blade, tests y reglas del
  equipo. Leer antes de crear o editar cualquier archivo PHP o Blade.
---

# Laravel E-commerce — Skill del proyecto MSGrupo5

## Stack y versiones

- **Laravel 12** / PHP ^8.2
- **Blade** + **Alpine.js** (interactividad client-side)
- **Tailwind CSS** (config extendida, ver skill de frontend)
- **Vite** para assets
- **PHPUnit 11** para tests
- Base de datos: MySQL (SQLite en tests con `RefreshDatabase`)
- Auth: Laravel Breeze (ya instalado)

---

## Estructura de directorios relevante

```
app/
  Http/
    Controllers/
      Auth/                      ← no tocar, generado por Breeze
      ProfileController.php
      ProductController.php      ← referencia para nuevos controllers públicos
      CartController.php         ← YA IMPLEMENTADO, no recrear
      UserCatalogController.php  ← maneja favoritos/wishlist del usuario
      DashboardController.php    ← dashboard admin básico
    Middleware/
      EnsureUserIsAdmin.php  ← middleware de rol admin, alias 'admin'
  Models/
    User.php        Cart.php       CartItem.php
    Product.php     Category.php   Favorite.php
    Order.php       OrderItem.php
  View/Components/
    AppLayout.php   GuestLayout.php

resources/views/
  layouts/
    app.blade.php        ← layout principal autenticado
    guest.blade.php      ← layout para login/register
    admin.blade.php      ← layout del panel admin
    navigation.blade.php ← navbar (actualizar cuando agregues rutas)
  components/ui/
    product-card.blade.php    ← componente de tarjeta de producto
    category-filter.blade.php ← sidebar de categorías
  products/
    index.blade.php      ← catálogo con sidebar + grid
  auth/                  ← no tocar

routes/
  web.php    ← todas las rutas del proyecto
  auth.php   ← rutas de Breeze, no tocar

database/
  migrations/   seeders/   factories/
```

---

## Modelos y relaciones

### User
- Campos: `name`, `apellido`, `email`, `password`, `role` (admin|cliente), `direccion_entrega`
- Helpers: `isAdmin()`, `isCliente()`
- Relaciones: `cart()` hasOne, `orders()` hasMany, `favorites()` hasMany

### Product
- Campos: `name`, `description`, `price` (float), `stock` (int), `image`, `category_id`, `is_active` (bool)
- Helpers: `inStock(): bool`
- Scopes: `scopeSearch(Builder $q, string $term)` — busca en name y description
- Relaciones: `category()` belongsTo, `cartItems()` hasMany, `orderItems()` hasMany, `favorites()` hasMany
- **Importante**: siempre filtrar `where('is_active', true)` en queries públicas

### Category
- Campos: `name`, `slug`
- El slug se auto-genera desde el nombre

### Cart / CartItem
- `Cart` pertenece a un `User` (hasOne desde User)
- `CartItem`: `cart_id`, `product_id`, `quantity`
- Un usuario tiene un solo carrito activo
- Helper estático: `Cart::getOrCreate($user)` — usar siempre en vez de crear el carrito manualmente
- **El CartController ya está completamente implementado** con validación de stock contra `Product.stock`

### Order / OrderItem
- Estados: `pending`, `paid`, `cancelled`
- Helpers: `isPending()`, `isPaid()`, `isCancelled()`
- `OrderItem`: `order_id`, `product_id`, `quantity`, `price` (precio al momento de compra)
- Al crear una Order desde el Cart: copiar el precio actual del producto en `order_items.price`

### Favorite
- `user_id`, `product_id` — tabla pivote simple

---

## Convenciones de Controllers

Seguir el patrón de `ProductController` como referencia:

```php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function index()
    {
        // Eager loading siempre que se usen relaciones
        $items = Product::with('category')
            ->where('is_active', true)
            ->paginate(12);
        return view('products.index', compact('items'));
    }

    public function show(Product $product)
    {
        // Route model binding siempre
        if (!$product->is_active) abort(404);
        return view('products.show', compact('product'));
    }
}
```

**Reglas:**
- Usar **route model binding** siempre que sea posible
- `abort(404)` para recursos no encontrados / inactivos
- Redirecciones con `redirect()->route('nombre.ruta')`
- Flash messages: `->with('success', 'Mensaje')` o `->with('error', 'Mensaje')`
- Validación inline con `$request->validate([...])` para métodos simples
- Comentarios en español, consistente con el resto del proyecto

---

## Convenciones de Rutas (`routes/web.php`)

```php
// Rutas ya existentes — NO recrear
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/search', [ProductController::class, 'search'])->name('products.search'); // redirige a index si ?q= vacío

Route::middleware('auth')->group(function () {
    // Carrito — YA IMPLEMENTADO COMPLETO
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Perfil — Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Catálogo de usuario (favoritos / wishlist)
    Route::post('/profile/catalog', [UserCatalogController::class, 'store'])->name('profile.catalog.store');
    Route::patch('/profile/catalog/{item}', [UserCatalogController::class, 'update'])->name('profile.catalog.update');
    Route::delete('/profile/catalog/{item}', [UserCatalogController::class, 'destroy'])->name('profile.catalog.destroy');
});

// Dashboard admin — ya existe, usa middleware auth+verified+admin
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Rutas pendientes de implementar:
// - Checkout (CheckoutController)
// - Admin CRUD de productos (Admin\ProductController)
// - Admin: orders, clients, roles (el sidebar del admin layout ya los referencia)
```

**Middleware disponibles (ya registrados en `bootstrap/app.php`):**
- `auth` — usuario autenticado (Breeze)
- `admin` — alias de `EnsureUserIsAdmin`, aborts 403 si `!$user->isAdmin()`
- `verified` — email verificado, requerido solo en `/dashboard`

---

## Vistas Blade

### Layouts
- Vistas de cliente: extender `<x-app-layout>`
- Vistas de guest: extender `<x-guest-layout>`
- Vistas de admin: **actualmente usan `<x-app-layout>`** (incluyendo `dashboard.blade.php`). `layouts/admin.blade.php` existe con sidebar pero ninguna vista lo usa todavía — al crear vistas admin nuevas, usar `layouts.admin` para migrar gradualmente
- **No mezclar**: una vez que una sección admin migre a `layouts.admin`, todas las vistas de esa sección deben usar ese layout

### Estructura de una vista típica

```blade
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-text leading-tight">
            Título de la página
        </h2>
    </x-slot>

    <div class="space-y-6 px-4 sm:px-6 lg:px-0">
        {{-- Mensajes flash --}}
        @if(session('success'))
            <div class="rounded-2xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Contenido --}}
    </div>
</x-app-layout>
```

### Componentes disponibles
- `<x-ui.product-card :product="$product" :isFavorite="false" />` — tarjeta de producto
- `<x-ui.category-filter :frontOnly="true" />` — sidebar de categorías
- `<x-primary-button>` — botón primario (violeta `#6c63ff`)
- `<x-secondary-button>` — botón secundario
- `<x-danger-button>` — botón de acción destructiva
- `<x-input-label>`, `<x-text-input>`, `<x-input-error>` — inputs de formulario

### Formularios con CSRF
```blade
<form action="{{ route('cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <x-primary-button>Agregar al carrito</x-primary-button>
</form>

{{-- Para DELETE/PATCH usar @method --}}
<form action="{{ route('cart.remove', $item) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Eliminar</button>
</form>
```

### Guard de rutas en vistas
Antes de usar `route()` verificar que existe para no romper vistas en desarrollo:
```blade
@if(Route::has('cart.add'))
    {{-- botón funcional --}}
@else
    {{-- botón deshabilitado (cursor-not-allowed) --}}
@endif
```

---

## Migrations

Nombrar con fecha actual y descripción clara:
```
php artisan make:migration add_columna_to_tabla_table
php artisan make:migration create_tabla_table
```

Siempre incluir `$table->timestamps()`. Usar `->nullable()` con criterio, preferir valores por defecto.

---

## Factories y Seeders

- `UserFactory`: genera rol `cliente` por defecto
- `ProductFactory`: asigna `category_id` aleatorio de categorías existentes
- `CategorySeeder`: crea las 6 categorías reales (Tarjetas de video, Motherboards, Monitores, Periféricos, Audio, Combos)
- `DatabaseSeeder`: llama CategorySeeder → ProductSeeder en ese orden (las categorías deben existir antes)
- Para el admin de prueba, crear manualmente con `role: 'admin'` en el seeder

---

## Tests

Ubicación: `tests/Feature/` para tests de HTTP, `tests/Unit/` para lógica pura.

```php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_puede_agregar_producto_al_carrito(): void
    {
        $user = User::factory()->create(['role' => 'cliente']);
        $product = Product::factory()->create(['stock' => 5, 'is_active' => true]);

        $response = $this->actingAs($user)
            ->post(route('cart.add'), ['product_id' => $product->id]);

        $response->assertRedirect();
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
        ]);
    }
}
```

**Reglas de tests:**
- Siempre usar `RefreshDatabase`
- Tests en español: `test_usuario_puede_...`, `test_admin_no_puede_...`
- Un assert por comportamiento cuando sea posible
- Testear el happy path Y el caso de error (sin stock, sin auth, etc.)

---

## Errores comunes a evitar

1. **No filtrar `is_active`** en queries públicas de productos
2. **No verificar stock** antes de agregar al carrito — el CartController ya lo hace, mantener ese patrón
3. **No copiar el precio** del producto en `order_items.price` al hacer checkout (el precio puede cambiar)
4. **Rutas sin nombre** — siempre usar `->name()`
5. **`Route::get('login', ...)` duplicado** en `web.php` — ya existe en `auth.php`, no agregar otra
6. **Ruta `/app` huérfana** — ignorar, no usar como referencia
7. **`via.placeholder.com`** está caído — usar `https://placehold.co/300x300` en factories
8. **`direccion_entrega` no existe en DB** — la migration `add_direccion_entrega_to_users_table` tiene `up()` y `down()` vacíos. La columna está en `$fillable` y en `ProfileUpdateRequest` pero NO en la base de datos. Antes de usar ese campo hay que corregir la migration
9. **No recrear el CartController** — ya está completamente implementado. Usar `Cart::getOrCreate($user)` como punto de entrada
10. **`Route::has('favorites.toggle')` retorna false** — el modelo `Favorite` existe pero no hay rutas ni controller. Algunas vistas ya tienen el guard `@if(Route::has(...))`, mantener ese patrón para features pendientes
11. **El role switcher del admin es solo Alpine.js** — no tiene backend. No asumir que cambiar el rol en la UI hace algo en la base de datos
