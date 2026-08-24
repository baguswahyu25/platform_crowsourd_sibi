<x-app-layout title="Hasil Validasi AI - SIBI Dataset Platform" role="contributor">
    <div class="max-w-4xl mx-auto space-y-8 py-6">
        @php
            $isPassed = ($dataset->auto_validation_status === 'passed' || $dataset->status->value === 'waiting_expert_validation');
        @endphp

        @if($isPassed)
            <!-- ========================================================================= -->
            <!-- CARD HASIL: VALIDASI AWAL BERHASIL (PASSED) -->
            <!-- ========================================================================= -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
                <!-- Banner Status Green Header -->
                <div class="bg-emerald-600 p-8 text-white text-center space-y-3">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center mx-auto shadow-inner border border-white/30">
                        <span class="material-symbols-outlined text-5xl">task_alt</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight">✓ VALIDASI AWAL BERHASIL</h1>
                    <p class="text-xs sm:text-sm text-emerald-100 max-w-md mx-auto">
                        Video peragaan bahasa isyarat SIBI Anda telah lolos analisis AI otomatis (OpenCV) dan memenuhi 4 kriteria kualitas.
                    </p>
                </div>

                <div class="p-6 sm:p-10 space-y-8">
                    <!-- Title & Label Metadata -->
                    <div class="flex flex-wrap items-center justify-between gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Judul Berkas Dataset</span>
                            <h2 class="text-base font-extrabold text-slate-900">{{ $dataset->title }}</h2>
                            <p class="text-xs text-slate-500">Label: "{{ $dataset->sign_label }}" • Kategori: {{ $dataset->category }}</p>
                        </div>
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-extrabold text-xs rounded-full border border-emerald-200">
                            AI Check Passed
                        </span>
                    </div>

                    <!-- 4 Criteria Analysis Scores & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- 1. Pencahayaan -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Pencahayaan</span>
                                <span class="text-emerald-600 font-extrabold">✓ Normal</span>
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->brightness_status ?? 'Normal' }}</p>
                            <p class="text-[11px] text-slate-400">Score: {{ $dataset->brightness_score ?? 84.5 }} / 255.0</p>
                        </div>

                        <!-- 2. Ketajaman -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Ketajaman Video</span>
                                <span class="text-emerald-600 font-extrabold">✓ Tajam</span>
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->blur_status ?? 'Tajam' }}</p>
                            <p class="text-[11px] text-slate-400">Laplacian Score: {{ $dataset->blur_score ?? 172.0 }} (Threshold: > 100)</p>
                        </div>

                        <!-- 3. Kelancaran -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Kelancaran Video</span>
                                <span class="text-emerald-600 font-extrabold">✓ Lancar</span>
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->freeze_status ?? 'Lancar' }}</p>
                            <p class="text-[11px] text-slate-400">Freeze Frame: {{ $dataset->freeze_percentage ?? 0.0 }}% (Max: 2.0%)</p>
                        </div>

                        <!-- 4. Resolusi & Frame Rate -->
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200 space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Resolusi & Frame Rate</span>
                                <span class="text-emerald-600 font-extrabold">✓ {{ $dataset->resolution_status ?? 'Tinggi' }}</span>
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->video_width ?? 1920 }} × {{ $dataset->video_height ?? 1080 }}</p>
                            <p class="text-[11px] text-slate-400">Frame Rate: {{ $dataset->video_fps ?? 60.0 }} FPS</p>
                        </div>
                    </div>

                    <!-- Status Alur Next Step -->
                    <div class="bg-blue-50/80 border border-blue-200 p-5 rounded-2xl space-y-2">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700">Status Alur Selanjutnya:</span>
                        <h3 class="text-base font-black text-blue-900 uppercase">MENUNGGU VALIDASI PAKAR SIBI</h3>
                        <p class="text-xs text-blue-800 leading-relaxed">
                            Video telah memenuhi 4 kriteria analisis AI otomatis dan saat ini telah berada dalam antrean peninjauan oleh Validator/Pakar SIBI. Anda tidak perlu melakukan tindakan lebih lanjut.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <a href="{{ route('contributor.dataset.index') }}" class="flex-1 min-h-[44px] px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">assignment_turned_in</span>
                            Dataset Saya
                        </a>
                        <a href="{{ route('landing') }}" class="flex-1 min-h-[44px] px-6 py-3 border border-slate-300 text-slate-700 hover:bg-slate-50 rounded-xl font-bold text-xs transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">home</span>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- ========================================================================= -->
            <!-- CARD HASIL: VALIDASI BELUM BERHASIL (FAILED) -->
            <!-- ========================================================================= -->
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
                            <h2 class="text-base font-extrabold text-slate-900">{{ $dataset->title }}</h2>
                            <p class="text-xs text-slate-500">Label: "{{ $dataset->sign_label }}" • Kategori: {{ $dataset->category }}</p>
                        </div>
                        <span class="px-3 py-1 bg-rose-100 text-rose-800 font-extrabold text-xs rounded-full border border-rose-200">
                            AI Check Failed
                        </span>
                    </div>

                    <!-- 4 Criteria Analysis Details (Failed state highlights) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- 1. Pencahayaan -->
                        <div class="p-5 rounded-2xl border {{ ($dataset->brightness_status ?? 'Normal') === 'Normal' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Pencahayaan</span>
                                @if(($dataset->brightness_status ?? 'Normal') === 'Normal')
                                    <span class="text-emerald-600 font-extrabold">✓ Normal</span>
                                @else
                                    <span class="text-rose-600 font-extrabold">✕ {{ $dataset->brightness_status ?? 'Terlalu Gelap' }}</span>
                                @endif
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->brightness_status ?? 'Terlalu Gelap' }}</p>
                            <p class="text-[11px] text-slate-400">Score: {{ $dataset->brightness_score ?? 35.2 }} / 255.0</p>
                        </div>

                        <!-- 2. Ketajaman -->
                        <div class="p-5 rounded-2xl border {{ ($dataset->blur_status ?? 'Tajam') === 'Tajam' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Ketajaman Video</span>
                                @if(($dataset->blur_status ?? 'Tajam') === 'Tajam')
                                    <span class="text-emerald-600 font-extrabold">✓ Tajam</span>
                                @else
                                    <span class="text-rose-600 font-extrabold">✕ Video Buram</span>
                                @endif
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->blur_status ?? 'Buram' }}</p>
                            <p class="text-[11px] text-slate-400">Laplacian Score: {{ $dataset->blur_score ?? 42.0 }} (Min: > 100)</p>
                        </div>

                        <!-- 3. Kelancaran -->
                        <div class="p-5 rounded-2xl border {{ ($dataset->freeze_status ?? 'Lancar') === 'Lancar' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Kelancaran Video</span>
                                @if(($dataset->freeze_status ?? 'Lancar') === 'Lancar')
                                    <span class="text-emerald-600 font-extrabold">✓ Lancar</span>
                                @else
                                    <span class="text-rose-600 font-extrabold">✕ {{ $dataset->freeze_status ?? 'Patah-patah' }}</span>
                                @endif
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->freeze_status ?? 'Patah-patah' }}</p>
                            <p class="text-[11px] text-slate-400">Freeze Frame: {{ $dataset->freeze_percentage ?? 15.5 }}% (Max: 2.0%)</p>
                        </div>

                        <!-- 4. Resolusi & Frame Rate -->
                        <div class="p-5 rounded-2xl border {{ ($dataset->resolution_status ?? 'Standar') !== 'Rendah' ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <span>Resolusi & Frame Rate</span>
                                @if(($dataset->resolution_status ?? 'Standar') !== 'Rendah')
                                    <span class="text-emerald-600 font-extrabold">✓ {{ $dataset->resolution_status ?? 'Tinggi' }}</span>
                                @else
                                    <span class="text-rose-600 font-extrabold">✕ Resolusi Rendah</span>
                                @endif
                            </div>
                            <p class="text-lg font-black text-slate-900">{{ $dataset->video_width ?? 640 }} × {{ $dataset->video_height ?? 480 }}</p>
                            <p class="text-[11px] text-slate-400">Frame Rate: {{ $dataset->video_fps ?? 15.0 }} FPS</p>
                        </div>
                    </div>

                    <!-- Notice Card -->
                    <div class="bg-rose-50 border border-rose-200 p-5 rounded-2xl space-y-1">
                        <h3 class="text-xs font-bold text-rose-900">Perhatian:</h3>
                        <p class="text-xs text-rose-700 leading-relaxed">
                            Video belum dapat diteruskan ke tahap validasi pakar SIBI. Silakan lakukan perekaman ulang dengan memperhatikan pencahayaan, ketajaman, kelancaran, dan resolusi video.
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2">
                        @if($dataset->dataset_need_id)
                            <a href="{{ route('contributor.dataset.upload', ['need_id' => $dataset->dataset_need_id]) }}" class="w-full min-h-[44px] px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
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
        @endif
    </div>
</x-app-layout>
