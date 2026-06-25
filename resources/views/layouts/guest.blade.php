<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TechStore') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ Vite::asset('resources/images/marketo_icono_solo.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-background text-text min-h-screen flex flex-col justify-center items-center p-4 selection:bg-primary/30">

        <x-ui.loader />

        <div class="w-full flex justify-center items-center">
            {{ $slot }}
        </div>

    </body>
</html>