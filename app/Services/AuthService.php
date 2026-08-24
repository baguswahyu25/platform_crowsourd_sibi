<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function login(string $email, string $password): bool
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            session()->regenerate();
            return true;
        }

        throw ValidationException::withMessages([
            'email' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.'],
        ]);
    }

    public function register(array $data): void
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        Auth::login($user);
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
