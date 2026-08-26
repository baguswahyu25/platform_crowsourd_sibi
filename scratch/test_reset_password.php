<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;

$testEmail = 'mbagusws25@gmail.com';
$user = User::where('email', $testEmail)->first();

if (!$user) {
    echo "Creating test user: " . $testEmail . "\n";
    $user = User::create([
        'name' => 'Bagus',
        'email' => $testEmail,
        'password' => Hash::make('OldPassword123!'),
        'role' => 'contributor',
        'email_verified_at' => now(),
    ]);
}

echo "1. User requesting password reset for: " . $user->email . "\n";

$rawToken = Str::random(64);
DB::table('password_reset_tokens')->updateOrInsert(
    ['email' => $user->email],
    [
        'token' => Hash::make($rawToken),
        'created_at' => now(),
    ]
);

$resetUrl = route('auth.reset-password', ['token' => $rawToken, 'email' => $user->email]);
echo "Generated Reset URL: " . $resetUrl . "\n";

echo "\n2. Sending Reset Password Mail via Gmail SMTP...\n";
try {
    Mail::to($user->email)->send(new ResetPasswordMail($resetUrl, $user->name));
    echo "✅ SUCCESS: Reset Password email sent to " . $user->email . "\n";
} catch (\Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n3. Testing Password Reset Token Verification & Password Update...\n";
$record = DB::table('password_reset_tokens')->where('email', $user->email)->first();
$isValidToken = Hash::check($rawToken, $record->token);
echo "Is Token Valid? " . ($isValidToken ? 'YES (Valid!)' : 'NO') . "\n";

if ($isValidToken) {
    $newPass = 'NewSecurePassword123!';
    $user->forceFill(['password' => Hash::make($newPass)])->save();
    DB::table('password_reset_tokens')->where('email', $user->email)->delete();
    
    echo "Password Updated! Verifying new password match...\n";
    $passMatch = Hash::check($newPass, $user->password);
    echo "Does new password match Hash? " . ($passMatch ? 'YES (SUCCESS - Password updated automatically!)' : 'NO') . "\n";
}
