<?php

namespace App\Repositories\Contracts;

use App\Models\Dataset;
use Illuminate\Database\Eloquent\Collection;

interface DatasetRepositoryInterface
{
    public function all(): Collection;
    public function findById(int $id): ?Dataset;
    public function getByUserId(int $userId): Collection;
    public function getPendingValidation(): Collection;
    public function create(array $data): Dataset;
    public function updateStatus(int $id, string $status): Dataset;
}
