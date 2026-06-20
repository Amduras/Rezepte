@php
    // Bestehende Schritte (Edit) oder ein leerer Schritt (Create)
    $existingSteps = isset($recipe) && $recipe->steps->isNotEmpty()
        ? $recipe->steps
        : collect([(object)[
            'instruction' => old('steps.0.instruction'),
        ]]);
@endphp

<section id="recipe_steps" class="w-full md:flex mb-5">
    <div class="w-full md:w-1/4 md:ml-5">
        <p class="text-base/7 font-semibold">Zubereitung</p>
        <p class="mt-1 text-sm/6 text-stone-400">Beschreibe die Zubereitungsschritte</p>
    </div>

    <div class="w-full md:w-3/4 md:ml-15 md:mr-15">

        <div id="steps-list" class="space-y-4">

            @foreach($existingSteps as $index => $step)
                <div class="step-row w-full md:flex md:items-start gap-4" data-index="{{ $index }}">

                    <div class="w-full md:w-1/12 flex md:justify-center items-center">
                        <span class="step-number text-2xl font-bold text-emerald-500">{{ $index + 1 }}</span>
                    </div>

                    <div class="w-full md:w-10/12">
                        <label class="block text-sm/6 font-medium text-stone-400 mb-1">Beschreibung:</label>
                        <textarea name="steps[{{ $index }}][instruction]" rows="3"
                                  placeholder="z.B. Mehl in eine große Schüssel sieben..."
                                  class="block w-full min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-stone-400 placeholder:text-gray-500 sm:text-sm/6 rounded-md outline-1 outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-emerald-500/50 resize-y @error("steps.$index.instruction") outline-red-500/70 @enderror">{{ old("steps.$index.instruction", $step->instruction ?? '') }}</textarea>
                        @error("steps.$index.instruction")
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full md:w-1/12 flex md:justify-center items-start pt-6">
                        <button type="button" class="remove-step-btn text-red-400 hover:text-red-300 transition-colors cursor-pointer"
                                style="visibility: {{ $existingSteps->count() <= 1 ? 'hidden' : 'visible' }};">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

        </div>

        <button type="button" id="add-step-btn" class="mt-4 flex items-center text-sm text-emerald-500 hover:text-emerald-400 transition-colors cursor-pointer">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Weiteren Schritt hinzufügen
        </button>

    </div>
</section>
