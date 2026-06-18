<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case User = 'user';
    case Contributor = 'contributor';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::User        => 'Benutzer',
            self::Contributor => 'Mitwirkender',
            self::Admin       => 'Administrator',
        };
    }

    public function level(): int
    {
        return match ($this) {
            self::User        => 1,
            self::Contributor => 2,
            self::Admin       => 3,
        };
    }

    public function atLeast(self $role): bool
    {
        return $this->level() >= $role->level();
    }
}
