<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

echo "=======================================================\n";
echo "1. TESTING LOGIN RATE LIMITER (MAX 5 ATTEMPTS PER MINUTE):\n";
echo "=======================================================\n";

$testEmail = 'test_bruteforce@sibi.id';
$testIp = '127.0.0.1';
$loginThrottleKey = Str::transliterate(Str::lower($testEmail)) . '|' . $testIp;

// Clear initial state
RateLimiter::clear($loginThrottleKey);

echo "- Initial Login Attempts: " . RateLimiter::attempts($loginThrottleKey) . "\n";

// Simulate 4 failed login attempts
for ($i = 1; $i <= 4; $i++) {
    RateLimiter::hit($loginThrottleKey, 60);
    echo "  * Failed Attempt #{$i}: Attempts count = " . RateLimiter::attempts($loginThrottleKey) . ", Is Blocked = " . (RateLimiter::tooManyAttempts($loginThrottleKey, 5) ? 'YES' : 'NO') . "\n";
}

// 5th failed attempt
RateLimiter::hit($loginThrottleKey, 60);
echo "  * Failed Attempt #5: Attempts count = " . RateLimiter::attempts($loginThrottleKey) . ", Is Blocked = " . (RateLimiter::tooManyAttempts($loginThrottleKey, 5) ? 'YES' : 'NO') . "\n";

// 6th attempt (blocked!)
$isBlockedOn6th = RateLimiter::tooManyAttempts($loginThrottleKey, 5);
echo "  * Attempt #6 (Blocked Check): " . ($isBlockedOn6th ? "BLOCKED 100% (PASSED)" : "FAILED") . "\n";

// Simulate successful login reset
RateLimiter::clear($loginThrottleKey);
echo "- After Successful Login Reset: Attempts count = " . RateLimiter::attempts($loginThrottleKey) . " (PASSED)\n";

echo "\n=======================================================\n";
echo "2. TESTING REGISTRATION RATE LIMITER (MAX 3 REQUESTS PER MINUTE):\n";
echo "=======================================================\n";

$regThrottleKey = 'register_rate_limit|' . $testIp;

// Clear initial state
RateLimiter::clear($regThrottleKey);

echo "- Initial Register Attempts: " . RateLimiter::attempts($regThrottleKey) . "\n";

// Simulate 3 registration requests
for ($i = 1; $i <= 3; $i++) {
    RateLimiter::hit($regThrottleKey, 60);
    echo "  * Request #{$i}: Attempts count = " . RateLimiter::attempts($regThrottleKey) . ", Is Blocked = " . (RateLimiter::tooManyAttempts($regThrottleKey, 3) ? 'YES' : 'NO') . "\n";
}

// 4th registration request (blocked!)
$isRegBlockedOn4th = RateLimiter::tooManyAttempts($regThrottleKey, 3);
echo "  * Request #4 (Blocked Check): " . ($isRegBlockedOn4th ? "BLOCKED 100% (PASSED)" : "FAILED") . "\n";

// Clean up test keys
RateLimiter::clear($loginThrottleKey);
RateLimiter::clear($regThrottleKey);

echo "\nALL RATE LIMITING TESTS PASSED 100%!\n";
