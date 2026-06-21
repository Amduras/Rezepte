@extends('layouts.app')

@section('title', 'Rezept bearbeiten: ' . $recipe->title)

@section('content')
    <div class="wrapper">
        <div class="max-w-5xl mx-auto px-4 py-6">

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('recipes.show', $recipe) }}"
                       class="text-stone-400 hover:text-emerald-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-stone-100">
                        Rezept bearbeiten
                    </h1>
                </div>

                <div class="flex items-center gap-2">
                    @if($recipe->isDraft())
                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-medium rounded-full border border-yellow-500/30">
                            📝 Entwurf
                        </span>
                    @elseif($recipe->isPublished())
                        <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-medium rounded-full border border-emerald-500/30">
                            ✅ Veröffentlicht
                        </span>
                    @elseif($recipe->isArchived())
                        <span class="px-3 py-1 bg-stone-500/20 text-stone-400 text-xs font-medium rounded-full border border-stone-500/30">
                             Archiviert
                        </span>
                    @endif
                </div>
            </div>

            <form class="bg-stone-800 rounded-2xl p-5"
                  action="{{ route('recipes.update', $recipe) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('recipes.partials.form')

            </form>

        </div>
    </div>
@endsection
