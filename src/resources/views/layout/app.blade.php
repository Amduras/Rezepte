<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-[#121212]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>{{ $title }} - DC Rezepte</title>
    </head>
    <body class="bg-[#121212] text-zinc-200 font-sans antialiased min-h-screen flex flex-col relative">
        @include('header')

        <main class="grow py-8 relative flex flex-col justify-center w-full">
            @yield('content')
        </main>
    </body>
</html>
