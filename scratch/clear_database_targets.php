<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Enums\DatasetNeedStatus;
use App\Models\Dataset;
use App\Models\DatasetNeed;
use App\Models\Validation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

echo "=======================================================\n";
echo "CLEANING DATABASE & RESETTING ALL DATASET NEED TARGETS:\n";
echo "=======================================================\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// 1. Truncate validations and datasets
Validation::truncate();
Dataset::truncate();

// 2. Reset ALL dataset_needs current_count to 0
DB::table('dataset_needs')->update([
    'current_count' => 0,
]);

// 3. For active dataset needs, ensure status = 'active'
DatasetNeed::where('status', '!=', DatasetNeedStatus::INACTIVE)->update([
    'status' => DatasetNeedStatus::ACTIVE,
]);

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "- Table 'validations' & 'datasets' truncated.\n";
echo "- Reset current_count to 0 for ALL dataset needs.\n";

echo "\n=======================================================\n";
echo "VERIFYING CLEAN DATABASE STATE:\n";
echo "=======================================================\n";

$fulfilledNeeds = DatasetNeed::where('status', DatasetNeedStatus::FULFILLED)
    ->orWhere('current_count', '>', 0)
    ->count();

$totalDatasets = Dataset::count();
$activeNeedsCount = DatasetNeed::where('status', DatasetNeedStatus::ACTIVE)->count();

echo "- Active Dataset Needs: {$activeNeedsCount} items (Status: Active)\n";
echo "- Fulfilled Needs (Target Terpenuhi): {$fulfilledNeeds} items (0 as expected!)\n";
echo "- Total Uploaded Datasets: {$totalDatasets} items\n";

echo "\nDATABASE CLEANUP COMPLETE!\n";
