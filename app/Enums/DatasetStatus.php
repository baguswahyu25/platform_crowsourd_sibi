<?php

namespace App\Enums;

enum DatasetStatus: string
{
    case PENDING = 'pending';
    case FAILED = 'failed';
    case WAITING_EXPERT_VALIDATION = 'waiting_expert_validation';
    case VALIDATED = 'validated';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Validasi AI',
            self::FAILED => 'Gagal Validasi AI',
            self::WAITING_EXPERT_VALIDATION => 'Menunggu Validasi Pakar',
            self::VALIDATED => 'Tervalidasi Pakar',
            self::REJECTED => 'Ditolak Pakar',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING => 'bg-amber-100 text-amber-800 border-amber-200',
            self::FAILED => 'bg-rose-100 text-rose-800 border-rose-200',
            self::WAITING_EXPERT_VALIDATION => 'bg-blue-100 text-blue-800 border-blue-200',
            self::VALIDATED => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::REJECTED => 'bg-rose-100 text-rose-800 border-rose-200',
        };
    }
}
