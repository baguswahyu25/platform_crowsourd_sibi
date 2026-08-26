<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Verifikasi Email - SIBI Dataset Platform</title>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Tailwind Configuration -->
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "secondary-fixed-dim": "#adc6ff",
                        "background": "#faf8ff",
                        "surface-tint": "#0053db",
                        "on-tertiary-container": "#ffede6",
                        "secondary-container": "#2170e4",
                        "tertiary": "#943700",
                        "surface-container-high": "#e7e7f3",
                        "primary-fixed-dim": "#b4c5ff",
                        "on-secondary-container": "#fefcff",
                        "on-secondary-fixed-variant": "#004395",
                        "secondary-fixed": "#d8e2ff",
                        "on-surface-variant": "#434655",
                        "outline-variant": "#c3c6d7",
                        "on-tertiary-fixed-variant": "#7d2d00",
                        "inverse-surface": "#2e3039",
                        "primary-fixed": "#dbe1ff",
                        "on-background": "#191b23",
                        "on-primary-fixed-variant": "#003ea8",
                        "surface-variant": "#e1e2ed",
                        "on-tertiary-fixed": "#360f00",
                        "surface-dim": "#d9d9e5",
                        "inverse-on-surface": "#f0f0fb",
                        "on-primary-fixed": "#00174b",
                        "on-error-container": "#93000a",
                        "surface-container-highest": "#e1e2ed",
                        "on-primary": "#ffffff",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f3f3fe",
                        "on-error": "#ffffff",
                        "surface-container": "#ededf9",
                        "tertiary-container": "#bc4800",
                        "on-surface": "#191b23",
                        "on-secondary-fixed": "#001a42",
                        "primary": "#004ac6",
                        "tertiary-fixed": "#ffdbcd",
                        "tertiary-fixed-dim": "#ffb596",
                        "surface-bright": "#faf8ff",
                        "surface": "#faf8ff",
                        "surface-container-lowest": "#ffffff",
                        "primary-container": "#2563eb",
                        "inverse-primary": "#b4c5ff",
                        "secondary": "#0058be",
                        "on-primary-container": "#eeefff",
                        "outline": "#737686",
                        "error-container": "#ffdad6",
                        "on-secondary": "#ffffff",
                        "on-tertiary": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "base": "8px",
                        "container-max": "1280px",
                        "gutter": "24px",
                        "md": "16px",
                        "xs": "4px",
                        "sm": "8px",
                        "3xl": "64px",
                        "2xl": "48px",
                        "lg": "24px",
                        "xl": "32px"
                    },
                    "fontFamily": {
                        "body-lg": ["Inter"],
                        "title-lg": ["Inter"],
                        "display-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "body-md": ["Inter"],
                        "label-md": ["Inter"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "title-lg": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "500" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-md": ["14px", { "lineHeight": "20px", "fontWeight": "500" }]
                    },
                    "boxShadow": {
                        'level-1': '0 0 0 1px #E2E8F0',
                        'level-2': '0px 4px 6px -1px rgba(0, 0, 0, 0.05), 0px 2px 4px -2px rgba(0, 0, 0, 0.05)',
                        'level-3': '0px 20px 25px -5px rgba(0, 0, 0, 0.1), 0px 8px 10px -6px rgba(0, 0, 0, 0.1)',
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24
        }
        
        .otp-input::-webkit-outer-spin-button,
        .otp-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        .otp-input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex flex-col font-body-md text-on-surface">

<!-- Main Content Canvas -->
<main class="flex-grow flex items-center justify-center p-md">
    <!-- Verification Card -->
    <div class="bg-surface-container-lowest rounded-[20px] shadow-level-1 w-full max-w-md p-xl flex flex-col items-center hover:shadow-level-2 transition-shadow duration-300">
        <!-- Brand / Icon -->
        <div class="h-16 w-16 bg-primary-container bg-opacity-10 rounded-full flex items-center justify-center mb-lg">
            <span class="material-symbols-outlined text-primary-container" style="font-size: 32px; font-variation-settings: 'FILL' 1;">
                mark_email_read
            </span>
        </div>

        <!-- Headers -->
        <div class="text-center mb-xl w-full">
            <h1 class="font-headline-md text-headline-md text-on-background mb-sm">Verifikasi Email</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Kami telah mengirimkan kode OTP ke email <span class="font-label-md text-label-md text-on-surface font-semibold">{{ $user->email ?? session('verify_email', 'anda@perusahaan.com') }}</span>. Silakan masukkan kode tersebut di bawah ini.
            </p>
        </div>

        <!-- Session Status Alert -->
        @if(session('status'))
            <div class="w-full mb-lg p-md rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-label-md text-center font-medium">
                {{ session('status') }}
            </div>
        @endif

        <!-- Error Alerts -->
        @if($errors->any())
            <div class="w-full mb-lg p-md rounded-xl bg-error-container text-on-error-container text-label-md">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- OTP Form -->
        <form action="{{ route('auth.verify-otp') }}" method="POST" class="w-full flex flex-col items-center" id="otp-form">
            @csrf
            <input type="hidden" name="otp" id="otp-hidden-input" required>

            <!-- OTP Inputs (6 digits) -->
            <div class="flex gap-xs sm:gap-sm mb-lg justify-center w-full" id="otp-container">
                <input autofocus="" class="otp-input w-12 h-14 text-center font-title-lg text-title-lg bg-surface-container-lowest border border-[#E2E8F0] rounded-[12px] focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:ring-offset-2 transition-all" maxlength="1" type="text" inputmode="numeric"/>
                <input class="otp-input w-12 h-14 text-center font-title-lg text-title-lg bg-surface-container-lowest border border-[#E2E8F0] rounded-[12px] focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:ring-offset-2 transition-all" maxlength="1" type="text" inputmode="numeric"/>
                <input class="otp-input w-12 h-14 text-center font-title-lg text-title-lg bg-surface-container-lowest border border-[#E2E8F0] rounded-[12px] focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:ring-offset-2 transition-all" maxlength="1" type="text" inputmode="numeric"/>
                <input class="otp-input w-12 h-14 text-center font-title-lg text-title-lg bg-surface-container-lowest border border-[#E2E8F0] rounded-[12px] focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:ring-offset-2 transition-all" maxlength="1" type="text" inputmode="numeric"/>
                <input class="otp-input w-12 h-14 text-center font-title-lg text-title-lg bg-surface-container-lowest border border-[#E2E8F0] rounded-[12px] focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:ring-offset-2 transition-all" maxlength="1" type="text" inputmode="numeric"/>
                <input class="otp-input w-12 h-14 text-center font-title-lg text-title-lg bg-surface-container-lowest border border-[#E2E8F0] rounded-[12px] focus:outline-none focus:ring-2 focus:ring-[#3B82F6] focus:ring-offset-2 transition-all" maxlength="1" type="text" inputmode="numeric"/>
            </div>

            <!-- Primary Action -->
            <button class="w-full h-[48px] bg-[#2563EB] text-[#FFFFFF] font-label-md text-label-md rounded-[14px] shadow-[inset_0_1px_0_rgba(255,255,255,0.2)] hover:bg-opacity-90 transition-colors mb-lg flex items-center justify-center gap-xs cursor-pointer min-h-[44px]" type="submit" id="verify-btn">
                Verifikasi
            </button>
        </form>

        <!-- Resend & Timer -->
        <div class="text-center w-full">
            <p class="font-body-md text-body-md text-on-surface-variant mb-xs">
                Kirim ulang kode dalam <span class="font-label-md text-label-md text-primary-container font-semibold" id="timer">00:55</span>
            </p>
            <form action="{{ route('auth.resend-otp') }}" method="POST" id="resend-form" class="inline">
                @csrf
                <button class="font-label-md text-label-md text-on-surface-variant transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" disabled="" id="resend-btn" type="submit">
                    Belum menerima kode? <span class="underline">Kirim ulang</span>
                </button>
            </form>
        </div>
    </div>
</main>

<!-- Footer Component -->
<footer class="bg-surface-container-lowest dark:bg-on-background border-t border-outline-variant w-full py-xl px-lg flex flex-col md:flex-row justify-between items-center max-w-container-max mx-auto mt-auto">
    <div class="font-title-lg text-title-lg font-bold text-primary mb-md md:mb-0">
        SIBI Platform
    </div>
    <div class="flex gap-lg font-label-sm text-label-sm text-on-surface-variant">
        <a class="hover:text-secondary transition-colors opacity-80 hover:opacity-100" href="#">Privacy Policy</a>
        <a class="hover:text-secondary transition-colors opacity-80 hover:opacity-100" href="#">Terms of Service</a>
        <a class="hover:text-secondary transition-colors opacity-80 hover:opacity-100" href="#">Contact Support</a>
    </div>
    <div class="font-label-sm text-label-sm text-on-surface-variant mt-md md:mt-0">
        © {{ date('Y') }} SIBI Dataset Platform. All rights reserved.
    </div>
</footer>

<script>
    // OTP Input Logic
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenInput = document.getElementById('otp-hidden-input');
    const otpForm = document.getElementById('otp-form');

    function syncOtp() {
        let combined = '';
        inputs.forEach(input => {
            combined += input.value;
        });
        hiddenInput.value = combined;
        
        // Auto-submit when all 6 digits are typed
        if (combined.length === 6) {
            otpForm.submit();
        }
    }
    
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Ensure only numbers are entered
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            syncOtp();
            
            if (e.target.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
        
        // Handle paste
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, inputs.length);
            
            for(let i = 0; i < pastedData.length; i++) {
                if(inputs[i]) {
                    inputs[i].value = pastedData[i];
                    if(i < inputs.length - 1) {
                        inputs[i+1].focus();
                    } else {
                        inputs[i].focus();
                    }
                }
            }
            syncOtp();
        });
    });

    // Timer Logic
    let timeLeft = 55;
    const timerDisplay = document.getElementById('timer');
    const resendBtn = document.getElementById('resend-btn');

    const countdown = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerDisplay.textContent = "00:00";
            resendBtn.disabled = false;
            resendBtn.classList.remove('text-on-surface-variant');
            resendBtn.classList.add('text-primary-container');
        } else {
            let seconds = timeLeft % 60;
            let minutes = Math.floor(timeLeft / 60);
            
            // Format with leading zeros
            let formattedTime = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            timerDisplay.textContent = formattedTime;
            timeLeft -= 1;
        }
    }, 1000);
</script>

</body>
</html>
