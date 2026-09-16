<x-app-layout title="Kebutuhan Dataset - SIBI Dataset Platform" role="contributor">
    <div class="space-y-8" x-data="{
        activeCat: 'all',
        activeWordGroup: 'all',
        searchTerm: '',

        setCat(cat) {
            this.activeCat = cat;
            this.activeWordGroup = 'all';
        }
    }">
        <!-- Header Section -->
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Kebutuhan Dataset SIBI</h1>
            <p class="text-sm text-slate-600 max-w-3xl leading-relaxed">
                Daftar kebutuhan dataset video Bahasa Isyarat Indonesia (SIBI). Pilih kategori utama (Abjad, Kata, Kata Imbuhan, Kalimat, atau Cerita Pendek) untuk memfilter kebutuhan pengumpulan video.
            </p>
        </div>

        <!-- 5 MAIN CATEGORY FILTER TABS -->
        <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap gap-2 overflow-x-auto">
            <button type="button" @click="setCat('all')" :class="activeCat === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl font-extrabold text-xs transition">
                Semua Kategori
            </button>
            <button type="button" @click="setCat('alphabet')" :class="activeCat === 'alphabet' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl font-extrabold text-xs transition">
                🔤 A. Abjad (Huruf A-Z & Angka 1-10)
            </button>
            <button type="button" @click="setCat('word')" :class="activeCat === 'word' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl font-extrabold text-xs transition">
                💬 B. Kata (Words)
            </button>
            <button type="button" @click="setCat('kata_imbuhan')" :class="activeCat === 'kata_imbuhan' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl font-extrabold text-xs transition">
                ✨ C. Kata Imbuhan
            </button>
            <button type="button" @click="setCat('sentence')" :class="activeCat === 'sentence' ? 'bg-teal-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl font-extrabold text-xs transition">
                📝 D. Kalimat
            </button>
            <button type="button" @click="setCat('short_story')" :class="activeCat === 'short_story' ? 'bg-purple-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'" class="px-4 py-2.5 rounded-xl font-extrabold text-xs transition">
                📚 E. Cerita Pendek
            </button>
        </div>

        <!-- Subcategory Filter Pills for WORD Category -->
        <div x-show="activeCat === 'word'" class="bg-blue-50/70 p-3.5 rounded-2xl border border-blue-100 flex flex-wrap items-center gap-2 text-xs">
            <span class="font-bold text-blue-900 uppercase tracking-wider mr-2 text-[11px]">Subkategori Kata:</span>
            <button type="button" @click="activeWordGroup = 'all'" :class="activeWordGroup === 'all' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 rounded-lg transition">Semua</button>
            <button type="button" @click="activeWordGroup = 'Kata ganti diri'" :class="activeWordGroup === 'Kata ganti diri' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 rounded-lg transition">Kata ganti diri</button>
            <button type="button" @click="activeWordGroup = 'Kata kerja (kata dasar)'" :class="activeWordGroup === 'Kata kerja (kata dasar)' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 rounded-lg transition">Kata kerja (kata dasar)</button>
            <button type="button" @click="activeWordGroup = 'Kata benda'" :class="activeWordGroup === 'Kata benda' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 rounded-lg transition">Kata benda</button>
            <button type="button" @click="activeWordGroup = 'Kata sifat'" :class="activeWordGroup === 'Kata sifat' ? 'bg-blue-600 text-white font-bold' : 'bg-white text-slate-700 hover:bg-slate-100'" class="px-3 py-1 rounded-lg transition">Kata sifat</button>
        </div>

        <!-- Search Bar -->
        <div x-show="activeCat !== 'sentence' && activeCat !== 'short_story'" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative w-full sm:w-96">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400">search</span>
                <input x-model="searchTerm" class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-sm text-slate-800" placeholder="Cari label SIBI (misal: Makan, Huruf A, Selamat Pagi)..." type="text">
            </div>
        </div>

        <!-- D. SENTENCE CATEGORY INFORMATIONAL CARD -->
        <div x-show="activeCat === 'sentence'" class="bg-teal-50 border-2 border-teal-200 rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-teal-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-teal-600/20">
                    <span class="material-symbols-outlined text-3xl">notes</span>
                </div>
                <div class="space-y-1">
                    <span class="px-3 py-1 bg-teal-100 text-teal-800 font-extrabold text-xs rounded-full uppercase">Contributor-Generated Sentence</span>
                    <h2 class="text-xl font-black text-teal-900">Kategori D. Kalimat (Sentence)</h2>
                    <p class="text-xs sm:text-sm text-teal-800 leading-relaxed max-w-2xl">
                        Platform menyediakan kategori Kalimat tanpa label predefined. Kontributor dapat membuat <strong>Kalimat Bahasa Indonesia sendiri</strong> yang diperagakan, lalu mengunggah video SIBI.
                    </p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                <a href="{{ route('contributor.dataset.upload') }}?category=sentence" class="w-full sm:w-auto px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 min-h-[44px]">
                    <span class="material-symbols-outlined text-base">add_circle</span>
                    Kontribusi & Unggah Dataset Kalimat Baru
                </a>
            </div>
        </div>

        <!-- E. SHORT STORY CATEGORY INFORMATIONAL CARD -->
        <div x-show="activeCat === 'short_story'" class="bg-purple-50 border-2 border-purple-200 rounded-3xl p-6 sm:p-8 space-y-4 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-purple-600 text-white rounded-2xl flex items-center justify-center shrink-0 shadow-lg shadow-purple-600/20">
                    <span class="material-symbols-outlined text-3xl">auto_stories</span>
                </div>
                <div class="space-y-1">
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 font-extrabold text-xs rounded-full uppercase">Contributor-Generated Metadata</span>
                    <h2 class="text-xl font-black text-purple-900">Kategori E. Cerita Pendek (Short Story)</h2>
                    <p class="text-xs sm:text-sm text-purple-700 leading-relaxed max-w-2xl">
                        Platform menyediakan kategori Cerita Pendek tanpa label predefined. Kontributor secara bebas dapat membuat <strong>Judul Cerita</strong>, <strong>Label Tag Cerita</strong>, <strong>Naskah/Transkrip Cerita Lengkap</strong>, dan mengunggah video peragaan SIBI.
                    </p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-3 pt-2">
                <a href="{{ route('contributor.dataset.upload') }}?category=short_story" class="w-full sm:w-auto px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2 min-h-[44px]">
                    <span class="material-symbols-outlined text-base">video_call</span>
                    Kontribusi & Unggah Dataset Cerita Pendek Baru
                </a>
            </div>
        </div>

        <!-- GRID PREDEFINED DATASET NEEDS -->
        <div x-show="activeCat !== 'sentence' && activeCat !== 'short_story'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($needs->where('status.value', '!=', 'inactive') as $need)
                @php
                    $catId = $need->category_id ?? 'word';
                    if ($catId === 'idiom_expression') $catId = 'kata_imbuhan';
                    $subGroup = $need->subcategory ?? '';
                    $titleLower = strtolower($need->title);

                    if ($catId === 'alphabet') {
                        $icon = str_starts_with($need->title, 'Angka') ? '123' : 'spellcheck';
                        $bgIcon = str_starts_with($need->title, 'Angka') ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700';
                        $catDisplay = str_starts_with($need->title, 'Angka') ? 'Abjad (Angka)' : 'Abjad (Huruf)';
                    } elseif ($catId === 'word') {
                        $icon = 'waving_hand';
                        $bgIcon = 'bg-emerald-100 text-emerald-700';
                        $catDisplay = 'Kata';
                    } elseif ($catId === 'kata_imbuhan') {
                        $icon = 'auto_awesome';
                        $bgIcon = 'bg-indigo-100 text-indigo-700';
                        $catDisplay = 'Kata Imbuhan';
                    } else {
                        $icon = 'dataset';
                        $bgIcon = 'bg-blue-100 text-blue-700';
                        $catDisplay = 'Predefined';
                    }
                @endphp

                <div x-show="(activeCat === 'all' || activeCat === '{{ $catId }}' || (activeCat === 'kata_imbuhan' && '{{ $need->category_id }}' === 'idiom_expression')) &&
                            (activeCat !== 'word' || activeWordGroup === 'all' || activeWordGroup === '{{ $subGroup }}') &&
                            (searchTerm === '' || '{{ $titleLower }}'.includes(searchTerm.toLowerCase().trim()))"
                     class="dataset-card bg-white border border-slate-200 rounded-3xl p-6 flex flex-col h-full hover:shadow-lg transition-all duration-200">
                    
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-14 h-14 {{ $bgIcon }} rounded-2xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                        </div>
                        <div class="text-right">
                            <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-800 font-extrabold text-[10px] uppercase block mb-1 border border-blue-100">{{ $catDisplay }}</span>
                            @if(!empty($need->subcategory))
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 font-extrabold text-[9px] block">{{ $need->subcategory }}</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-base font-bold text-slate-900 mb-1">{{ $need->title }}</h3>
                    <p class="text-xs text-slate-500 mb-6 leading-relaxed">{{ $need->description }}</p>

                    <div class="mt-auto space-y-4">
                        <div class="bg-blue-50/70 p-3 rounded-2xl border border-blue-100 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-blue-600 uppercase font-extrabold">Progres Pengumpulan</p>
                                <p class="text-xs text-slate-700 font-bold">Video Terkumpul</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-600 text-white font-extrabold text-xs rounded-xl shadow-xs">
                                {{ $need->current_count }} Video
                            </span>
                        </div>

                        <a href="{{ route('contributor.dataset.upload', ['need_id' => $need->id]) }}" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 text-xs shadow-md min-h-[44px]">
                            <span class="material-symbols-outlined text-base">cloud_upload</span>
                            Unggah Dataset
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 text-sm">Belum ada kebutuhan dataset yang terdaftar saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
