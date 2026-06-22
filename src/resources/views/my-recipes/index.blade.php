@extends('layouts.app')

@section('title', 'Meine Rezepte')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-emerald-400">📝 Meine Rezepte</h1>
            <a href="{{ route('recipes.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Neues Rezept
            </a>
        </div>

        @if($recipes->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($recipes as $recipe)
                    <div class="recipe-card group">
                        <a href="{{ route('recipes.show', $recipe) }}">
                            <div class="relative overflow-hidden rounded-xl bg-stone-800 border border-stone-700 hover:border-emerald-500/50 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/10">
                                @php
                                    $image = $recipe->images->first()?->image_url ?? $recipe->image_url;
                                @endphp

                                @if($image)
                                    <div class="relative aspect-[4/3] overflow-hidden">
                                        <img src="{{ Storage::url($image) }}"
                                             alt="{{ $recipe->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                                        {{-- Status-Badge --}}
                                        <div class="absolute top-3 right-3">
                                            @if($recipe->isDraft())
                                                <span class="px-2 py-1 bg-yellow-500/90 text-stone-900 text-xs font-bold rounded-full">
                                                    Entwurf
                                                </span>
                                            @elseif($recipe->isPublished())
                                                <span class="px-2 py-1 bg-emerald-500/90 text-white text-xs font-bold rounded-full">
                                                    Veröffentlicht
                                                </span>
                                            @endif
                                        </div>

                                        @if($recipe->total_time)
                                            <div class="absolute bottom-3 left-3 bg-emerald-500/90 backdrop-blur-sm text-white px-3 py-1.5 rounded-full text-sm font-medium flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $recipe->formatted_total_time }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="aspect-[4/3] flex items-center justify-center bg-stone-700">
                                        <svg class="w-16 h-16 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-stone-100 group-hover:text-emerald-400 transition-colors line-clamp-2">
                                        {{ $recipe->title }}
                                    </h3>

                                    @if($recipe->description)
                                        <p class="text-sm text-stone-400 mt-2 line-clamp-2">
                                            {{ Str::limit($recipe->description, 100) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center justify-between mt-4 pt-3 border-t border-stone-700">
                                        <div class="text-xs text-stone-500">
                                            {{ $recipe->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <svg class="w-20 h-20 mx-auto text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h2 class="text-2xl font-bold text-stone-300 mb-2">Noch keine Rezepte erstellt</h2>
                <p class="text-stone-500 mb-6">Erstelle dein erstes Rezept und teile es mit der Community!</p>
                <a href="{{ route('recipes.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Rezept erstellen
                </a>
            </div>
        @endif
    </div>
@endsection
