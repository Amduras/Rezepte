<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeIngredient extends Model
{
    /** @var string */
    protected $table = 'recipe_ingredients';

    /** @var bool */
    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'recipe_id',
        'name',
        'quantity',
        'unit',
        'note',
    ];

    /** @var list<string> */
    protected $casts = [
        'recipe_id' => 'integer',
    ];


    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }


    public function getFormattedQuantityAttribute(): string
    {
        $parts = [];

        if ($this->quantity) {
            $parts[] = $this->quantity;
        }

        if ($this->unit) {
            $parts[] = $this->unit;
        }

        return implode(' ', $parts);
    }

    public function getDisplayNameAttribute(): string
    {
        $result = $this->formatted_quantity;

        if ($this->name) {
            $result .= ($result ? ' ' : '') . $this->name;
        }

        if ($this->note) {
            $result .= " ({$this->note})";
        }

        return $result;
    }
}
