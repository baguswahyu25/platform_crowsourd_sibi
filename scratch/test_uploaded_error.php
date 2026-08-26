<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Validator;

echo "1. Testing Validation Messages Translation for 'uploaded' & 'max':\n";

$validator = Validator::make([
    'dataset_file' => null,
], [
    'dataset_file' => 'required|file|uploaded|mimes:mp4,mov,avi,mkv,webm|max:3072',
], [
    'dataset_file.required' => 'Berkas video wajib dipilih sebelum melakukan pengunggahan.',
    'dataset_file.file' => 'Berkas yang diunggah harus berupa file video valid.',
    'dataset_file.uploaded' => 'Ukuran berkas video yang diunggah melebihi batas maksimum 3 MB atau gagal terunggah ke server.',
    'dataset_file.mimes' => 'Format berkas video harus berupa MP4, MOV, AVI, MKV, atau WEBM.',
    'dataset_file.max' => 'Ukuran berkas video melebihi batas maksimum 3 MB.',
]);

if ($validator->fails()) {
    echo "Validation Errors:\n";
    foreach ($validator->errors()->all() as $err) {
        echo "- " . $err . "\n";
    }
}
