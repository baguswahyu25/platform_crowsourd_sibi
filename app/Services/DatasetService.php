<?php

namespace App\Services;

use App\Repositories\Contracts\DatasetRepositoryInterface;
use App\Models\Dataset;
use App\Models\DatasetNeed;
use App\Enums\DatasetNeedStatus;
use Illuminate\Database\Eloquent\Collection;

class DatasetService
{
    public function __construct(
        protected DatasetRepositoryInterface $datasetRepository
    ) {}

    public function getAllDatasets(): Collection
    {
        return $this->datasetRepository->all();
    }

    public function getUserDatasets(int $userId): Collection
    {
        return $this->datasetRepository->getByUserId($userId);
    }

    public function getPendingDatasets(): Collection
    {
        return $this->datasetRepository->getPendingValidation();
    }

    public function storeDataset(array $data, int $userId): Dataset
    {
        $data['user_id'] = $userId;
        $data['contributor_code'] = 'Kontributor ' . $userId;
        $data['status'] = $data['status'] ?? 'pending';

        $dataset = $this->datasetRepository->create($data);

        // Increment dataset need current_count if assigned
        if (!empty($data['dataset_need_id'])) {
            $need = DatasetNeed::find($data['dataset_need_id']);
            if ($need) {
                $need->increment('current_count');
                if ($need->current_count >= $need->target_count) {
                    $need->update(['status' => DatasetNeedStatus::FULFILLED]);
                }
            }
        }

        return $dataset;
    }
}
