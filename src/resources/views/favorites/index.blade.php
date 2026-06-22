@extends('layouts.app')

@section('title', 'Meine Favoriten')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-emerald-400 mb-6">❤️ Meine Favoriten</h1>

        @if($favorites->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($favorites as $recipe)
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
                                        <div class="flex items-center gap-2 text-xs text-stone-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span class="truncate max-w-[100px]">{{ $recipe->author->username }}</span>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <h2 class="text-2xl font-bold text-stone-300 mb-2">Noch keine Favoriten</h2>
                <p class="text-stone-500 mb-6">Speichere Rezepte, die dir gefallen, um sie hier zu finden.</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors">
                    Rezepte entdecken
                </a>
            </div>
        @endif
    </div>
@endsection
