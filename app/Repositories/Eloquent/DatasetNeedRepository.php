<?php

namespace App\Repositories\Eloquent;

use App\Models\DatasetNeed;
use App\Repositories\Contracts\DatasetNeedRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DatasetNeedRepository implements DatasetNeedRepositoryInterface
{
    public function all(): Collection
    {
        return DatasetNeed::with('creator')->latest()->get();
    }

    public function getActiveNeeds(): Collection
    {
        return DatasetNeed::where('status', 'active')->latest()->get();
    }

    public function create(array $data): DatasetNeed
    {
        return DatasetNeed::create($data);
    }
}
