<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\AuthService;
use App\Models\User;

$authService = app(AuthService::class);

$testEmail = 'flow_test_' . time() . '@example.com';
$password = 'Password123!';

echo "1. User Registering with email: " . $testEmail . "\n";
$user = $authService->register([
    'name' => 'Flow Test User',
    'email' => $testEmail,
    'password' => $password,
    'role' => 'contributor',
]);

echo "Created User ID: " . $user->id . "\n";
echo "OTP Code: " . $user->otp_code . "\n";
echo "Is Logged In? " . (auth()->check() ? 'YES' : 'NO (Correct!)') . "\n";
echo "Session verify_user_id: " . session('verify_user_id') . "\n";

echo "\n2. Verifying OTP Code...\n";
$verified = $authService->verifyOtp($user, $user->otp_code);
echo "OTP Verified Result: " . ($verified ? 'SUCCESS' : 'FAILED') . "\n";

$user->refresh();
echo "Is Email Verified? " . ($user->email_verified_at ? 'YES (' . $user->email_verified_at->toDateTimeString() . ')' : 'NO') . "\n";
echo "Is Logged In After OTP Verification? " . (auth()->check() ? 'YES (FAIL - Should require login!)' : 'NO (SUCCESS - Must Login!)') . "\n";

echo "\n3. User Logging In via Login Page...\n";
$loggedIn = $authService->login($testEmail, $password);
echo "Login Status: " . ($loggedIn ? 'SUCCESS' : 'FAILED') . "\n";
echo "Current Logged In User: " . (auth()->user()->email) . "\n";
echo "User Role: " . (is_object(auth()->user()->role) ? auth()->user()->role->value : auth()->user()->role) . "\n";
