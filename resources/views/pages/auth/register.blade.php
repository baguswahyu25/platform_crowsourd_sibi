<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registrasi Kontributor - SIBI Dataset Platform</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
    <div class="relative z-10 w-full max-w-[480px] bg-surface-container-lowest border border-outline-variant/30 rounded-[20px] shadow-sm p-xl transition-all duration-500" id="register-card">
        <div class="mb-xl text-center">
            <h1 class="font-headline-lg text-headline-lg text-on-surface mb-xs">Registrasi Kontributor</h1>
            <p class="font-body-md text-on-surface-variant">Bergabunglah dalam pengembangan dataset bahasa isyarat Indonesia.</p>
        </div>

        @if($errors->any())
            <div class="mb-md p-md rounded-xl bg-error-container text-on-error-container text-label-md">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('auth.register.store') }}" method="POST" class="space-y-lg" id="registration-form">
            @csrf

            <!-- Nama Lengkap -->
            <div class="space-y-xs">
                <label class="font-label-md text-label-md text-on-surface-variant block" for="name">Nama Lengkap</label>
                <div class="relative">
                    <input class="w-full h-12 px-md bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required type="text"/>
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-xs">
                <label class="font-label-md text-label-md text-on-surface-variant block" for="email">Email</label>
                <div class="relative">
                    <input class="w-full h-12 px-md bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required type="email"/>
                </div>
            </div>

            <!-- Password Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <!-- Password -->
                <div class="space-y-xs">
                    <label class="font-label-md text-label-md text-on-surface-variant block" for="password">Password</label>
                    <div class="relative">
                        <input class="w-full h-12 px-md bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="password" name="password" placeholder="••••••••" required type="password"/>
                    </div>
                </div>
                <!-- Konfirmasi Password -->
                <div class="space-y-xs">
                    <label class="font-label-md text-label-md text-on-surface-variant block" for="password_confirmation">Konfirmasi</label>
                    <div class="relative">
                        <input class="w-full h-12 px-md bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-md text-body-md" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required type="password"/>
                    </div>
                </div>
            </div>

            <!-- Checkbox -->
            <div class="flex items-start gap-sm pt-xs">
                <input class="mt-1 w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary" id="terms" required type="checkbox"/>
                <label class="font-body-md text-body-md text-on-surface-variant cursor-pointer" for="terms">
                    Saya menyetujui <a class="text-primary hover:underline" href="#">syarat dan ketentuan</a>.
                </label>
            </div>

            <!-- Action Button -->
            <button class="w-full h-12 bg-primary text-on-primary font-label-md text-label-md rounded-xl shadow-sm hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-sm mt-md min-h-[44px]" id="submit-btn" type="submit">
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

</body>
</html>
