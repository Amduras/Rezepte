<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'role',
        'status',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password_hash',
    ];

    /**
     * Moderne Casts mit nativen Enums
     */
    protected $casts = [
        'role'         => UserRole::class,
        'status'       => UserStatus::class,
        'created_at'   => 'immutable_datetime',
        'updated_at'   => 'immutable_datetime',
        'deleted_at'   => 'immutable_datetime',
    ];

    // 🔐 AUTH (Anpassung an password_hash statt password)

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    // 🔗 RELATIONEN

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'author_id');
    }

    public function publishedRecipes(): HasMany
    {
        return $this->recipes()->where('status', 'published');
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'user_favorites')
            ->withPivot('created_at')
            ->withTimestamps();
    }

    // ✅ ROLE-CHECKS (mit Enum)

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isContributor(): bool
    {
        return $this->role === UserRole::Contributor;
    }

    public function canContribute(): bool
    {
        return $this->role->atLeast(UserRole::Contributor);
    }

    public function hasRoleAtLeast(UserRole $role): bool
    {
        return $this->role->atLeast($role);
    }

    // ✅ STATUS-CHECKS (mit Enum)

    public function isActive(): bool
    {
        return $this->status === UserStatus::Active;
    }

    public function isBanned(): bool
    {
        return $this->status === UserStatus::Banned;
    }

    public function isPending(): bool
    {
        return $this->status === UserStatus::Pending;
    }

    // 🔍 SCOPES

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', UserStatus::Active);
    }

    public function scopeBanned(Builder $query): Builder
    {
        return $query->where('status', UserStatus::Banned);
    }

    public function scopeWithRole(Builder $query, UserRole $role): Builder
    {
        return $query->where('role', $role);
    }

    public function scopeContributors(Builder $query): Builder
    {
        return $query->where('role', UserRole::Contributor);
    }

    public function scopeAdmins(Builder $query): Builder
    {
        return $query->where('role', UserRole::Admin);
    }
}
