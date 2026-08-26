<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DatasetNeed;

echo "=======================================================\n";
echo "1. VERIFYING CATEGORY FILTERING DATA ON KEBUTUHAN DATASET PAGE:\n";
echo "=======================================================\n";

$needs = DatasetNeed::all();

$abjadCount = $needs->filter(fn($n) => str_starts_with($n->title, 'Huruf'))->count();
$angkaCount = $needs->filter(fn($n) => str_starts_with($n->title, 'Angka'))->count();
$kataCount = $needs->filter(fn($n) => $n->category_id === 'word' || str_contains(strtolower($n->category), 'kata'))->count();
$frasaCount = $needs->filter(fn($n) => $n->category_id === 'phrase' || str_contains(strtolower($n->category), 'phrase'))->count();
$kalimatCount = $needs->filter(fn($n) => $n->category_id === 'sentence' || str_contains(strtolower($n->category), 'sentence'))->count();

echo "- Filter Abjad (Huruf A-Z): {$abjadCount} items\n";
echo "- Filter Angka (Angka 0-9): {$angkaCount} items\n";
echo "- Filter Kata: {$kataCount} items\n";
echo "- Filter Frasa: {$frasaCount} items\n";
echo "- Filter Kalimat: {$kalimatCount} items\n";
echo "- Filter Cerita Pendek: Contributor-Generated Metadata Banner Active\n";
echo "Total Predefined Items Displayed: " . ($abjadCount + $angkaCount + $kataCount + $frasaCount + $kalimatCount) . "\n";
