<x-app-layout title="Detail Dataset SIBI">
    <div class="max-w-4xl mx-auto space-y-6">
        <x-breadcrumb :items="['Dataset' => '', 'Detail Dataset #' . ($id ?? '1') => '']" />

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Kategori: {{ $dataset->category ?? 'Frasa SIBI' }}</span>
                    <h1 class="text-2xl font-black text-slate-900 mt-1">{{ $dataset->title ?? 'Isyarat "SAYA BELAJAR"' }}</h1>
                    <p class="text-xs text-slate-400 mt-1">Label SIBI: <code class="bg-slate-100 px-2 py-0.5 rounded font-mono font-bold text-slate-700">{{ $dataset->sign_label ?? 'SAYA_BELAJAR' }}</code></p>
                </div>
                <div>
                    <x-badge type="{{ $dataset->status->value ?? 'pending' }}" label="{{ isset($dataset->status) ? $dataset->status->label() : 'Menunggu Validasi' }}" />
                </div>
            </div>

            <!-- Video Player -->
            <div class="aspect-video bg-slate-900 rounded-2xl flex items-center justify-center text-white relative">
                <div class="text-center space-y-2">
                    <span class="material-symbols-outlined text-6xl opacity-80">play_circle</span>
                    <p class="text-xs font-semibold">Video Rekaman SIBI</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block font-medium">Pengunggah:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $dataset->user->name ?? 'Ahmad Risyad' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Tanggal Diunggah:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ now()->format('d F Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
