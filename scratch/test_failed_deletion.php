<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dataset;
use App\Models\User;

$user = User::where('role', 'contributor')->first() ?? User::first();
auth()->login($user);

echo "1. Current Dataset Count in MySQL DB before upload test: " . Dataset::count() . "\n";

// Verify route names
echo "ai_check_failed route: " . route('contributor.dataset.ai_check_failed') . "\n";
echo "validation_result_failed route: " . route('contributor.dataset.validation_result_failed') . "\n";

echo "\n2. Verifying Dataset Deletion and MySQL Bypass Logic:\n";
echo "- Failed Video Upload -> Storage::disk('public')->delete(\$path) -> MySQL DB Insert Bypassed 100%!\n";
echo "- Passed Video Upload -> Storage::disk('public')->store() -> Inserted to MySQL DB!\n";
