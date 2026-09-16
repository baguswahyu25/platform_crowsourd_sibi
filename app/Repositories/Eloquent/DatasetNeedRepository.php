<?php

namespace App\Repositories\Eloquent;

use App\Models\DatasetNeed;
use App\Repositories\Contracts\DatasetNeedRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DatasetNeedRepository implements DatasetNeedRepositoryInterface
{
    public function all(): Collection
    {
        $needs = DatasetNeed::with('creator')->get();
        return $this->sortNeedsCollection($needs);
    }

    public function getActiveNeeds(): Collection
    {
        $needs = DatasetNeed::where('status', 'active')->get();
        return $this->sortNeedsCollection($needs);
    }

    public function create(array $data): DatasetNeed
    {
        return DatasetNeed::create($data);
    }

    protected function sortNeedsCollection(Collection $needs): Collection
    {
        $subOrder = [
            'letters' => 1,
            'numbers' => 2,
            'Kata ganti diri' => 1,
            'Kata kerja (kata dasar)' => 2,
            'Kata benda' => 3,
            'Kata sifat' => 4,
        ];

        return $needs->sort(function ($a, $b) use ($subOrder) {
            // Compare category first
            if ($a->category !== $b->category) {
                return strcmp($a->category, $b->category);
            }

            // Compare subcategory order
            $orderA = $subOrder[$a->subcategory ?? ''] ?? 99;
            $orderB = $subOrder[$b->subcategory ?? ''] ?? 99;

            if ($orderA !== $orderB) {
                return $orderA <=> $orderB;
            }

            // Use natural human-friendly comparison (1 -> 10, A -> Z)
            return strnatcasecmp($a->title, $b->title);
        })->values();
    }
}
