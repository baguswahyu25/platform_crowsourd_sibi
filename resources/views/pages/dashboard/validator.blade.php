<x-app-layout title="Dashboard Validator SIBI - SIBI Dataset Platform" role="validator">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Validator SIBI</h1>
                <p class="text-xs text-slate-500 mt-1">Tinjau, validasi, dan berikan catatan evaluasi untuk dataset gesture isyarat SIBI yang masuk.</p>
            </div>
            <x-button variant="primary" icon="play_arrow" href="{{ route('validator.antrean') }}">Mulai Validasi Antrean ({{ $pendingDatasets->count() }})</x-button>
        </div>

        <!-- 4 Column Responsive Cards Grid for Validator -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <x-card title="Antrean Belum Diperiksa" value="{{ $pendingDatasets->count() }}" icon="pending_actions" trend="Perlu Peninjauan" :trendUp="false" />
            <x-card title="Disetujui / Di-Validasi" value="{{ $allDatasets->filter(fn($d) => ($d->status->value ?? $d->status) === 'validated')->count() }}" icon="check_circle" trend="Total Validated" :trendUp="true" />
            <x-card title="Ditolak Pakar (Tidak Valid)" value="{{ $allDatasets->filter(fn($d) => ($d->status->value ?? $d->status) === 'rejected')->count() }}" icon="cancel" trend="Perlu Upload Ulang" :trendUp="false" />
            <x-card title="Total Dataset Masuk" value="{{ $allDatasets->count() }}" icon="video_library" trend="Sistem Crowdsourcing" :trendUp="true" />
        </div>

        <!-- Pending Validation Queue Quick Action Section -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Antrean Validasi Terbaru (Lulus AI)</h2>
                    <p class="text-xs text-slate-500">Klik 'Tinjau Sample' untuk menganalisis video asli kontributor.</p>
                </div>
                <a href="{{ route('validator.antrean') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Antrean &rarr;</a>
            </div>

            <x-table>
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Sampel SIBI</th>
                        <th class="px-5 py-3.5">Pengunggah</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Tanggal Unggah</th>
                        <th class="px-5 py-3.5 text-right">Aksi Evaluasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($pendingDatasets->take(5) as $dataset)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-slate-900 flex items-center space-x-3">
                                <span class="material-symbols-outlined text-blue-600">videocam</span>
                                <div>
                                    <span class="block">#DS-{{ $dataset->id }} - {{ $dataset->title }}</span>
                                    <span class="text-[11px] text-slate-500 font-normal">Label: "{{ $dataset->sign_label }}"</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                <span class="font-bold block">{{ $dataset->user->name ?? 'Kontributor SIBI' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $dataset->contributor_code ?? ('ID: ' . $dataset->user_id) }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-700 font-medium">{{ $dataset->category }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ $dataset->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('validator.detail', $dataset->id) }}" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition min-h-[44px]">
                                    <span class="material-symbols-outlined text-sm mr-1.5">rate_review</span> Tinjau Sample
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500">
                                Tidak ada antrean dataset yang menunggu validasi pakar saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>
    </div>
</x-app-layout>
