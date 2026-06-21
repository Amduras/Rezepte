<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Tägliche Random-Auswahl (24h Cache)
        $dailyRecipes = Cache::remember('daily_recipes', now()->endOfDay(), function () {
            return Recipe::published()
                ->with(['author', 'images'])
                ->inRandomOrder()
                ->limit(5)
                ->get();
        });

        // Neueste 15 Rezepte (chronologisch)
        $latestRecipes = Recipe::published()
            ->with(['author', 'images'])
            ->latestFirst()
            ->limit(15)
            ->get();

        return view('welcome', [
            'dailyRecipes' => $dailyRecipes,
            'latestRecipes' => $latestRecipes,
        ]);
    }
}
