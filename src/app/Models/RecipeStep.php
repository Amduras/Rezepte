<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecipeStep extends Model
{
    /** @var string */
    protected $table = 'recipe_steps';

    /** @var bool */
    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'recipe_id',
        'step_number',
        'instruction',
    ];

    /** @var list<string> */
    protected $casts = [
        'recipe_id'   => 'integer',
        'step_number' => 'integer',
    ];


    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
