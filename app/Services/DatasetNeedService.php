<?php

namespace App\Services;

use App\Repositories\Contracts\DatasetNeedRepositoryInterface;
use App\Models\DatasetNeed;
use Illuminate\Database\Eloquent\Collection;

class DatasetNeedService
{
    public function __construct(
        protected DatasetNeedRepositoryInterface $needRepository
    ) {}

    public function getAllNeeds(): Collection
    {
        return $this->needRepository->all();
    }

    public function getActiveNeeds(): Collection
    {
        return $this->needRepository->getActiveNeeds();
    }

    public function createNeed(array $data, int $creatorId): DatasetNeed
    {
        $data['created_by'] = $creatorId;
        return $this->needRepository->create($data);
    }
}
