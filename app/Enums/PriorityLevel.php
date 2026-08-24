<?php

namespace App\Enums;

enum PriorityLevel: string
{
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';

    public function label(): string
    {
        return match ($this) {
            self::HIGH => 'Tinggi',
            self::MEDIUM => 'Sedang',
            self::LOW => 'Rendah',
        };
    }
}
