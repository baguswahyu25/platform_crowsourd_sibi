<x-app-layout title="Profil Saya - SIBI Dataset Platform">
    <div class="max-w-3xl mx-auto space-y-6">
        <x-breadcrumb :items="['Profil Pengguna' => '']" />

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
            <div class="flex items-center space-x-4 border-b border-slate-100 pb-6">
                <div class="h-16 w-16 rounded-full bg-blue-600 text-white font-extrabold text-2xl flex items-center justify-center shadow-md">
                    {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900">{{ auth()->user()->name ?? 'Pengguna Demo' }}</h1>
                    <p class="text-xs text-slate-500">{{ auth()->user()->email ?? 'demo@sibi.id' }}</p>
                    <span class="inline-block mt-2 px-3 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-extrabold uppercase">
                        {{ auth()->user()->role->value ?? 'Kontributor' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                @csrf
                <x-input label="Nama Lengkap" name="name" value="{{ auth()->user()->name ?? 'Pengguna Demo' }}" required />
                <x-input label="Alamat Email (Tetap)" name="email" value="{{ auth()->user()->email ?? 'demo@sibi.id' }}" disabled />

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-input label="Institusi / Komunitas" name="institution" value="{{ auth()->user()->institution ?? 'Universitas Indonesia' }}" />
                    <x-input label="Nomor Telepon/WA" name="phone" value="{{ auth()->user()->phone ?? '081234567890' }}" />
                </div>

                <div class="flex justify-end pt-4">
                    <x-button type="submit" variant="primary" icon="save">Simpan Perubahan Profil</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
