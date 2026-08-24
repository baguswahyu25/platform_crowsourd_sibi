<?php

namespace App\Repositories\Contracts;

use App\Models\DatasetNeed;
use Illuminate\Database\Eloquent\Collection;

interface DatasetNeedRepositoryInterface
{
    public function all(): Collection;
    public function getActiveNeeds(): Collection;
    public function create(array $data): DatasetNeed;
}
