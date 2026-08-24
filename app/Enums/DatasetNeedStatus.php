<?php

namespace App\Enums;

enum DatasetNeedStatus: string
{
    case ACTIVE = 'active';
    case FULFILLED = 'fulfilled';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::FULFILLED => 'Terpenuhi',
            self::CLOSED => 'Ditutup',
        };
    }
}
