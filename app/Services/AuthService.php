<?php

namespace App\Services;

use App\Mail\OtpVerificationMail;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            'email' => ['Alamat email atau kata sandi yang Anda masukkan salah / tidak terdaftar.'],
        ]);
    }

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        
        // Generasi Kode OTP 6 Digit & Waktu Kedaluwarsa (10 Menit)
        $otpCode = sprintf("%06d", random_int(100000, 999999));
        $data['otp_code'] = $otpCode;
        $data['otp_expires_at'] = now()->addMinutes(10);

        $user = $this->userRepository->create($data);
        
        // Simpan info sesi verifikasi (tanpa login otomatis)
        session([
            'verify_user_id' => $user->id,
            'verify_email' => $user->email,
        ]);

        // Pengiriman Email Asli ke Gmail User
        try {
            Mail::to($user->email)->send(new OtpVerificationMail($otpCode, $user->name));
            Log::info("Email OTP berhasil dikirimkan ke {$user->email}");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email OTP ke {$user->email}: " . $e->getMessage());
        }

        return $user;
    }

    public function verifyOtp(User $user, string $otp): bool
    {
        $submittedOtp = trim($otp);

        if ($user->otp_code && $user->otp_code === $submittedOtp) {
            if ($user->otp_expires_at && $user->otp_expires_at->isFuture()) {
                $user->forceFill([
                    'email_verified_at' => now(),
                    'otp_code' => null,
                    'otp_expires_at' => null,
                ])->save();

                // Pastikan user tidak terautentikasi dan hapus sesi verifikasi
                Auth::logout();
                session()->forget(['verify_user_id', 'verify_email']);

                return true;
            }
        }

        return false;
    }

    public function resendOtp(User $user): string
    {
        $otpCode = sprintf("%06d", random_int(100000, 999999));

        $user->forceFill([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ])->save();

        // Pengiriman Ulang Email OTP Asli ke Gmail User
        try {
            Mail::to($user->email)->send(new OtpVerificationMail($otpCode, $user->name));
            Log::info("Email OTP baru berhasil dikirimkan ulang ke {$user->email}");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim ulang email OTP ke {$user->email}: " . $e->getMessage());
        }

        return $otpCode;
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
