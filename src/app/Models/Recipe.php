<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RecipeDifficulty;
use App\Enums\RecipeStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    /** @var string */
    protected $table = 'recipes';

    /** @var list<string> */
    protected $fillable = [
        'author_id',
        'title',
        'description',
        'prep_time',
        'cook_time',
        'servings',
        'difficulty',
        'image_url',
        'tags',
        'status',
    ];

    /**
     * - Native Enums für type-safe ENUM-Spalten
     * - immutable_datetime verhindert Seiteneffekte bei Carbon-Objekten
     * - 'array' castet JSON automatisch in PHP-Arrays
     */
    protected $casts = [
        'author_id'  => 'integer',
        'prep_time'  => 'integer',
        'cook_time'  => 'integer',
        'servings'   => 'integer',
        'difficulty' => RecipeDifficulty::class,
        'status'     => RecipeStatus::class,
        'tags'       => 'array',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
        'deleted_at' => 'immutable_datetime',
    ];


    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(RecipeStep::class)->orderBy('step_number');
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorites')
            ->withPivot('created_at')
            ->withTimestamps();
    }


    public function getTotalTimeAttribute(): ?int
    {
        if (is_null($this->prep_time) && is_null($this->cook_time)) {
            return null;
        }
        return ($this->prep_time ?? 0) + ($this->cook_time ?? 0);
    }

    public function getFormattedTotalTimeAttribute(): string
    {
        $minutes = $this->total_time;
        if (is_null($minutes)) {
            return 'k.A.';
        }

        if ($minutes < 60) {
            return "{$minutes} Min.";
        }

        $hours = intdiv($minutes, 60);
        $remaining = $minutes % 60;

        return $remaining > 0 ? "{$hours} Std. {$remaining} Min." : "{$hours} Std.";
    }

    public function images(): HasMany
    {
        return $this->hasMany(RecipeImage::class)->orderBy('sort_order');
    }

    // Optional: Helper für das erste Bild (als Fallback)
    public function getPrimaryImageAttribute(): ?string
    {
        return $this->images->first()?->image_url ?? $this->image_url;
    }

    public function isPublished(): bool
    {
        return $this->status === RecipeStatus::Published;
    }

    public function isDraft(): bool
    {
        return $this->status === RecipeStatus::Draft;
    }

    public function isArchived(): bool
    {
        return $this->status === RecipeStatus::Archived;
    }

    public function isEasy(): bool
    {
        return $this->difficulty === RecipeDifficulty::Easy;
    }

    public function isMedium(): bool
    {
        return $this->difficulty === RecipeDifficulty::Medium;
    }

    public function isHard(): bool
    {
        return $this->difficulty === RecipeDifficulty::Hard;
    }


    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', RecipeStatus::Published);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', RecipeStatus::Draft);
    }

    public function scopeDifficulty(Builder $query, RecipeDifficulty $difficulty): Builder
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeWithTag(Builder $query, string $tag): Builder
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopeByAuthor(Builder $query, int $userId): Builder
    {
        return $query->where('author_id', $userId);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }
}
