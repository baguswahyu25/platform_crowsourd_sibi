<?php

namespace App\Repositories\Eloquent;

use App\Models\Dataset;
use App\Repositories\Contracts\DatasetRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DatasetRepository implements DatasetRepositoryInterface
{
    public function all(): Collection
    {
        return Dataset::with(['user', 'datasetNeed', 'validation'])->latest()->get();
    }

    public function findById(int $id): ?Dataset
    {
        return Dataset::with(['user', 'datasetNeed', 'validation'])->find($id);
    }

    public function getByUserId(int $userId): Collection
    {
        return Dataset::where('user_id', $userId)->latest()->get();
    }

    public function getPendingValidation(): Collection
    {
        return Dataset::whereIn('status', ['waiting_expert_validation', 'pending'])->with('user')->latest()->get();
    }

    public function create(array $data): Dataset
    {
        return Dataset::create($data);
    }

    public function updateStatus(int $id, string $status): Dataset
    {
        $dataset = Dataset::findOrFail($id);
        $dataset->update(['status' => $status]);
        return $dataset;
    }
}
