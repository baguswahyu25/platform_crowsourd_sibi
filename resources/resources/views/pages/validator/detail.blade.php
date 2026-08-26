<x-app-layout title="Peninjauan & Validasi Sample SIBI - Validator" role="validator">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Validator' => route('validator.dashboard'), 'Antrean Validasi' => route('validator.antrean'), 'Peninjauan Sample' => '']" />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Media Player Section (7 Cols Desktop) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Video Player Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-600">Kategori Dataset: {{ $dataset->category ?? 'Alphabet' }}</span>
                            <h1 class="text-xl font-black text-slate-900 mt-0.5">{{ $dataset->title }}</h1>
                            <p class="text-xs text-slate-500">Label: "{{ $dataset->sign_label }}"</p>
                        </div>
                        <span class="text-xs text-slate-400 font-bold">ID: #DS-{{ $dataset->id }}</span>
                    </div>

                    <!-- Video Container -->
                    <div class="aspect-video bg-slate-950 rounded-2xl overflow-hidden flex items-center justify-center relative shadow-inner">
                        @if(!empty($dataset->file_path) && file_exists(storage_path('app/public/' . $dataset->file_path)))
                            <video src="{{ asset('storage/' . $dataset->file_path) }}" controls class="w-full h-full object-contain"></video>
                        @else
                            <div class="text-center text-white space-y-2 p-6">
                                <span class="material-symbols-outlined text-6xl text-blue-500">play_circle</span>
                                <p class="text-xs font-semibold">Video Sampel Rekaman Gesture SIBI</p>
                                <p class="text-[11px] text-slate-400">Path: {{ $dataset->file_path ?? 'datasets/sample.mp4' }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Short Story Transcript (Khusus Kategori Short Story) -->
                    @if(($dataset->category ?? '') === 'Short Story' || !empty($dataset->story_content))
                        <div class="bg-purple-50 p-5 rounded-2xl border border-purple-200 space-y-2">
                            <div class="flex items-center gap-2 text-purple-900 font-extrabold text-xs">
                                <span class="material-symbols-outlined text-base text-purple-700">auto_stories</span>
                                <span>Naskah / Transkrip Cerita Pendek (Contributor-Generated):</span>
                            </div>
                            <p class="text-xs text-purple-900 leading-relaxed italic bg-white p-3.5 rounded-xl border border-purple-100">
                                "{{ $dataset->story_content ?? $dataset->description }}"
                            </p>
                        </div>
                    @endif

                    <!-- Contributor Info -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Kontributor:</span>
                            <span class="font-bold text-slate-800">{{ $dataset->user->name ?? 'Ahmad Risyad' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Institusi:</span>
                            <span class="font-bold text-slate-800">{{ $dataset->user->institution ?? 'Universitas Indonesia' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">Ukuran Berkas & Resolusi:</span>
                            <span class="font-bold text-slate-800">{{ $dataset->video_width ?? 1920 }}×{{ $dataset->video_height ?? 1080 }} ({{ round(($dataset->file_size ?? 12000000)/(1024*1024), 1) }} MB @ {{ $dataset->video_fps ?? 30 }} FPS)</span>
                        </div>
                    </div>
                </div>

                <!-- OpenCV AI Analysis Breakdown Card -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">smart_toy</span>
                        Hasil Analisis Otomatis AI (OpenCV)
                    </h3>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Pencahayaan</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $dataset->brightness_status ?? 'Normal' }}</span>
                            <span class="text-[10px] text-slate-400 block">Score: {{ $dataset->brightness_score ?? 85.0 }}</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Ketajaman</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $dataset->blur_status ?? 'Tajam' }}</span>
                            <span class="text-[10px] text-slate-400 block">Laplacian: {{ $dataset->blur_score ?? 180.0 }}</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Kelancaran Video</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $dataset->freeze_status ?? 'Lancar' }}</span>
                            <span class="text-[10px] text-slate-400 block">Freeze Frame: {{ $dataset->freeze_percentage ?? 0 }}%</span>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block">Resolusi</span>
                            <span class="font-bold text-slate-900 text-sm">{{ $dataset->resolution_status ?? 'Tinggi' }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $dataset->video_width ?? 1920 }}×{{ $dataset->video_height ?? 1080 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Decision Panel (5 Cols Desktop) -->
            <div class="lg:col-span-5 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Keputusan Validasi Pakar SIBI</h2>
                    <p class="text-xs text-slate-500 mt-1">Evaluasi keakuratan posisi peragaan tangan SIBI, kelancaran gesture, serta kesesuaian transkrip naskah cerita.</p>
                </div>

                <form action="{{ route('validator.process', $dataset->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Status Keputusan Evaluasi</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3.5 rounded-2xl border border-emerald-200 bg-emerald-50/50 cursor-pointer hover:bg-emerald-50 transition">
                                <input type="radio" name="status" value="validated" class="text-emerald-600 focus:ring-emerald-500 mr-3" checked />
                                <div>
                                    <span class="text-xs font-bold text-emerald-900 block">✓ SETUJU / TERVALIDASI (APPROVED)</span>
                                    <span class="text-[11px] text-emerald-700">Peragaan isyarat SIBI sesuai dan berkualitas.</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3.5 rounded-2xl border border-blue-200 bg-blue-50/50 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="status" value="revision" class="text-blue-600 focus:ring-blue-500 mr-3" />
                                <div>
                                    <span class="text-xs font-bold text-blue-900 block">⏳ MINTA REVISI (REVISION)</span>
                                    <span class="text-[11px] text-blue-700">Gerakan kurang jelas atau butuh perekaman ulang.</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3.5 rounded-2xl border border-rose-200 bg-rose-50/50 cursor-pointer hover:bg-rose-50 transition">
                                <input type="radio" name="status" value="rejected" class="text-rose-600 focus:ring-rose-500 mr-3" />
                                <div>
                                    <span class="text-xs font-bold text-rose-900 block">✕ TOLAK DATASET (REJECTED)</span>
                                    <span class="text-[11px] text-rose-700">Peragaan tidak sesuai standar SIBI atau naskah tidak cocok.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Catatan Evaluasi / Alasan Penolakan (Rejection Reason)</label>
                        <textarea name="notes" rows="4" placeholder="Tuliskan catatan evaluasi atau alasan penolakan untuk diberikan kepada kontributor..." class="w-full p-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-sm transition outline-none resize-none"></textarea>
                    </div>

                    <x-button type="submit" variant="primary" icon="check_circle" class="w-full text-center min-h-[44px]">
                        Kirim Hasil Evaluasi Validasi
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
