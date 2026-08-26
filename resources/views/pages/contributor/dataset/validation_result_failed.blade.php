<x-app-layout title="Hasil Validasi AI - SIBI Dataset Platform" role="contributor">
    <div class="max-w-4xl mx-auto space-y-8 py-6">
        @php
            $d = $failedDataset ?? [];
        @endphp

        <!-- CARD HASIL: VALIDASI BELUM BERHASIL (FAILED) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            <!-- Banner Status Red Header -->
            <div class="bg-rose-600 p-8 text-white text-center space-y-3">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto shadow-inner border border-white/30">
                    <span class="material-symbols-outlined text-5xl">cancel</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">✕ VALIDASI BELUM BERHASIL</h1>
                <p class="text-xs sm:text-sm text-rose-100 max-w-md mx-auto">
                    Video belum memenuhi salah satu atau beberapa kriteria analisis AI otomatis (OpenCV). Silakan lakukan perekaman ulang.
                </p>
            </div>

            <div class="p-6 sm:p-10 space-y-8">
                <!-- Title & Label Metadata -->
                <div class="flex flex-wrap items-center justify-between gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <div>
                        <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Judul Berkas Dataset</span>
                        <h2 class="text-base font-extrabold text-slate-900">{{ $d['title'] ?? 'Peragaan SIBI' }}</h2>
                        <p class="text-xs text-slate-500">Label: "{{ $d['sign_label'] ?? 'A' }}" • Kategori: {{ $d['category'] ?? 'Abjad SIBI' }}</p>
                    </div>
                    <span class="px-3 py-1 bg-rose-100 text-rose-800 font-extrabold text-xs rounded-full border border-rose-200">
                        AI Check Failed
                    </span>
                </div>

                <!-- 4 Criteria Analysis Details (Failed state highlights) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- 1. Pencahayaan -->
                    <div class="p-5 rounded-2xl border {{ ($d['brightness_status'] ?? 'Normal') === 'Normal' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <span>Pencahayaan</span>
                            @if(($d['brightness_status'] ?? 'Normal') === 'Normal')
                                <span class="text-emerald-600 font-extrabold">✓ Normal</span>
                            @else
                                <span class="text-rose-600 font-extrabold">✕ {{ $d['brightness_status'] ?? 'Terlalu Gelap' }}</span>
                            @endif
                        </div>
                        <p class="text-lg font-black text-slate-900">{{ $d['brightness_status'] ?? 'Terlalu Gelap' }}</p>
                        <p class="text-[11px] text-slate-400">Score: {{ $d['brightness_score'] ?? 35.2 }} / 255.0</p>
                    </div>

                    <!-- 2. Ketajaman -->
                    <div class="p-5 rounded-2xl border {{ ($d['blur_status'] ?? 'Tajam') === 'Tajam' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <span>Ketajaman Video</span>
                            @if(($d['blur_status'] ?? 'Tajam') === 'Tajam')
                                <span class="text-emerald-600 font-extrabold">✓ Tajam</span>
                            @else
                                <span class="text-rose-600 font-extrabold">✕ Video Buram</span>
                            @endif
                        </div>
                        <p class="text-lg font-black text-slate-900">{{ $d['blur_status'] ?? 'Buram' }}</p>
                        <p class="text-[11px] text-slate-400">Laplacian Score: {{ $d['blur_score'] ?? 42.0 }} (Min: > 50)</p>
                    </div>

                    <!-- 3. Kelancaran -->
                    <div class="p-5 rounded-2xl border {{ ($d['freeze_status'] ?? 'Lancar') === 'Lancar' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <span>Kelancaran Video</span>
                            @if(($d['freeze_status'] ?? 'Lancar') === 'Lancar')
                                <span class="text-emerald-600 font-extrabold">✓ Lancar</span>
                            @else
                                <span class="text-rose-600 font-extrabold">✕ {{ $d['freeze_status'] ?? 'Patah-patah' }}</span>
                            @endif
                        </div>
                        <p class="text-lg font-black text-slate-900">{{ $d['freeze_status'] ?? 'Patah-patah' }}</p>
                        <p class="text-[11px] text-slate-400">Freeze Frame: {{ $d['freeze_percentage'] ?? 36.5 }}% (Max: 35.0%)</p>
                    </div>

                    <!-- 4. Resolusi & Frame Rate -->
                    <div class="p-5 rounded-2xl border {{ ($d['resolution_status'] ?? 'Standar') !== 'Rendah' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                        <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <span>Resolusi & Frame Rate</span>
                            @if(($d['resolution_status'] ?? 'Standar') !== 'Rendah')
                                <span class="text-emerald-600 font-extrabold">✓ {{ $d['resolution_status'] ?? 'Tinggi' }}</span>
                            @else
                                <span class="text-rose-600 font-extrabold">✕ Resolusi Rendah</span>
                            @endif
                        </div>
                        <p class="text-lg font-black text-slate-900">{{ $d['video_width'] ?? 640 }} × {{ $d['video_height'] ?? 480 }}</p>
                        <p class="text-[11px] text-slate-400">Frame Rate: {{ $d['video_fps'] ?? 30.0 }} FPS</p>
                    </div>
                </div>

                <!-- Notice Card -->
                <div class="bg-rose-50 border border-rose-200 p-5 rounded-2xl space-y-2">
                    <h3 class="text-xs font-bold text-rose-900 uppercase tracking-wider">Informasi Penting:</h3>
                    <p class="text-xs text-rose-700 leading-relaxed font-semibold">
                        Video belum dapat dikirim ke tahap validasi pakar. Silakan perbaiki video sesuai kriteria dan upload kembali.
                    </p>
                    <p class="text-[11px] text-rose-600">
                        * Berkas video gagal ini tidak disimpan di database maupun penyimpanan server Anda untuk menghemat memori.
                    </p>
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    @if(!empty($d['dataset_need_id']))
                        <a href="{{ route('contributor.dataset.upload', ['need_id' => $d['dataset_need_id']]) }}" class="w-full min-h-[44px] px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">upload</span>
                            Upload Ulang Video
                        </a>
                    @else
                        <a href="{{ route('contributor.dataset.upload') }}" class="w-full min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">upload</span>
                            Upload Ulang Video
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
