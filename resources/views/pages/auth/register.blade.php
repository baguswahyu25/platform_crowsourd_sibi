<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registrasi Kontributor - SIBI Dataset Platform</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Cloudflare Turnstile Script -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "surface-container-low": "#f3f3fe",
                    "inverse-on-surface": "#f0f0fb",
                    "secondary": "#0058be",
                    "tertiary-fixed": "#ffdbcd",
                    "background": "#faf8ff",
                    "on-primary": "#ffffff",
                    "surface-variant": "#e1e2ed",
                    "surface-dim": "#d9d9e5",
                    "on-error": "#ffffff",
                    "error": "#ba1a1a",
                    "inverse-primary": "#b4c5ff",
                    "on-secondary-container": "#fefcff",
                    "on-tertiary-fixed-variant": "#7d2d00",
                    "on-secondary-fixed-variant": "#004395",
                    "surface-container": "#ededf9",
                    "tertiary-fixed-dim": "#ffb596",
                    "on-tertiary-container": "#ffede6",
                    "secondary-fixed": "#d8e2ff",
                    "error-container": "#ffdad6",
                    "inverse-surface": "#2e3039",
                    "primary-container": "#2563eb",
                    "primary-fixed": "#dbe1ff",
                    "on-secondary-fixed": "#001a42",
                    "surface-container-lowest": "#ffffff",
                    "outline": "#737686",
                    "secondary-fixed-dim": "#adc6ff",
                    "on-primary-fixed": "#00174b",
                    "surface-tint": "#0053db",
                    "primary": "#004ac6",
                    "on-tertiary-fixed": "#360f00",
                    "on-surface": "#191b23",
                    "secondary-container": "#2170e4",
                    "on-error-container": "#93000a",
                    "on-primary-container": "#eeefff",
                    "surface-container-highest": "#e1e2ed",
                    "surface-bright": "#faf8ff",
                    "outline-variant": "#c3c6d7",
                    "on-tertiary": "#ffffff",
                    "tertiary-container": "#bc4800",
                    "on-background": "#191b23",
                    "surface-container-high": "#e7e7f3",
                    "on-surface-variant": "#434655",
                    "on-primary-fixed-variant": "#003ea8",
                    "surface": "#faf8ff",
                    "primary-fixed-dim": "#b4c5ff",
                    "on-secondary": "#ffffff",
                    "tertiary": "#943700"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "gutter": "24px",
                    "md": "16px",
                    "base": "8px",
                    "xs": "4px",
                    "xl": "32px",
                    "lg": "24px",
                    "container-max": "1280px",
                    "3xl": "64px",
                    "sm": "8px",
                    "2xl": "48px"
            },
            "fontFamily": {
                    "label-sm": ["Inter"],
                    "headline-lg": ["Inter"],
                    "body-lg": ["Inter"],
                    "display-lg": ["Inter"],
                    "body-md": ["Inter"],
                    "label-md": ["Inter"],
                    "headline-lg-mobile": ["Inter"],
                    "title-lg": ["Inter"],
                    "headline-md": ["Inter"]
            },
            "fontSize": {
                    "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "500"}],
                    "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                    "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                    "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "label-md": ["14px", {"lineHeight": "20px", "fontWeight": "500"}],
                    "headline-lg-mobile": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                    "title-lg": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "headline-md": ["24px", {"lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600"}]
            }
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .form-input-error {
            border-color: #ba1a1a !important;
            box-shadow: 0 0 0 1px #ba1a1a !important;
        }
    </style>
</head>
<body class="bg-background font-body-md text-on-background min-h-screen flex flex-col justify-between">

