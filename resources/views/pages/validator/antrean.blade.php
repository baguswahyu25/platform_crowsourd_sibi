<x-app-layout title="Antrean Validasi SIBI - Validator SIBI Platform" role="validator">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Validator' => route('validator.dashboard'), 'Antrean Validasi' => '']" />

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Antrean Validasi Dataset SIBI</h1>
                <p class="text-xs text-slate-500 mt-1">Daftar berkas rekaman yang diunggah oleh para kontributor, siap untuk ditinjau oleh Validator Pakar SIBI.</p>
            </div>
            <div class="px-4 py-2 bg-blue-50 text-blue-700 rounded-xl font-bold text-xs border border-blue-100 self-start sm:self-auto">
                {{ $pendingDatasets->count() }} Berkas Menunggu Peninjauan
            </div>
        </div>

        <x-table>
            <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">ID & Judul Sampel</th>
                    <th class="px-5 py-3.5">Kontributor (Label & Nama)</th>
                    <th class="px-5 py-3.5">Kategori</th>
                    <th class="px-5 py-3.5">Waktu Diunggah</th>
                    <th class="px-5 py-3.5 text-right">Aksi Peninjauan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($pendingDatasets as $dataset)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-slate-900">
                            #DS-{{ $dataset->id }} - {{ $dataset->title }}
                            <span class="block text-[11px] text-slate-500 font-normal">Label: "{{ $dataset->sign_label }}"</span>
                        </td>
                        <td class="px-5 py-4">
                            <span class="font-bold text-blue-700 block">
                                {{ $dataset->contributor_code ?? ('Kontributor ' . $dataset->user_id) }}
                            </span>
                            <span class="text-[11px] text-slate-500">
                                {{ $dataset->user->name ?? 'Ahmad Risyad' }} ({{ $dataset->user->datasets->count() ?? 1 }}x Upload)
                            </span>
                        </td>
                        <td class="px-5 py-4 text-slate-700 font-medium">{{ $dataset->category }}</td>
                        <td class="px-5 py-4 text-slate-500">{{ $dataset->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('validator.detail', $dataset->id) }}" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-xs transition inline-flex items-center min-h-[44px]">
                                <span class="material-symbols-outlined text-sm mr-1.5">rate_review</span> Tinjau Sample
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-500">
                            Tidak ada antrean dataset yang menunggu validasi saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>
</x-app-layout>
