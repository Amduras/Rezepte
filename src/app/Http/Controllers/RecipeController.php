<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RecipeDifficulty;
use App\Enums\RecipeStatus;
use App\Http\Requests\StoreRecipeRequest;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Zeigt das Formular zum Erstellen eines Rezepts
     */
    public function create(): View
    {
        return view('recipes.create', [
            'difficulties' => RecipeDifficulty::cases(),
        ]);
    }

    /**
     * Speichert ein neues Rezept inkl. Zutaten & Schritte
     */
    public function store(StoreRecipeRequest $request): RedirectResponse
    {
        $imageUrl = null;
        if ($request->hasFile('images')) {
            $image = $request->file('images')[0];
            $imageUrl = $image->store('recipes', 'public');
        }

        $tags = null;
        if ($request->filled('tags')) {
            $tags = array_values(
                array_filter(
                    array_map('trim', explode(',', $request->input('tags')))
                )
            );
        }

        $recipe = DB::transaction(function () use ($request, $imageUrl, $tags): Recipe {

            // 1) Rezept anlegen
            $recipe = Recipe::create([
                'author_id'   => Auth::id(),
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
                'prep_time'   => $request->input('prep_time'),
                'cook_time'   => $request->input('cook_time'),
                'servings'    => $request->input('servings'),
                'difficulty'  => RecipeDifficulty::from($request->input('difficulty')),
                'image_url'   => $imageUrl,
                'tags'        => $tags,
                'status'      => RecipeStatus::Draft,
            ]);

            // 2) Zutaten speichern
            if ($request->has('ingredients')) {
                foreach ($request->input('ingredients') as $ingredient) {
                    if (empty($ingredient['name'])) {
                        continue;
                    }

                    $recipe->ingredients()->create([
                        'name'     => $ingredient['name'],
                        'quantity' => $ingredient['quantity'] ?? null,
                        'unit'     => $ingredient['unit'] ?? null,
                        'note'     => $ingredient['note'] ?? null,
                    ]);
                }
            }

            // 3) Zubereitungsschritte speichern
            if ($request->has('steps')) {
                $stepNumber = 1;
                foreach ($request->input('steps') as $step) {
                    if (empty(trim($step['instruction'] ?? ''))) {
                        continue;
                    }

                    $recipe->steps()->create([
                        'step_number' => $stepNumber++,
                        'instruction' => $step['instruction'],
                    ]);
                }
            }

            return $recipe;
        });

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'Rezept wurde erfolgreich erstellt!');
    }
}
