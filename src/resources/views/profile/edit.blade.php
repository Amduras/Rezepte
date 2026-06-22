@extends('layouts.app')

@section('title', 'Profil bearbeiten')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-stone-100 mb-6">Profil bearbeiten</h1>

        <form action="{{ route('profile.update') }}" method="POST" class="bg-stone-800 rounded-2xl p-6 space-y-5">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="p-4 bg-red-900/40 border border-red-500/50 rounded-lg">
                    <ul class="list-disc list-inside text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Username --}}
            <div>
                <label for="username" class="block text-sm/6 font-medium text-stone-400 mb-1">Benutzername:</label>
                <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required
                       class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50 @error('username') outline-red-500/70 @enderror">
                @error('username')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- E-Mail --}}
            <div>
                <label for="email" class="block text-sm/6 font-medium text-stone-400 mb-1">E-Mail:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50 @error('email') outline-red-500/70 @enderror">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Passwort (optional) --}}
            <div>
                <label for="password" class="block text-sm/6 font-medium text-stone-400 mb-1">Neues Passwort (optional):</label>
                <input type="password" id="password" name="password"
                       class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50 @error('password') outline-red-500/70 @enderror">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Passwort bestätigen --}}
            <div>
                <label for="password_confirmation" class="block text-sm/6 font-medium text-stone-400 mb-1">Passwort bestätigen:</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="block w-full bg-transparent py-2 px-3 text-base text-stone-200 placeholder:text-gray-500 rounded-md outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('home') }}" class="px-6 py-2 bg-stone-700 hover:bg-stone-600 text-stone-200 rounded-lg transition-colors">
                    Abbrechen
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors font-medium">
                    Änderungen speichern
                </button>
            </div>
        </form>
    </div>
@endsection
