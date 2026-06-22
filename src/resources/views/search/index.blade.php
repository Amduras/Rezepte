@extends('layouts.app')

@section('title', 'Suche: ' . ($query ?: 'Ergebnisse'))

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-emerald-400 mb-4">
                 Suche
            </h1>

            <form action="{{ route('search.index') }}" method="GET" class="max-w-2xl">
                <div class="flex gap-3">
                    <div class="flex-1 relative">
                        <input type="text"
                               name="q"
                               value="{{ $query }}"
                               placeholder="Suche nach Rezepten, Zutaten, Tags..."
                               autofocus
                               class="block w-full bg-stone-800 border border-stone-700 py-3 px-4 pr-12 text-base text-stone-200 placeholder:text-stone-500 rounded-lg outline-1 outline-offset-1 outline-white/10 focus:outline-2 focus:-outline-offset-2 focus:outline-emerald-500/50">

                        @if($query)
                            <a href="{{ route('search.index') }}"
                               class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-500 hover:text-stone-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Suchen
                    </button>
                </div>
            </form>
        </div>

        @if($query)
            <p class="text-stone-400 mb-6">
                {{ $recipes->total() }} Ergebnis{{ $recipes->total() !== 1 ? 'se' : '' }} für
                <span class="text-emerald-400 font-medium">"{{ $query }}"</span>
            </p>
        @endif

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
                                    <div class="relative aspect-4/3 overflow-hidden">
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

                                        @if($query && str_contains(strtolower($recipe->title), strtolower($query)))
                                            <div class="absolute top-3 right-3 bg-emerald-500/90 text-white text-xs font-bold px-2 py-1 rounded-full">
                                                Treffer im Titel
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
                                    <h3 class="text-lg font-bold text-stone-100 group-hover:text-emerald-400 transition-colors line-clamp-2">
                                        @if($query && str_contains(strtolower($recipe->title), strtolower($query)))
                                            {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<span class="text-emerald-400 bg-emerald-500/10 px-1 rounded">$1</span>', e($recipe->title)) !!}
                                        @else
                                            {{ $recipe->title }}
                                        @endif
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

                                        @if($recipe->difficulty)
                                            <span class="text-xs text-stone-500 capitalize">
                                                {{ $recipe->difficulty->label() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $recipes->links() }}
            </div>
        @elseif($query)
            <div class="text-center py-20">
                <svg class="w-20 h-20 mx-auto text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-2xl font-bold text-stone-300 mb-2">Keine Ergebnisse gefunden</h2>
                <p class="text-stone-500 mb-6">
                    Versuche es mit anderen Suchbegriffen oder durchstöbere die
                    <a href="{{ route('home') }}" class="text-emerald-400 hover:text-emerald-300 underline">Startseite</a>.
                </p>
            </div>
        @else
            {{-- Leere Suche --}}
            <div class="text-center py-20">
                <svg class="w-20 h-20 mx-auto text-stone-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h2 class="text-2xl font-bold text-stone-300 mb-2">Wonach suchst du?</h2>
                <p class="text-stone-500">
                    Gib einen Suchbegriff ein, um Rezepte zu finden. Du kannst nach Titeln, Zutaten, Tags oder Autoren suchen.
                </p>
            </div>
        @endif
    </div>
@endsection
