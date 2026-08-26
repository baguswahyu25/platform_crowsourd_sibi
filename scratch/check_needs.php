<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DatasetNeed;

echo "=======================================================\n";
echo "SUMMARY OF PREDEFINED DATASET NEEDS BY CATEGORY:\n";
echo "=======================================================\n";

$cats = DatasetNeed::where('status', 'active')
    ->selectRaw('category_id, category, COUNT(*) as count')
    ->groupBy('category_id', 'category')
    ->get();

foreach ($cats as $c) {
    echo "- Category ID: '{$c->category_id}' ({$c->category}): {$c->count} predefined labels\n";
}

echo "\nDetailed Subcategory Breakdown for Word:\n";
$wordSubcats = DatasetNeed::where('status', 'active')
    ->where('category_id', 'word')
    ->selectRaw('subcategory, COUNT(*) as count')
    ->groupBy('subcategory')
    ->get();

foreach ($wordSubcats as $ws) {
    echo "  * Subcategory '{$ws->subcategory}': {$ws->count} predefined labels\n";
}

echo "\nBangun Versions Check:\n";
$bangunNeeds = DatasetNeed::where('title', 'LIKE', '%Bangun%')->get();
foreach ($bangunNeeds as $bn) {
    echo "  * ID {$bn->id}: {$bn->title} (Status: {$bn->status->value})\n";
}

echo "\nTotal Active Predefined Labels in Database: " . DatasetNeed::where('status', 'active')->count() . "\n";
