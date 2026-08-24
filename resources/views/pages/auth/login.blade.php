<x-guest-layout title="Masuk - SIBI Dataset Platform">
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 bg-slate-50">
        <div class="w-full max-w-md bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xl">
            <div class="text-center mb-8">
                <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 mb-4">
                    <img src="{{ asset('storage/logo.jpg') }}" alt="Logo" class="h-12 w-auto rounded-xl shadow-xs object-cover"/>
                </a>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Selamat Datang Kembali</h1>
                <p class="text-xs text-slate-500 mt-1">Masuk ke akun SIBI Dataset Platform Anda</p>
            </div>

            <form action="{{ route('auth.login.store') }}" method="POST" class="space-y-5">
                @csrf
                <x-input label="Alamat Email" name="email" type="email" placeholder="nama@domain.com" required />
                <x-input label="Kata Sandi" name="password" type="password" placeholder="••••••••" required />

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-2"/>
                        Ingat saya
                    </label>
                    <a href="{{ route('auth.forgot-password') }}" class="font-bold text-blue-600 hover:underline">Lupa Password?</a>
                </div>

                <x-button type="submit" variant="primary" class="w-full text-center">Masuk ke Akun</x-button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-500 border-t border-slate-100 pt-4">
                Belum memiliki akun? <a href="{{ route('auth.register') }}" class="font-bold text-blue-600 hover:underline">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</x-guest-layout>
