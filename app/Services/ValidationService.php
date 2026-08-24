<?php

namespace App\Services;

use App\Models\Validation;
use App\Repositories\Contracts\DatasetRepositoryInterface;

class ValidationService
{
    public function __construct(
        protected DatasetRepositoryInterface $datasetRepository
    ) {}

    public function validateDataset(int $datasetId, int $validatorId, string $status, ?string $notes): Validation
    {
        $dataset = $this->datasetRepository->findById($datasetId);
        $dataset->update(['status' => $status]);

        return Validation::create([
            'dataset_id' => $datasetId,
            'validator_id' => $validatorId,
            'status' => $status,
            'notes' => $notes,
            'validated_at' => now(),
        ]);
    }
}
