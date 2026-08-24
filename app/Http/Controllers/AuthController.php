<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function showLogin(): View
    {
        return view('pages.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $this->authService->login($request->email, $request->password);

        $user = auth()->user();
        $role = is_object($user->role) ? $user->role->value : $user->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'validator' => redirect()->route('validator.dashboard'),
            default => redirect()->route('contributor.dashboard'),
        };
    }

    public function showRegister(): View
    {
        return view('pages.auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['role'] = 'contributor'; // Pendaftaran publik khusus Kontributor

        $this->authService->register($validated);

        return redirect()->route('contributor.dashboard');
    }

    public function showForgotPassword(): View
    {
        return view('pages.auth.forgot-password');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('landing');
    }
}
