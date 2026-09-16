<x-app-layout title="Profil Saya - SIBI Dataset Platform">
    <div class="max-w-3xl mx-auto space-y-6" x-data="{
        photoPreview: null,
        previewFile(event) {
            const file = event.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file foto melebihi 2 MB!');
                    event.target.value = '';
                    this.photoPreview = null;
                    return;
                }
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }">
        <x-breadcrumb :items="['Profil Pengguna' => '']" />

        <!-- Alert Success -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center gap-3 shadow-xs">
                <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Alert Validation Errors -->
        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold space-y-1 shadow-xs">
                <div class="flex items-center gap-2 font-bold text-rose-900">
                    <span class="material-symbols-outlined text-rose-600 text-xl">warning</span>
                    <span>Gagal Memperbarui Profil:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Section Foto Profil -->
                <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 border-b border-slate-100 pb-6">
                    <div class="relative group shrink-0">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" alt="Foto Profil Preview" class="h-24 w-24 rounded-full object-cover shadow-md border-2 border-blue-500"/>
                        </template>
                        <template x-if="!photoPreview">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . ltrim(str_replace(['public/', 'storage/'], '', auth()->user()->avatar), '/')) }}" alt="{{ auth()->user()->name }}" class="h-24 w-24 rounded-full object-cover shadow-md border-2 border-slate-200"/>
                            @else
                                <div class="h-24 w-24 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 text-white font-black text-3xl flex items-center justify-center shadow-md">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                                </div>
                            @endif
                        </template>
                        <label for="avatar-input" class="absolute bottom-0 right-0 p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg cursor-pointer transition">
                            <span class="material-symbols-outlined text-base">photo_camera</span>
                            <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" @change="previewFile($event)">
                        </label>
                    </div>

                    <div class="space-y-1 text-center sm:text-left flex-1">
                        <h1 class="text-xl font-extrabold text-slate-900">{{ auth()->user()->name }}</h1>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                            <span class="px-3 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[10px] font-extrabold uppercase border border-blue-100">
                                Peran: {{ auth()->user()->role->value ?? auth()->user()->role }}
                            </span>
                            <span class="text-[11px] text-slate-400">Format: JPG, JPEG, PNG, WEBP (Max 2 MB)</span>
                        </div>
                    </div>
                </div>

                <!-- Input Fields -->
                <div class="space-y-4">
                    <x-input label="Nama Lengkap" name="name" value="{{ old('name', auth()->user()->name) }}" required />
                    <x-input label="Alamat Email (Tetap)" name="email" value="{{ auth()->user()->email }}" disabled />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input label="Institusi / Komunitas" name="institution" value="{{ old('institution', auth()->user()->institution) }}" placeholder="Masukkan nama instansi atau komunitas..." />
                        <x-input label="Nomor Telepon/WA" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Contoh: 081234567890" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <x-button type="submit" variant="primary" icon="save">Simpan Perubahan Profil</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
