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

            <form action="#" method="POST" class="space-y-4">
                @csrf
                <x-input label="Alamat Email" name="email" type="email" placeholder="nama@domain.com" required />
                <x-button type="submit" variant="primary" class="w-full text-center">Kirim Link Pemulihan</x-button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-100 pt-4">
                <a href="{{ route('auth.login') }}" class="font-bold text-blue-600 hover:underline">Kembali ke Halaman Masuk</a>
            </div>
        </div>
    </div>
</x-guest-layout>
