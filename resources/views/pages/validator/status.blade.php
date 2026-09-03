<x-app-layout title="Status & Catatan Validasi - Validator" role="validator">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Validator' => route('validator.dashboard'), 'Status Validasi' => '']" />

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Status & Catatan Validasi SIBI</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar sampel dataset yang ditolak atau tidak valid dan memerlukan pengunggahan ulang oleh kontributor.</p>
            </div>
        </div>

        <x-table>
            <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Judul & Label</th>
                    <th class="px-5 py-3.5">Kontributor</th>
                    <th class="px-5 py-3.5">Status Validasi</th>
                    <th class="px-5 py-3.5">Catatan Evaluasi Pakar SIBI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($allDatasets->where('status.value', 'rejected') as $dataset)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-slate-900">#DS-{{ $dataset->id }} - {{ $dataset->title }}</td>
                        <td class="px-5 py-4 text-slate-600">{{ $dataset->user->name ?? 'Kontributor' }}</td>
                        <td class="px-5 py-4"><x-badge type="rejected" label="Ditolak Pakar" /></td>
                        <td class="px-5 py-4 text-slate-600 font-medium italic">"{{ $dataset->rejection_reason ?? 'Peragaan isyarat tidak sesuai standar SIBI.' }}"</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-slate-500">
                            Belum ada catatan dataset yang ditolak oleh Pakar SIBI.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>
</x-app-layout>
