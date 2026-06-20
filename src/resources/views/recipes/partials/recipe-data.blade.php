<section id="recipe_data" class="w-full md:flex mb-5">
    <div class="w-full md:w-1/4 md:ml-5">
        <p class="text-base/7 font-semibold">Allgemeine Rezept Daten</p>
        <p class="mt-1 text-sm/6 text-stone-400">Das sind die allgemeinen Daten für das Rezept</p>
    </div>

    <div class="w-full md:w-3/4 md:ml-15">

        <div class="form__group w-full md:w-8/12">
            <label for="title" class="block text-sm/6 font-medium text-stone-400">Titel:</label>
            <input type="text" id="title" name="title"
                   value="{{ old('title', $recipe->title ?? '') }}"
                   placeholder="Name des Rezepts"
                   class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 @error('title') outline-red-500/70 @enderror">
            @error('title')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form__group mt-5 md:w-8/12">
            <label for="description" class="block text-sm/6 font-medium text-stone-400">Beschreibung:</label>
            <textarea id="description" name="description" rows="3"
                      placeholder="Kurze Beschreibung des Rezepts..."
                      class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 resize-y @error('description') outline-red-500/70 @enderror">{{ old('description', $recipe->description ?? '') }}</textarea>
            @error('description')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form__group mt-5 w-full md:flex md:w-8/12 justify-between gap-4">
            <div class="flex-1">
                <label for="prep_time" class="block text-sm/6 font-medium text-stone-400">Vorbereitungszeit:</label>
                <input type="number" id="prep_time" name="prep_time" min="0"
                       value="{{ old('prep_time', $recipe->prep_time ?? '') }}"
                       placeholder="in Minuten"
                       class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 @error('prep_time') outline-red-500/70 @enderror">
                @error('prep_time')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex-1">
                <label for="cook_time" class="block text-sm/6 font-medium text-stone-400">Kochzeit:</label>
                <input type="number" id="cook_time" name="cook_time" min="0"
                       value="{{ old('cook_time', $recipe->cook_time ?? '') }}"
                       placeholder="in Minuten"
                       class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 @error('cook_time') outline-red-500/70 @enderror">
                @error('cook_time')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex-1">
                <label for="difficulty" class="block text-sm/6 font-medium text-stone-400">Schwierigkeit:</label>
                <select id="difficulty" name="difficulty"
                        class="select-arrow block w-full min-w-0 grow bg-transparent py-1.5 pr-10 pl-1 text-base text-stone-400 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 @error('difficulty') outline-red-500/70 @enderror">
                    <option value="" disabled {{ old('difficulty', $recipe->difficulty?->value ?? '') === '' ? 'selected' : '' }}>
                        Bitte wählen...
                    </option>
                    @foreach($difficulties as $diff)
                        <option value="{{ $diff->value }}"
                                {{ old('difficulty', $recipe->difficulty?->value ?? '') === $diff->value ? 'selected' : '' }}>
                            {{ $diff->label() }}
                        </option>
                    @endforeach
                </select>
                @error('difficulty')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex-1">
                <label for="servings" class="block text-sm/6 font-medium text-stone-400">Portionen:</label>
                <input type="number" id="servings" name="servings" min="1"
                       value="{{ old('servings', $recipe->servings ?? '') }}"
                       placeholder="Anzahl"
                       class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 @error('servings') outline-red-500/70 @enderror">
                @error('servings')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form__group mt-5 md:w-8/12">
            <label for="tags" class="block text-sm/6 font-medium text-stone-400">Tags:</label>
            <input type="text" id="tags" name="tags"
                   value="{{ old('tags', isset($recipe) && $recipe->tags ? implode(', ', $recipe->tags) : '') }}"
                   placeholder="z.B. vegetarisch, italienisch, unter-30-min"
                   class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 @error('tags') outline-red-500/70 @enderror">
            <p class="text-xs text-stone-500 mt-1">Mehrere Tags mit Komma trennen</p>
            @error('tags')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form__group mt-5 md:w-8/12">
            <label for="dropzone-file" class="block text-sm/6 font-medium text-stone-400">Bilder:</label>

            @if(isset($recipe) && $recipe->image_url)
                <div class="mb-3 relative inline-block">
                    <img src="{{ Storage::url($recipe->image_url) }}" alt="Aktuelles Bild" class="h-32 rounded-lg object-cover">
                    <p class="text-xs text-stone-500 mt-1">Aktuelles Bild – neue Auswahl ersetzt es.</p>
                </div>
            @endif

            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 bg-stone-900 border border-dashed border-emerald-500/50 rounded-lg cursor-pointer hover:bg-stone-800 transition-colors @error('images.*') border-red-500/70 @enderror">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-8 h-8 mb-4 text-stone-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2"/>
                    </svg>
                    <p class="mb-2 text-sm text-stone-300"><span class="font-semibold">Klicken zum Hochladen</span> oder Drag & Drop</p>
                    <p class="text-xs text-stone-500">PNG, JPG, WebP (max. 5 MB)</p>
                </div>
                <input id="dropzone-file" name="images[]" type="file" class="hidden" multiple accept="image/*">
            </label>
            @error('images.*')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

    </div>
</section>

<hr class="h-0.5 border-0 bg-stone-700 md:ml-5 md:mr-15 mb-5">
