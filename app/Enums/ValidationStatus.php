<?php

namespace App\Enums;

enum ValidationStatus: string
{
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case REVISION_REQUESTED = 'revision_requested';

    public function label(): string
    {
        return match ($this) {
            self::APPROVED => 'Disetujui',
            self::REJECTED => 'Ditolak',
            self::REVISION_REQUESTED => 'Minta Revisi',
        };
    }
}
