<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>

    @vite('resources/css/app.css')
</head>
<body class="min-h-screen flex flex-col bg-gray-100 text-gray-900">

    <!-- Header -->
    <header class="bg-violet-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold">NexusTech</h1>

        <nav class="space-x-6">
            <a href="/" class="hover:underline">Inicio</a>
            <a href="/productos" class="hover:underline">Productos</a>
            <a href="/carrito" class="hover:underline">Carrito</a>
            <a href="/login" class="hover:underline font-semibold">Login</a>
        </nav>
    </header>

    <!-- Contenido -->
    <main class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-4 text-center">Recuperar contraseña</h2>

            <p class="text-sm text-gray-600 text-center mb-6">
                Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
            </p>

            {{-- Mensaje de éxito --}}
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-100 p-3 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Errores --}}
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-700">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        autofocus
                        class="w-full border border-gray-300 rounded-lg shadow-sm px-4 py-2 focus:ring-2 focus:ring-violet-500 focus:border-violet-500 outline-none"
                    >
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full bg-violet-600 text-white py-3 px-4 rounded-lg hover:bg-violet-700 transition"
                    >
                        Enviar enlace de recuperación
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                <a href="{{ route('login') }}" class="text-violet-600 font-semibold hover:underline">
                    Volver al inicio de sesión
                </a>
            </p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center p-4">
        <p>© {{ date('Y') }} Grupo 5 - Proyecto E-Commerce</p>
    </footer>

</body>
</html>