<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Página no encontrada — {{ config('app.name', 'Marketo') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-jakarta bg-background text-text antialiased min-h-screen flex flex-col items-center justify-center px-4">

    <div class="w-full max-w-lg text-center">

        <div class="flex justify-center mb-8">
            <div class="relative flex items-center justify-center w-36 h-36 rounded-[40px] bg-surface border border-border shadow-[0_0_80px_rgba(108,99,255,0.08)]">
                <div class="absolute inset-0 rounded-[40px] border border-primary/10"></div>
                <svg class="w-16 h-16 text-primary/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-.375c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v.375c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-background border border-border flex items-center justify-center">
                    <svg class="w-4 h-4 text-error" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>

        <p class="font-oxanium text-[80px] leading-none font-bold text-primary/20 select-none mb-2">404</p>

        <h1 class="text-h3 font-semibold text-text mb-3">Página no encontrada</h1>

        <p class="text-body text-muted mb-10 leading-relaxed">
            Lo que buscás no existe, fue eliminado o el enlace es incorrecto.
        </p>

        <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2.5 bg-primary text-background font-semibold text-sm px-7 py-3.5 rounded-2xl hover:bg-primary/90 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/60">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Volver al inicio
        </a>

    </div>

    <div class="absolute bottom-8">
        <x-app.logo :compact="false" />
    </div>

</body>

</html>
