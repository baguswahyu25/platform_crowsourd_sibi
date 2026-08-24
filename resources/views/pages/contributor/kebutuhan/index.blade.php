<x-app-layout title="Target Kebutuhan Dataset - SIBI Dataset Platform" role="contributor">
    <div class="space-y-8">
        <!-- Header Section -->
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">Kebutuhan Dataset</h1>
            <p class="text-sm text-slate-600 max-w-3xl leading-relaxed">
                Berikut adalah daftar kebutuhan dataset video Bahasa Isyarat Indonesia (SIBI) yang masih dibutuhkan. Kontributor dapat melihat target, progres, dan langsung mengunggah video berdasarkan kebutuhan dataset yang dipilih.
            </p>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative w-full sm:w-96">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400">search</span>
                <input id="search-input" class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition text-sm text-slate-800" placeholder="Cari label isyarat..." type="text">
            </div>
            <div class="flex items-center gap-1 bg-slate-200/70 p-1 rounded-xl self-start sm:self-auto" id="filter-buttons">
                <button data-filter="all" class="filter-btn px-4 py-2 rounded-lg bg-white text-blue-600 font-bold shadow-xs transition text-xs">Semua</button>
                <button data-filter="active" class="filter-btn px-4 py-2 rounded-lg text-slate-600 hover:bg-white/50 transition text-xs font-semibold">Belum Terpenuhi</button>
                <button data-filter="fulfilled" class="filter-btn px-4 py-2 rounded-lg text-slate-600 hover:bg-white/50 transition text-xs font-semibold">Sudah Terpenuhi</button>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="dataset-grid">
            @forelse($needs as $need)
                @php
                    $isFulfilled = ($need->status->value === 'fulfilled' || $need->current_count >= $need->target_count);
                    $percentage = round(($need->current_count / max($need->target_count, 1)) * 100);
                    if ($percentage > 100) $percentage = 100;
                    $remaining = max(0, $need->target_count - $need->current_count);

                    $catName = strtolower($need->category);
                    if (str_contains($catName, 'abjad')) {
                        $icon = 'spellcheck';
                        $bgIcon = 'bg-blue-100 text-blue-700';
                    } elseif (str_contains($catName, 'angka')) {
                        $icon = '123';
                        $bgIcon = 'bg-amber-100 text-amber-700';
                    } else {
                        $icon = 'waving_hand';
                        $bgIcon = 'bg-indigo-100 text-indigo-700';
                    }
                @endphp

                @if($isFulfilled)
                    <!-- Completed Card -->
                    <div class="dataset-card bg-slate-50 border border-slate-200 rounded-3xl p-6 flex flex-col h-full opacity-75" data-status="fulfilled" data-title="{{ strtolower($need->title) }}">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-14 h-14 bg-slate-200 text-slate-500 rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-600 font-extrabold text-[10px] uppercase">SELESAI</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ $need->title }}</h3>
                        <p class="text-xs text-slate-500 mb-6 leading-relaxed">{{ $need->description }}</p>
                        <div class="mt-auto space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>{{ $need->current_count }} / {{ $need->target_count }} Video</span>
                                    <span class="text-slate-500">100%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-slate-400 rounded-full" style="width: 100%;"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 py-3 border-y border-slate-200/80 text-center">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Target</p>
                                    <p class="font-bold text-slate-800 text-xs">{{ $need->target_count }}</p>
                                </div>
                                <div class="border-x border-slate-200/80">
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Terkumpul</p>
                                    <p class="font-bold text-slate-800 text-xs">{{ $need->current_count }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Sisa</p>
                                    <p class="font-bold text-slate-800 text-xs">0</p>
                                </div>
                            </div>
                            <button class="w-full py-3 bg-slate-200 text-slate-500 rounded-xl font-bold text-xs cursor-not-allowed flex items-center justify-center gap-2 min-h-[44px]" disabled>
                                <span class="material-symbols-outlined text-base">check_circle</span>
                                Target Terpenuhi
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Incomplete Card -->
                    <div class="dataset-card bg-white border border-slate-200 rounded-3xl p-6 flex flex-col h-full hover:shadow-lg transition-all duration-200" data-status="active" data-title="{{ strtolower($need->title) }}">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-14 h-14 {{ $bgIcon }} rounded-2xl flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl">{{ $icon }}</span>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-extrabold text-[10px] uppercase">BELUM TERPENUHI</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">{{ $need->title }}</h3>
                        <p class="text-xs text-slate-500 mb-6 leading-relaxed">{{ $need->description }}</p>
                        <div class="mt-auto space-y-4">
                            <div>
                                <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                                    <span>{{ $need->current_count }} / {{ $need->target_count }} Video</span>
                                    <span class="text-blue-600">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $percentage }}%;"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2 py-3 border-y border-slate-100 text-center">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Target</p>
                                    <p class="font-bold text-slate-800 text-xs">{{ $need->target_count }}</p>
                                </div>
                                <div class="border-x border-slate-100">
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Terkumpul</p>
                                    <p class="font-bold text-slate-800 text-xs">{{ $need->current_count }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold">Sisa</p>
                                    <p class="font-bold text-slate-800 text-xs">{{ $remaining }}</p>
                                </div>
                            </div>
                            <a href="{{ route('contributor.dataset.upload', ['need_id' => $need->id]) }}" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 text-xs shadow-md min-h-[44px]">
                                <span class="material-symbols-outlined text-base">cloud_upload</span>
                                Unggah Dataset
                            </a>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 text-sm">Belum ada kebutuhan dataset yang terdaftar saat ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Empty State -->
        <div class="hidden flex flex-col items-center justify-center py-16 bg-slate-50 rounded-3xl border border-dashed border-slate-300 text-center" id="empty-state">
            <div class="w-16 h-16 mb-3 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl">folder_off</span>
            </div>
            <h4 class="text-base font-bold text-slate-900 mb-1">Tidak Ada Kebutuhan Dataset</h4>
            <p class="text-xs text-slate-500 max-w-sm">Kebutuhan dataset yang Anda cari tidak ditemukan. Coba gunakan kata kunci lain.</p>
        </div>
    </div>

    <!-- Interactive Search & Filter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const cards = document.querySelectorAll('.dataset-card');
            const emptyState = document.getElementById('empty-state');
            const grid = document.getElementById('dataset-grid');
            const filterBtns = document.querySelectorAll('.filter-btn');

            let currentFilter = 'all';

            function filterCards() {
                const term = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;

                cards.forEach(card => {
                    const title = card.getAttribute('data-title') || '';
                    const status = card.getAttribute('data-status') || '';

                    const matchesSearch = title.includes(term);
                    const matchesFilter = (currentFilter === 'all') || (status === currentFilter);

                    if (matchesSearch && matchesFilter) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCount === 0) {
                    grid.classList.add('hidden');
                    emptyState.classList.remove('hidden');
                } else {
                    grid.classList.remove('hidden');
                    emptyState.classList.add('hidden');
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', filterCards);
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('bg-white', 'text-blue-600', 'font-bold', 'shadow-xs');
                        b.classList.add('text-slate-600');
                    });
                    this.classList.remove('text-slate-600');
                    this.classList.add('bg-white', 'text-blue-600', 'font-bold', 'shadow-xs');

                    currentFilter = this.getAttribute('data-filter');
                    filterCards();
                });
            });
        });
    </script>
</x-app-layout>
