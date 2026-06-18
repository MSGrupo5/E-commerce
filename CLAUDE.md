# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**NexusTech** — an academic e-commerce project built with Laravel 12 (PHP 8.2+), Tailwind CSS 3, and Alpine.js (via Blade). The app is a multi-role marketplace where any authenticated user can sell products (seller role), and a separate admin role manages the platform.

## Commands

```bash
# Full dev stack (server + queue + Vite hot-reload, concurrently)
composer run dev

# Run tests
composer run test
# or a single test file
php artisan test --filter=ExampleTest

# Code formatting (Laravel Pint)
./vendor/bin/pint

# Migrations & seeding
php artisan migrate
php artisan db:seed          # creates admin@nexustech.com (role=admin) + test@example.com

# Rebuild assets
npm run build

# Create storage symlink (required once after clone)
php artisan storage:link
```

## Architecture

### Roles & Access Control

Three roles on `users.role`: `admin`, `usuario` (default for new registrations), and any authenticated user can act as a seller via the `/panel` routes.

- `admin` middleware alias → `EnsureUserIsAdmin` (`app/Http/Middleware/EnsureUserIsAdmin.php`)
- `ProductPolicy` (`app/Policies/ProductPolicy.php`) gates seller product edits: only the owner (`user_id`) or an admin can update/delete a product
- The policy is registered manually in `AppServiceProvider::boot()` via `Gate::policy()`

### Route Groups

| Prefix | Middleware | Name prefix | Purpose |
|---|---|---|---|
| `/` | — | `home`, `products.*` | Public catalog |
| `/admin` | `auth`, `admin` | `admin.` | Admin panel |
| `/panel` | `auth` | `seller.` | Seller panel |
| `/profile` | `auth` | `profile.*` | User profile |
| `/cart` | `auth` | `cart.*` | Shopping cart |

### Controllers

Namespaced into three groups mirroring the route groups:
- `App\Http\Controllers\` — public-facing (ProductController, CartController, UserCatalogController)
- `App\Http\Controllers\Admin\` — DashboardController, ProductController, UserController
- `App\Http\Controllers\Seller\` — DashboardController, ProductController, OrderController

Both `Admin\ProductController` and `Seller\ProductController` exist — be careful to import the correct one.

### Models & Relationships

```
User ──hasOne──> Cart ──hasMany──> CartItem ──belongsTo──> Product
     ──hasMany──> Order ──hasMany──> OrderItem ──belongsTo──> Product
     ──hasMany──> Favorite ──belongsTo──> Product
     ──hasMany──> Product   (seller relationship — foreign key user_id)

Product ──belongsTo──> Category
        ──belongsTo──> User (aliased as seller())
```

`Product::seller()` and `Product::user()` both point to `User` via `user_id` — `seller()` is the named alias used in views/queries for clarity.

`Product::scopeSearch()` searches `name`, `description`, and the related seller's `name` field.

### Frontend Stack

- **Tailwind CSS** with a custom dark-mode design system. All color tokens (`background`, `surface`, `border`, `primary`, `accent`, `text`, `muted`, `success`, `error`, `warning`) are defined in `tailwind.config.js` — use these tokens, not raw hex values.
- **Fonts**: `font-oxanium` (brand/headings) and `font-jakarta` (body) — both defined as Tailwind font families.
- **Alpine.js** is used inline in Blade for interactive UI (toast notifications, dropdowns).
- **Vite** compiles assets; `@vite(['resources/css/app.css', 'resources/js/app.js'])` in layouts.

### Layouts

- `layouts/app.blade.php` — main storefront layout with full nav + footer
- `layouts/admin.blade.php` — admin panel layout
- `seller/layout.blade.php` — seller panel layout
- `layouts/guest.blade.php` — unauthenticated pages (auth flows)

Product images are stored via `Storage::disk('public')` under `products/` and served through the `storage` symlink.

## Git & Commit Conventions

Branch pattern: `MSGRUP-{ticket}-{short-description}` branching from `develop`. PRs require 1 approval before merging to `develop`. Never push directly to `main`.

Commit format: `{US-ID}/{type}: {message}`
- Types: `feat`, `fix`, `style`, `refactor`, `db`, `chore`, `docs`
- Example: `MSGRUP-95/feat: agregar página 404 personalizada`
