@extends('layouts.app')

@section('title', 'Meine Rezepte - Startseite')

@section('content')
   {{-- 🎠 TÄGLICHE RANDOM-AUSWAHL (Full-Width Card-Carousel) --}}
    @if($dailyRecipes->isNotEmpty())
        <section class="mb-12">
            <div class="px-4">
                <h2 class="text-2xl font-bold text-emerald-400 mb-6 text-center">
                    ✨ Heute für dich entdeckt
                </h2>

                <div class="relative group">
                    <div id="daily-carousel" class="flex transition-transform duration-500 ease-out">
                        @foreach($dailyRecipes as $index => $recipe)
                            <div class="daily-slide min-w-full px-2 md:px-4 lg:px-8">
                                <a href="{{ route('recipes.show', $recipe) }}" class="block group/daily">
                                    {{-- Card Container --}}
                                    <div class="bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 border border-stone-200 max-w-4xl mx-auto">

                                        {{-- Bild-Bereich --}}
                                        @php
                                            $image = $recipe->images->first()?->image_url ?? $recipe->image_url;
                                        @endphp

                                        <div class="relative aspect-[16/9] md:aspect-[21/9] overflow-hidden bg-stone-100">
                                            @if($image)
                                                <img src="{{ Storage::url($image) }}"
                                                     alt="{{ $recipe->title }}"
                                                     class="w-full h-full object-cover group-hover/daily:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-stone-200">
                                                    <svg class="w-24 h-24 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            {{-- Zeit-Badge --}}
                                            @if($recipe->total_time)
                                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-emerald-400/95 backdrop-blur-sm text-stone-900 px-6 py-2 rounded-full text-base font-bold flex items-center gap-2 shadow-lg">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $recipe->formatted_total_time }}
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Text-Bereich --}}
                                        <div class="p-6 md:p-8">
                                            <h3 class="text-xl md:text-2xl font-bold text-stone-800 mb-3 text-center">
                                                {{ $recipe->title }}
                                            </h3>

                                            <p class="text-stone-600 text-base md:text-lg leading-relaxed text-center max-w-3xl mx-auto">
                                                {{ Str::limit($recipe->description, 200) ?: 'Ein leckeres Rezept, das du nicht verpassen solltest!' }}
                                            </p>

                                            {{-- Meta-Info --}}
                                            <div class="flex items-center justify-center gap-4 mt-6 text-sm text-stone-500 flex-wrap">
                                                @if($recipe->difficulty)
                                                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-stone-100 rounded-full">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                        </svg>
                                                        {{ $recipe->difficulty->label() }}
                                                    </span>
                                                @endif
                                                @if($recipe->servings)
                                                    <span class="flex items-center gap-1.5 px-3 py-1.5 bg-stone-100 rounded-full">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                        {{ $recipe->servings }} Portionen
                                                    </span>
                                                @endif
                                                <span class="flex items-center gap-1.5 px-3 py-1.5 bg-stone-100 rounded-full">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $recipe->author->username }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    {{-- Navigation Buttons --}}
                    <button id="daily-prev" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white shadow-xl text-stone-700 hover:bg-emerald-500 hover:text-white transition-all opacity-0 group-hover:opacity-100 cursor-pointer z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <button id="daily-next" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white shadow-xl text-stone-700 hover:bg-emerald-500 hover:text-white transition-all opacity-0 group-hover:opacity-100 cursor-pointer z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    {{-- Dots --}}
                    <div class="flex justify-center gap-3 mt-6">
                        @foreach($dailyRecipes as $index => $recipe)
                            <button class="daily-dot w-3 h-3 rounded-full bg-stone-300 hover:bg-emerald-500 transition-all {{ $index === 0 ? 'bg-emerald-500 w-8' : '' }}"
                                    data-index="{{ $index }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if($latestRecipes->isNotEmpty())
        <section>
            <div class="max-w-7xl mx-auto px-4">
                <h2 class="text-2xl font-bold text-emerald-400 mb-6">
                    🔥 Neueste Rezepte
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($latestRecipes as $recipe)
                        <div class="recipe-card group">
                            <a href="{{ route('recipes.show', $recipe) }}">
                                <div class="relative overflow-hidden rounded-xl bg-stone-800 border border-stone-700 hover:border-emerald-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/10">
                                    @php
                                        $image = $recipe->images->first()?->image_url ?? $recipe->image_url;
                                    @endphp

                                    @if($image)
                                        <div class="relative aspect-4/3 overflow-hidden">
                                            <img src="{{ Storage::url($image) }}"
                                                 alt="{{ $recipe->title }}"
                                                 class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">

                                            @if($recipe->total_time)
                                                <div class="absolute bottom-3 left-3 bg-emerald-500/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-sm font-medium flex items-center gap-1.5">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $recipe->formatted_total_time }}
                                                </div>
                                            @endif

                                            @if($recipe->difficulty)
                                                <div class="absolute bottom-3 right-3 bg-stone-900/80 backdrop-blur-sm text-stone-200 px-2.5 py-1 rounded-full text-xs font-medium capitalize">
                                                    {{ $recipe->difficulty->label() }}
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="aspect-4/3 flex items-center justify-center bg-stone-700">
                                            <svg class="w-16 h-16 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="p-4">
                                        <h3 class="text-lg font-bold text-stone-100 group-hover:text-emerald-400 transition-colors line-clamp-2 min-h-14">
                                            {{ $recipe->title }}
                                        </h3>

                                        @if($recipe->description)
                                            <p class="text-sm text-stone-400 mt-2 line-clamp-2">
                                                {{ Str::limit($recipe->description, 100) }}
                                            </p>
                                        @endif

                                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-stone-700">
                                            <div class="flex items-center gap-2 text-xs text-stone-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                <span class="truncate max-w-25">{{ $recipe->author->username }}</span>
                                            </div>

                                            @if($recipe->servings)
                                                <div class="text-xs text-stone-500 flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    {{ $recipe->servings }} Port.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                @if($latestRecipes->count() >= 15)
                    <div class="text-center mt-8">
                        <button class="px-6 py-3 bg-stone-800 hover:bg-stone-700 text-stone-300 rounded-lg transition-colors border border-stone-700">
                            Mehr Rezepte laden
                        </button>
                    </div>
                @endif
            </div>
        </section>
    @else
        <div class="max-w-7xl mx-auto px-4 py-20 text-center">
            <svg class="w-20 h-20 mx-auto text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h2 class="text-2xl font-bold text-stone-300 mb-2">Noch keine Rezepte vorhanden</h2>
            <p class="text-stone-500 mb-6">Sei der Erste, der ein Rezept erstellt!</p>
            @auth
                <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Rezept erstellen
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors">
                    Jetzt registrieren
                </a>
            @endauth
        </div>
    @endif
@endsection
