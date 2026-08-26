<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Dataset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

echo "1. Testing PHP Max Upload Limit Configurations:\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";

echo "\n2. Testing Real Video File Processing with OpenCV Script...\n";

// Create a dummy video file using OpenCV or test file
$videoPath = storage_path('app/public/test_sample.mp4');

// Create dummy 1-second video if not exists using OpenCV
exec('python -c "import cv2, numpy as np; out = cv2.VideoWriter(\'' . str_replace('\\', '/', $videoPath) . '\', cv2.VideoWriter_fourcc(*\'mp4v\'), 30, (640, 480)); img = np.zeros((480, 640, 3), np.uint8); [out.write(img) for _ in range(30)]; out.release()"');

if (file_exists($videoPath)) {
    echo "Sample Video Created at: " . $videoPath . " (Size: " . filesize($videoPath) . " bytes)\n";

    $uploadedFile = new UploadedFile(
        $videoPath,
        'test_sample.mp4',
        'video/mp4',
        null,
        true
    );

    echo "Is Uploaded File Valid? " . ($uploadedFile->isValid() ? 'YES' : 'NO') . "\n";
    $storedPath = $uploadedFile->store('datasets', 'public');
    echo "Stored Path: " . $storedPath . "\n";
} else {
    echo "Could not create sample video file.\n";
}
