<x-app-layout title="Riwayat Validasi - Validator" role="validator">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Validator' => route('validator.dashboard'), 'Riwayat Validasi' => '']" />

        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Riwayat Evaluasi Validasi SIBI</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar historis seluruh sampel dataset SIBI yang telah diperiksa oleh Pakar SIBI.</p>
        </div>

        <x-table>
            <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">ID & Judul Sampel</th>
                    <th class="px-5 py-3.5">Kontributor</th>
                    <th class="px-5 py-3.5">Hasil Keputusan</th>
                    <th class="px-5 py-3.5">Catatan Feedback Pakar</th>
                    <th class="px-5 py-3.5">Tanggal Validasi</th>
                    <th class="px-5 py-3.5">Validator</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($validations as $val)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-slate-900">
                            #DS-{{ $val->dataset->id ?? $val->dataset_id }} - {{ $val->dataset->title ?? 'Sampel Dataset' }}
                            @if(optional($val->dataset)->sign_label)
                                <span class="block text-[11px] text-slate-500 font-normal">Label: "{{ $val->dataset->sign_label }}"</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            <span class="font-bold text-slate-900 block">{{ optional(optional($val->dataset)->user)->name ?? 'Kontributor SIBI' }}</span>
                            <span class="text-[11px] text-slate-400">{{ optional($val->dataset)->contributor_code ?? ('ID: ' . optional($val->dataset)->user_id) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @if(($val->status ?? '') === 'validated')
                                <x-badge type="validated" label="Disetujui" />
                            @else
                                <x-badge type="rejected" label="Ditolak" />
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-medium italic">
                            "{{ $val->feedback ?? 'Tidak ada catatan.' }}"
                        </td>
                        <td class="px-5 py-4 text-slate-500">
                            {{ $val->created_at ? $val->created_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4 text-slate-600 font-medium">
                            {{ optional($val->validator)->name ?? 'Pakar SIBI' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                            Belum ada riwayat evaluasi validasi dataset SIBI yang tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>
</x-app-layout>
