@extends('layouts.app')

@section('title', 'Registrieren')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md bg-stone-800 rounded-2xl p-8 shadow-xl">

            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-stone-100">Konto erstellen</h1>
                <p class="text-sm text-stone-400 mt-1">Werde Teil unserer Rezept-Community</p>
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

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm/6 font-medium text-stone-400 mb-1">Benutzername:</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus minlength="{{ \App\Models\User::USERNAME_MIN_LENGTH }}" maxlength="{{ \App\Models\User::USERNAME_MAX_LENGTH }}"
                           class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">
                </div>

                <div>
                    <label for="email" class="block text-sm/6 font-medium text-stone-400 mb-1">E-Mail:</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">
                </div>

                <div>
                    <label for="password" class="block text-sm/6 font-medium text-stone-400 mb-1">Passwort:</label>
                    <input type="password" id="password" name="password" required
                           class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm/6 font-medium text-stone-400 mb-1">Passwort bestätigen:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">
                </div>

                <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-lg transition-colors">
                    Registrieren
                </button>

                <p class="text-center text-sm text-stone-400">
                    Bereits registriert?
                    <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-medium">
                        Jetzt anmelden
                    </a>
                </p>
            </form>
        </div>
    </div>
@endsection
