<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    /**
     * Favorit toggeln (hinzufügen/entfernen)
     */
    public function toggle(Recipe $recipe): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->favorites()->where('recipe_id', $recipe->id)->exists()) {
            $user->favorites()->detach($recipe);
            $message = 'Rezept wurde aus Favoriten entfernt.';
        } else {
            $user->favorites()->attach($recipe);
            $message = 'Rezept wurde zu Favoriten hinzugefügt!';
        }

        return back()->with('success', $message);
    }

    /**
     * Alle Favoriten anzeigen
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $favorites = $user->favorites()
            ->with(['author', 'images'])
            ->published()
            ->latestFirst()
            ->get();

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }
}
