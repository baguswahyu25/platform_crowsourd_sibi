<x-app-layout title="Riwayat Validasi - Validator" role="validator">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Validator' => route('validator.dashboard'), 'Riwayat Validasi' => '']" />

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Evaluasi Validasi SIBI</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar historis seluruh sampel dataset SIBI yang pernah Anda periksa.</p>
        </div>

        <x-table>
            <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">ID & Judul Sampel</th>
                    <th class="px-5 py-3.5">Kontributor</th>
                    <th class="px-5 py-3.5">Hasil Keputusan</th>
                    <th class="px-5 py-3.5">Tanggal Validasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-5 py-4 font-bold text-slate-900">#DS-88 - Isyarat "SELAMAT MALAM"</td>
                    <td class="px-5 py-4 text-slate-600">Siti Nurhaliza</td>
                    <td class="px-5 py-4"><x-badge type="validated" label="Disetujui" /></td>
                    <td class="px-5 py-4 text-slate-500">29 Juli 2026, 16:40</td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-5 py-4 font-bold text-slate-900">#DS-85 - Isyarat "MAAF"</td>
                    <td class="px-5 py-4 text-slate-600">Budi Santoso</td>
                    <td class="px-5 py-4"><x-badge type="validated" label="Disetujui" /></td>
                    <td class="px-5 py-4 text-slate-500">28 Juli 2026, 11:20</td>
                </tr>
            </tbody>
        </x-table>
    </div>
</x-app-layout>
