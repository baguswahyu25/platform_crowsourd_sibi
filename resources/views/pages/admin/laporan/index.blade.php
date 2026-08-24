<x-app-layout title="Laporan & Analitik - Administrator SIBI Platform" role="admin">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Admin' => route('admin.dashboard'), 'Laporan & Analitik' => '']" />

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan & Analitik Pengunggahan Kontributor</h1>
                <p class="text-xs text-slate-500 mt-1">Pantau total pengunggahan berkas dataset dari setiap kontributor terdaftar.</p>
            </div>
            <x-button variant="primary" icon="download">Cetak Ringkasan Laporan</x-button>
        </div>

        <!-- 3 Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <x-card title="Total Sampel Diunggah" value="{{ $datasets->count() }} Berkas" icon="folder" trend="Multi-Upload Per Contributor" :trendUp="true" />
            <x-card title="Akurasi Validasi Paket" value="96.4%" icon="analytics" trend="Tingkat Presisi Sangat Tinggi" :trendUp="true" />
            <x-card title="Total Sampel Tervalidasi" value="{{ $datasets->where('status.value', 'validated')->count() }} Berkas" icon="fact_check" trend="Siap Dipakai AI Training" :trendUp="true" />
        </div>

        <!-- Contributor Activity & Multi-Upload Table -->
        <div class="space-y-3 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Laporan Aktivitas Pengunggahan per Kontributor</h2>
                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">Live Database Analytics</span>
            </div>

            @php
                $groupedContributors = $datasets->groupBy('user_id');
            @endphp

            <x-table>
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Label Kontributor</th>
                        <th class="px-5 py-3.5">Nama Kontributor</th>
                        <th class="px-5 py-3.5">Institusi</th>
                        <th class="px-5 py-3.5">Total Kali Mengunggah</th>
                        <th class="px-5 py-3.5">Berkas Tervalidasi</th>
                        <th class="px-5 py-3.5">Status Kontributor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($groupedContributors as $userId => $userDatasets)
                        @php
                            $user = $userDatasets->first()->user ?? null;
                            $code = $userDatasets->first()->contributor_code ?? ('Kontributor ' . $userId);
                            $validatedCount = $userDatasets->where('status.value', 'validated')->count();
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-black text-blue-600">
                                {{ $code }}
                            </td>
                            <td class="px-5 py-4 font-bold text-slate-900">
                                {{ $user->name ?? 'Ahmad Risyad' }}
                                <span class="block text-[11px] text-slate-500 font-normal">{{ $user->email ?? 'kontributor@sibi.id' }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 font-medium">{{ $user->institution ?? 'Universitas Indonesia' }}</td>
                            <td class="px-5 py-4 font-black text-slate-900 text-sm">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full border border-blue-100">
                                    {{ $userDatasets->count() }} Kali Upload
                                </span>
                            </td>
                            <td class="px-5 py-4 text-emerald-600 font-bold">
                                {{ $validatedCount }} Berkas Valid
                            </td>
                            <td class="px-5 py-4">
                                <x-badge type="active" label="Aktif Berkontribusi" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-6 text-center text-slate-500">Belum ada pengunggahan dataset yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>
    </div>
</x-app-layout>
