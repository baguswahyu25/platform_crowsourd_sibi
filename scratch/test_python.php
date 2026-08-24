<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Process;

$pythonScript = base_path('ai_validation/analyze_video.py');
$pythonBinary = 'C:\\Users\\asus\\.pyenv\\pyenv-win\\versions\\3.12.7\\python.exe';

echo "Testing direct EXE: " . $pythonBinary . "\n";
echo "File exists: " . (file_exists($pythonBinary) ? 'YES' : 'NO') . "\n";

$res = Process::run([
    $pythonBinary,
    $pythonScript,
    storage_path('app/public/datasets/7RrhPsOFKQj1TQpTRMn83TLAhz7AvUR4umcbbtC6.mp4')
]);

echo "Exit code: " . $res->exitCode() . "\n";
echo "Output: " . $res->output() . "\n";
echo "Error output: " . $res->errorOutput() . "\n";
