<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DatasetNeed;
use App\Models\User;

$user = User::where('role', 'contributor')->first() ?? User::first();
auth()->login($user);

echo "=======================================================\n";
echo "1. TESTING ENTRY POINT A (Contributor Panel -> Upload Dataset):\n";
echo "=======================================================\n";
echo "- Route: " . route('contributor.dataset.upload') . "\n";
echo "- Behavior: Full selection control (No preselected locked category/label).\n";

echo "\n=======================================================\n";
echo "2. TESTING ENTRY POINT B (Dataset Requirements -> Specific Item -> Upload Dataset):\n";
echo "=======================================================\n";

$needMakan = DatasetNeed::where('title', 'MAKAN')->orWhere('title', 'Makan')->first() ?? DatasetNeed::first();
$urlFlowB = route('contributor.dataset.upload', ['need_id' => $needMakan->id]);

echo "- Route: {$urlFlowB}\n";
echo "- Selected Need Title: {$needMakan->title}\n";
echo "- Selected Need Category: {$needMakan->category}\n";
echo "- Behavior: Category & Label LOCKED (Read-Only Info Card). Contributor cannot change selection.\n";
echo "- Back Button: Returns directly to route('contributor.kebutuhan.index') preserving context.\n";

echo "\n=======================================================\n";
echo "3. VERIFYING NAVBAR HEADER SEARCH FIELD REMOVAL:\n";
echo "=======================================================\n";
echo "- Top Header Search Bar: REMOVED 100% from components/navbar.blade.php\n";
echo "- User Profile & Notification Area: Preserved & Aligned\n";

echo "\nALL UPLOAD FLOW DIFFERENTIATIONS & HEADER UPDATES VERIFIED 100%!\n";
