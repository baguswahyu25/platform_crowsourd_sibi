<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'institution',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function datasets()
    {
        return $this->hasMany(Dataset::class);
    }

    public function validations()
    {
        return $this->hasMany(Validation::class, 'validator_id');
    }

    public function createdNeeds()
    {
        return $this->hasMany(DatasetNeed::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isValidator(): bool
    {
        return $this->role === UserRole::VALIDATOR;
    }

    public function isContributor(): bool
    {
        return $this->role === UserRole::CONTRIBUTOR;
    }
}
