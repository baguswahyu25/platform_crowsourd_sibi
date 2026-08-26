<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Http\UploadedFile;

// Log in as contributor
$user = User::where('role', 'contributor')->first() ?? User::first();
auth()->login($user);

echo "1. Current Logged In User: " . auth()->user()->name . " (Role: " . auth()->user()->role->value . ")\n";

// Verify routes
echo "\n2. Verifying Route Names:\n";
echo "store route: " . route('contributor.dataset.store') . "\n";
echo "ai_check route: " . route('contributor.dataset.ai_check', ['id' => 1]) . "\n";
echo "validation_result route: " . route('dataset.validation-result', 1) . "\n";

// Test Dataset record
$dataset = Dataset::latest()->first();
if ($dataset) {
    echo "\n3. Testing Latest Dataset Record:\n";
    echo "ID: " . $dataset->id . "\n";
    echo "Title: " . $dataset->title . "\n";
    echo "Auto Validation Status: " . $dataset->auto_validation_status . "\n";
    echo "Brightness Status: " . $dataset->brightness_status . "\n";
    echo "Blur Status: " . $dataset->blur_status . "\n";
    echo "Freeze Status: " . $dataset->freeze_status . "\n";
    echo "Resolution Status: " . $dataset->resolution_status . "\n";
    echo "\nAI Check URL for this dataset: " . route('contributor.dataset.ai_check', ['id' => $dataset->id]) . "\n";
    echo "Validation Result URL for this dataset: " . route('dataset.validation-result', $dataset->id) . "\n";
}
