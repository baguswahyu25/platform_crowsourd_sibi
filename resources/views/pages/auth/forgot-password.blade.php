<x-guest-layout title="Lupa Password - SIBI Dataset Platform">
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-slate-50">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xl">
            <div class="text-center mb-6">
                <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 mb-3">
                    <img src="{{ asset('storage/logo.jpg') }}" alt="Logo" class="h-12 w-auto rounded-xl shadow-xs object-cover"/>
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Atur Ulang Kata Sandi</h1>
                <p class="text-xs text-slate-500 mt-1">Masukkan email terdaftar Anda untuk menerima instruksi pemulihan kata sandi.</p>
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

            <form action="{{ route('auth.forgot-password.store') }}" method="POST" class="space-y-4" novalidate>
                @csrf
                <x-input label="Alamat Email" name="email" type="email" placeholder="nama@domain.com" value="{{ old('email') }}" required />
                <x-button type="submit" variant="primary" class="w-full text-center min-h-[44px]">Kirim Link Pemulihan</x-button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-100 pt-4">
                <a href="{{ route('auth.login') }}" class="font-bold text-blue-600 hover:underline">Kembali ke Halaman Masuk</a>
            </div>
        </div>
    </div>
</x-guest-layout>
