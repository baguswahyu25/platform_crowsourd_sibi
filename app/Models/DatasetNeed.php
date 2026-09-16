<?php

namespace App\Models;

use App\Enums\DatasetNeedStatus;
use App\Enums\PriorityLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatasetNeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'category_id',
        'subcategory',
        'description',
        'current_count',
        'priority',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'priority' => PriorityLevel::class,
            'status' => DatasetNeedStatus::class,
            'current_count' => 'integer',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function datasets()
    {
        return $this->hasMany(Dataset::class);
    }
}
