<?php

namespace App\Services;

use App\Models\Validation;
use App\Models\DatasetNeed;
use App\Enums\DatasetNeedStatus;
use App\Repositories\Contracts\DatasetRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ValidationService
{
    public function __construct(
        protected DatasetRepositoryInterface $datasetRepository
    ) {}

    public function validateDataset(int $datasetId, int $validatorId, string $status, ?string $notes): ?Validation
    {
        return DB::transaction(function () use ($datasetId, $validatorId, $status, $notes) {
            $dataset = $this->datasetRepository->findById($datasetId);
            if (!$dataset) {
                return null;
            }

            if ($status === 'validated') {
                // EXPERT VALID:
                // Transfer video file to Repository storage (Cloudflare R2 / S3 with local fallback)
                $oldPath = $dataset->file_path;
                $newFilePath = $oldPath;

                if (!empty($oldPath)) {
                    $cleanPath = ltrim(str_replace(['public/', 'storage/'], '', $oldPath), '/');
                    $filename = basename($cleanPath);
                    $repoPath = 'repository/' . $filename;
                    $localFullPath = storage_path('app/public/' . $cleanPath);

                    $uploadedToR2 = false;

                    // Check if R2 disk credentials exist
                    $r2Key = config('filesystems.disks.r2.key');
                    $r2Secret = config('filesystems.disks.r2.secret');
                    $r2Bucket = config('filesystems.disks.r2.bucket');

                    if (!empty($r2Key) && !empty($r2Secret) && !empty($r2Bucket) && (Storage::disk('public')->exists($cleanPath) || file_exists($localFullPath))) {
                        try {
                            $fileStream = fopen($localFullPath, 'r');
                            if ($fileStream) {
                                $putSuccess = Storage::disk('r2')->put($repoPath, $fileStream);
                                if (is_resource($fileStream)) {
                                    fclose($fileStream);
                                }
                                if ($putSuccess) {
                                    $uploadedToR2 = true;
                                    $r2Url = config('filesystems.disks.r2.url');
                                    $newFilePath = !empty($r2Url) ? rtrim($r2Url, '/') . '/' . $repoPath : $repoPath;
                                    // Remove temp candidate from public storage
                                    Storage::disk('public')->delete($cleanPath);
                                }
                            }
                        } catch (\Throwable $e) {
                            Log::warning("Cloudflare R2 storage transfer failed, falling back to local storage: " . $e->getMessage());
                        }
                    }

                    // Local storage fallback if R2 is not configured or failed
                    if (!$uploadedToR2 && Storage::disk('public')->exists($cleanPath)) {
                        Storage::disk('public')->makeDirectory('repository');
                        Storage::disk('public')->copy($cleanPath, $repoPath);
                        $newFilePath = $repoPath;
                    }
                }

                $dataset->file_path = $newFilePath;
                $dataset->status = 'validated';
                $dataset->save();

                return Validation::create([
                    'dataset_id' => $datasetId,
                    'validator_id' => $validatorId,
                    'status' => 'validated',
                    'feedback' => $notes,
                ]);
            } else {
                // EXPERT REJECT / INVALID:
                // 1. Remove candidate video from storage
                if (!empty($dataset->file_path)) {
                    $cleanPath = ltrim(str_replace(['public/', 'storage/'], '', $dataset->file_path), '/');
                    Storage::disk('public')->delete($cleanPath);
                }

                // 2. Decrement dataset need current_count if connected
                if (!empty($dataset->dataset_need_id)) {
                    $need = DatasetNeed::find($dataset->dataset_need_id);
                    if ($need && $need->current_count > 0) {
                        $need->decrement('current_count');
                    }
                }

                // 3. Remove validation records if any exist
                Validation::where('dataset_id', $datasetId)->delete();

                // 4. Delete candidate dataset record completely
                $dataset->delete();

                return null;
            }
        });
    }
}
