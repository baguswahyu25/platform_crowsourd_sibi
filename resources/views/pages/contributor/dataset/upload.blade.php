<x-app-layout title="Unggah Dataset - SIBI Dataset Platform" role="contributor">
    @php
        $isLockedFlow = isset($selectedNeed) && $selectedNeed !== null;
        $initialCategory = request()->query('category') ?? ($selectedNeed->category_id ?? 'alphabet');
    @endphp

    <div class="max-w-6xl mx-auto space-y-8" x-data="{
        isLocked: {{ $isLockedFlow ? 'true' : 'false' }},
        activeCategory: '{{ $initialCategory }}',
        activeSubcategory: '{{ $isLockedFlow ? ($selectedNeed->subcategory ?? 'letters') : 'letters' }}',
        selectedNeedId: '{{ $selectedNeed->id ?? '' }}',
        
        // Contributor-Generated Inputs (Sentence & Short Story)
        sentenceContent: '{{ old('sentence_content', '') }}',
        storyTitle: '{{ old('title', '') }}',
        storyLabel: '{{ old('sign_label', '') }}',
        storyContent: '{{ old('story_content', '') }}',
        descriptionText: '{{ old('description', '') }}',

        // Master Needs Data from Backend
        needs: {{ Js::from($needs) }},

        get filteredNeeds() {
            return this.needs.filter(n => {
                if (n.status === 'inactive') return false;
                if (this.activeCategory === 'alphabet') {
                    return n.category_id === 'alphabet' && 
                           (this.activeSubcategory === 'letters' ? (n.subcategory === 'letters' || n.title.startsWith('Huruf')) : (n.subcategory === 'numbers' || n.title.startsWith('Angka')));
                } else if (this.activeCategory === 'word') {
                    return n.category_id === 'word';
                } else if (this.activeCategory === 'idiom_expression') {
                    return n.category_id === 'idiom_expression';
                }
                return false;
            });
        },

        selectedNeed: null,

        selectCategory(cat) {
            if (this.isLocked) return;
            this.activeCategory = cat;
            if (cat !== 'sentence' && cat !== 'short_story') {
                this.updateSelectedNeed();
            }
        },

        selectSubcategory(sub) {
            if (this.isLocked) return;
            this.activeSubcategory = sub;
            this.updateSelectedNeed();
        },

        updateSelectedNeed() {
            if (this.isLocked) return;
            const list = this.filteredNeeds.filter(n => n.status !== 'fulfilled' && n.current_count < n.target_count);
            if (list.length > 0) {
                this.selectedNeed = list[0];
                this.selectedNeedId = list[0].id;
            } else if (this.filteredNeeds.length > 0) {
                this.selectedNeed = this.filteredNeeds[0];
                this.selectedNeedId = this.filteredNeeds[0].id;
            } else {
                this.selectedNeed = null;
                this.selectedNeedId = '';
            }
        },

        onNeedChange(id) {
            if (this.isLocked) return;
            this.selectedNeed = this.needs.find(n => n.id == id) || null;
            this.selectedNeedId = id;
        },

        init() {
            @if($isLockedFlow)
                const selId = {{ $selectedNeed->id }};
                const found = this.needs.find(n => n.id == selId);
                if (found) {
                    this.selectedNeed = found;
                    this.selectedNeedId = found.id;
                    if (found.category_id) this.activeCategory = found.category_id;
                    if (found.subcategory) this.activeSubcategory = found.subcategory;
                }
            @else
                if (this.activeCategory !== 'sentence' && this.activeCategory !== 'short_story') {
                    this.updateSelectedNeed();
                }
            @endif
        }
    }">

        <!-- Error Alert Banners -->
        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold flex items-center gap-3 shadow-xs">
                <span class="material-symbols-outlined text-rose-600 text-xl">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-semibold space-y-1 shadow-xs">
                <div class="flex items-center gap-2 font-bold text-rose-900">
                    <span class="material-symbols-outlined text-rose-600 text-xl">warning</span>
                    <span>Perhatian:</span>
                </div>
                <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- FLOW B: UPLOAD DATASET FROM DATASET REQUIREMENTS (SPECIFIC & LOCKED FLOW) -->
        <!-- ========================================================================= -->
        @if($isLockedFlow)
            <div>
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-3 py-0.5 bg-blue-100 text-blue-800 text-[10px] font-extrabold rounded-full uppercase tracking-wider">Flow B: Target Terkunci</span>
                        <span class="text-xs text-slate-400">• Dari Halaman Kebutuhan Dataset</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Unggah Dataset Kebutuhan Khusus</h1>
                    <p class="text-sm text-slate-600 max-w-2xl leading-relaxed">
                        Anda memasuki alur pengunggahan khusus untuk label yang dipilih dari Kebutuhan Dataset. Kategori dan label telah terkunci otomatis secara terikat.
                    </p>
                </div>

                @php
                    $isFulfilled = ($selectedNeed->status->value === 'fulfilled' || $selectedNeed->current_count >= $selectedNeed->target_count);
                    $percentage = round(($selectedNeed->current_count / max($selectedNeed->target_count, 1)) * 100);
                    if ($percentage > 100) $percentage = 100;
                    $remaining = max(0, $selectedNeed->target_count - $selectedNeed->current_count);
                @endphp

                @if($isFulfilled)
                    <!-- ALERT KEBUTUHAN SUDAH TERPENUHI -->
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

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                            <a href="{{ route('contributor.kebutuhan.index') }}" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 min-h-[44px]">
                                <span class="material-symbols-outlined text-base">arrow_back</span>
                                Kembali ke Kebutuhan Dataset
                            </a>
                            <a href="{{ route('contributor.dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center min-h-[44px]">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <div class="lg:col-span-8 space-y-6">
                            <!-- LOCKED READ-ONLY INFORMATION CARD -->
                            <div class="bg-blue-50/80 border-2 border-blue-200 rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm">
                                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-blue-200/80 pb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                                            <span class="material-symbols-outlined text-xl">lock</span>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-extrabold text-blue-900">Kategori & Label Dataset Terkunci (Read-Only)</h3>
                                            <p class="text-[11px] text-blue-700">Nilai ini diambil secara otomatis dari Kebutuhan Dataset pilihan Anda.</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 font-extrabold text-[11px] rounded-full">
                                        Terkumpul: {{ $selectedNeed->current_count }}/{{ $selectedNeed->target_count }} Video
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-white p-4 rounded-2xl border border-blue-100">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">Selected Category</span>
                                        <p class="text-base font-black text-slate-900">{{ $selectedNeed->category }}</p>
                                    </div>
                                    <div class="bg-white p-4 rounded-2xl border border-blue-100">
                                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1">Selected Label</span>
                                        <p class="text-base font-black text-blue-600">{{ $selectedNeed->title }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Form for Locked Flow -->
                            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
                                <form action="{{ route('contributor.dataset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="uploadFormLocked">
                                    @csrf
                                    <input type="hidden" name="dataset_need_id" value="{{ $selectedNeed->id }}">
                                    <input type="hidden" name="category" value="{{ $selectedNeed->category }}">
                                    <input type="hidden" name="category_id" value="{{ $selectedNeed->category_id }}">
                                    <input type="hidden" name="title" value="Peragaan {{ $selectedNeed->title }}">
                                    <input type="hidden" name="sign_label" value="{{ $selectedNeed->title }}">

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Tambahan (Opsional)</label>
                                        <textarea name="description" x-model="descriptionText" class="w-full p-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-sm transition resize-none" placeholder="Tambahkan deskripsi singkat mengenai video peragaan..." rows="3"></textarea>
                                    </div>

                                    <!-- Upload Area Video -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">File Video Peragaan (Maksimal 3 MB)</label>
                                        <div class="border-2 border-dashed border-slate-300 rounded-3xl p-8 flex flex-col items-center justify-center gap-4 bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-600 transition cursor-pointer relative group">
                                            <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                                <span class="material-symbols-outlined text-4xl">cloud_upload</span>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-bold text-slate-800 text-base mb-1">Seret dan letakkan file video di sini</p>
                                                <p class="text-xs text-slate-500 mb-4">atau</p>
                                                <button class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-xs shadow-md pointer-events-none" type="button">Pilih Video</button>
                                                <p class="text-[11px] text-slate-400 mt-4">Format didukung: MP4, MOV, AVI (Maksimal 3 MB)</p>
                                            </div>
                                            <input accept="video/*" class="absolute inset-0 opacity-0 cursor-pointer" type="file" name="dataset_file" id="file-input-locked" required onchange="handleFileSelectLocked(this)">
                                        </div>
                                    </div>

                                    <div class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-4 items-center gap-4 group" id="file-preview-locked">
                                        <div class="w-20 h-16 bg-slate-900 rounded-xl flex items-center justify-center text-white shrink-0">
                                            <span class="material-symbols-outlined text-3xl">play_circle</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-900 text-sm truncate mb-1" id="file-name-locked">video_sample.mp4</h4>
                                            <p class="text-xs text-slate-500" id="file-size-locked">2.4 MB</p>
                                        </div>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition" type="button" onclick="removeSelectedFileLocked()">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>

                                    <div class="space-y-3 pt-2">
                                        <div class="flex items-start space-x-3 bg-blue-50/50 p-3.5 rounded-2xl border border-blue-100">
                                            <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" id="confirm-guidelines-locked" type="checkbox" required>
                                            <label class="text-xs font-semibold text-slate-700 cursor-pointer" for="confirm-guidelines-locked">Saya telah memastikan video mengikuti panduan perekaman SIBI.</label>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                                        <a href="{{ route('contributor.kebutuhan.index') }}" class="px-6 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                                            Kembali ke Kebutuhan Dataset
                                        </a>
                                        <button class="flex-1 min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2" type="submit">
                                            <span class="material-symbols-outlined text-sm">publish</span>
                                            Unggah Dataset
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="lg:col-span-4">
                            <x-video-guidelines />
                        </div>
                    </div>
                @endif
            </div>
        @else
            <!-- ========================================================================= -->
            <!-- FLOW A: UPLOAD DATASET FROM CONTRIBUTOR PANEL (FULL SELECTION CONTROL) -->
            <!-- ========================================================================= -->
            <div>
                <!-- Hero Heading -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-3 py-0.5 bg-purple-100 text-purple-800 text-[10px] font-extrabold rounded-full uppercase tracking-wider">Flow A: Kontrol Pilihan Bebas</span>
                        <span class="text-xs text-slate-400">• Dari Menu Panel Kontributor</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Upload Dataset Umum</h1>
                    <p class="text-sm text-slate-600 max-w-2xl leading-relaxed">
                        Pilih kategori dataset (Abjad, Kata, Idiom/Ungkapan, Kalimat, atau Cerita Pendek).
                    </p>
                </div>

                <!-- 5 MAIN CATEGORY SELECTION TABS -->
                <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap gap-2 mb-6">
                    <button type="button" @click="selectCategory('alphabet')" :class="activeCategory === 'alphabet' ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="flex-1 min-w-[130px] px-3 py-3 rounded-xl font-extrabold text-xs transition flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">abc</span>
                        A. Abjad
                    </button>
                    <button type="button" @click="selectCategory('word')" :class="activeCategory === 'word' ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="flex-1 min-w-[130px] px-3 py-3 rounded-xl font-extrabold text-xs transition flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">spellcheck</span>
                        B. Kata
                    </button>
                    <button type="button" @click="selectCategory('idiom_expression')" :class="activeCategory === 'idiom_expression' ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="flex-1 min-w-[130px] px-3 py-3 rounded-xl font-extrabold text-xs transition flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">auto_awesome</span>
                        C. Idiom/Ungkapan
                    </button>
                    <button type="button" @click="selectCategory('sentence')" :class="activeCategory === 'sentence' ? 'bg-teal-600 text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="flex-1 min-w-[130px] px-3 py-3 rounded-xl font-extrabold text-xs transition flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">notes</span>
                        D. Kalimat
                    </button>
                    <button type="button" @click="selectCategory('short_story')" :class="activeCategory === 'short_story' ? 'bg-purple-600 text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="flex-1 min-w-[130px] px-3 py-3 rounded-xl font-extrabold text-xs transition flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-base">auto_stories</span>
                        E. Cerita Pendek
                    </button>
                </div>

                <!-- PREDEFINED CATEGORIES FORM (Alphabet, Word, Idiom/Expression) -->
                <div x-show="activeCategory !== 'sentence' && activeCategory !== 'short_story'" class="space-y-6">
                    <!-- Subcategory Selector (Alphabet: Letters A-Z vs Numbers 1-10) -->
                    <div x-show="activeCategory === 'alphabet'" class="bg-blue-50/60 p-4 rounded-2xl border border-blue-100 flex items-center gap-3">
                        <span class="text-xs font-bold text-blue-900 uppercase tracking-wider shrink-0">Subkategori Abjad:</span>
                        <div class="flex gap-2">
                            <button type="button" @click="selectSubcategory('letters')" :class="activeSubcategory === 'letters' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition">
                                1. Huruf (A - Z)
                            </button>
                            <button type="button" @click="selectSubcategory('numbers')" :class="activeSubcategory === 'numbers' ? 'bg-blue-600 text-white shadow-xs' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-4 py-1.5 rounded-lg text-xs font-bold transition">
                                2. Angka (1 - 10)
                            </button>
                        </div>
                    </div>

                    <!-- Label Selector -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Label Predefined Kebutuhan Dataset</label>
                        <div class="relative">
                            <select :value="selectedNeedId" @change="onNeedChange($event.target.value)" class="w-full h-12 pl-4 pr-10 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-slate-800 font-semibold text-sm transition shadow-xs appearance-none cursor-pointer">
                                <template x-for="item in filteredNeeds" :key="item.id">
                                    <option :value="item.id" :disabled="item.status === 'fulfilled' || item.current_count >= item.target_count">
                                        <span x-text="(item.status === 'fulfilled' || item.current_count >= item.target_count) ? '[TERPENUHI] ' : ''"></span>
                                        <span x-text="item.title"></span> (<span x-text="item.current_count"></span>/<span x-text="item.target_count"></span> Video)
                                    </option>
                                </template>
                            </select>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 pointer-events-none">expand_more</span>
                        </div>
                    </div>

                    <!-- Selected Need Summary Card -->
                    <template x-if="selectedNeed">
                        <div class="bg-blue-50/80 border border-blue-200/80 rounded-3xl p-6 flex flex-col md:flex-row items-center gap-6">
                            <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-600/20">
                                <span class="material-symbols-outlined text-3xl">dataset</span>
                            </div>
                            <div class="flex-1 w-full">
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-base font-bold text-blue-900" x-text="selectedNeed.category + ': ' + selectedNeed.title"></h3>
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold" x-text="Math.round((selectedNeed.current_count / Math.max(selectedNeed.target_count, 1)) * 100) + '% Terpenuhi'"></span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 mb-4">
                                    <div class="bg-white/80 p-3 rounded-xl border border-white">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Target</p>
                                        <p class="font-bold text-slate-900 text-sm" x-text="selectedNeed.target_count + ' Video'"></p>
                                    </div>
                                    <div class="bg-white/80 p-3 rounded-xl border border-white">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Terkumpul</p>
                                        <p class="font-bold text-blue-600 text-sm" x-text="selectedNeed.current_count + ' Video'"></p>
                                    </div>
                                    <div class="bg-white/80 p-3 rounded-xl border border-white">
                                        <p class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1">Sisa Kuota</p>
                                        <p class="font-bold text-slate-900 text-sm" x-text="Math.max(0, selectedNeed.target_count - selectedNeed.current_count) + ' Video'"></p>
                                    </div>
                                </div>
                                <div class="w-full h-2 bg-blue-200/60 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full transition-all duration-1000" :style="'width: ' + Math.min(100, Math.round((selectedNeed.current_count / Math.max(selectedNeed.target_count, 1)) * 100)) + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Form Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <div class="lg:col-span-8 space-y-6">
                            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                                <form action="{{ route('contributor.dataset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="uploadFormGeneral">
                                    @csrf
                                    <input type="hidden" name="dataset_need_id" :value="selectedNeedId">
                                    <input type="hidden" name="category" :value="activeCategory">
                                    <input type="hidden" name="subcategory" :value="activeSubcategory">
                                    <input type="hidden" name="title" :value="selectedNeed ? 'Peragaan ' + selectedNeed.title : 'Peragaan SIBI'">
                                    <input type="hidden" name="sign_label" :value="selectedNeed ? selectedNeed.title : 'Label SIBI'">

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Deskripsi Tambahan (Opsional)</label>
                                        <textarea name="description" x-model="descriptionText" class="w-full p-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 outline-none text-sm transition resize-none" placeholder="Tambahkan deskripsi singkat mengenai video peragaan..." rows="3"></textarea>
                                    </div>

                                    <!-- Upload Area Video -->
                                    <div class="relative group">
                                        <div class="border-2 border-dashed border-slate-300 rounded-3xl p-8 flex flex-col items-center justify-center gap-4 bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-600 transition cursor-pointer">
                                            <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                                <span class="material-symbols-outlined text-4xl">cloud_upload</span>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-bold text-slate-800 text-base mb-1">Seret dan letakkan file video di sini</p>
                                                <p class="text-xs text-slate-500 mb-4">atau</p>
                                                <button class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-xs shadow-md pointer-events-none" type="button">Pilih Video</button>
                                                <p class="text-[11px] text-slate-400 mt-4">Format didukung: MP4, MOV, AVI (Maksimal 3 MB)</p>
                                            </div>
                                            <input accept="video/*" class="absolute inset-0 opacity-0 cursor-pointer" type="file" name="dataset_file" id="file-input-general" required onchange="handleFileSelectGeneral(this)">
                                        </div>
                                    </div>

                                    <div class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-4 items-center gap-4 group" id="file-preview-general">
                                        <div class="w-20 h-16 bg-slate-900 rounded-xl flex items-center justify-center text-white shrink-0">
                                            <span class="material-symbols-outlined text-3xl">play_circle</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-900 text-sm truncate mb-1" id="file-name-general">video_sample.mp4</h4>
                                            <p class="text-xs text-slate-500" id="file-size-general">2.4 MB</p>
                                        </div>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition" type="button" onclick="removeSelectedFileGeneral()">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>

                                    <div class="space-y-3 pt-2">
                                        <div class="flex items-start space-x-3 bg-blue-50/50 p-3.5 rounded-2xl border border-blue-100">
                                            <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" id="confirm-guidelines-general" type="checkbox" required>
                                            <label class="text-xs font-semibold text-slate-700 cursor-pointer" for="confirm-guidelines-general">Saya telah memastikan video mengikuti panduan perekaman SIBI.</label>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                                        <a href="{{ route('contributor.dashboard') }}" class="px-6 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                                            Kembali ke Dashboard
                                        </a>
                                        <button class="flex-1 min-h-[44px] bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2" type="submit">
                                            <span class="material-symbols-outlined text-sm">publish</span>
                                            Unggah Dataset
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="lg:col-span-4">
                            <x-video-guidelines />
                        </div>
                    </div>
                </div>

                <!-- D. SENTENCE CATEGORY FORM (Contributor-Generated Sentence Content) -->
                <div x-show="activeCategory === 'sentence'" class="space-y-6">
                    <div class="bg-teal-50 border border-teal-200 rounded-3xl p-6 flex items-start gap-4">
                        <div class="w-12 h-12 bg-teal-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-teal-600/20">
                            <span class="material-symbols-outlined text-2xl">notes</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-extrabold text-teal-900">Kategori D. Kalimat (Sentence)</h3>
                            <p class="text-xs text-teal-800 leading-relaxed">
                                Kategori ini tidak menggunakan label predefined. Buat dan tuliskan kalimat Bahasa Indonesia Anda sendiri yang diperagakan, lalu unggah video SIBI.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <div class="lg:col-span-8 space-y-6">
                            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                                <form action="{{ route('contributor.dataset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="uploadFormSentence">
                                    @csrf
                                    <input type="hidden" name="category" value="sentence">
                                    <input type="hidden" name="category_id" value="sentence">

                                    <!-- Sentence Label / Content Input -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Kalimat Bahasa Indonesia (Sentence Label / Content) <span class="text-rose-500">*</span>
                                        </label>
                                        <textarea name="sentence_content" x-model="sentenceContent" required rows="4" placeholder="Tuliskan kalimat lengkap dalam Bahasa Indonesia yang Anda peragakan di dalam video (Contoh: Saya sedang belajar Bahasa Isyarat Indonesia.)..." class="w-full p-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-teal-600 focus:border-teal-600 outline-none text-sm transition resize-none"></textarea>
                                        <p class="text-[11px] text-slate-400">Kalimat ini akan menjadi label acuan teks resmi untuk sampel video SIBI Anda.</p>
                                    </div>

                                    <!-- Video Upload -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Video Peragaan SIBI Kalimat (Maksimal 3 MB) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="border-2 border-dashed border-slate-300 rounded-3xl p-8 flex flex-col items-center justify-center gap-4 bg-slate-50/50 hover:bg-teal-50/50 hover:border-teal-600 transition cursor-pointer relative group">
                                            <div class="w-16 h-16 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                                <span class="material-symbols-outlined text-4xl">video_call</span>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-bold text-slate-800 text-base mb-1">Seret dan letakkan video kalimat di sini</p>
                                                <p class="text-xs text-slate-500 mb-4">atau</p>
                                                <button class="px-6 py-2.5 bg-teal-600 text-white rounded-xl font-bold text-xs shadow-md pointer-events-none" type="button">Pilih / Rekam Video</button>
                                                <p class="text-[11px] text-slate-400 mt-4">Format: MP4, MOV, AVI (Maksimal 3 MB)</p>
                                            </div>
                                            <input accept="video/*" class="absolute inset-0 opacity-0 cursor-pointer" type="file" name="dataset_file" id="file-input-sentence" required onchange="handleFileSelectSentence(this)">
                                        </div>
                                    </div>

                                    <div class="hidden bg-teal-50 border border-teal-200 rounded-2xl p-4 items-center gap-4 group" id="file-preview-sentence">
                                        <div class="w-20 h-16 bg-slate-900 rounded-xl flex items-center justify-center text-white shrink-0">
                                            <span class="material-symbols-outlined text-3xl">play_circle</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-900 text-sm truncate mb-1" id="file-name-sentence">sentence_video.mp4</h4>
                                            <p class="text-xs text-slate-500" id="file-size-sentence">2.8 MB</p>
                                        </div>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition" type="button" onclick="removeSelectedFileSentence()">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>

                                    <div class="space-y-3 pt-2">
                                        <div class="flex items-start space-x-3 bg-teal-50/50 p-3.5 rounded-2xl border border-teal-100">
                                            <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 cursor-pointer" id="confirm-guidelines-sentence" type="checkbox" required>
                                            <label class="text-xs font-semibold text-slate-700 cursor-pointer" for="confirm-guidelines-sentence">Saya menyatakan peragaan kalimat SIBI dalam video sesuai dengan kalimat teks yang saya tulis.</label>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                                        <a href="{{ route('contributor.dashboard') }}" class="px-6 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                                            Kembali ke Dashboard
                                        </a>
                                        <button class="flex-1 min-h-[44px] bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2" type="submit">
                                            <span class="material-symbols-outlined text-sm">publish</span>
                                            Kirim Dataset Kalimat
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="lg:col-span-4">
                            <x-video-guidelines />
                        </div>
                    </div>
                </div>

                <!-- E. SHORT STORY CATEGORY FORM (Contributor-Generated Metadata) -->
                <div x-show="activeCategory === 'short_story'" class="space-y-6">
                    <div class="bg-purple-50 border border-purple-200 rounded-3xl p-6 flex items-start gap-4">
                        <div class="w-12 h-12 bg-purple-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-purple-600/20">
                            <span class="material-symbols-outlined text-2xl">auto_stories</span>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-base font-extrabold text-purple-900">Kategori E. Cerita Pendek (Short Story)</h3>
                            <p class="text-xs text-purple-700 leading-relaxed">
                                Kategori ini tidak menggunakan label predefined. Buat judul cerita pendek Anda sendiri, tuliskan label identifikasi cerita, masukkan naskah/transkrip lengkap cerita, dan unggah video peragaan isyarat SIBI.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                        <div class="lg:col-span-8 space-y-6">
                            <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                                <form action="{{ route('contributor.dataset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="uploadFormShortStory">
                                    @csrf
                                    <input type="hidden" name="category" value="short_story">
                                    <input type="hidden" name="category_id" value="short_story">

                                    <!-- 1. Short Story Title -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            1. Judul Cerita Pendek (Short Story Title) <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="title" x-model="storyTitle" required placeholder="Contoh: Pengalaman Pertama di Kampus" class="w-full h-12 px-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 text-slate-800 font-semibold text-sm transition shadow-xs">
                                        <p class="text-[11px] text-slate-400">Buat judul cerita pendek karya Anda sendiri.</p>
                                    </div>

                                    <!-- 2. Short Story Label -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            2. Label / Identifikasi Cerita (Short Story Label) <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="text" name="sign_label" x-model="storyLabel" required placeholder="Contoh: pengalaman-kampus atau kegiatan-harian" class="w-full h-12 px-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 text-slate-800 font-semibold text-sm transition shadow-xs">
                                        <p class="text-[11px] text-slate-400">Label singkat sebagai pengelompokan tema cerita pendek Anda.</p>
                                    </div>

                                    <!-- 3. Short Story Content / Transcript -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            3. Naskah / Transkrip Cerita Pendek (Story Content / Transcript) <span class="text-rose-500">*</span>
                                        </label>
                                        <textarea name="story_content" x-model="storyContent" required rows="6" placeholder="Tuliskan naskah atau isi cerita lengkap dalam Bahasa Indonesia yang Anda peragakan di dalam video..." class="w-full p-4 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 outline-none text-sm transition resize-none"></textarea>
                                        <p class="text-[11px] text-slate-400">Transkrip teks cerita pendek ini akan menjadi acuan peragaan isyarat SIBI video Anda.</p>
                                    </div>

                                    <!-- 4. Video Upload -->
                                    <div class="space-y-2">
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            4. Video Peragaan SIBI Cerita Pendek (Maksimal 3 MB) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="border-2 border-dashed border-slate-300 rounded-3xl p-8 flex flex-col items-center justify-center gap-4 bg-slate-50/50 hover:bg-purple-50/50 hover:border-purple-600 transition cursor-pointer relative group">
                                            <div class="w-16 h-16 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center mb-1 group-hover:scale-110 transition">
                                                <span class="material-symbols-outlined text-4xl">video_call</span>
                                            </div>
                                            <div class="text-center">
                                                <p class="font-bold text-slate-800 text-base mb-1">Seret dan letakkan video cerita pendek di sini</p>
                                                <p class="text-xs text-slate-500 mb-4">atau</p>
                                                <button class="px-6 py-2.5 bg-purple-600 text-white rounded-xl font-bold text-xs shadow-md pointer-events-none" type="button">Pilih / Rekam Video</button>
                                                <p class="text-[11px] text-slate-400 mt-4">Format: MP4, MOV, AVI (Maksimal 3 MB)</p>
                                            </div>
                                            <input accept="video/*" class="absolute inset-0 opacity-0 cursor-pointer" type="file" name="dataset_file" id="file-input-story" required onchange="handleFileSelectStory(this)">
                                        </div>
                                    </div>

                                    <div class="hidden bg-purple-50 border border-purple-200 rounded-2xl p-4 items-center gap-4 group" id="file-preview-story">
                                        <div class="w-20 h-16 bg-slate-900 rounded-xl flex items-center justify-center text-white shrink-0">
                                            <span class="material-symbols-outlined text-3xl">play_circle</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-900 text-sm truncate mb-1" id="file-name-story">short_story_video.mp4</h4>
                                            <p class="text-xs text-slate-500" id="file-size-story">2.8 MB</p>
                                        </div>
                                        <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition" type="button" onclick="removeSelectedFileStory()">
                                            <span class="material-symbols-outlined">close</span>
                                        </button>
                                    </div>

                                    <div class="space-y-3 pt-2">
                                        <div class="flex items-start space-x-3 bg-purple-50/50 p-3.5 rounded-2xl border border-purple-100">
                                            <input class="mt-0.5 h-4 w-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500 cursor-pointer" id="confirm-guidelines-story" type="checkbox" required>
                                            <label class="text-xs font-semibold text-slate-700 cursor-pointer" for="confirm-guidelines-story">Saya menyatakan naskah cerita pendek dan video peragaan SIBI yang diunggah adalah karya milik sendiri.</label>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                                        <a href="{{ route('contributor.dashboard') }}" class="px-6 min-h-[44px] bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-2">
                                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                                            Kembali ke Dashboard
                                        </a>
                                        <button class="flex-1 min-h-[44px] bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2" type="submit">
                                            <span class="material-symbols-outlined text-sm">publish</span>
                                            Kirim Dataset Cerita Pendek
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="lg:col-span-4">
                            <x-video-guidelines />
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function handleFileSelectLocked(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('file-name-locked').innerText = file.name;
                document.getElementById('file-size-locked').innerText = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                document.getElementById('file-preview-locked').classList.remove('hidden');
                document.getElementById('file-preview-locked').classList.add('flex');
            }
        }

        function removeSelectedFileLocked() {
            const input = document.getElementById('file-input-locked');
            if (input) input.value = '';
            document.getElementById('file-preview-locked').classList.add('hidden');
            document.getElementById('file-preview-locked').classList.remove('flex');
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

        function handleFileSelectSentence(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('file-name-sentence').innerText = file.name;
                document.getElementById('file-size-sentence').innerText = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                document.getElementById('file-preview-sentence').classList.remove('hidden');
                document.getElementById('file-preview-sentence').classList.add('flex');
            }
        }

        function removeSelectedFileSentence() {
            const input = document.getElementById('file-input-sentence');
            if (input) input.value = '';
            document.getElementById('file-preview-sentence').classList.add('hidden');
            document.getElementById('file-preview-sentence').classList.remove('flex');
        }

        function handleFileSelectStory(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                document.getElementById('file-name-story').innerText = file.name;
                document.getElementById('file-size-story').innerText = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
                document.getElementById('file-preview-story').classList.remove('hidden');
                document.getElementById('file-preview-story').classList.add('flex');
            }
        }

        function removeSelectedFileStory() {
            const input = document.getElementById('file-input-story');
            if (input) input.value = '';
            document.getElementById('file-preview-story').classList.add('hidden');
            document.getElementById('file-preview-story').classList.remove('flex');
        }
    </script>
</x-app-layout>
