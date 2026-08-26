<x-app-layout title="Detail Dataset SIBI">
    <div class="max-w-4xl mx-auto space-y-6">
        <x-breadcrumb :items="['Dataset' => route('contributor.dataset.index'), 'Detail Dataset #' . $dataset->id => '']" />

        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-6">
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Kategori: {{ $dataset->category ?? 'Abjad' }}</span>
                    <h1 class="text-2xl font-black text-slate-900 mt-1">{{ $dataset->title }}</h1>
                    <p class="text-xs text-slate-400 mt-1">Label SIBI: <code class="bg-slate-100 px-2 py-0.5 rounded font-mono font-bold text-slate-700">{{ $dataset->sign_label }}</code></p>
                </div>
                <div>
                    @if(($dataset->status->value ?? 'pending') === 'validated')
                        <x-badge type="validated" label="Disetujui / Valid" />
                    @else
                        <x-badge type="pending" label="Menunggu Validasi" />
                    @endif
                </div>
            </div>

            <!-- Video Player Asli Kontributor -->
            <div class="aspect-video bg-slate-950 rounded-2xl overflow-hidden relative shadow-inner border border-slate-800 flex items-center justify-center">
                @if($dataset->has_video)
                    <video controls preload="metadata" class="w-full h-full object-contain">
                        <source src="{{ $dataset->video_url }}" type="{{ $dataset->file_type ?? 'video/mp4' }}">
                        <p class="text-xs text-rose-400 p-4 text-center">Video tidak dapat diputar pada browser ini.</p>
                    </video>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-center p-6 space-y-2 text-slate-400">
                        <span class="material-symbols-outlined text-5xl text-rose-500">video_off</span>
                        <p class="text-xs font-bold text-slate-200">Video dataset tidak ditemukan atau tidak dapat diakses.</p>
                        <p class="text-[11px] text-slate-500 font-mono">Path: {{ $dataset->file_path ?? 'TIDAK TERSEDIA' }}</p>
                    </div>
                @endif
            </div>

            <!-- Short Story Transcript (Khusus Kategori Short Story) -->
            @if(!empty($dataset->story_content))
                <div class="bg-purple-50 p-5 rounded-2xl border border-purple-200 space-y-2">
                    <div class="flex items-center gap-2 text-purple-900 font-extrabold text-xs">
                        <span class="material-symbols-outlined text-base text-purple-700">auto_stories</span>
                        <span>Naskah / Transkrip Cerita Pendek:</span>
                    </div>
                    <p class="text-xs text-purple-900 leading-relaxed italic bg-white p-3.5 rounded-xl border border-purple-100">
                        "{{ $dataset->story_content }}"
                    </p>
                </div>
            @endif

            <!-- Metadata Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-100 text-xs">
                <div>
                    <span class="text-slate-400 block font-medium">Pengunggah:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $dataset->user->name ?? $dataset->contributor_code }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Tanggal Diunggah:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $dataset->created_at ? $dataset->created_at->format('d F Y, H:i') : now()->format('d F Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Spesifikasi Berkas Video:</span>
                    <span class="font-bold text-slate-800 text-sm">{{ $dataset->video_width ?? 1920 }}×{{ $dataset->video_height ?? 1080 }} ({{ round(($dataset->file_size ?? 0)/(1024*1024), 2) }} MB)</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
