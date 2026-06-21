@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
    {{-- 🎯 HEADER --}}
    <div class="w-full max-w-3xl mx-auto text-center mb-6 px-4">
        <h1 class="text-2xl md:text-3xl font-bold uppercase tracking-wider text-white">
            {{ $recipe->title }}
        </h1>
        <p class="text-zinc-400 mt-2 text-sm md:text-base font-light">
            {{ $recipe->description }}
        </p>

        <div class="mt-4 flex flex-wrap justify-center items-center gap-3 text-xs text-stone-400">
            <span>
                von <span class="text-emerald-400 font-medium">{{ $recipe->author->username }}</span>
            </span>
            <span>•</span>
            <span>{{ $recipe->created_at->diffForHumans() }}</span>

            @if($recipe->tags && count($recipe->tags) > 0)
                <span>•</span>
                <div class="flex flex-wrap gap-1">
                    @foreach($recipe->tags as $tag)
                        <span class="px-2 py-0.5 bg-stone-700/50 rounded-full text-stone-300">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- 🖼️ BILDER-BEREICH mit 1/2/3+ Logik --}}
    @php
        $images = $recipe->images;
        $totalImages = $images->count();
    @endphp

    @if($totalImages > 0)
        <div class="relative w-full overflow-hidden group mb-8">

            {{-- FALL: 3+ Bilder → Endloses Carousel --}}
            @if($totalImages >= 3)
                <div id="recipe-carousel" class="relative w-full h-88 md:h-136 overflow-hidden">
                    <div id="carousel-track" class="flex h-full transition-transform duration-500 ease-out">

                        {{-- Klone vom Ende (3 Stück) --}}
                        @for($i = 0; $i < 3; $i++)
                            <div class="carousel-item shrink-0 w-[75%] md:w-[60%] px-2">
                                <img src="{{ Storage::url($images[$totalImages - 3 + $i]->image_url) }}"
                                     class="w-full h-full object-cover rounded-xl shadow-2xl border border-zinc-900">
                            </div>
                        @endfor

                        {{-- Originale Bilder --}}
                        @foreach($images as $index => $image)
                            <div class="carousel-item shrink-0 w-[75%] md:w-[60%] px-2" data-index="{{ $index }}">
                                <img src="{{ Storage::url($image->image_url) }}"
                                     class="w-full h-full object-cover rounded-xl shadow-2xl border border-zinc-900">
                            </div>
                        @endforeach

                        {{-- Klone vom Anfang (3 Stück) --}}
                        @for($i = 0; $i < 3; $i++)
                            <div class="carousel-item shrink-0 w-[75%] md:w-[60%] px-2">
                                <img src="{{ Storage::url($images[$i]->image_url) }}"
                                     class="w-full h-full object-cover rounded-xl shadow-2xl border border-zinc-900">
                            </div>
                        @endfor

                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <button id="carousel-prev" class="absolute left-2 md:left-[18vw] top-1/2 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white text-zinc-900 shadow-2xl hover:scale-110 active:scale-95 transition-all duration-200 z-20 cursor-pointer opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <button id="carousel-next" class="absolute right-2 md:right-[18vw] top-1/2 -translate-y-1/2 w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-white text-zinc-900 shadow-2xl hover:scale-110 active:scale-95 transition-all duration-200 z-20 cursor-pointer opacity-0 group-hover:opacity-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                {{-- Dots --}}
                <div id="carousel-dots" class="flex justify-center space-x-2 mt-6">
                    @foreach($images as $index => $image)
                        <span class="carousel-dot w-2 h-2 rounded-full bg-zinc-700 transition-all duration-300 cursor-pointer"></span>
                    @endforeach
                </div>

            {{-- FALL: 2 Bilder → Nebeneinander --}}
            @elseif($totalImages === 2)
                <div class="flex justify-center gap-4 px-4">
                    @foreach($images as $image)
                        <div class="w-full max-w-md">
                            <img src="{{ Storage::url($image->image_url) }}"
                                 class="w-full h-64 md:h-96 object-cover rounded-xl shadow-2xl border border-zinc-900">
                        </div>
                    @endforeach
                </div>

            {{-- FALL: 1 Bild → Zentriert --}}
            @else
                <div class="flex justify-center px-4">
                    <div class="w-full max-w-2xl">
                        <img src="{{ Storage::url($images->first()->image_url) }}"
                             class="w-full h-64 md:h-96 object-cover rounded-xl shadow-2xl border border-zinc-900">
                    </div>
                </div>
            @endif

        </div>
    @endif

    {{-- 📊 INFO-BALKEN --}}
    <div class="max-w-4xl mx-auto px-4 mb-6">
        <div class="flex justify-between flex-col md:flex-row gap-4">
            <span class="text-center bg-emerald-500/20 border border-emerald-500/30 p-3 rounded-xl flex-1">
                <p class="font-bold text-stone-200">Vorbereitungszeit</p>
                <p class="flex items-center justify-center mt-1 text-stone-300">
                    <img src="{{ asset('logos/clock.svg') }}" class="w-8 mr-3" alt="">
                    {{ $recipe->prep_time ? $recipe->prep_time . ' Min.' : 'k.A.' }}
                </p>
            </span>

            <span class="text-center bg-emerald-500/20 border border-emerald-500/30 p-3 rounded-xl flex-1">
                <p class="font-bold text-stone-200">Kochzeit</p>
                <p class="flex items-center justify-center mt-1 text-stone-300">
                    <img src="{{ asset('logos/topf.svg') }}" class="w-8 mr-3" alt="">
                    {{ $recipe->cook_time ? $recipe->cook_time . ' Min.' : 'k.A.' }}
                </p>
            </span>

            <span class="text-center bg-emerald-500/20 border border-emerald-500/30 p-3 rounded-xl flex-1">
                <p class="font-bold text-stone-200">Gesamtzeit</p>
                <p class="flex items-center justify-center mt-1 text-stone-300">
                    <img src="{{ asset('logos/clock.svg') }}" class="w-8 mr-3" alt="">
                    {{ $recipe->formatted_total_time }}
                </p>
            </span>

            <span class="text-center bg-emerald-500/20 border border-emerald-500/30 p-3 rounded-xl flex-1">
                <p class="font-bold text-stone-200">Schwierigkeit</p>
                <p class="mt-1 text-stone-300">
                    <img src="{{ asset('logos/' . $recipe->difficulty->value . '.png') }}"
                         class="w-16 mx-auto"
                         alt="{{ $recipe->difficulty->label() }}">
                </p>
            </span>
        </div>
    </div>

    {{-- 🍽️ HAUPTBEREICH --}}
    <div class="flex flex-row min-h-screen relative max-w-7xl mx-auto">

        {{-- LINKE SPALTE: Zubereitung --}}
        <div id="section-left" class="w-full md:w-3/4 p-5">

            @if($recipe->steps->isNotEmpty())
                <div class="mb-8">
                    <h2 class="text-2xl text-emerald-500 mb-4">Zubereitung</h2>
                    <ol class="space-y-4">
                        @foreach($recipe->steps as $step)
                            <li class="flex gap-4 bg-stone-800/50 p-4 rounded-xl border border-stone-700/50">
                                <span class="text-3xl font-bold text-emerald-500 shrink-0">
                                    {{ $step->step_number }}
                                </span>
                                <p class="text-stone-200 leading-relaxed pt-1">
                                    {{ $step->instruction }}
                                </p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @else
                <p class="text-stone-500 italic">Für dieses Rezept wurden noch keine Zubereitungsschritte hinterlegt.</p>
            @endif

        </div>

        {{-- 📱 FLOATING BUTTON --}}
        <button id="ingredients__show"
                class="md:hidden fixed bottom-6 right-6 z-40 bg-emerald-500 text-white p-4 rounded-full shadow-2xl flex items-center justify-center transition-transform active:scale-95">
            <img src="{{ asset('logos/leaf.png') }}" class="w-8 h-8" alt="">
            <span class="ml-2 font-bold">Zutaten</span>
        </button>

        {{-- RECHTE SPALTE: Zutaten --}}
        <div id="section-right"
             class="w-full md:w-1/4 p-5 hidden md:block md:relative bg-[#121212] md:bg-transparent">

            <div class="md:hidden flex justify-between items-center mb-6 border-b border-emerald-500/50 pb-2">
                <h2 class="text-xl font-bold text-emerald-600">Zutaten</h2>
                <img src="{{ asset('logos/close.png') }}"
                     id="ingredients__close"
                     class="w-8 h-8 cursor-pointer hidden bg-emerald-500/50 rounded-full p-1">
            </div>

            <div class="wrapper md:sticky md:top-30">
                <div class="ingredients__wrapper md:block top-10">

                    <div class="servings__wrapper flex bg-emerald-500/20 border border-emerald-500/30 rounded-xl w-full text-center justify-between">
                        <div class="mt-auto mb-auto pl-3 cursor-pointer text-xl text-stone-200 select-none"
                             id="servings-left">&lt;</div>
                        <span>
                            <p class="font-bold text-stone-200">Portionen:</p>
                            <p id="servings-amount" class="text-stone-100">{{ $recipe->servings ?? 1 }}</p>
                        </span>
                        <div class="mt-auto mb-auto pr-3 cursor-pointer text-xl text-stone-200 select-none"
                             id="servings-right">&gt;</div>
                    </div>

                    <div id="recipe__list" class="mt-4">
                        <h2 class="text-emerald-500 text-2xl mb-3">Zutatenliste</h2>

                        @if($recipe->ingredients->isNotEmpty())
                            <ul id="ingredients-list" class="space-y-2 invisible md:visible">
                                @foreach($recipe->ingredients as $ingredient)
                                    <li class="text-stone-300 flex gap-2">
                                        <span class="text-emerald-400">•</span>
                                        <span>
                                            @if($ingredient->quantity)
                                                <span class="font-semibold text-stone-100">{{ $ingredient->quantity }}</span>
                                            @endif
                                            @if($ingredient->unit)
                                                <span>{{ $ingredient->unit }}</span>
                                            @endif
                                            <span>{{ $ingredient->name }}</span>
                                            @if($ingredient->note)
                                                <span class="text-stone-500 text-sm">({{ $ingredient->note }})</span>
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-stone-500 italic">Keine Zutaten hinterlegt.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- 🛠️ Admin-Aktionen --}}
    @auth
        @if(Auth::id() === $recipe->author_id || Auth::user()->isAdmin())
            <div class="max-w-4xl mx-auto px-4 py-8 flex gap-3 justify-end">
                <a href="{{ route('recipes.edit', $recipe) }}"
                   class="px-4 py-2 bg-stone-700 hover:bg-stone-600 text-stone-200 rounded-lg transition-colors text-sm">
                    Bearbeiten
                </a>
                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                      onsubmit="return confirm('Rezept wirklich löschen?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition-colors text-sm">
                        Löschen
                    </button>
                </form>
            </div>
        @endif
    @endauth
@endsection
