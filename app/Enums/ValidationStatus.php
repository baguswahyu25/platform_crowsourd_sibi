<?php

namespace App\Enums;

enum ValidationStatus: string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
        };
    }
}
