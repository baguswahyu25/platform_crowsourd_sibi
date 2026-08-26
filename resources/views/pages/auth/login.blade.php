<x-guest-layout title="Masuk - SIBI Dataset Platform">
    <!-- Cloudflare Turnstile Script -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-slate-50">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xl">
            <div class="text-center mb-6">
                <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 mb-4">
                    <img src="{{ asset('storage/logo.jpg') }}" alt="Logo" class="h-12 w-auto rounded-xl shadow-xs object-cover"/>
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
                <p class="text-xs text-slate-500 mt-1">Masuk ke akun SIBI Dataset Platform Anda</p>
            </div>

            @if(session('status'))
                <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
                    <span class="material-symbols-outlined text-base text-emerald-600">check_circle</span>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <span class="material-symbols-outlined text-base text-rose-600">error</span>
                        <span>Pesan Kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('auth.login.store') }}" method="POST" class="space-y-5" novalidate>
                @csrf
                <x-input label="Alamat Email" name="email" type="email" placeholder="nama@domain.com" value="{{ old('email') }}" required />
                <x-input label="Kata Sandi" name="password" type="password" placeholder="••••••••" required />

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-2"/>
                        Ingat saya
                    </label>
                    <a href="{{ route('auth.forgot-password') }}" class="font-bold text-blue-600 hover:underline">Lupa Password?</a>
                </div>

                <!-- Cloudflare Turnstile Verification Widget (Always Visible + Callbacks) -->
                <div class="flex flex-col items-center justify-center space-y-2 my-3">
                    <div 
                        class="cf-turnstile" 
                        data-sitekey="{{ config('services.turnstile.site_key') }}"
                        data-appearance="always"
                        data-callback="onTurnstileLoginSuccess"
                        data-expired-callback="onTurnstileLoginExpired"
                        data-error-callback="onTurnstileLoginError">
                    </div>
                    <p id="turnstile-status-text" class="text-[11px] font-semibold text-amber-600 flex items-center gap-1 text-center">
                        <span class="material-symbols-outlined text-xs">shield</span>
                        <span>Selesaikan verifikasi keamanan di atas untuk masuk.</span>
                    </p>
                </div>

                <!-- Submit Button: Initially Disabled until Turnstile passes -->
                <x-button id="login-submit-btn" type="submit" variant="primary" class="w-full text-center min-h-[44px] opacity-50 cursor-not-allowed transition-all duration-200" disabled>
                    Masuk ke Akun
                </x-button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-100 pt-4">
                Belum memiliki akun? <a href="{{ route('auth.register') }}" class="font-bold text-blue-600 hover:underline">Daftar Sekarang</a>
            </div>
        </div>
    </div>

    <!-- Turnstile Frontend Callback Script -->
    <script>
        function onTurnstileLoginSuccess(token) {
            const btn = document.getElementById('login-submit-btn');
            const statusText = document.getElementById('turnstile-status-text');
            if (btn) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.classList.add('cursor-pointer', 'opacity-100', 'shadow-lg');
            }
            if (statusText) {
                statusText.className = 'text-[11px] font-semibold text-emerald-600 flex items-center gap-1 text-center';
                statusText.innerHTML = '<span class="material-symbols-outlined text-xs">check_circle</span><span>Verifikasi keamanan berhasil! Anda dapat masuk sekarang.</span>';
            }
        }

        function onTurnstileLoginExpired() {
            disableLoginButton('Verifikasi keamanan telah kedaluwarsa. Silakan lakukan verifikasi kembali.');
        }

        function onTurnstileLoginError() {
            disableLoginButton('Verifikasi keamanan belum berhasil. Silakan lakukan verifikasi kembali.');
        }

        function disableLoginButton(message) {
            const btn = document.getElementById('login-submit-btn');
            const statusText = document.getElementById('turnstile-status-text');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.classList.remove('cursor-pointer', 'opacity-100', 'shadow-lg');
            }
            if (statusText) {
                statusText.className = 'text-[11px] font-semibold text-amber-600 flex items-center gap-1 text-center';
                statusText.innerHTML = '<span class="material-symbols-outlined text-xs">warning</span><span>' + message + '</span>';
            }
        }
    </script>
</x-guest-layout>
