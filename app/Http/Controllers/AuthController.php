<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Verifikasi token Cloudflare Turnstile ke API siteverify resmi Cloudflare.
     */
    protected function verifyTurnstile(Request $request): bool
    {
        $token = $request->input('cf-turnstile-response');

        if (empty($token)) {
            return false;
        }

        try {
            $secret = config('services.turnstile.secret_key');

            if (empty($secret)) {
                Log::warning('Turnstile secret_key belum dikonfigurasi pada config/services.php atau .env');
                return false;
            }

            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $request->ip(),
            ]);

            return $response->successful() && $response->json('success') === true;
        } catch (\Throwable $e) {
            Log::error('Cloudflare Turnstile siteverify verification exception: ' . $e->getMessage());
            return false;
        }
    }

    public function showLogin(): View
    {
        return view('pages.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        // Key unik pembatasan percobaan login (Kombinasi Email + IP Address)
        $throttleKey = Str::transliterate(Str::lower($request->input('email', ''))).'|'.$request->ip();

        // 1. Periksa Rate Limit Login (Maksimal 5 percobaan gagal dalam 1 menit)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return redirect()->back()->withInput()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Silakan coba kembali dalam 1 menit.',
            ]);
        }

        // 2. Validasi Backend Cloudflare Turnstile untuk Login
        if (!$this->verifyTurnstile($request)) {
            return redirect()->back()->withInput()->withErrors([
                'turnstile' => 'Silakan selesaikan verifikasi keamanan terlebih dahulu.',
            ]);
        }

        // 3. Eksekusi Autentikasi Login
        try {
            $this->authService->login($request->email, $request->password);
        } catch (ValidationException $e) {
            // Catat 1 kali percobaan login yang gagal pada RateLimiter
            RateLimiter::hit($throttleKey, 60);
            throw $e;
        }

        // 4. Jika login berhasil, reset counter percobaan gagal
        RateLimiter::clear($throttleKey);

        $user = auth()->user();
        
        // Jika email belum diverifikasi, logout dan minta verifikasi OTP terlebih dahulu
        if (!$user->email_verified_at) {
            $userId = $user->id;
            $userEmail = $user->email;

            $this->authService->logout();

            session([
                'verify_user_id' => $userId,
                'verify_email' => $userEmail,
            ]);

            return redirect()->route('auth.verify-email')->withErrors([
                'otp' => 'Email Anda belum diverifikasi. Silakan masukkan kode OTP yang telah dikirimkan ke email Anda.',
            ]);
        }

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
        // Key unik pembatasan pendaftaran (Berdasarkan IP Address)
        $regThrottleKey = 'register_rate_limit|' . $request->ip();

        // 1. Periksa Rate Limit Registrasi (Maksimal 3 request registrasi dalam 1 menit)
        if (RateLimiter::tooManyAttempts($regThrottleKey, 3)) {
            return redirect()->back()->withInput()->withErrors([
                'email' => 'Terlalu banyak percobaan pendaftaran. Silakan coba kembali dalam 1 menit.',
            ]);
        }

        // 2. Validasi Backend Cloudflare Turnstile untuk Registrasi
        if (!$this->verifyTurnstile($request)) {
            return redirect()->back()->withInput()->withErrors([
                'turnstile' => 'Verifikasi keamanan gagal. Silakan lakukan verifikasi ulang.',
            ]);
        }

        // 3. Catat percobaan request pendaftaran pada RateLimiter
        RateLimiter::hit($regThrottleKey, 60);

        $validated = $request->validated();
        $validated['role'] = 'contributor'; // Pendaftaran publik khusus Kontributor

        $user = $this->authService->register($validated);

        // Setelah registrasi, alihkan ke halaman Verifikasi Email OTP
        return redirect()->route('auth.verify-email')->with('status', 'Registrasi berhasil! Kode OTP 6-digit telah dikirimkan ke email Anda.');
    }

    public function showVerifyEmail(): View|RedirectResponse
    {
        $userId = session('verify_user_id') ?? auth()->id();
        
        if (!$userId) {
            return redirect()->route('auth.login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('auth.login');
        }
        
        // Jika email sudah diverifikasi, minta pengguna untuk login terlebih dahulu
        if ($user->email_verified_at) {
            return redirect()->route('auth.login')->with('status', 'Email Anda sudah terverifikasi. Silakan masuk (login) menggunakan email dan kata sandi Anda.');
        }

        return view('pages.auth.verify_email', compact('user'));
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $userId = session('verify_user_id') ?? auth()->id();
        
        if (!$userId) {
            return redirect()->route('auth.login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.required' => 'Kode OTP 6-digit wajib diisi.',
            'otp.size' => 'Kode OTP harus terdiri dari 6 digit angka.',
        ]);

        $success = $this->authService->verifyOtp($user, $request->input('otp'));

        if ($success) {
            // Setelah verifikasi OTP berhasil, alihkan ke halaman Login
            return redirect()->route('auth.login')->with('status', 'Verifikasi email berhasil! Silakan masuk (login) terlebih dahulu menggunakan email dan kata sandi Anda.');
        }

        return redirect()->back()->withErrors([
            'otp' => 'Kode OTP tidak valid atau telah kedaluwarsa. Silakan periksa kembali.',
        ]);
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $userId = session('verify_user_id') ?? auth()->id();
        
        if (!$userId) {
            return redirect()->route('auth.login');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('auth.login');
        }

        $this->authService->resendOtp($user);

        return redirect()->back()->with('status', 'Kode OTP baru telah dikirimkan ke email Anda.');
    }

    public function showForgotPassword(): View
    {
        return view('pages.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors([
                'email' => 'Alamat email ini tidak terdaftar dalam sistem kami.',
            ]);
        }

        // Generasi token unik 64 karakter
        $rawToken = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($rawToken),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('auth.reset-password', [
            'token' => $rawToken,
            'email' => $user->email,
        ]);

        // Pengiriman Email Reset Password Asli ke Gmail User
        try {
            Mail::to($user->email)->send(new ResetPasswordMail($resetUrl, $user->name));
            Log::info("Email Reset Password berhasil dikirimkan ke {$user->email}");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email reset password ke {$user->email}: " . $e->getMessage());
        }

        return redirect()->back()->with('status', "Link reset password telah dikirimkan ke inbox email Anda ({$user->email}). Silakan periksa email Anda.");
    }

    public function showResetPassword(Request $request, string $token): View
    {
        $email = $request->query('email', '');
        return view('pages.auth.reset_password', compact('token', 'email'));
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/', // Minimal 1 huruf besar
                'regex:/[0-9]/', // Minimal 1 angka
                'regex:/[!@#$%^&*(),.?":{}|<>_]/', // Minimal 1 simbol unik
                'confirmed'
            ],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.exists' => 'Alamat email tidak terdaftar dalam sistem kami.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.regex' => 'Kata sandi baru harus kombinasi: minimal 8 karakter, mengandung minimal 1 huruf besar (A-Z), 1 angka (0-9), dan 1 simbol unik (!@#$%^&* dll).',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok dengan kata sandi yang dimasukkan.',
        ]);

        // Verifikasi Token
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return redirect()->back()->withErrors([
                'email' => 'Token reset password tidak valid atau telah kedaluwarsa. Silakan minta link reset password baru.',
            ]);
        }

        // Cek jika token sudah lebih dari 60 menit
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('auth.forgot-password')->withErrors([
                'email' => 'Link reset password telah kedaluwarsa (lebih dari 60 menit). Silakan minta link baru.',
            ]);
        }

        // Perbarui Password Pengguna
        $user = User::where('email', $request->email)->first();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        // Hapus token yang sudah terpakai
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('auth.login')->with('status', '✓ Kata sandi Anda berhasil diperbarui! Silakan masuk (login) menggunakan kata sandi baru Anda.');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('landing');
    }
}
