<x-app-layout title="Dashboard Kontributor - SIBI Dataset Platform" role="contributor">
    <div class="space-y-6">
        <!-- Page Header & Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="space-y-2 text-center md:text-left">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-xs rounded-full text-xs font-bold uppercase tracking-wider">
                    {{ auth()->user()->name ?? 'Kontributor 1' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Halo, Selamat Datang Kembali!</h1>
                <p class="text-xs sm:text-sm text-blue-100 max-w-xl">Terima kasih atas kontribusi Anda. Anda dapat mengunggah berkas dataset gerakan isyarat SIBI lebih dari 1 kali tanpa batas.</p>
            </div>
        </div>

        <!-- 4 Column Responsive Cards Grid for Contributor -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <x-card title="Total Dataset Tersimpan" value="{{ $myDatasets->count() }} Berkas" icon="folder" trend="Dataset Aktif di DB" :trendUp="true" />
            <x-card title="Disetujui / Valid" value="{{ $myDatasets->where('status.value', 'validated')->count() }} Berkas" icon="check_circle" trend="Masuk Repository" :trendUp="true" />
            <x-card title="Menunggu Validasi Pakar" value="{{ $myDatasets->whereIn('status.value', ['waiting_expert_validation', 'pending'])->count() }} Berkas" icon="pending_actions" trend="Sedang Ditinjau Pakar" :trendUp="true" />
            <x-card title="Skor Kontributor" value="{{ $myDatasets->count() * 50 }} Poin" icon="military_tech" trend="Top Contributor" :trendUp="true" />
        </div>

        <!-- High Priority Needs Call to Action -->
        <div class="bg-amber-50/80 border border-amber-200/80 rounded-3xl p-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="h-12 w-12 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold shrink-0">
                    <span class="material-symbols-outlined text-2xl">priority_high</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-amber-900">Bisa Unggah Berulang Kali!</h3>
                    <p class="text-xs text-amber-700 mt-0.5">Kontributor diperbolehkan mengunggah video isyarat SIBI yang sama atau berbeda berulang kali untuk memperkaya variasi dataset.</p>
                </div>
            </div>
            <a href="{{ route('contributor.dataset.upload') }}" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm transition min-h-[44px] flex items-center shrink-0">
                Unggah Dataset Lagi
            </a>
        </div>

        <!-- My Uploaded Datasets Table -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Dataset Diunggah Terbaru oleh Anda</h2>
                <a href="{{ route('contributor.dataset.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Dataset Saya ({{ $myDatasets->count() }}) &rarr;</a>
            </div>

            <x-table>
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Judul & Label SIBI</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Tanggal Unggah</th>
                        <th class="px-5 py-3.5">Status Validasi</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($myDatasets->take(5) as $dataset)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $dataset->title }}
                                <span class="block text-[11px] text-slate-500 font-normal">Label: "{{ $dataset->sign_label }}"</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $dataset->category }}</td>
                            <td class="px-5 py-4 text-slate-500">{{ $dataset->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-5 py-4">
                                @if(($dataset->status->value ?? 'pending') === 'validated')
                                    <x-badge type="validated" label="Disetujui / Valid" />
                                @else
                                    <x-badge type="pending" label="Menunggu Validasi" />
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('dataset.detail', $dataset->id) }}" class="text-blue-600 hover:underline font-bold">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-slate-500">Belum ada dataset yang diunggah. Klik "Unggah Dataset Baru" untuk mulai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>
    </div>
</x-app-layout>
