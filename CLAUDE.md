# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Marketo** — un marketplace académico construido con Laravel 12 (PHP 8.2+), Tailwind CSS 3 y Alpine.js (vía Blade). Cualquier usuario registrado puede vender productos en su panel de vendedor; un rol separado de admin gestiona la plataforma. Los visitantes no autenticados pueden navegar y agregar al carrito (sesión PHP), pero el checkout requiere login.

## Commands

```bash
# Setup inicial tras clonar (install deps, .env, key:generate, migrate, npm install + build)
composer run setup

# Stack completo de dev (servidor + queue + Vite hot-reload, concurrentemente)
composer run dev

# Correr todos los tests
composer run test
# O un test individual
php artisan test --filter=ExampleTest

# Formateo de código (Laravel Pint)
./vendor/bin/pint

# Migraciones y seeding
php artisan migrate
php artisan db:seed          # crea admin@marketo.com (role=admin) + test@example.com

# Compilar assets
npm run build

# Symlink de storage (requerido una vez después de clonar)
php artisan storage:link
```

## Architecture

### Roles & Access Control

Tres roles en `users.role`: `admin`, `usuario` (default al registrarse). Todos los `usuario` tienen acceso al panel de vendedor `/panel` automáticamente. `User::isAdmin()` y `User::isUsuario()` disponibles como helpers.

- Middleware `admin` → `EnsureUserIsAdmin` (`app/Http/Middleware/EnsureUserIsAdmin.php`)
- `ProductPolicy` (`app/Policies/ProductPolicy.php`) — controla create, update, delete, toggleActivo. Solo el dueño (`user_id`) o admin puede modificar/eliminar. Registrada manualmente en `AppServiceProvider::boot()`.
- `MergeGuestCart` listener (`app/Listeners/MergeGuestCart.php`) — escucha `Illuminate\Auth\Events\Login` y fusiona el carrito de sesión al carrito de DB del usuario.

### Route Groups

| Prefix | Middleware | Name prefix | Propósito |
|---|---|---|---|
| `/` | — | `home`, `products.*` | Catálogo público |
| `/carrito` | — (GET/POST público) | `cart.*` | Carrito (guest y auth) |
| `/favoritos` | `auth` | `favorites.*` | Favoritos |
| `/pedido` | `auth` | `checkout.*` | Checkout |
| `/admin` | `auth`, `admin` | `admin.` | Panel admin |
| `/panel` | `auth` | `seller.` | Panel vendedor |
| `/profile` | `auth` | `profile.*` | Perfil de usuario |

> **Nota:** `/carrito` GET y POST son públicos (guests pueden agregar al carrito). `/carrito/{cartItem}` PATCH y DELETE, y todo `/favoritos`, requieren auth.

### Controllers

Tres namespaces:
- `App\Http\Controllers\` — público: `ProductController`, `CartController`, `CheckoutController`, `FavoriteController`
- `App\Http\Controllers\Admin\` — `DashboardController`, `ProductController` (index + destroy únicamente), `UserController`, `CategoryController` (CRUD de categorías, sin destroy si la categoría tiene productos asociados)
- `App\Http\Controllers\Seller\` — `DashboardController`, `ProductController` (resource completo + `toggleActivo()`), `OrderController` (ventas recibidas), `ComprasController` (compras hechas por el vendedor como comprador, vía `pedidos`/`compras`)

`Seller\ProductController` usa `StoreProductRequest` y `UpdateProductRequest` en lugar de validación inline. Ambas requests aplican `App\Rules\SinContenidoOfensivo` a `name` y `description` (filtro de contenido ofensivo por tokens/frases en español rioplatense) y limitan `description` a 1000 caracteres.

### FormRequests (`app/Http/Requests/`)

| Request | Usado por |
|---|---|
| `StoreProductRequest` | `Seller\ProductController::store()` — authorize vía ProductPolicy |
| `UpdateProductRequest` | `Seller\ProductController::update()` — authorize vía ProductPolicy |
| `AddToCartRequest` | `CartController::store()` — authorize: true (guests y auth) |
| `UpdateCartRequest` | `CartController::update()` — authorize: auth check |
| `ProcessCheckoutRequest` | `CheckoutController::store()` |

`Admin\CategoryController` valida inline (`unique:categories,name`) en vez de usar FormRequests dedicados.

### Models & Relationships

```
User ──hasOne──> Cart ──hasMany──> CartItem ──belongsTo──> Product
     ──hasMany──> Order ──hasMany──> OrderItem ──belongsTo──> Product
     ──hasMany──> Favorite ──belongsTo──> Product
     ──hasMany──> Product   (como vendedor, FK user_id)

Product ──belongsTo──> Category (tiene slug para filtrado de catálogo)
        ──belongsTo──> User (alias seller())
