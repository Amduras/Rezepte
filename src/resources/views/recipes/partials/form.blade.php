@include('recipes.partials.errors')

@include('recipes.partials.recipe-data')

@include('recipes.partials.ingredients')

@include('recipes.partials.steps')

<div class="flex justify-end gap-3 p-5">
    <a href="{{ url()->previous() }}" class="px-6 py-2 bg-stone-700 hover:bg-stone-600 text-stone-200 rounded-lg transition-colors">
        Abbrechen
    </a>
    <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg transition-colors font-medium">
        {{ isset($recipe) ? 'Änderungen speichern' : 'Rezept erstellen' }}
    </button>
</div>
