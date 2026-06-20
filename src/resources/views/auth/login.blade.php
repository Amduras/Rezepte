@extends('layouts.app')

@section('title', 'Anmelden')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-stone-800 rounded-2xl p-8 shadow-xl">

            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-stone-100">Willkommen zurück</h1>
                <p class="text-sm text-stone-400 mt-1">Melde dich an, um fortzufahren</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-900/40 border border-red-500/50 rounded-lg">
                    <ul class="list-disc list-inside text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm/6 font-medium text-stone-400 mb-1">Benutzername:</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                           class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">
                </div>

                <div>
                    <label for="password" class="block text-sm/6 font-medium text-stone-400 mb-1">Passwort:</label>
                    <input type="password" id="password" name="password" required
                           class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" value="1"
                           class="h-4 w-4 rounded border-stone-600 bg-stone-700 text-emerald-500 focus:ring-emerald-500/50">
                    <label for="remember" class="text-sm text-stone-400">Angemeldet bleiben</label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-lg transition-colors">
                    Anmelden
                </button>

                <p class="text-center text-sm text-stone-400">
                    Noch kein Konto?
                    <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-medium">
                        Jetzt registrieren
                    </a>
                </p>
            </form>
        </div>
    </div>
@endsection
