<?php

declare(strict_types=1);

namespace App\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Banned = 'banned';
    case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Active  => 'Aktiv',
            self::Banned  => 'Gesperrt',
            self::Pending => 'Ausstehend',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active  => 'green',
            self::Banned  => 'red',
            self::Pending => 'yellow',
        };
    }
}
