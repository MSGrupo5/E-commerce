# NexusTech E-commerce

## Stack
- **Backend**: Laravel 12 / PHP ^8.2
- **Frontend**: Tailwind CSS 3, Alpine.js 3, Blade, Vite 7
- **Auth**: Laravel Breeze (session-based)
- **DB**: MySQL (`ecommerce_db`) — SQLite in-memory for tests (`phpunit.xml`)
- **Mail**: `MAIL_MAILER=log` — password reset links in `storage/logs/laravel.log`

## Commands
```bash
composer run dev       # php artisan serve + queue:listen + npm run dev (concurrently)
composer run test      # config:clear + php artisan test
./vendor/bin/pint      # Laravel Pint formatting
php artisan migrate:fresh --seed
php artisan storage:link   # required once after clone
npm run build / npm run dev
# Single test: php artisan test --filter=NombreTest
```

## Convenciones de código
- **Tipado**: Usar `declare(strict_types=1)` en nuevos archivos
- **Modelos**: `$fillable`, `$casts`, Type hints en relaciones (BelongsTo/HasMany/HasOne), helpers booleanos (isX()), scopes con `Builder`
- **Controladores**: Route Model Binding, `compact()` para pasar datos a vistas
- **Blade**: Componentes con `@props`, clases Tailwind con sintaxis oscura (`bg-background`, `text-muted`, `border-border`, `bg-primary`, `text-primary`, `text-error`)
- **Tailwind theme**: custom colors `background:#0b0b0f`, `surface:#141419`, `border:#1e1e2a`, `primary:#6c63ff`, `accent:#00d4ff`, `text:#f1f5f9`, `muted:#94a3b8`
- **Alpine.js**: Para interactividad cliente (modales, dropdowns, toggle password visibility)
- **Idioma**: UI en español, hardcoded (sin archivos de traducción)
- **Validación de imagen**: `nullable|image|mimes:jpeg,png,jpg,webp|max:2048` en todos los controllers con subida de imágenes (Seller, UserCatalog)
## Roles
- `admin` — full access via `admin` middleware (alias in `bootstrap/app.php`)
- `usuario` — default for new registrations
- Any authenticated user can sell via `/panel` routes (no seller role column)
- ProductPolicy gates ownership: owner (`user_id`) or admin can update/delete

## Route Groups
| Prefix | Middleware | Name | Purpose |
|--------|-----------|------|---------|
| `/` | — | `home`, `products.*` | Public catalog |
| `/admin` | `auth`, `admin` | `admin.` | Admin panel |
| `/panel` | `auth` | `seller.` | Seller panel |
| `/profile` | `auth` | `profile.*` | User profile |
| `/cart` | `auth` | `cart.*` | Shopping cart (already implemented) |

## Controller Namespaces
- `App\Http\Controllers\` — public (ProductController, CartController, UserCatalogController)
- `App\Http\Controllers\Admin\` — DashboardController, ProductController, UserController
- `App\Http\Controllers\Seller\` — DashboardController, ProductController, OrderController

Both `Admin\ProductController` and `Seller\ProductController` exist — watch imports.

## Key Gotchas
- **`is_active` not in `Product::$fillable`** — seeder sets it but mass-assignment may fail
- **`direccion_entrega` migration has empty `up()`/`down()`** — column in `$fillable` & `ProfileUpdateRequest` but may not exist in DB
- **CartController is fully implemented** — do not recreate. Use `Cart::getOrCreate($user)` as entry point
- **`UserCatalogController` exists** — lets users manage their own products from profile
- **`Product::seller()` and `Product::user()`** both point to `User` via `user_id` — `seller()` is the alias used in views
- **`scopeSearch()`** searches `name`, `description`, and seller's `name`
- **Product images** stored via `Storage::disk('public')` under `products/`
- **Admin views currently use `x-app-layout`** — `layouts.admin.blade.php` exists with sidebar but no views use it yet
- **Two fonts**: `font-oxanium` (headings) + `font-jakarta` (body); Figtree is Blade default fallback
- **Tailwind tokens**: `background:#0b0b0f`, `surface:#141419`, `border:#1e1e2a`, `primary:#6c63ff`, `accent:#00d4ff`, `text:#f1f5f9`, `muted:#94a3b8`, `success:#10b981`, `error:#ef4444`, `warning:#f59e0b`
- **`Route::has('favorites.toggle')` returns false** — Favorite model exists but no routes/controller
- **login/register routes** are in `routes/auth.php` (Breeze), do not duplicate in `web.php`

## Tests
- `tests/Feature/` for HTTP tests, `tests/Unit/` for logic
- Use `RefreshDatabase`; test names in Spanish (`test_usuario_puede_...`)
- CI: runs on ubuntu-latest with MySQL service, `composer install`, `npm run build`, `php artisan test`

## Pending Features
- Favorites toggle (model exists, no routes)
- Checkout / Order creation flow
- Admin: orders, clients, roles views
- Checkout flow not wired to create Orders from Cart
- Role switcher in admin UI is Alpine.js only — no backend

## Layouts
- `<x-app-layout>` — main storefront (authenticated)
- `<x-guest-layout>` — auth pages
- `<x-admin-layout>` — admin panel (sidebar exists, unused by views)
- `<x-seller-layout>` — seller panel
- `@vite(['resources/css/app.css', 'resources/js/app.js'])` in layouts
