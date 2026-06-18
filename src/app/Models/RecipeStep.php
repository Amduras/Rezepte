<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeStep extends Model
{
    use HasFactory;

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'recipe_id',
        'step_number',
        'instruction',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'step_number' => 'integer',
    ];

    // 🔗 RELATIONEN

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    // 🧮 ACCESSORS

    /**
     * Nummerierter Schritt: "Schritt 1: ..."
     */
    public function getNumberedInstructionAttribute(): string
    {
        return "Schritt {$this->step_number}: {$this->instruction}";
    }

    /**
     * Ist dies der erste Schritt?
     */
    public function isFirstStep(): bool
    {
        return $this->step_number === 1;
    }

    // 🔍 SCOPES

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('step_number', 'asc');
    }

    public function scopeForRecipe(Builder $query, int $recipeId): Builder
    {
        return $query->where('recipe_id', $recipeId);
    }
}
