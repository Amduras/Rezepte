<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RecipeDifficulty;
use App\Enums\RecipeStatus;
use App\Http\Requests\StoreRecipeRequest;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RecipeController extends Controller
{
    /**
     * Prüft, ob der aktuelle User das Rezept bearbeiten darf.
     * Nur der Autor oder ein Admin hat Zugriff.
     */
    private function canEditRecipe(Recipe $recipe): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        return $user->id === $recipe->author_id || $user->isAdmin();
    }

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
        $recipe = $this->persistRecipe($request);

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'Rezept wurde erfolgreich erstellt!');
    }

    /**
     * Detailansicht eines Rezepts
     */
    public function show(Recipe $recipe): View
    {
        $recipe->load(['author', 'ingredients', 'steps']);

        return view('recipes.show', compact('recipe'));
    }

    /**
     * Bearbeitungs-Formular
     */
    public function edit(Recipe $recipe): View
    {
        if (!$this->canEditRecipe($recipe)) {
            abort(403, 'Du darfst dieses Rezept nicht bearbeiten.');
        }

        $recipe->load(['ingredients', 'steps']);

        return view('recipes.edit', [
            'recipe' => $recipe,
            'difficulties' => RecipeDifficulty::cases(),
        ]);
    }

    public function publish(Recipe $recipe): RedirectResponse
    {
        if (!$this->canEditRecipe($recipe)) abort(403);
        $recipe->update(['status' => RecipeStatus::Published]);
        return back()->with('success', 'Rezept wurde veröffentlicht!');
    }

    public function unpublish(Recipe $recipe): RedirectResponse
    {
        if (!$this->canEditRecipe($recipe)) abort(403);
        $recipe->update(['status' => RecipeStatus::Draft]);
        return back()->with('success', 'Rezept wurde als Entwurf gespeichert.');
    }

    /**
     * Aktualisiert ein bestehendes Rezept
     */
    public function update(StoreRecipeRequest $request, Recipe $recipe): RedirectResponse
    {
        if (!$this->canEditRecipe($recipe)) {
            abort(403, 'Du darfst dieses Rezept nicht bearbeiten.');
        }

        $this->persistRecipe($request, $recipe);

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'Rezept wurde erfolgreich aktualisiert!');
    }

    private function parseTags(?string $tags): ?array
    {
        if (!$tags) return null;
        return array_values(
            array_filter(
                array_map('trim', explode(',', $tags))
            )
        );
    }

    /**
     * Löscht ein Rezept (Soft-Delete)
     */
    public function destroy(Recipe $recipe): RedirectResponse
    {
        if (!$this->canEditRecipe($recipe)) {
            abort(403);
        }

        // Alle Bilder löschen
        foreach ($recipe->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }

        $recipe->delete();

        return redirect()->route('home')->with('success', 'Rezept wurde gelöscht.');
    }

    public function myRecipes()
    {
        $recipes = Recipe::where('author_id', auth()->id())
            ->latest()
            ->paginate(12);

        return view('recipes.my-recipes', compact('recipes'));
    }

    /**
     * Gemeinsame Logik zum Speichern/Aktualisieren eines Rezepts.
     * Wird von store() und update() verwendet.
     *
     * @param Recipe|null $recipe Wenn null → neues Rezept wird erstellt
     */
    private function persistRecipe(StoreRecipeRequest $request, ?Recipe $recipe = null): Recipe
    {
        return DB::transaction(function () use ($request, $recipe): Recipe {

            //  Rezept-Daten
            $data = [
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
                'prep_time'   => $request->input('prep_time'),
                'cook_time'   => $request->input('cook_time'),
                'servings'    => $request->input('servings'),
                'difficulty'  => RecipeDifficulty::from($request->input('difficulty')),
                'tags'        => $this->parseTags($request->input('tags')),
            ];

            if ($recipe) {
                $recipe->update($data);
            } else {
                $data['author_id'] = Auth::id();
                $data['status'] = RecipeStatus::Draft;
                $recipe = Recipe::create($data);
            }

            // ️ Bilder verarbeiten
            if ($request->boolean('replace_images')) {
                // Alle alten Bilder löschen
                foreach ($recipe->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_url);
                    $oldImage->delete();
                }
            } elseif ($request->has('delete_images')) {
                // Markierte Bilder löschen
                $deleteIds = $request->input('delete_images');
                foreach ($recipe->images as $oldImage) {
                    if (in_array($oldImage->id, $deleteIds)) {
                        Storage::disk('public')->delete($oldImage->image_url);
                        $oldImage->delete();
                    }
                }
            }

            // Neue Bilder hinzufügen
            if ($request->hasFile('images')) {
                $sortOrder = $recipe->images()->max('sort_order') ?? 0;
                foreach ($request->file('images') as $image) {
                    $path = $image->store('recipes', 'public');
	                   $recipe->images()->create([
                        'image_url' => $path,
                        'sort_order' => ++$sortOrder,
                    ]);
                }
            }

            // 🥕 Zutaten
            $recipe->ingredients()->delete();
            if ($request->has('ingredients')) {
                foreach ($request->input('ingredients') as $ingredient) {
                    if (empty($ingredient['name'])) continue;
                    $recipe->ingredients()->create([
                        'name'     => $ingredient['name'],
                        'quantity' => $ingredient['quantity'] ?? null,
                        'unit'     => $ingredient['unit'] ?? null,
                        'note'     => $ingredient['note'] ?? null,
                    ]);
                }
            }

            // 👣 Schritte
            $recipe->steps()->delete();
            if ($request->has('steps')) {
                $stepNumber = 1;
                foreach ($request->input('steps') as $step) {
                    if (empty(trim($step['instruction'] ?? ''))) continue;
                    $recipe->steps()->create([
                        'step_number' => $stepNumber++,
                        'instruction' => $step['instruction'],
                    ]);
                }
            }
            return $recipe;
        });
    }
}
