<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail;

$targetEmail = 'mbagusws25@gmail.com';
$testOtp = sprintf("%06d", mt_rand(100000, 999999));

echo "Menguji pengiriman email OTP ke: " . $targetEmail . "\n";
echo "Mailer: " . config('mail.default') . "\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Username: " . config('mail.mailers.smtp.username') . "\n";

try {
    Mail::to($targetEmail)->send(new OtpVerificationMail($testOtp, 'Wahyu'));
    echo "\n✅ BERHASIL! Email OTP (Kode: {$testOtp}) telah terkirim langsung ke inbox " . $targetEmail . "\n";
} catch (\Throwable $e) {
    echo "\n❌ GAGAL: " . $e->getMessage() . "\n";
}
