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
        <nav class="container mx-auto px-4 py-3 flex justify-between items-center gap-4">
            <a href="{{ route('home') }}" class="text-xl font-bold text-emerald-500 hover:text-emerald-400 transition-colors shrink-0">
                DC Rezepte
            </a>

            <form action="{{ route('search.index') }}" method="GET" class="flex-1 max-w-md hidden md:block">
                <div class="relative">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Rezepte suchen..."
                           class="block w-full bg-stone-900 border border-stone-700 py-2 px-4 pr-10 text-sm text-stone-200 placeholder:text-stone-500 rounded-lg outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">

                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-500 hover:text-emerald-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-4 shrink-0">
                @auth
                    <a href="{{ route('recipes.create') }}" class="text-stone-300 hover:text-emerald-500 transition-colors hidden lg:block">
                        + Neues Rezept
                    </a>

                    <div class="relative group">
                        <button class="text-stone-300 hover:text-emerald-500 transition-colors flex items-center gap-1">
                            {{ Auth::user()->username }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div class="absolute right-0 mt-2 w-48 bg-stone-800 border border-stone-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-stone-300 hover:bg-stone-700 hover:text-emerald-500 transition-colors">
                                ️ Profil bearbeiten
                            </a>
                            <a href="{{ route('my-recipes.index') }}" class="block px-4 py-2 text-sm text-stone-300 hover:bg-stone-700 hover:text-emerald-500 transition-colors">
                                📝 Meine Rezepte
                            </a>
                            <a href="{{ route('favorites.index') }}" class="block px-4 py-2 text-sm text-stone-300 hover:bg-stone-700 hover:text-emerald-500 transition-colors">
                                ❤️ Meine Favoriten
                            </a>
                            <div class="border-t border-stone-700 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-stone-300 hover:bg-stone-700 hover:text-red-400 transition-colors">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
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

        <div class="md:hidden px-4 pb-3">
            <form action="{{ route('search.index') }}" method="GET">
                <div class="relative">
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="Rezepte suchen..."
                           class="block w-full bg-stone-900 border border-stone-700 py-2 px-4 pr-10 text-sm text-stone-200 placeholder:text-stone-500 rounded-lg outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">

                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-500 hover:text-emerald-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
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
