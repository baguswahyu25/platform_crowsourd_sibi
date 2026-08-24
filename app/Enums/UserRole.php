<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case VALIDATOR = 'validator';
    case CONTRIBUTOR = 'contributor';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::VALIDATOR => 'Validator SIBI',
            self::CONTRIBUTOR => 'Kontributor',
        };
    }
}
