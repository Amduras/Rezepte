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
            <label class="block text-sm/6 font-medium text-stone-400 mb-2">Bilder:</label>

            @if(isset($recipe) && $recipe->images->isNotEmpty())
                <div class="mb-4">
                    <p class="text-xs text-stone-500 mb-3">Bestehende Bilder (zum Löschen markieren):</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($recipe->images as $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image->image_url) }}"
                                     alt="Rezeptbild"
                                     class="w-full h-32 object-cover rounded-lg border border-stone-700">

                                <label class="absolute top-2 right-2 bg-red-600/90 hover:bg-red-500 text-white p-1.5 rounded-full cursor-pointer transition-colors">
                                    <input type="checkbox"
                                           name="delete_images[]"
                                           value="{{ $image->id }}"
                                           class="hidden">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </label>

                                <div class="absolute bottom-2 left-2 bg-stone-900/80 text-stone-200 text-xs px-2 py-1 rounded">
                                    #{{ $image->sort_order }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4 flex items-center gap-2">
                    <input type="checkbox"
                           id="replace_all_images"
                           name="replace_images"
                           value="1"
                           class="h-4 w-4 rounded border-stone-600 bg-stone-700 text-emerald-500 focus:ring-emerald-500/50">
                    <label for="replace_all_images" class="text-sm text-stone-400 cursor-pointer">
                        Alle bestehenden Bilder ersetzen
                    </label>
                </div>
            @endif

            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 bg-stone-900 border-2 border-dashed border-emerald-500/50 rounded-lg cursor-pointer hover:bg-stone-800 hover:border-emerald-500/70 transition-all @error('images.*') border-red-500/70 @enderror">
                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                    <svg class="w-10 h-10 mb-3 text-stone-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="mb-2 text-sm text-stone-300">
                        <span class="font-semibold">Klicken zum Hochladen</span> oder Drag & Drop
                    </p>
                    <p class="text-xs text-stone-500">PNG, JPG, WebP (max. 5 MB pro Bild, max. 10 Bilder)</p>
                </div>
                <input id="dropzone-file" name="images[]" type="file" class="hidden" multiple accept="image/*">
            </label>

            @error('images.*')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>

    </div>
</section>

<hr class="h-0.5 border-0 bg-stone-700 md:ml-5 md:mr-15 mb-5">
