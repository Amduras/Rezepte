<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Meine Rezepte')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-900 text-stone-100 min-h-screen">

    <header class="bg-stone-800 border-b border-stone-700">
        <nav class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-500">
                DC Rezepte
            </a>

            <div class="flex items-center gap-4">
                @auth
                    <span class="text-stone-400 text-sm">
                        Hallo, <span class="text-stone-200 font-medium">{{ Auth::user()->username }}</span>
                    </span>
                    <a href="{{ route('recipes.create') }}" class="text-stone-300 hover:text-emerald-500 transition-colors">
                        + Neues Rezept
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-stone-300 hover:text-red-400 transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-stone-300 hover:text-emerald-500 transition-colors">
                        Anmelden
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm rounded-lg transition-colors">
                        Registrieren
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    @if (session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="p-4 bg-emerald-900/50 border border-emerald-500 rounded-lg text-emerald-300">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>

    <footer class="bg-stone-800 border-t border-stone-700 mt-auto py-4">
        <div class="container mx-auto px-4 text-center text-stone-500 text-sm">
            &copy; {{ date('Y') }} DC Rezepte
        </div>
    </footer>
@stack('scripts')
</body>
</html>
