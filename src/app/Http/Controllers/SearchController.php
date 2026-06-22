<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->input('q', '');

        // Leere Suche → keine Ergebnisse anzeigen
        if (trim($query) === '') {
            return view('search.index', [
                'query' => '',
                'recipes' => collect(),
            ]);
        }

        // Suche über mehrere Tabellen
        $recipes = Recipe::query()
            ->published()
            ->with(['author', 'images'])
            ->where(function ($q) use ($query) {
                // Titel
                $q->where('recipes.title', 'LIKE', "%{$query}%")
                    // Beschreibung
                    ->orWhere('recipes.description', 'LIKE', "%{$query}%")
                    // Tags (JSON-Spalte)
                    ->orWhereRaw("JSON_SEARCH(recipes.tags, 'one', ?) IS NOT NULL", ["%{$query}%"])
                    // Autor-Username
                    ->orWhereHas('author', function ($aq) use ($query) {
                        $aq->where('username', 'LIKE', "%{$query}%");
                    })
                    // Zutaten-Name
                    ->orWhereHas('ingredients', function ($iq) use ($query) {
                        $iq->where('name', 'LIKE', "%{$query}%");
                    });
            })
            ->latestFirst()
            ->paginate(12)
            ->withQueryString(); // Behält den Such-Parameter bei Pagination

        return view('search.index', [
            'query' => $query,
            'recipes' => $recipes,
        ]);
    }
}
