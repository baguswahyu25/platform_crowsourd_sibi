<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dataset_id',
        'validator_id',
        'status',
        'feedback',
        'rejection_reason',
        'validated_at',
    ];

    public function dataset()
    {
        return $this->belongsTo(Dataset::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}
