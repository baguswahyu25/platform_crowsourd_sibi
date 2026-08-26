<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;

echo "1. Testing Register Password Validation Rules:\n";

$passwordsToTest = [
    'simple' => '123456',
    'no_uppercase' => 'password123!',
    'no_number' => 'Password!',
    'no_symbol' => 'Password123',
    'valid_complex' => 'Password123!',
];

$regRequest = new RegisterRequest();
$regRules = $regRequest->rules();
$regMessages = $regRequest->messages();

foreach ($passwordsToTest as $key => $pass) {
    $v = Validator::make([
        'name' => 'Test User',
        'email' => 'unique_' . time() . '_test@example.com',
        'password' => $pass,
        'password_confirmation' => $pass,
    ], $regRules, $regMessages);

    echo "Password '{$pass}': " . ($v->fails() ? "FAIL (Errors: " . implode(' | ', $v->errors()->all()) . ")" : "PASS (Valid!)") . "\n";
}

echo "\n2. Testing Register Duplicate Email Message:\n";
$dupV = Validator::make([
    'name' => 'Duplicate User',
    'email' => 'admin@sibi.id', // Already seeded email
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
], $regRules, $regMessages);
echo "Duplicate email 'admin@sibi.id': " . implode(' | ', $dupV->errors()->get('email')) . "\n";

echo "\n3. Testing Login Empty Fields Messages:\n";
$loginReq = new LoginRequest();
$loginV = Validator::make([], $loginReq->rules(), $loginReq->messages());
echo "Empty Login Errors: " . implode(' | ', $loginV->errors()->all()) . "\n";
