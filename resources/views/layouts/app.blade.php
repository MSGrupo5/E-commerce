<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Catálogo Gaming')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Header -->
    <header class="bg-violet-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">NexusTech</h1>
        <nav class="space-x-4">
            <a href="/" class="hover:underline">Inicio</a>
            <a href="/catalogo" class="hover:underline">Productos</a>
            <a href="/carrito" class="hover:underline">Carrito</a>
            <a href="/login" class="hover:underline">Login</a>
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
