<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Process;

$paths = [
    'python',
    'C:\\Users\\asus\\.pyenv\\pyenv-win\\shims\\python.bat',
    'C:\\Users\\asus\\.pyenv\\pyenv-win\\versions\\3.11.0\\python.exe',
    'C:\\laragon\\bin\\python\\python-3.10\\python.exe',
    'C:\\Users\\asus\\AppData\\Local\\Programs\\Python\\Python311\\python.exe',
    'C:\\Users\\asus\\AppData\\Local\\Programs\\Python\\Python312\\python.exe',
];

foreach ($paths as $p) {
    $res = Process::run([$p, '-c', 'import cv2; print("CV2 OK in " . cv2.__file__)']);
    echo "Path: {$p}\n";
    echo "Exit: " . $res->exitCode() . "\n";
    echo "Out: " . trim($res->output()) . "\n";
    echo "Err: " . trim($res->errorOutput()) . "\n\n";
}
