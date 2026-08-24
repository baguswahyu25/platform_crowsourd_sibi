<?php

namespace App\Models;

use App\Enums\ValidationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset_id',
        'validator_id',
        'status',
        'notes',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ValidationStatus::class,
            'validated_at' => 'datetime',
        ];
    }

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}
