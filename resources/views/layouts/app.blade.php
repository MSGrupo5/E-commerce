<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Catálogo Simple')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
    <!-- Header -->
    <header class="bg-blue-600 text-white p-4">
        <h1 class="text-2xl font-bold">Catálogo Simple</h1>
        <nav>
            <a href="/" class="mr-4">Inicio</a>
            <a href="/carrito" class="mr-4">Carrito</a>
            <a href="/login">Login</a>
        </nav>
    </header>

    <!-- Contenido -->
    <main class="p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center p-4 mt-10">
        <p>&copy; 2026 Grupo 5 - Proyecto E-Commerce</p>
    </footer>
</body>
</html>