# 🛒 Marketo E-commerce

> Plataforma de catálogo multidivendedor — Proyecto académico · Gestión de Sistemas · Metodología Scrum

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=flat&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=flat&logo=alpinedotjs&logoColor=white)
![Estado](https://img.shields.io/badge/Sprint_1-En_desarrollo-yellow?style=flat)

---

## Descripción

Marketo es un marketplace donde los usuarios pueden registrarse, explorar un catálogo de productos y comprar o vender artículos. Cada usuario registrado puede publicar sus propios productos desde un panel de vendedor, mientras que los administradores cuentan con un panel exclusivo para gestionar usuarios, productos y el funcionamiento general de la plataforma.

### ✨ Funcionalidades principales

- **Catálogo público** — navegación y búsqueda de productos con filtros
- **Registro y autenticación** — login/register con Laravel Breeze (sesiones)
- **Panel de vendedor** — los usuarios registrados pueden publicar, editar y eliminar sus propios productos
- **Panel de administración** — gestión completa de usuarios y productos
- **Carrito de compras** — funcionalidad completa para agregar y gestionar productos
- **Subida de imágenes** — cada producto puede incluir una imagen (JPEG, PNG, WebP; hasta 2 MB)
- **Diseño responsive** — interfaz oscura moderna con Tailwind CSS y Alpine.js

---

## Stack Tecnológico

| Tecnología | Versión | Propósito |
|---|---|---|
| **Laravel** | ^12.0 | Framework backend (MVC) |
| **PHP** | ^8.2 | Lenguaje de servidor |
| **MySQL** | 8.0 | Base de datos principal |
| **SQLite** | — | Base de datos en memoria para tests |
| **Tailwind CSS** | ^3.1 | Estilos utilitarios |
| **Alpine.js** | ^3.4 | Interactividad en el frontend |
| **Vite** | ^7.0 | Bundler de assets |
| **Laravel Breeze** | ^2.4 | Sistema de autenticación |

---

## Equipo

| Nombre | Rol |
|---|---|
| Valeria Budiño | Scrum Master |
| Samuel Casanueva | Product Owner + QA / Deploy |
| Ramiro Corrales | Developer — Backend |
| Mauricio Cardozo | Developer — Backend / DB |
| Nicolás Mendoza | Developer — Frontend / UI |
| Tomás Centurión | Developer — Frontend / UI |
| Marcos Urbina | Developer — Full Stack / Integración |

---

## Requisitos Previos

- **PHP** >= 8.2
- **Composer** >= 2.x → https://getcomposer.org
- **Node.js** >= 18.x + npm → https://nodejs.org
- **MySQL** >= 8.0
- **Git**

---

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/MSGrupo5/E-commerce.git
cd E-commerce

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node
npm install

# 4. Copiar el archivo de entorno
cp .env.example .env

# 5. Generar clave de la aplicación
php artisan key:generate

# 6. Configurar .env con tus credenciales de MySQL (ver Configuración del Entorno)

# 7. Crear la base de datos en MySQL
# CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 8. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# 9. Crear el enlace simbólico para imágenes
php artisan storage:link
```

### Iniciar el servidor de desarrollo

```bash
composer run dev
```

Esto ejecuta simultáneamente:
- `php artisan serve` — servidor HTTP en `http://localhost:8000`
- `php artisan queue:listen` — procesador de colas
- `npm run dev` — compilación de assets con Vite

> También podés ejecutar cada comando por separado si preferís.

---

## Configuración del Entorno (.env)

```env
APP_NAME="Marketo E-commerce"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=log
FILESYSTEM_DISK=public
```

> `MAIL_MAILER=log` — los emails no se envían al exterior; el link de recuperación de contraseña queda registrado en `storage/logs/laravel.log`.

---

## Ejecutar Tests

```bash
composer run test
```

Los tests usan **SQLite en memoria** con `RefreshDatabase`, por lo que no requieren configuración adicional de base de datos.

---

## Estructura de Rutas

| Prefix | Middleware | Descripción |
|---|---|---|
| `/` | — | Catálogo público de productos |
| `/admin/*` | `auth` + `admin` | Panel de administración |
| `/panel/*` | `auth` | Panel de vendedor |
| `/profile/*` | `auth` | Perfil de usuario |
| `/cart/*` | `auth` | Carrito de compras |

### Roles

- **`admin`** — acceso completo al panel de administración
- **`usuario`** — rol por defecto al registrarse; cualquier usuario puede vender desde `/panel`

---

## Manejo de imágenes de productos

- **Almacenamiento**: `storage/app/public/products/` (disco `public` de Laravel)
- **Acceso público**: requiere `php artisan storage:link` después de clonar el repositorio
- **Validación**: `nullable|image|mimes:jpeg,png,jpg,webp|max:2048` — imagen opcional, hasta 2 MB
- **Actualización**: al subir una nueva imagen, la anterior se elimina automáticamente
- **Eliminación**: al eliminar un producto sin pedidos asociados, su imagen también se borra

---

## Flujo de Trabajo Git

```
main        ← solo código listo para producción (nunca pushear directo)
develop     ← integración del equipo (base para todas las features)
feature/    ← una rama por historia de usuario
```

```bash
# Crear rama para una historia
git checkout develop
git pull origin develop
git checkout -b feature/US-01-registro-cliente

# Subir cambios
git push origin feature/US-01-registro-cliente

# Abrir Pull Request → develop (requiere 1 aprobación antes de mergear)
```

---

## Convención de Commits

```
feat:     nueva funcionalidad
fix:      corrección de bug
style:    cambios de estilos / Tailwind
refactor: refactoring sin cambio funcional
db:       migraciones o seeders
chore:    mantenimiento / dependencias
docs:     documentación
```

```bash
# Ejemplos
git commit -m "US-01/feat: agregar formulario de registro de cliente"
git commit -m "US-01/db: agregar migración para tabla favorites"
git commit -m "US-01/style: aplicar paleta de colores al layout principal"
git commit -m "US-01/docs: documentar variables de entorno en .env.example"
```

---
