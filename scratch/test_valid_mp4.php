<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

$videoPath = storage_path('app/public/test_sample.mp4');

$uploadedFile = new UploadedFile(
    $videoPath,
    'test_sample.mp4',
    'video/mp4',
    null,
    true
);

$v = Validator::make([
    'dataset_file' => $uploadedFile
], [
    'dataset_file' => 'required|file|mimes:mp4,mov,avi,mkv,webm|max:3072'
], [
    'dataset_file.required' => 'Berkas video wajib dipilih sebelum melakukan pengunggahan.',
    'dataset_file.file' => 'Berkas yang diunggah harus berupa file video valid.',
    'dataset_file.mimes' => 'Format berkas video harus berupa MP4, MOV, AVI, MKV, atau WEBM.',
    'dataset_file.max' => 'Ukuran berkas video melebihi batas maksimum 3 MB.',
]);

if ($v->fails()) {
    echo "FAIL: " . implode(', ', $v->errors()->all()) . "\n";
} else {
    echo "PASSED 100%! (Video file 1.3MB valid and accepted!)\n";
}