```

Detalles clave de modelos:
- `Product::seller()` y `Product::user()` apuntan al mismo `User` vía `user_id` — preferir `seller()` en vistas.
- `Product::scopeSearch()` — busca solo en `name` (LIKE).
- `Product` usa `SoftDeletes` — eliminar un producto no borra sus `OrderItem`/`CartItem` históricos.
- `Product::is_active` — productos inactivos retornan 404 en `ProductController::show()`. Los vendedores pueden togglear con `Seller\ProductController::toggleActivo()`.
- `Product::inStock()` — helper `stock > 0`.
- `Product::imageUrl` (accessor) — si `image` ya es una URL absoluta (`http...`) la devuelve tal cual, sino la resuelve vía `asset('storage/...')`.
- `Cart::getOrCreate(User $user)` — factory estático; usar siempre en lugar de `Cart::firstOrCreate()` directamente.
- `User::apellido`, `User::direccion_entrega` — campos adicionales (no estándar de Breeze).

### Guest Cart (Carrito de invitados)

Estructura de sesión: `session('cart.items') = [['product_id' => int, 'quantity' => int], ...]`

- `CartController::index()` — si guest, lee sesión y hydrata con modelos Product. Si auth, lee DB cart.
- `CartController::store()` — si guest, guarda/incrementa en sesión con validación de stock. Si auth, usa DB cart.
- Al hacer login, `MergeGuestCart` fusiona la sesión al DB cart automáticamente y borra `session('cart')`.

### Checkout Flow

1. `/carrito` → usuario ve items y CTA "Finalizar compra" (auth) o "Iniciar sesión para comprar" (guest)
2. `/pedido` — `CheckoutController::index()` muestra form con dirección de entrega
3. POST `/pedido` — `CheckoutController::store()` envuelve en `DB::transaction()` la creación de `Order` + `OrderItem`s, el decremento de stock y el vaciado de carrito
4. Redirige a `/pedido/{order}/confirmacion`

### Servicios

- `App\Services\CurrencyService` — cotiza ARS→USD contra `https://dolarapi.com/v1/dolares/blue` (campo `venta`), cacheado 1h (`Cache::remember`). Si la API falla o la tabla de cache no existe (p. ej. en CI sin `cache` table), usa el fallback hardcodeado `1200.0` en vez de romper. Se invoca en **cada request**: `AppServiceProvider::boot()` registra un `View::composer('*', ...)` que comparte la variable `usdToArs` (el rate de `getRate()`) a todas las vistas.
- `App\Rules\SinContenidoOfensivo` — `ValidationRule` usado en `name`/`description` de productos; bloquea tokens y frases ofensivas (homofobia, racismo, ableísmo, xenofobia, vulgaridad rioplatense) con normalización de espacios para detectar variantes pegadas.

`AppServiceProvider::boot()` también fija `Paginator::defaultView('vendor.pagination.marketo')` — la paginación en toda la app usa la vista custom `resources/views/vendor/pagination/marketo.blade.php` en vez del estilo Tailwind por defecto.

### Frontend Stack

- **Tailwind CSS** con sistema de diseño oscuro personalizado. Todos los tokens de color (`background`, `surface`, `border`, `primary`, `accent`, `text`, `muted`, `success`, `error`, `warning`) están en `tailwind.config.js` — usar estos tokens, no valores hex directos.
- **Fuentes**: `font-oxanium` (brand/headings) y `font-jakarta` (body) — definidas como font families de Tailwind.
- **Alpine.js** — usado inline en Blade para UI interactiva (toasts, dropdowns, preview de imágenes).
- **Vite** compila assets; `@vite(['resources/css/app.css', 'resources/js/app.js'])` en layouts.

### Layouts

- `layouts/app.blade.php` — layout principal del storefront con nav (logo Marketo, carrito con contador, user dropdown) + footer
- `layouts/admin.blade.php` — layout del panel de administración (sidebar + header)
- `seller/layout.blade.php` — layout del panel de vendedor (sidebar + main content) — **no está bajo `layouts/`**
- `layouts/guest.blade.php` — páginas no autenticadas (auth flows)

### Blade Components

| Componente | Archivo | Props |
|---|---|---|
| `<x-app.logo>` | `components/app/logo.blade.php` | `$compact=false` |
| `<x-ui.alert>` | `components/ui/alert.blade.php` | `$type`, `$message`, `$autoDismiss=true` |
| `<x-ui.stat-card>` | `components/ui/stat-card.blade.php` | `$label`, `$value`, `$icon` (SVG path), `$color='primary'` |
| `<x-ui.empty-state>` | `components/ui/empty-state.blade.php` | `$icon`, `$title`, `$description`, `$slot` |
| `<x-ui.product-card>` | `components/ui/product-card.blade.php` | `$product`, `$isFavorite=false` |
| `<x-ui.category-filter>` | `components/ui/category-filter.blade.php` | `$categories`, `$categorySlug`, `$frontOnly` |

Las imágenes de productos se almacenan en `Storage::disk('public')` bajo `products/` y se sirven vía symlink `storage`.

## Git & Commit Conventions

Branch pattern: `MSGRUP-{ticket}-{short-description}` desde `develop`. PRs requieren 1 aprobación antes de mergear a `develop`. Nunca pushear directo a `main`.

Commit format: `{US-ID}/{type}: {message}`
- Types: `feat`, `fix`, `style`, `refactor`, `db`, `chore`, `docs`
- Ejemplo: `MSGRUP-95/feat: agregar página 404 personalizada`
