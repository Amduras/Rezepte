<?php

declare(strict_types=1);

namespace App\Enums;

enum RecipeDifficulty: string
{
    case Easy = 'easy';
    case Medium = 'medium';
    case Hard = 'hard';

    public function label(): string
    {
        return match ($this) {
            self::Easy => 'Einfach',
            self::Medium => 'Mittel',
            self::Hard => 'Schwer',
        };
    }
}
