<?php

namespace App\Models;

use App\Enums\DatasetStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dataset_need_id',
        'contributor_code',
        'title',
        'category',
        'sign_label',
        'description',
        'file_path',
        'file_type',
        'file_size',
        // AUTO VALIDATION
        'brightness_score',
        'brightness_status',
        'blur_score',
        'blur_status',
        'freeze_percentage',
        'freeze_status',
        'video_width',
        'video_height',
        'video_fps',
        'resolution_status',
        'auto_validation_status',
        'validation_message',
        // OVERALL STATUS
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => DatasetStatus::class,
            'file_size' => 'integer',
            'brightness_score' => 'float',
            'blur_score' => 'float',
            'freeze_percentage' => 'float',
            'video_width' => 'integer',
            'video_height' => 'integer',
            'video_fps' => 'float',
        ];
    }

    public function getContributorDisplayAttribute(): string
    {
        $code = $this->contributor_code ?? ('Kontributor ' . ($this->user_id ?? 1));
        $userName = $this->user ? $this->user->name : 'Kontributor';
        return "{$code} ({$userName})";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function datasetNeed()
    {
        return $this->belongsTo(DatasetNeed::class);
    }

    public function validation()
    {
        return $this->hasOne(Validation::class);
    }
}
