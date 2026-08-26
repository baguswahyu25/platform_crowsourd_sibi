<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\AuthService;
use App\Models\User;

$authService = app(AuthService::class);

$testEmail = 'test_otp_' . time() . '@example.com';

echo "1. Registering user with email: " . $testEmail . "\n";
$user = $authService->register([
    'name' => 'Test OTP User',
    'email' => $testEmail,
    'password' => 'Password123!',
    'role' => 'contributor',
]);

echo "Created User ID: " . $user->id . "\n";
echo "OTP Code: " . $user->otp_code . "\n";
echo "OTP Expires At: " . $user->otp_expires_at . "\n";

$generatedOtp = $user->otp_code;

// Test Invalid OTP
$invalidResult = $authService->verifyOtp($user, '000000');
echo "2. Testing Invalid OTP ('000000'): " . ($invalidResult ? 'PASSED (FAIL)' : 'FAILED (SUCCESS - Invalid Rejected)') . "\n";

// Test Valid OTP
$validResult = $authService->verifyOtp($user, $generatedOtp);
echo "3. Testing Valid OTP ('{$generatedOtp}'): " . ($validResult ? 'SUCCESS (Verified!)' : 'FAILED') . "\n";

$user->refresh();
echo "User email_verified_at: " . ($user->email_verified_at ? $user->email_verified_at->toDateTimeString() : 'NULL') . "\n";
echo "User otp_code after verification: " . ($user->otp_code ?? 'NULL') . "\n";

// Test Resend OTP
$newOtp = $authService->resendOtp($user);
echo "4. Testing Resend OTP: " . $newOtp . "\n";
$user->refresh();
echo "User new otp_code: " . $user->otp_code . "\n";
