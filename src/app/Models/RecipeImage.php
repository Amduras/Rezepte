<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeImage extends Model
{
    protected $table = 'recipe_images';

    protected $fillable = [
        'recipe_id',
        'image_url',
        'sort_order',
    ];

    protected $casts = [
        'recipe_id' => 'integer',
        'sort_order' => 'integer',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
