<x-guest-layout title="Kata Sandi Baru - SIBI Dataset Platform">
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-slate-50">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xl">
            <div class="text-center mb-6">
                <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 mb-3">
                    <img src="{{ asset('storage/logo.jpg') }}" alt="Logo" class="h-12 w-auto rounded-xl shadow-xs object-cover"/>
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Buat Kata Sandi Baru</h1>
                <p class="text-xs text-slate-500 mt-1">Silakan buat kata sandi baru untuk akun email <strong class="text-slate-800">{{ $email }}</strong>.</p>
            </div>

            @if($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold space-y-1">
                    <div class="flex items-center gap-2 font-bold text-rose-900">
                        <span class="material-symbols-outlined text-base text-rose-600">error</span>
                        <span>Periksa Kembali Isian Anda:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('auth.reset-password.store') }}" method="POST" class="space-y-5" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Email</label>
                    <input type="email" class="w-full h-12 px-4 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-semibold text-sm cursor-not-allowed" value="{{ $email }}" disabled readonly />
                </div>

                <div class="space-y-1">
                    <x-input label="Kata Sandi Baru" name="password" type="password" placeholder="••••••••" required />
                </div>

                <div class="space-y-1">
                    <x-input label="Konfirmasi Kata Sandi Baru" name="password_confirmation" type="password" placeholder="••••••••" required />
                </div>

                <!-- Password Format Helper Note -->
                <div class="bg-blue-50/80 border border-blue-100 p-3 rounded-xl text-[11px] text-blue-900 space-y-1">
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

                <x-button type="submit" variant="primary" class="w-full text-center min-h-[44px]">Perbarui Kata Sandi</x-button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-100 pt-4">
                <a href="{{ route('auth.login') }}" class="font-bold text-blue-600 hover:underline">Batal & Kembali ke Halaman Masuk</a>
            </div>
        </div>
    </div>
</x-guest-layout>
