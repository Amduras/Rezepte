<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeIngredient extends Model
{
    use HasFactory;

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'recipe_id',
        'name',
        'quantity',
        'unit',
        'note',
    ];

    // 🔗 RELATIONEN

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    // 🧮 ACCESSORS

    /**
     * Formatierte Mengenangabe: "200 g", "1/2 TL", "nach Belieben"
     */
    public function getFormattedQuantityAttribute(): string
    {
        $parts = array_filter([
            trim((string) ($this->quantity ?? '')),
            trim((string) ($this->unit ?? '')),
        ]);

        return implode(' ', $parts);
    }

    /**
     * Komplette Zutat: "200 g Mehl (gesiebt)"
     */
    public function getFullDescriptionAttribute(): string
    {
        $base = trim("{$this->formatted_quantity} {$this->name}");

        return $this->note
            ? "{$base} ({$this->note})"
            : $base;
    }

    /**
     * Prüft ob die Zutat eine Mengenangabe hat
     */
    public function hasQuantity(): bool
    {
        return !empty(trim((string) $this->quantity));
    }
}
