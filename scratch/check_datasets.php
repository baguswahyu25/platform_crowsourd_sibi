<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dataset;
use Illuminate\Support\Facades\Storage;

$datasets = Dataset::all();

echo "Total Datasets in DB: " . $datasets->count() . "\n\n";

foreach ($datasets as $ds) {
    echo "ID: {$ds->id} | Title: {$ds->title} | Path: {$ds->file_path}\n";
    $fullPathPublic = storage_path('app/public/' . ltrim($ds->file_path, '/'));
    $fullPathApp = storage_path('app/' . ltrim($ds->file_path, '/'));
    $existsPublic = file_exists($fullPathPublic);
    $existsApp = file_exists($fullPathApp);
    $existsDisk = Storage::disk('public')->exists(ltrim($ds->file_path, 'public/'));
    
    echo "  - storage_path('app/public/'): " . ($existsPublic ? "EXISTS" : "NOT FOUND ({$fullPathPublic})") . "\n";
    echo "  - storage_path('app/'): " . ($existsApp ? "EXISTS" : "NOT FOUND") . "\n";
    echo "  - Storage::disk('public')->exists(): " . ($existsDisk ? "EXISTS" : "NOT FOUND") . "\n";
    echo "  - Storage::url(): " . Storage::url($ds->file_path) . "\n";
    echo "  - Asset URL: " . asset('storage/' . ltrim($ds->file_path, '/storage/')) . "\n\n";
}
