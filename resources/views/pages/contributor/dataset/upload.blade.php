<x-app-layout title="Unggah Dataset - SIBI Dataset Platform" role="contributor">
    <div class="max-w-6xl mx-auto space-y-8">
        @if(isset($selectedNeed) && $selectedNeed)
            <!-- ========================================================================= -->
            <!-- DESAIN HTML 2: TAMPILAN UNGGAH DENGAN KEBUTUHAN DATASET SPESIFIK PILIHAN -->
            <!-- ========================================================================= -->
            @php
                $isFulfilled = ($selectedNeed->status->value === 'fulfilled' || $selectedNeed->current_count >= $selectedNeed->target_count);
                $percentage = round(($selectedNeed->current_count / max($selectedNeed->target_count, 1)) * 100);
                if ($percentage > 100) $percentage = 100;
                $remaining = max(0, $selectedNeed->target_count - $selectedNeed->current_count);
            @endphp

            <div>
                <!-- Hero Heading -->
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Unggah Dataset</h1>
                    <p class="text-sm text-slate-600 max-w-2xl leading-relaxed">Unggah video peragaan bahasa isyarat sesuai dengan kebutuhan dataset yang telah Anda pilih sebelumnya untuk membantu pengembangan model SIBI.</p>
                </div>

                @if($isFulfilled)
                    <!-- ALERT KEBUTUHAN SUDAH TERPENUHI (BLOCKED UPLOAD) -->
                    <div class="bg-rose-50 border-2 border-rose-200 rounded-3xl p-6 sm:p-8 space-y-6 shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-14 h-14 bg-rose-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-rose-600/20">
                                <span class="material-symbols-outlined text-3xl">task_alt</span>
                            </div>
                            <div class="space-y-1">
                                <span class="px-3 py-1 bg-rose-100 text-rose-800 font-extrabold text-xs rounded-full uppercase">Target Terpenuhi (Closed)</span>
                                <h2 class="text-xl font-extrabold text-rose-900">Kebutuhan Dataset Label "{{ $selectedNeed->title }}" Sudah Terpenuhi</h2>
                                <p class="text-xs sm:text-sm text-rose-700 leading-relaxed">
                                    Kuota sampel video untuk label ini telah mencapai target <strong>{{ $selectedNeed->target_count }} / {{ $selectedNeed->target_count }} Video (100%)</strong>. Pengunggahan baru untuk label ini telah ditutup oleh sistem.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 bg-white/80 p-4 rounded-2xl border border-rose-100 text-center">
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Target</p>
                                <p class="font-black text-slate-800 text-sm">{{ $selectedNeed->target_count }} Video</p>
                            </div>
                            <div class="border-x border-slate-200">
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Terkumpul</p>
                                <p class="font-black text-emerald-600 text-sm">{{ $selectedNeed->current_count }} Video</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 uppercase font-bold">Sisa Kuota</p>
                                <p class="font-black text-rose-600 text-sm">0 Video</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                            <a href="{{ route('contributor.kebutuhan.index') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2 min-h-[44px]">
                                <span class="material-symbols-outlined text-base">checklist</span>
                                Pilih Target Kebutuhan Lainnya yang Masih Belum Terpenuhi
                            </a>
                            <a href="{{ route('contributor.dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center min-h-[44px]">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <!-- Left Column: Form & Info -->
                        <div class="lg:col-span-8 space-y-6">
                            <!-- Information Card -->
                            <div class="bg-blue-50/60 border border-blue-200/80 rounded-3xl p-6 flex flex-col md:flex-row gap-6 items-center">
                                <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-600/20">
                                    <span class="material-symbols-outlined text-3xl">info</span>
                                </div>
                                <div class="flex-1 w-full">
                                    <div class="flex flex-wrap justify-between items-start mb-4 gap-2">
                                        <div>
                                            <h3 class="text-base font-bold text-blue-900">Informasi Kebutuhan Dataset</h3>
                                            <p class="text-xs text-blue-700">Sedang mengerjakan kebutuhan khusus</p>
                                        </div>
                                        <span class="px-3 py-1 bg-amber-100 text-amber-800 font-extrabold text-xs rounded-full">
                                            Belum Terpenuhi
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                                        <div class="bg-white/80 p-3 rounded-xl border border-white">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Label Dataset</p>
                                            <p class="font-bold text-slate-900 text-sm">{{ $selectedNeed->title }}</p>
                                        </div>
                                        <div class="bg-white/80 p-3 rounded-xl border border-white">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Target</p>
                                            <p class="font-bold text-slate-900 text-sm">{{ $selectedNeed->target_count }} Video</p>
                                        </div>
                                        <div class="bg-white/80 p-3 rounded-xl border border-white">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Terkumpul</p>
                                            <p class="font-bold text-blue-600 text-sm">{{ $selectedNeed->current_count }} Video</p>
                                        </div>
                                        <div class="bg-white/80 p-3 rounded-xl border border-white">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Sisa</p>
                                            <p class="font-bold text-slate-900 text-sm">{{ $remaining }} Video</p>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs font-bold text-slate-600">
                                            <span>Progres Penyelesaian</span>
                                            <span>{{ $percentage }}%</span>
                                        </div>
                                        <div class="w-full bg-blue-200/60 rounded-full h-2 overflow-hidden">
                                            <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Card -->
                            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm">
                                <form action="{{ route('contributor.dataset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="uploadFormNeed">
                                    @csrf
                                    <input type="hidden" name="dataset_need_id" value="{{ $selectedNeed->id }}">
                                    <input type="hidden" name="title" value="Peragaan {{ $selectedNeed->title }}">
                                    <input type="hidden" name="sign_label" value="{{ $selectedNeed->title }}">
                                    <input type="hidden" name="category" value="{{ $selectedNeed->category }}">

                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Label Dataset</label>
                                        <div class="flex items-center px-4 py-3 bg-slate-100/80 rounded-xl border border-slate-200 text-slate-800 font-bold text-sm cursor-not-allowed">
                                            <span class="material-symbols-outlined mr-2 text-blue-600">label</span>
                                            {{ $selectedNeed->title }} ({{ $selectedNeed->category }})
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Deskripsi Tambahan (Opsional)</label>
                                        <textarea name="description" class="w-full px-4 py-3 bg-white rounded-xl border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-sm transition-all min-h-[100px] resize-none" placeholder="Tambahkan deskripsi singkat mengenai video yang diunggah..."></textarea>
                                    </div>

                                    <!-- Upload Area -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">File Video</label>
                                        <div class="border-2 border-dashed border-slate-300 bg-slate-50/50 rounded-3xl p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-blue-50/50 hover:border-blue-600 transition-all group relative overflow-hidden" id="drop-zone-need">
                                            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                                <span class="material-symbols-outlined text-3xl">cloud_upload</span>
                                            </div>
                                            <p class="text-base font-bold text-slate-800 mb-1">Seret file video ke sini atau <span class="text-blue-600">klik untuk memilih</span></p>
                                            <p class="text-slate-500 text-xs mb-6">MP4, MOV, atau AVI (Maksimal 100 MB)</p>
                                            <input type="file" name="dataset_file" accept="video/*" class="absolute inset-0 opacity-0 cursor-pointer" id="file-input-need" required onchange="handleFileSelectNeed(this)">
                                            <button class="bg-blue-600 text-white font-bold py-2.5 px-6 rounded-xl shadow-md hover:bg-blue-700 transition pointer-events-none text-xs" type="button">Pilih Video</button>

                                            <!-- Selected File Preview Card -->
                                            <div class="hidden absolute inset-0 bg-white p-6 flex-col items-center justify-center" id="file-preview-need">
                                                <div class="w-full flex gap-4 items-center bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                                    <div class="w-20 h-16 rounded-lg bg-slate-900 flex items-center justify-center text-white shrink-0">
                                                        <span class="material-symbols-outlined text-3xl">play_circle</span>
                                                    </div>
                                                    <div class="flex-1 text-left min-w-0">
                                                        <h4 class="font-bold text-slate-900 text-sm truncate mb-1" id="file-name-need">video_sample.mp4</h4>
                                                        <p class="text-xs text-slate-500" id="file-size-need">12.4 MB</p>
                                                    </div>
                                                    <button type="button" onclick="removeSelectedFileNeed()" class="p-2 text-rose-600 hover:bg-rose-50 rounded-full transition" title="Hapus">
                                                        <span class="material-symbols-outlined">delete</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-3 pt-2">
                                        <div class="flex items-start space-x-3 bg-blue-50/50 p-3.5 rounded-2xl border border-blue-100">
                                            <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" id="confirm-guidelines-need" type="checkbox" required>
                                            <label class="text-xs font-semibold text-slate-700 cursor-pointer" for="confirm-guidelines-need">Saya telah memastikan video mengikuti panduan perekaman yang ditentukan.</label>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                        <a href="{{ route('contributor.kebutuhan.index') }}" class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">
                                            Kembali
                                        </a>
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition flex items-center gap-2 text-xs min-h-[44px]" type="submit" id="btn-upload-need">
                                            <span class="material-symbols-outlined text-sm">check_circle</span>
                                            Unggah Dataset
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Right Column: Reusable Guidelines Sidebar -->
                        <div class="lg:col-span-4">
                            <x-video-guidelines />
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- ========================================================================= -->
            <!-- DESAIN HTML 1: TAMPILAN UNGGAH UMUM (KLIK DARI MENU SIDEBAR KONTRIBUTOR) -->
            <!-- ========================================================================= -->
            <div>
                <!-- Hero Heading -->
                <div class="mb-6">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Upload Dataset</h1>
                    <p class="text-sm text-slate-600 max-w-2xl leading-relaxed">Unggah video Bahasa Isyarat Indonesia sesuai kebutuhan dataset yang telah dipilih.</p>
                </div>

                @php
                    $activeNeeds = $needs->reject(fn($n) => $n->status->value === 'fulfilled' || $n->current_count >= $n->target_count);
                    $fulfilledNeeds = $needs->filter(fn($n) => $n->status->value === 'fulfilled' || $n->current_count >= $n->target_count);
                    $defaultNeed = $activeNeeds->first() ?? $needs->first();
                @endphp

                <!-- Workflow Logic: Select & Summary -->
                <div class="space-y-6 mb-8">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Kebutuhan Dataset (Hanya yang Belum Terpenuhi)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400">search</span>
                            <select id="need-selector" class="w-full h-12 pl-12 pr-10 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-slate-800 font-semibold text-sm transition shadow-xs appearance-none cursor-pointer" onchange="updateSelectedNeedSummary(this)">
                                <optgroup label="✅ BELUM TERPENUHI (TERBUKA)">
                                    @foreach($activeNeeds as $needItem)
                                        <option value="{{ $needItem->id }}" data-title="{{ $needItem->title }}" data-target="{{ $needItem->target_count }}" data-current="{{ $needItem->current_count }}" data-category="{{ $needItem->category }}">
                                            {{ $needItem->category }}: {{ $needItem->title }} ({{ $needItem->current_count }}/{{ $needItem->target_count }} Video)
                                        </option>
                                    @endforeach
                                </optgroup>
                                @if($fulfilledNeeds->isNotEmpty())
                                    <optgroup label="🔒 SUDAH TERPENUHI (TUTUP)">
                                        @foreach($fulfilledNeeds as $needItem)
                                            <option value="{{ $needItem->id }}" disabled data-title="{{ $needItem->title }}" data-target="{{ $needItem->target_count }}" data-current="{{ $needItem->current_count }}" data-category="{{ $needItem->category }}">
                                                [SUDAH TERPENUHI] {{ $needItem->category }}: {{ $needItem->title }} ({{ $needItem->current_count }}/{{ $needItem->target_count }} Video)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Dataset Summary Card -->
                    <div class="bg-blue-50/80 border border-blue-200/80 rounded-3xl p-6 flex flex-col md:flex-row items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-600/20">
                            <span class="material-symbols-outlined text-3xl">dataset</span>
                        </div>
                        <div class="flex-1 w-full">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-base font-bold text-blue-900" id="summary-title">{{ $defaultNeed->category ?? 'Abjad' }}: {{ $defaultNeed->title ?? 'Huruf A' }}</h3>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold" id="summary-badge">
                                    {{ round((($defaultNeed->current_count ?? 10) / max($defaultNeed->target_count ?? 20, 1)) * 100) }}% Terpenuhi
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="bg-white/80 p-3 rounded-xl border border-white">
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Target</p>
                                    <p class="font-bold text-slate-900 text-sm" id="summary-target">{{ $defaultNeed->target_count ?? 20 }} Video</p>
                                </div>
                                <div class="bg-white/80 p-3 rounded-xl border border-white">
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Terkumpul</p>
                                    <p class="font-bold text-blue-600 text-sm" id="summary-current">{{ $defaultNeed->current_count ?? 10 }} Video</p>
                                </div>
                                <div class="bg-white/80 p-3 rounded-xl border border-white">
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Sisa</p>
                                    <p class="font-bold text-slate-900 text-sm" id="summary-remaining">{{ max(0, ($defaultNeed->target_count ?? 20) - ($defaultNeed->current_count ?? 10)) }} Video</p>
                                </div>
                            </div>
                            <div class="w-full h-2 bg-blue-200/60 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" id="summary-progress-bar" style="width: {{ round((($defaultNeed->current_count ?? 10) / max($defaultNeed->target_count ?? 20, 1)) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Layout: Two Columns -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Upload Form -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                            <form action="{{ route('contributor.dataset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="uploadFormGeneral">
                                @csrf
                                <input type="hidden" name="dataset_need_id" id="form-need-id" value="{{ $defaultNeed->id ?? 1 }}">
                                <input type="hidden" name="title" id="form-title" value="Peragaan {{ $defaultNeed->title ?? 'Huruf A' }}">
                                <input type="hidden" name="sign_label" id="form-sign-label" value="{{ $defaultNeed->title ?? 'Huruf A' }}">
                                <input type="hidden" name="category" id="form-category" value="{{ $defaultNeed->category ?? 'Abjad SIBI' }}">

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Label Dataset Selected</label>
                                    <input class="w-full h-12 px-4 bg-slate-100/80 border border-slate-200 rounded-xl text-slate-800 font-bold text-sm cursor-not-allowed" readonly type="text" id="selected-label-input" value="{{ $defaultNeed->title ?? 'Huruf A' }}">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi (Opsional)</label>
                                    <textarea name="description" class="w-full p-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-sm transition resize-none" placeholder="Tambahkan deskripsi singkat mengenai video yang diunggah (opsional)." rows="4"></textarea>
                                </div>

                                <!-- Upload Area -->
                                <div class="relative group" id="dropZoneGeneral">
                                    <div class="border-2 border-dashed border-slate-300 rounded-3xl p-8 flex flex-col items-center justify-center gap-4 bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-600 transition cursor-pointer">
                                        <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                            <span class="material-symbols-outlined text-4xl">cloud_upload</span>
                                        </div>
                                        <div class="text-center">
                                            <p class="font-bold text-slate-800 text-base mb-1">Seret dan letakkan file video di sini</p>
                                            <p class="text-xs text-slate-500 mb-4">atau</p>
                                            <button class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-xs shadow-md pointer-events-none" type="button">Pilih Video</button>
                                            <p class="text-[11px] text-slate-400 mt-4">Format didukung: MP4, MOV, AVI (Maks 100MB)</p>
                                        </div>
                                        <input accept="video/*" class="absolute inset-0 opacity-0 cursor-pointer" type="file" name="dataset_file" id="file-input-general" required onchange="handleFileSelectGeneral(this)">
                                    </div>
                                </div>

                                <!-- File Selected Preview State -->
                                <div class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-4 items-center gap-4 group" id="file-preview-general">
                                    <div class="w-20 h-16 bg-slate-900 rounded-xl flex items-center justify-center text-white shrink-0">
                                        <span class="material-symbols-outlined text-3xl">play_circle</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-slate-900 text-sm truncate mb-1" id="file-name-general">video_sample.mp4</h4>
                                        <p class="text-xs text-slate-500" id="file-size-general">12.4 MB</p>
                                    </div>
                                    <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition" type="button" onclick="removeSelectedFileGeneral()">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <div class="flex items-start space-x-3 bg-blue-50/50 p-3.5 rounded-2xl border border-blue-100">
                                        <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" id="confirm-guidelines-general" type="checkbox" required>
                                        <label class="text-xs font-semibold text-slate-700 cursor-pointer" for="confirm-guidelines-general">Saya telah memastikan video mengikuti panduan perekaman yang ditentukan.</label>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                                    <button class="flex-1 min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2" type="submit">
                                        <span class="material-symbols-outlined text-sm">publish</span>
                                        Unggah Dataset
                                    </button>
                                    <a href="{{ route('contributor.dashboard') }}" class="px-6 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center">
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Right Column: Reusable Guidelines Sidebar -->
                    <div class="lg:col-span-4">
                        <x-video-guidelines />
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function updateSelectedNeedSummary(selectEl) {
            const option = selectEl.options[selectEl.selectedIndex];
            if (!option) return;

            const id = option.value;
            const title = option.getAttribute('data-title');
            const target = parseInt(option.getAttribute('data-target') || 20);
            const current = parseInt(option.getAttribute('data-current') || 0);
            const category = option.getAttribute('data-category') || 'Abjad SIBI';

            const remaining = Math.max(0, target - current);
            let pct = Math.round((current / Math.max(target, 1)) * 100);
            if (pct > 100) pct = 100;

            document.getElementById('selected-label-input').value = title;
            document.getElementById('form-need-id').value = id;
            document.getElementById('form-title').value = 'Peragaan ' + title;
            document.getElementById('form-sign-label').value = title;
            document.getElementById('form-category').value = category;

            document.getElementById('summary-title').innerText = category + ': ' + title;
            document.getElementById('summary-badge').innerText = pct + '% Terpenuhi';
            document.getElementById('summary-target').innerText = target + ' Video';
            document.getElementById('summary-current').innerText = current + ' Video';
            document.getElementById('summary-remaining').innerText = remaining + ' Video';
            document.getElementById('summary-progress-bar').style.width = pct + '%';
        }

        function handleFileSelectNeed(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('file-name-need').innerText = file.name;
                document.getElementById('file-size-need').innerText = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                document.getElementById('file-preview-need').classList.remove('hidden');
                document.getElementById('file-preview-need').classList.add('flex');
            }
        }

        function removeSelectedFileNeed() {
            const input = document.getElementById('file-input-need');
            if (input) input.value = '';
            document.getElementById('file-preview-need').classList.add('hidden');
            document.getElementById('file-preview-need').classList.remove('flex');
        }

        function handleFileSelectGeneral(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('file-name-general').innerText = file.name;
                document.getElementById('file-size-general').innerText = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                document.getElementById('file-preview-general').classList.remove('hidden');
                document.getElementById('file-preview-general').classList.add('flex');
            }
        }

        function removeSelectedFileGeneral() {
            const input = document.getElementById('file-input-general');
            if (input) input.value = '';
            document.getElementById('file-preview-general').classList.add('hidden');
            document.getElementById('file-preview-general').classList.remove('flex');
        }
    </script>
</x-app-layout>
