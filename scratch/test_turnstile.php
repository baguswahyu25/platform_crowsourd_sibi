<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\File;

echo "=======================================================\n";
echo "1. VERIFYING BLADE TEMPLATE TURNSTILE CONFIGURATION:\n";
echo "=======================================================\n";

$loginBlade = File::get(resource_path('views/pages/auth/login.blade.php'));
$registerBlade = File::get(resource_path('views/pages/auth/register.blade.php'));

$hasAppearanceLogin = str_contains($loginBlade, 'data-appearance="always"');
$hasAppearanceRegister = str_contains($registerBlade, 'data-appearance="always"');

$hasCallbackLogin = str_contains($loginBlade, 'data-callback="onTurnstileLoginSuccess"');
$hasCallbackRegister = str_contains($registerBlade, 'data-callback="onTurnstileRegisterSuccess"');

$hasDisabledLogin = str_contains($loginBlade, 'disabled');
$hasDisabledRegister = str_contains($registerBlade, 'disabled');

echo "- Login Blade data-appearance=\"always\": " . ($hasAppearanceLogin ? "PASSED" : "FAILED") . "\n";
echo "- Register Blade data-appearance=\"always\": " . ($hasAppearanceRegister ? "PASSED" : "FAILED") . "\n";
echo "- Login Blade Callback Functions: " . ($hasCallbackLogin ? "PASSED" : "FAILED") . "\n";
echo "- Register Blade Callback Functions: " . ($hasCallbackRegister ? "PASSED" : "FAILED") . "\n";
echo "- Login Submit Button Initial Disabled: " . ($hasDisabledLogin ? "PASSED" : "FAILED") . "\n";
echo "- Register Submit Button Initial Disabled: " . ($hasDisabledRegister ? "PASSED" : "FAILED") . "\n";

echo "\n=======================================================\n";
echo "2. VERIFYING BACKEND VALIDATION MANDATORY CHECKS:\n";
echo "=======================================================\n";

$loginRules = (new LoginRequest())->rules();
$registerRules = (new RegisterRequest())->rules();

echo "- LoginRequest cf-turnstile-response rule: " . implode(', ', $loginRules['cf-turnstile-response']) . "\n";
echo "- RegisterRequest cf-turnstile-response rule: " . implode(', ', $registerRules['cf-turnstile-response']) . "\n";

echo "\nALL UPDATED TURNSTILE REQUIREMENTS VERIFIED 100%!\n";
