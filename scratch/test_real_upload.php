<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

$user = User::where('role', 'contributor')->first() ?? User::first();
auth()->login($user);

echo "1. Logged in user: " . auth()->user()->name . "\n";

// Create a 1.3MB test video file
$testVideoPath = storage_path('app/public/video_1_3mb.mp4');

// Create 1.3MB dummy video file (1,363,148 bytes)
$fileSizeTarget = 1.3 * 1024 * 1024;
$handle = fopen($testVideoPath, 'wb');
fwrite($handle, str_repeat('0', $fileSizeTarget));
fclose($handle);

echo "Created 1.3 MB Test File at: " . $testVideoPath . " (Actual Size: " . filesize($testVideoPath) . " bytes / " . round(filesize($testVideoPath) / (1024*1024), 2) . " MB)\n";

$uploadedFile = new UploadedFile(
    $testVideoPath,
    'video_1_3mb.mp4',
    'video/mp4',
    null,
    true
);

echo "Is 1.3MB File Valid? " . ($uploadedFile->isValid() ? 'YES' : 'NO') . "\n";

$validator = Validator::make([
    'dataset_file' => $uploadedFile,
], [
    'dataset_file' => 'required|file|mimes:mp4,mov,avi,mkv,webm|max:3072',
], [
    'dataset_file.required' => 'Berkas video wajib dipilih sebelum melakukan pengunggahan.',
    'dataset_file.file' => 'Berkas yang diunggah harus berupa file video valid.',
    'dataset_file.mimes' => 'Format berkas video harus berupa MP4, MOV, AVI, MKV, atau WEBM.',
    'dataset_file.max' => 'Ukuran berkas video melebihi batas maksimum 3 MB.',
]);

echo "Validator Result for 1.3MB Video: " . ($validator->fails() ? 'FAIL (' . implode(', ', $validator->errors()->all()) . ')' : 'PASSED 100% (No Error!)') . "\n";
