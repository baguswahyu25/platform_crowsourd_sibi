<x-app-layout title="Kelola Kebutuhan & Label Dataset - Administrator" role="admin">
    <div class="space-y-6" x-data="{
        editModalOpen: false,
        editData: { id: '', title: '', priority: 'high', status: 'active' },
        addCat: 'word',
        openEdit(need) {
            this.editData = {
                id: need.id,
                title: need.title,
                priority: need.priority.value || need.priority,
                status: need.status.value || need.status
            };
            this.editModalOpen = true;
        }
    }">
        <x-breadcrumb :items="['Panel Admin' => route('admin.dashboard'), 'Kelola Kebutuhan & Label' => '']" />

        <!-- Success Alert -->
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center gap-3">
                <span class="material-symbols-outlined text-emerald-600 text-xl">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Kebutuhan & Label Dataset</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola label predefined untuk kategori Abjad, Kata, Kata Imbuhan, serta tinjau sampel Kalimat & Cerita Pendek.</p>
            </div>
            <x-button variant="primary" icon="add" x-on:click="$dispatch('open-modal', 'add-need-modal')">Tambah Label Predefined</x-button>
        </div>

        <!-- CATEGORY FILTER TABS -->
        <div class="flex flex-wrap gap-2 bg-white p-2 rounded-2xl border border-slate-200 shadow-sm">
            <a href="{{ route('admin.kebutuhan.index', ['category' => 'all']) }}" class="px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $categoryFilter === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                Semua Label ({{ $needs->count() }})
            </a>
            <a href="{{ route('admin.kebutuhan.index', ['category' => 'alphabet']) }}" class="px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $categoryFilter === 'alphabet' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                🔤 Abjad
            </a>
            <a href="{{ route('admin.kebutuhan.index', ['category' => 'word']) }}" class="px-4 py-2 rounded-xl text-xs font-extrabold transition {{ $categoryFilter === 'word' ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                💬 Kata
            </a>
            <a href="{{ route('admin.kebutuhan.index', ['category' => 'kata_imbuhan']) }}" class="px-4 py-2 rounded-xl text-xs font-extrabold transition {{ in_array($categoryFilter, ['kata_imbuhan', 'idiom_expression']) ? 'bg-blue-600 text-white shadow-xs' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                ✨ Kata Imbuhan
            </a>
            <a href="#sentence-section" class="px-4 py-2 rounded-xl text-xs font-extrabold bg-teal-50 text-teal-800 border border-teal-200 hover:bg-teal-100 transition">
                📝 Kalimat ({{ $sentenceSubmissions->count() }})
            </a>
            <a href="#short-story-section" class="px-4 py-2 rounded-xl text-xs font-extrabold bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition">
                📚 Cerita Pendek ({{ $shortStorySubmissions->count() }})
            </a>
        </div>

        <!-- TABLE PREDEFINED LABELS -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm">Daftar Label Predefined (Master Data Platform)</h3>
                <span class="text-xs text-slate-500">Total: {{ $needs->count() }} Label</span>
            </div>
            <x-table>
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Judul Label</th>
                        <th class="px-5 py-3.5">Kategori Utama</th>
                        <th class="px-5 py-3.5">Subkategori</th>
                        <th class="px-5 py-3.5">Video Terkumpul</th>
                        <th class="px-5 py-3.5">Prioritas</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($needs as $need)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-slate-900">{{ $need->title }}</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-extrabold text-[10px]">
                                    {{ $need->category }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $need->subcategory ?? '-' }}</td>
                            <td class="px-5 py-4 text-slate-700 font-bold">
                                {{ $need->current_count }} Video
                            </td>
                            <td class="px-5 py-4">
                                @if(($need->priority->value ?? $need->priority) === 'high')
                                    <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-[10px] font-extrabold">TINGGI</span>
                                @elseif(($need->priority->value ?? $need->priority) === 'medium')
                                    <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold">SEDANG</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-extrabold">RENDAH</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if(($need->status->value ?? $need->status) === 'active')
                                    <x-badge type="active" label="Aktif" />
                                @elseif(($need->status->value ?? $need->status) === 'fulfilled')
                                    <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-extrabold">Aktif</span>
                                @else
                                    <x-badge type="inactive" label="Nonaktif" />
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right space-x-2">
                                <button type="button" @click="openEdit({{ $need }})" class="text-blue-600 font-bold hover:underline">Edit</button>
                                <form action="{{ route('admin.kebutuhan.toggle', $need->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-amber-600 font-bold hover:underline">
                                        {{ ($need->status->value ?? $need->status) === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-500">Belum ada label predefined untuk kategori ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>

        <!-- SENTENCE SUBMISSIONS REVIEW SECTION -->
        <div id="sentence-section" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-teal-100 text-teal-700 rounded-xl flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-xl">notes</span>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Pengajuan Kategori Kalimat (Contributor-Generated)</h3>
                        <p class="text-xs text-slate-500">Daftar sampel kalimat yang ditulis dan diunggah langsung oleh kontributor.</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full font-bold text-xs">{{ $sentenceSubmissions->count() }} Pengajuan</span>
            </div>

            <x-table>
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Kontributor</th>
                        <th class="px-5 py-3.5">Kalimat Bahasa Indonesia</th>
                        <th class="px-5 py-3.5">Tanggal Unggah</th>
                        <th class="px-5 py-3.5">Status Validasi</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($sentenceSubmissions as $ds)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-blue-600">#DS-{{ $ds->id }}</td>
                            <td class="px-5 py-4 font-bold text-slate-800">{{ $ds->user->name ?? 'Kontributor' }}</td>
                            <td class="px-5 py-4 text-slate-900 font-medium max-w-xs">
                                "{{ $ds->story_content ?? $ds->title }}"
                            </td>
                            <td class="px-5 py-4 text-slate-500">{{ $ds->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800">
                                    {{ $ds->status->value ?? $ds->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('validator.detail', $ds->id) }}" class="text-blue-600 font-bold hover:underline">Tinjau Sample</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-500">Belum ada sampel kalimat yang diunggah kontributor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>

        <!-- SHORT STORY SUBMISSIONS REVIEW SECTION -->
        <div id="short-story-section" class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center font-bold">
                        <span class="material-symbols-outlined text-xl">auto_stories</span>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base">Pengajuan Kategori Cerita Pendek (Contributor-Generated)</h3>
                        <p class="text-xs text-slate-500">Daftar naskah dan sampel cerita pendek buatan kontributor platform.</p>
                    </div>
                </div>
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full font-bold text-xs">{{ $shortStorySubmissions->count() }} Pengajuan</span>
            </div>

            <x-table>
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">ID</th>
                        <th class="px-5 py-3.5">Judul & Label Cerita</th>
                        <th class="px-5 py-3.5">Kontributor</th>
                        <th class="px-5 py-3.5">Ringkasan Naskah Transkrip</th>
                        <th class="px-5 py-3.5">Tanggal Unggah</th>
                        <th class="px-5 py-3.5">Status Validasi</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($shortStorySubmissions as $ds)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-blue-600">#DS-{{ $ds->id }}</td>
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $ds->title }}
                                <span class="block text-[11px] text-purple-700 font-semibold">Tag: "{{ $ds->sign_label }}"</span>
                            </td>
                            <td class="px-5 py-4 text-slate-800">{{ $ds->user->name ?? 'Kontributor' }}</td>
                            <td class="px-5 py-4 text-slate-600 italic max-w-xs truncate">
                                "{{ Str::limit($ds->story_content ?? $ds->description, 80) }}"
                            </td>
                            <td class="px-5 py-4 text-slate-500">{{ $ds->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-blue-100 text-blue-800">
                                    {{ $ds->status->value ?? $ds->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('validator.detail', $ds->id) }}" class="text-blue-600 font-bold hover:underline">Tinjau Sample</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-500">Belum ada sampel cerita pendek yang diunggah kontributor.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>

        <!-- MODAL TAMBAH LABEL PREDEFINED -->
        <x-modal name="add-need-modal" title="Tambah Label Predefined Kebutuhan Dataset">
            <form action="{{ route('admin.kebutuhan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Label SIBI</label>
                    <input type="text" name="title" required placeholder="Contoh: Terimakasih" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori Utama</label>
                        <select name="category_id" x-model="addCat" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                            <option value="alphabet">Abjad (Alphabet)</option>
                            <option value="word">Kata (Word)</option>
                            <option value="kata_imbuhan">Kata Imbuhan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Subkategori</label>
                        <template x-if="addCat === 'word'">
                            <select name="subcategory" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="Kata ganti diri">Kata ganti diri</option>
                                <option value="Kata kerja (kata dasar)">Kata kerja (kata dasar)</option>
                                <option value="Kata benda">Kata benda</option>
                                <option value="Kata sifat">Kata sifat</option>
                            </select>
                        </template>
                        <template x-if="addCat === 'alphabet'">
                            <select name="subcategory" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="letters">Huruf (A-Z)</option>
                                <option value="numbers">Angka (1-10)</option>
                            </select>
                        </template>
                        <template x-if="addCat !== 'word' && addCat !== 'alphabet'">
                            <input type="text" name="subcategory" placeholder="Subkategori (Opsional)" class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Prioritas</label>
                    <select name="priority" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                        <option value="high" selected>Tinggi</option>
                        <option value="medium">Sedang</option>
                        <option value="low">Rendah</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <x-button type="button" variant="outline" x-on:click="$dispatch('close-modal', 'add-need-modal')">Batal</x-button>
                    <x-button type="submit" variant="primary">Simpan Label</x-button>
                </div>
            </form>
        </x-modal>

        <!-- MODAL EDIT LABEL PREDEFINED -->
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs" @click="editModalOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="editModalOpen" x-transition class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100 p-6 space-y-4">
                    <h3 class="text-base font-bold text-slate-900">Edit Label Predefined Dataset</h3>
                    <form :action="'/admin/kebutuhan/' + editData.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Label SIBI</label>
                            <input type="text" name="title" x-model="editData.title" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Prioritas</label>
                            <select name="priority" x-model="editData.priority" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="high">Tinggi</option>
                                <option value="medium">Sedang</option>
                                <option value="low">Rendah</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" x-model="editData.status" required class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-blue-600 outline-none">
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-bold text-xs hover:bg-slate-200">Batal</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold text-xs hover:bg-blue-700 shadow-md">Simpan Pembaruan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
