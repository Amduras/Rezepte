<?php

declare(strict_types=1);

namespace App\Enums;

enum RecipeStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Entwurf',
            self::Published => 'Veröffentlicht',
            self::Archived  => 'Archiviert',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft     => 'gray',
            self::Published => 'green',
            self::Archived  => 'red',
        };
    }
}
