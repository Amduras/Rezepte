@php
    // Bestehende Zutaten (Edit) oder eine leere Zeile (Create)
    $existingIngredients = isset($recipe) && $recipe->ingredients->isNotEmpty()
        ? $recipe->ingredients
        : collect([(object)[
            'name' => old('ingredients.0.name'),
            'quantity' => old('ingredients.0.quantity'),
            'unit' => old('ingredients.0.unit'),
            'note' => old('ingredients.0.note'),
        ]]);
@endphp

<section id="recipe_ingredients" class="w-full md:flex mb-5">
    <div class="w-full md:w-1/4 md:ml-5">
        <p class="text-base/7 font-semibold">Zutaten</p>
        <p class="mt-1 text-sm/6 text-stone-400">Füge die Zutaten für dein Rezept hinzu</p>
    </div>

    <div class="w-full md:w-3/4 md:ml-15 md:mr-15">

        <div class="hidden md:flex md:items-end gap-4 mb-2">
            <div class="w-5/12"><label class="block text-sm/6 font-medium text-stone-400">Zutat:</label></div>
            <div class="w-2/12"><label class="block text-sm/6 font-medium text-stone-400">Menge:</label></div>
            <div class="w-2/12"><label class="block text-sm/6 font-medium text-stone-400">Einheit:</label></div>
            <div class="w-2/12"><label class="block text-sm/6 font-medium text-stone-400">Notiz:</label></div>
            <div class="w-1/12"></div>
        </div>

        <div id="ingredients-list" class="space-y-3">

            @foreach($existingIngredients as $index => $ingredient)
                <div class="ingredient-row w-full md:flex md:items-end gap-4" data-index="{{ $index }}">

                    <div class="w-full md:w-5/12">
                        <label class="block text-sm/6 font-medium text-stone-400 md:hidden">Zutat:</label>
                        <input type="text"
                               name="ingredients[{{ $index }}][name]"
                               value="{{ old("ingredients.$index.name", $ingredient->name ?? '') }}"
                               placeholder="z.B. Mehl"
                               class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 @error("ingredients.$index.name") outline-red-500/70 @enderror">
                        @error("ingredients.$index.name")
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full md:w-2/12">
                        <label class="block text-sm/6 font-medium text-stone-400 md:hidden">Menge:</label>
                        <input type="text"
                               name="ingredients[{{ $index }}][quantity]"
                               value="{{ old("ingredients.$index.quantity", $ingredient->quantity ?? '') }}"
                               placeholder="z.B. 200"
                               class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50">
                    </div>

                    <div class="w-full md:w-2/12">
                        <label class="block text-sm/6 font-medium text-stone-400 md:hidden">Einheit:</label>
                        <input type="text"
                               name="ingredients[{{ $index }}][unit]"
                               value="{{ old("ingredients.$index.unit", $ingredient->unit ?? '') }}"
                               placeholder="g, ml, Stk"
                               class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50">
                    </div>

                    <div class="w-full md:w-2/12">
                        <label class="block text-sm/6 font-medium text-stone-400 md:hidden">Notiz:</label>
                        <input type="text"
                               name="ingredients[{{ $index }}][note]"
                               value="{{ old("ingredients.$index.note", $ingredient->note ?? '') }}"
                               placeholder="z.B. gesiebt"
                               class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50">
                    </div>

                    <div class="w-full md:w-1/12 flex md:justify-center items-center pb-1">
                        <button type="button" class="remove-ingredient-btn text-red-400 hover:text-red-300 transition-colors cursor-pointer"
                                style="visibility: {{ $existingIngredients->count() <= 1 ? 'hidden' : 'visible' }};">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

        </div>

        <button type="button" id="add-ingredient-btn" class="mt-4 flex items-center text-sm text-emerald-500 hover:text-emerald-400 transition-colors cursor-pointer">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Weitere Zutat hinzufügen
        </button>

    </div>
</section>

<hr class="h-0.5 border-0 bg-stone-700 md:ml-5 md:mr-15 mb-5">
