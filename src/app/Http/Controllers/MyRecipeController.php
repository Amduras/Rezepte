<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyRecipeController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        $recipes = Recipe::byAuthor($user->id)
            ->with(['author', 'images'])
            ->latestFirst()
            ->get();

        return view('my-recipes.index', [
            'recipes' => $recipes,
        ]);
    }
}
