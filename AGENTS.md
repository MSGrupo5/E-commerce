# NexusTech E-commerce — Contexto del Proyecto

## Stack
- **Backend**: Laravel 12, PHP ^8.2
- **Frontend**: Tailwind CSS v3 (dark theme), Alpine.js 3, Blade, Vite 7
- **Database**: MySQL (`ecommerce`, root/doki2020)
- **Auth**: Laravel Breeze (session-based)

## Estructura de directorios
```
app/
  Http/
    Controllers/
      Auth/        -- AuthenticatedSession, RegisteredUser, Password, VerifyEmail, etc.
      Controller.php
      ProductController.php    -- index (paginate 12), show (check is_active)
      ProfileController.php    -- edit, update, destroy
    Middleware/
      EnsureUserIsAdmin.php    -- checks isAdmin(), abort(403)
    Requests/
      Auth/LoginRequest.php    -- rate limit (5 attempts)
      ProfileUpdateRequest.php -- name, email, apellido, direccion_entrega
  Models/
    User.php       -- role (cliente|admin), cart(), orders(), favorites()
    Product.php    -- category(), cartItems(), orderItems(), favorites(), scopeSearch, inStock()
    Category.php   -- products()
    Cart.php       -- user(), items()
    CartItem.php   -- cart(), product()
    Order.php      -- user(), items(), isPending/isPaid/isCancelled
    OrderItem.php  -- order(), product()
    Favorite.php   -- user(), product()
  Providers/
    AppServiceProvider.php     -- empty
  View/Components/
    AppLayout.php, GuestLayout.php
bootstrap/app.php              -- alias 'admin' => EnsureUserIsAdmin
routes/
  web.php         -- /, /productos, /dashboard, /profile, /productos/{product}
  auth.php        -- register, login, password, verify, logout
  console.php     -- inspire
database/
  migrations/     -- 14 migrations (users, products, categories, carts, orders, etc.)
  seeders/        -- DatabaseSeeder, CategorySeeder, ProductSeeder
resources/views/
  layouts/
    app.blade.php         -- Frontend layout (NexusTech header/search/cart/favorites/footer)
    admin.blade.php       -- Admin panel layout (sidebar, role switcher UI)
    guest.blade.php       -- Auth pages layout
  products/
    index.blade.php       -- Product grid + category filter + pagination
    show.blade.php        -- **NO EXISTE** (referenciado por ProductController)
  auth/                   -- register, login, forgot-password, reset-password, verify-email
  profile/                -- edit, partials (delete-user, update-password, update-profile)
  components/ui/
    product-card.blade.php    -- Card with image, favorite toggle, price, stock, cart button
    category-filter.blade.php -- Sidebar category filter (hardcoded sample data as fallback)
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

## Estado actual y pendientes conocidos

### Completado
- Auth completo (register, login, logout, password reset, email verification)
- Productos: listado con paginación, categorías, búsqueda (scope)
- Perfil de usuario: editar nombre, apellido, email, direccion_entrega
- Layouts: frontend (NexusTech) y admin (panel con sidebar)
- Migraciones: todas las tablas creadas (users, products, categories, carts, orders, etc.)
- Seeders: CategorySeeder + ProductSeeder
- Admin middleware registrado como alias 'admin'

### Pendiente / Incompleto
1. **`products.show`** — La vista no existe (ProductController::show la referencia)
2. **Migración `add_direccion_entrega_to_users_table`** — Tiene `up()` y `down()` vacíos (solo `//`)
3. **Carrito** — Los modelos Cart/CartItem existen, pero no hay rutas, controladores ni lógica backend. La UI referencia `route('cart.add')`
4. **Favoritos** — Modelo Favorite existe, pero no hay ruta `favorites.toggle` ni controlador
5. **Órdenes** — Modelos Order/OrderItem existen, pero no hay lógica de checkout
6. **Admin panel** — Las vistas admin/ están vacías. El layout admin tiene navegación a `/catalog`, `/orders`, `/clients`, `/roles` sin rutas backend
7. **Búsqueda** — El formulario de búsqueda apunta a `GET /search` sin ruta definida
8. **Rutas faltantes** en vistas: `/cart`, `/favorites`, `/orders`, `/catalog`, `/search`, `/support`, `/shipping`, `/warranty`, `/terms`, `/privacy`
9. **Paginación personalizada** — `vendor/pagination/` está vacío (usa defaults de Laravel)
10. **Role switcher en admin** — Es solo UI con Alpine.js (no hay roles más allá de cliente/admin)

## Comandos útiles
```bash
npm run dev          # Iniciar Vite dev server
php artisan serve    # Iniciar Laravel dev server
php artisan migrate:fresh --seed  # Reset DB + seed
php artisan make:controller NombreController
php artisan make:model Nombre -m
php artisan make:middleware Nombre
```
