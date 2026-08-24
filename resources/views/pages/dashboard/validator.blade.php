<x-app-layout title="Dashboard Validator SIBI - SIBI Dataset Platform" role="validator">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Validator SIBI</h1>
                <p class="text-xs text-slate-500 mt-1">Tinjau, validasi, dan berikan catatan evaluasi untuk dataset gesture isyarat SIBI yang masuk.</p>
            </div>
            <x-button variant="primary" icon="play_arrow" href="{{ route('validator.antrean') }}">Mulai Validasi Antrean (45)</x-button>
        </div>

        <!-- 4 Column Responsive Cards Grid for Validator -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <x-card title="Antrean Belum Diperiksa" value="45" icon="pending_actions" trend="Perlu Tindakan SEGERA" :trendUp="false" />
            <x-card title="Disetujui Bulan Ini" value="184" icon="check_circle" trend="+22% produktivitas" :trendUp="true" />
            <x-card title="Revisi Diminta" value="28" icon="rate_review" trend="Perlu tindak lanjut kontributor" :trendUp="true" />
            <x-card title="Rata-rata Waktu Validasi" value="1.2 Hari" icon="timer" trend="Sangat Cepat" :trendUp="true" />
        </div>

        <!-- Pending Validation Queue Quick Action Section -->
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Antrean Validasi Terbaru</h2>
                    <p class="text-xs text-slate-500">Klik 'Tinjau Video/Gambar' untuk memeriksa keakuratan isyarat SIBI.</p>
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
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-slate-900 flex items-center space-x-3">
                            <span class="material-symbols-outlined text-blue-600">videocam</span>
                            <span>Isyarat "TERIMA KASIH"</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600">Budi Santoso</td>
                        <td class="px-5 py-4 text-slate-700 font-medium">Kata Kunci</td>
                        <td class="px-5 py-4 text-slate-500">Hari ini, 09:30</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('validator.detail', 101) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                                <span class="material-symbols-outlined text-sm mr-1">rate_review</span> Tinjau Sample
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-slate-900 flex items-center space-x-3">
                            <span class="material-symbols-outlined text-blue-600">videocam</span>
                            <span>Isyarat "NAMA SAYA"</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600">Dewi Anggraini</td>
                        <td class="px-5 py-4 text-slate-700 font-medium">Frasa SIBI</td>
                        <td class="px-5 py-4 text-slate-500">Kemarin, 14:15</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('validator.detail', 102) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition">
                                <span class="material-symbols-outlined text-sm mr-1">rate_review</span> Tinjau Sample
                            </a>
                        </td>
                    </tr>
                </tbody>
            </x-table>
        </div>
    </div>
</x-app-layout>
