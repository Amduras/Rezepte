<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // 📏 Konstanten für Username-Validierung
    public const USERNAME_MIN_LENGTH = 4;
    public const USERNAME_MAX_LENGTH = 50;
    public const USERNAME_REGEX = '/^[\w\-]+$/';

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password_hash',
        'role',
        'status',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token', // ✅ NEU
    ];

    protected $casts = [
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
        'deleted_at' => 'immutable_datetime',
    ];

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isContributor(): bool
    {
        return $this->role === 'contributor';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'author_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Recipe::class, 'user_favorites')
            ->withTimestamps();
    }
}
