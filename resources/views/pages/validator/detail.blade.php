<x-app-layout title="Peninjauan & Validasi Sample SIBI - Validator" role="validator">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Validator' => route('validator.dashboard'), 'Antrean Validasi' => route('validator.antrean'), 'Peninjauan Sample' => '']" />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Media Player Section (7 Cols Desktop) -->
            <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Pemutar Video Sampel Isyarat</span>
                    <span class="text-xs text-slate-400">ID: #DS-{{ $dataset->id ?? '101' }}</span>
                </div>

                <!-- Video / Media Container -->
                <div class="aspect-video bg-slate-900 rounded-2xl overflow-hidden flex items-center justify-center relative">
                    <div class="text-center text-white space-y-2">
                        <span class="material-symbols-outlined text-6xl opacity-80">play_circle</span>
                        <p class="text-xs font-semibold">Video Sampel Rekaman Gesture SIBI</p>
                        <p class="text-[11px] text-slate-400">Label: TERIMA KASIH</p>
                    </div>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Pengunggah:</span>
                        <span class="font-bold text-slate-800">{{ $dataset->user->name ?? 'Budi Santoso' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Institusi:</span>
                        <span class="font-bold text-slate-800">{{ $dataset->user->institution ?? 'Universitas Indonesia' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Format & Resolusi:</span>
                        <span class="font-bold text-slate-800">MP4 (1080p @ 60fps)</span>
                    </div>
                </div>
            </div>

            <!-- Validation Decision Panel (5 Cols Desktop) -->
            <div class="lg:col-span-5 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-6">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Keputusan Validasi SIBI</h2>
                    <p class="text-xs text-slate-500 mt-1">Pilih keputusan keakuratan posisi tangan, kelancaran gerakan, dan posisi kamera.</p>
                </div>

                <form action="{{ route('validator.process', $dataset->id ?? 101) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Status Keputusan</label>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-xl border border-emerald-200 bg-emerald-50/50 cursor-pointer hover:bg-emerald-50 transition">
                                <input type="radio" name="status" value="validated" class="text-emerald-600 focus:ring-emerald-500 mr-3" checked />
                                <div>
                                    <span class="text-xs font-bold text-emerald-900 block">SETUJU (VALID)</span>
                                    <span class="text-[11px] text-emerald-700">Gerakan isyarat SIBI tepat dan berkualitas.</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-xl border border-blue-200 bg-blue-50/50 cursor-pointer hover:bg-blue-50 transition">
                                <input type="radio" name="status" value="revision" class="text-blue-600 focus:ring-blue-500 mr-3" />
                                <div>
                                    <span class="text-xs font-bold text-blue-900 block">MINTA REVISI</span>
                                    <span class="text-[11px] text-blue-700">Gerakan kurang jelas atau butuh rekaman ulang.</span>
                                </div>
                            </label>
                            <label class="flex items-center p-3 rounded-xl border border-rose-200 bg-rose-50/50 cursor-pointer hover:bg-rose-50 transition">
                                <input type="radio" name="status" value="rejected" class="text-rose-600 focus:ring-rose-500 mr-3" />
                                <div>
                                    <span class="text-xs font-bold text-rose-900 block">TOLAK SAMPLE</span>
                                    <span class="text-[11px] text-rose-700">Isyarat tidak sesuai standar SIBI.</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <x-input label="Catatan Validator (Umpan Balik)" name="notes" type="textarea" placeholder="Berikan saran atau instruksi perbaikan jika ada..." />

                    <x-button type="submit" variant="primary" icon="check_circle" class="w-full text-center min-h-[44px]">
                        Kirim Hasil Evaluasi Validasi
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
