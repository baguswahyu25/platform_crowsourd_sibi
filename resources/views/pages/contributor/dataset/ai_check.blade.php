<x-app-layout title="Pemeriksaan AI - SIBI Dataset Platform" role="contributor">
    @php
        $bPassed = ($dataset->brightness_status ?? 'Normal') === 'Normal';
        $blurPassed = ($dataset->blur_status ?? 'Tajam') === 'Tajam';
        $freezePassed = ($dataset->freeze_status ?? 'Lancar') === 'Lancar' || ($dataset->freeze_status ?? 'Lancar') === 'Ada Gejala Lag';
        $resPassed = ($dataset->resolution_status ?? 'Sesuai Standar') === 'Sesuai Standar';
        $fpsPassed = ($dataset->fps_status ?? 'Sesuai Standar') === 'Sesuai Standar';
    @endphp

    <div class="max-w-6xl mx-auto space-y-8" x-data="{ 
        state: 'checking', 
        result: '{{ $dataset->auto_validation_status ?? 'passed' }}',
        progress: 0,
        currentStep: 0,
        items: [
            { label: 'Pencahayaan Subjek', status: 'checking', detail: '{{ $dataset->brightness_status ?? 'Normal' }}' },
            { label: 'Ketajaman Video', status: 'pending', detail: '{{ $dataset->blur_status ?? 'Tajam' }}' },
            { label: 'Kelancaran Video', status: 'pending', detail: '{{ $dataset->freeze_status ?? 'Lancar' }}' },
            { label: 'Resolusi Video', status: 'pending', detail: '{{ $dataset->video_width ?? 1280 }}×{{ $dataset->video_height ?? 720 }}' },
            { label: 'Frame Rate (FPS)', status: 'pending', detail: '{{ $dataset->video_fps ?? 30 }} FPS' }
        ],
        targetStatuses: [
            '{{ $bPassed ? "valid" : "failed" }}',
            '{{ $blurPassed ? "valid" : "failed" }}',
            '{{ $freezePassed ? "valid" : "failed" }}',
            '{{ $resPassed ? "valid" : "failed" }}',
            '{{ $fpsPassed ? "valid" : "failed" }}'
        ],
        startScan() {
            this.progress = 0;
            this.currentStep = 0;
            this.items[0].status = 'checking';

            let interval = setInterval(() => {
                if (this.currentStep < 4) {
                    this.items[this.currentStep].status = this.targetStatuses[this.currentStep];
                    this.currentStep++;
                    this.items[this.currentStep].status = 'checking';
                    this.progress = Math.round((this.currentStep / 5) * 100);
                } else if (this.currentStep === 4) {
                    this.items[4].status = this.targetStatuses[4];
                    this.progress = 100;
                    this.currentStep = 5;
                    clearInterval(interval);

                    setTimeout(() => {
                        window.location.href = '{{ route("dataset.validation-result", $dataset->id) }}';
                    }, 600);
                }
            }, 500);
        }
    }" x-init="startScan()">

        <!-- TAMPILAN: SCANNING / PEMERIKSAAN AI SEDANG BERLANGSUNG -->
        <div class="flex flex-col items-center justify-center py-10">
            <div class="w-full max-w-2xl bg-white rounded-3xl border border-slate-200 shadow-xl p-8 sm:p-12 text-center space-y-8">
                <!-- Scanning Animation Graphic -->
                <div class="relative w-32 h-32 mx-auto flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full border-4 border-blue-100"></div>
                    <div class="absolute inset-2 rounded-full border-2 border-blue-500 border-dashed animate-[spin_10s_linear_infinite]"></div>
                    <div class="relative bg-blue-600 text-white w-16 h-16 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30 overflow-hidden">
                        <span class="material-symbols-outlined text-3xl z-10">smart_toy</span>
                        <div class="absolute top-0 left-0 w-full h-1/2 bg-gradient-to-b from-transparent to-white/40 animate-[scanline_2s_linear_infinite] z-20 pointer-events-none"></div>
                    </div>
                </div>

                <!-- Typography -->
                <div class="space-y-2">
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Video sedang diperiksa AI</h1>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                        Harap tunggu. Sistem AI (OpenCV) sedang memproses 5 parameter kualitas video secara otomatis.
                    </p>
                </div>

                <!-- Synchronized Top Progress Bar -->
                <div class="w-full max-w-md mx-auto space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold">
                        <span class="text-slate-500 uppercase tracking-wider">Progres Pemindaian AI</span>
                        <span class="text-blue-600 transition-all duration-300" x-text="progress + '%'">0%</span>
                    </div>
                    <div class="w-full h-3.5 bg-slate-100 rounded-full overflow-hidden p-0.5 border border-slate-200">
                        <div class="h-full bg-blue-600 rounded-full transition-all duration-500 ease-out" :style="'width: ' + progress + '%'"></div>
                    </div>
                </div>

                <!-- Synchronized Checklist Loading Items (5 Parameters) -->
                <div class="w-full max-w-md mx-auto bg-slate-50 rounded-2xl p-6 border border-slate-200/80 text-left space-y-4">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Memeriksa 5 Parameter Utama:</h3>
                    <ul class="space-y-3.5 text-xs">
                        <template x-for="(item, index) in items" :key="index">
                            <li class="flex items-center justify-between transition-all duration-300" :class="item.status === 'pending' ? 'opacity-40' : 'opacity-100'">
                                <div class="flex items-center space-x-3">
                                    <!-- Valid State Icon -->
                                    <template x-if="item.status === 'valid'">
                                        <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </div>
                                    </template>
                                    <!-- Failed State Icon -->
                                    <template x-if="item.status === 'failed'">
                                        <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </div>
                                    </template>
                                    <!-- Checking Loading Spinner Icon -->
                                    <template x-if="item.status === 'checking'">
                                        <div class="w-6 h-6 text-blue-600 flex items-center justify-center animate-spin">
                                            <span class="material-symbols-outlined text-base">hourglass_top</span>
                                        </div>
                                    </template>
                                    <!-- Pending Icon -->
                                    <template x-if="item.status === 'pending'">
                                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 text-slate-400 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-xs">more_horiz</span>
                                        </div>
                                    </template>

                                    <div>
                                        <span class="font-bold text-slate-800 text-sm block" x-text="item.label"></span>
                                        <span class="text-[11px] text-slate-500 font-medium" x-text="item.detail"></span>
                                    </div>
                                </div>

                                <!-- Status Badges -->
                                <template x-if="item.status === 'valid'">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">✓ TERUJI</span>
                                </template>
                                <template x-if="item.status === 'failed'">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-50 text-rose-700 border border-rose-200">✕ GAGAL</span>
                                </template>
                                <template x-if="item.status === 'checking'">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 animate-pulse">⏳ MEMERIKSA...</span>
                                </template>
                                <template x-if="item.status === 'pending'">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 text-slate-500">PENDING</span>
                                </template>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
