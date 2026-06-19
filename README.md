# 🛒 eCommerce Laravel

> Proyecto académico — Gestión de Sistemas · Metodología Scrum

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=flat&logo=tailwindcss&logoColor=white)
![Estado](https://img.shields.io/badge/Sprint_0-En_curso-yellow?style=flat)

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
git clone https://github.com/[usuario]/ecommerce-laravel.git
cd ecommerce-laravel

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node
npm install

# 4. Copiar el archivo de entorno
cp .env.example .env

# 5. Generar clave de la aplicación
php artisan key:generate

# 6. Configurar .env con tus credenciales de MySQL (ver sección siguiente)

# 7. Crear la base de datos en MySQL
# CREATE DATABASE ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 8. Ejecutar las migraciones
php artisan migrate

# 9. Crear el enlace simbólico para imágenes
php artisan storage:link

# 10. Compilar assets y levantar el servidor
npm run dev
php artisan serve
```

La aplicación estará disponible en: **http://localhost:8000**

---

## Configuración del Entorno (.env)

```env
APP_NAME="eCommerce Laravel"
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

> `MAIL_MAILER=log` — los emails no se envían; el link de recuperación de contraseña queda registrado en `storage/logs/laravel.log`.

---

## Manejo de imágenes de productos

- **Almacenamiento**: `storage/app/public/products/` (disco `public` de Laravel)
- **Acceso público**: requiere `php artisan storage:link` después de clonar el repositorio (ver paso 9 de Instalación)
- **Validación**: `nullable|image|mimes:jpeg,png,jpg,webp|max:2048` — imagen opcional, hasta 2 MB, formatos JPEG/PNG/WebP
- **Actualización**: al subir una nueva imagen en `update()`, la imagen anterior se elimina automáticamente del storage
- **Eliminación**: al eliminar un producto sin pedidos asociados (force delete), su imagen también se borra

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
git checkout -b feature/US-01-registro-cliente  # tipo/JIRA-ID-descripcion-corta

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