<main class="flex-grow flex items-center justify-center px-gutter py-3xl relative overflow-hidden">
    <!-- Background Ambient Elements -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-40">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-primary/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-secondary/10 rounded-full blur-[120px]"></div>
    </div>

    <!-- Registration Card -->
    <div class="relative z-10 w-full max-w-[520px] bg-surface-container-lowest border border-outline-variant/30 rounded-[20px] shadow-sm p-xl transition-all duration-500" id="register-card">
        <div class="mb-xl text-center">
            <h1 class="font-headline-lg text-headline-lg text-on-surface mb-xs">Registrasi Kontributor</h1>
            <p class="font-body-md text-on-surface-variant">Bergabunglah dalam pengembangan dataset bahasa isyarat Indonesia.</p>
        </div>

        @if(session('error'))
            <div class="mb-md p-md rounded-xl bg-error-container text-on-error-container text-label-md flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">warning</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-md p-md rounded-xl bg-error-container text-on-error-container text-label-md">
                <div class="font-bold mb-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span>Periksa Kembali Isian Anda:</span>
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('auth.register.store') }}" method="POST" class="space-y-lg" id="registration-form" novalidate>
            @csrf

            <!-- Nama Lengkap -->
            <div class="space-y-xs">
                <label class="font-label-md text-label-md text-on-surface-variant block" for="name">Nama Lengkap</label>
                <div class="relative">
                    <input class="w-full h-12 px-md bg-surface-container-lowest border {{ $errors->has('name') ? 'border-error' : 'border-outline-variant' }} rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required type="text"/>
                </div>
                @error('name')
                    <p class="text-xs text-error font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="space-y-xs">
                <label class="font-label-md text-label-md text-on-surface-variant block" for="email">Email</label>
                <div class="relative">
                    <input class="w-full h-12 px-md bg-surface-container-lowest border {{ $errors->has('email') ? 'border-error' : 'border-outline-variant' }} rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required type="email"/>
                </div>
                @error('email')
                    <p class="text-xs text-error font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Row -->
            <div class="space-y-xs">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                    <!-- Password -->
                    <div class="space-y-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant block" for="password">Password</label>
                        <div class="relative">
                            <input class="w-full h-12 px-md bg-surface-container-lowest border {{ $errors->has('password') ? 'border-error' : 'border-outline-variant' }} rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="password" name="password" placeholder="••••••••" required type="password"/>
                        </div>
                    </div>
                    <!-- Konfirmasi Password -->
                    <div class="space-y-xs">
                        <label class="font-label-md text-label-md text-on-surface-variant block" for="password_confirmation">Konfirmasi Password</label>
                        <div class="relative">
                            <input class="w-full h-12 px-md bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required type="password"/>
                        </div>
                    </div>
                </div>

                <!-- Password Format Helper Note -->
                <div class="bg-blue-50/80 border border-blue-100 p-3 rounded-xl text-[11px] text-blue-900 mt-2 space-y-1">
                    <p class="font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-xs text-blue-600">info</span>
                        Ketentuan Kata Sandi:
                    </p>
                    <ul class="list-disc list-inside text-blue-800 space-y-0.5 pl-1">
                        <li>Minimal <strong>8 karakter</strong></li>
                        <li>Mengandung minimal <strong>1 huruf besar (A-Z)</strong></li>
                        <li>Mengandung minimal <strong>1 angka (0-9)</strong></li>
                        <li>Mengandung minimal <strong>1 simbol unik</strong> (!@#$%^&* dll)</li>
                    </ul>
                </div>

                @error('password')
                    <p class="text-xs text-error font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Checkbox -->
            <div class="flex items-start gap-sm pt-xs">
                <input class="mt-1 w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary cursor-pointer" id="terms" required type="checkbox"/>
                <label class="font-body-md text-body-md text-on-surface-variant cursor-pointer" for="terms">
                    Saya menyetujui <a class="text-primary hover:underline" href="#">syarat dan ketentuan</a>.
                </label>
            </div>

            <!-- Cloudflare Turnstile Verification Widget (Always Visible + Callbacks) -->
            <div class="flex flex-col items-center justify-center space-y-2 my-md">
                <div 
                    class="cf-turnstile" 
                    data-sitekey="{{ config('services.turnstile.site_key') }}"
                    data-appearance="always"
                    data-callback="onTurnstileRegisterSuccess"
                    data-expired-callback="onTurnstileRegisterExpired"
                    data-error-callback="onTurnstileRegisterError">
                </div>
                <p id="turnstile-register-status" class="text-[11px] font-medium text-amber-700 flex items-center gap-1 text-center">
                    <span class="material-symbols-outlined text-xs text-amber-600">shield</span>
                    <span>Selesaikan verifikasi keamanan di atas sebelum mendaftar.</span>
                </p>
            </div>

            <!-- Action Submit Button: Initially Disabled until Turnstile passes -->
            <button class="w-full h-12 bg-slate-300 text-on-primary font-label-md text-label-md rounded-xl shadow-sm transition-all flex items-center justify-center gap-sm mt-md min-h-[44px] cursor-not-allowed opacity-60" id="submit-btn" type="submit" disabled>
                <span>Daftar</span>
            </button>
        </form>

        <div class="mt-xl text-center">
            <p class="font-body-md text-body-md text-on-surface-variant">
                Sudah memiliki akun? 
                <a class="text-primary font-semibold hover:underline" href="{{ route('auth.login') }}">Masuk</a>
            </p>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="w-full py-xl bg-surface-container-lowest border-t border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-gutter flex flex-col md:flex-row justify-between items-center gap-md">
        <p class="font-body-md text-body-md text-on-surface-variant text-center md:text-left">
            © {{ date('Y') }} SIBI Dataset Platform. Hak Cipta Dilindungi.
        </p>
        <div class="flex gap-lg">
            <a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface transition-colors" href="#">Kebijakan Privasi</a>
            <a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface transition-colors" href="#">Syarat & Ketentuan</a>
            <a class="font-body-md text-body-md text-on-surface-variant hover:text-on-surface transition-colors" href="#">Kontak Kami</a>
        </div>
    </div>
</footer>

<!-- Turnstile Register Callback Script -->
<script>
    function onTurnstileRegisterSuccess(token) {
        const btn = document.getElementById('submit-btn');
        const statusText = document.getElementById('turnstile-register-status');
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('opacity-60', 'cursor-not-allowed', 'bg-slate-300');
            btn.classList.add('cursor-pointer', 'opacity-100', 'bg-primary', 'hover:opacity-90', 'active:scale-[0.98]');
        }
        if (statusText) {
            statusText.className = 'text-[11px] font-medium text-emerald-700 flex items-center gap-1 text-center';
            statusText.innerHTML = '<span class="material-symbols-outlined text-xs text-emerald-600">check_circle</span><span>Verifikasi keamanan berhasil! Anda dapat mendaftar sekarang.</span>';
        }
    }

    function onTurnstileRegisterExpired() {
        disableRegisterButton('Verifikasi keamanan telah kedaluwarsa. Silakan lakukan verifikasi kembali.');
    }

    function onTurnstileRegisterError() {
        disableRegisterButton('Verifikasi keamanan belum berhasil. Silakan lakukan verifikasi kembali.');
    }

    function disableRegisterButton(message) {
        const btn = document.getElementById('submit-btn');
        const statusText = document.getElementById('turnstile-register-status');
        if (btn) {
            btn.disabled = true;
            btn.classList.add('opacity-60', 'cursor-not-allowed', 'bg-slate-300');
            btn.classList.remove('cursor-pointer', 'opacity-100', 'bg-primary');
        }
        if (statusText) {
            statusText.className = 'text-[11px] font-medium text-amber-700 flex items-center gap-1 text-center';
            statusText.innerHTML = '<span class="material-symbols-outlined text-xs text-amber-600">warning</span><span>' + message + '</span>';
        }
    }
</script>

</body>
</html>
