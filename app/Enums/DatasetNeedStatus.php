<?php

namespace App\Enums;

enum DatasetNeedStatus: string
{
    case ACTIVE = 'active';
    case FULFILLED = 'fulfilled';
    case CLOSED = 'closed';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::FULFILLED => 'Terpenuhi',
            self::CLOSED => 'Ditutup',
            self::INACTIVE => 'Nonaktif',
        };
    }
}
