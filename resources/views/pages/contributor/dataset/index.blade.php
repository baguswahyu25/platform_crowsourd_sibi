<x-app-layout title="Dataset Saya - Kontributor SIBI Platform" role="contributor">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Kontributor' => route('contributor.dashboard'), 'Dataset Saya' => '']" />

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
            <div class="space-y-1">
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 font-extrabold text-xs rounded-full uppercase">
                        {{ auth()->user()->name ?? 'Kontributor 1' }}
                    </span>
                    <span class="text-xs font-bold text-slate-500">• {{ $myDatasets->count() }} Kali Pengunggahan Berhasil</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Dataset Diunggah Saya</h1>
                <p class="text-xs text-slate-500">Riwayat seluruh berkas rekaman gerakan SIBI yang telah Anda kirimkan.</p>
            </div>
            <x-button variant="primary" icon="cloud_upload" href="{{ route('contributor.dataset.upload') }}">Unggah Dataset Baru</x-button>
        </div>

        <!-- Summary Cards for Contributor Uploads -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kali Mengunggah</p>
                <p class="text-2xl font-black text-slate-900">{{ $myDatasets->count() }} Berkas</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Disetujui / Valid</p>
                <p class="text-2xl font-black text-emerald-600">{{ $myDatasets->where('status.value', 'validated')->count() }} Berkas</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Menunggu Validasi Pakar</p>
                <p class="text-2xl font-black text-amber-600">{{ $myDatasets->where('status.value', 'waiting_expert_validation')->count() }} Berkas</p>
            </div>
        </div>

        <x-table>
            <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Urutan Upload</th>
                    <th class="px-5 py-3.5">Judul & Label SIBI</th>
                    <th class="px-5 py-3.5">Kategori</th>
                    <th class="px-5 py-3.5">Ukuran File</th>
                    <th class="px-5 py-3.5">Tanggal Unggah</th>
                    <th class="px-5 py-3.5">Status Validasi</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                @forelse($myDatasets as $index => $dataset)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-blue-600">
                            Pengunggahan #{{ $myDatasets->count() - $index }}
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-900 space-y-1">
                            <div>
                                {{ $dataset->title }}
                                <span class="block text-[11px] text-slate-500 font-normal">Label: "{{ $dataset->sign_label }}"</span>
                            </div>

                            @if(($dataset->category ?? '') === 'Short Story' || !empty($dataset->story_content))
                                <div class="bg-purple-50 p-2.5 rounded-xl border border-purple-100 text-[11px] text-purple-900 font-normal italic">
                                    <strong>Naskah:</strong> "{{ Str::limit($dataset->story_content ?? $dataset->description, 100) }}"
                                </div>
                            @endif

                            @if(!empty($dataset->rejection_reason))
                                <div class="bg-rose-50 p-2.5 rounded-xl border border-rose-200 text-[11px] text-rose-800 font-normal">
                                    <strong>Catatan Evaluasi:</strong> {{ $dataset->rejection_reason }}
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 font-medium">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ ($dataset->category ?? '') === 'Short Story' ? 'bg-purple-100 text-purple-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $dataset->category }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-slate-500">{{ number_format($dataset->file_size / (1024*1024), 1) }} MB</td>
                        <td class="px-5 py-4 text-slate-500">{{ $dataset->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-4">
                            @if(($dataset->status->value ?? 'pending') === 'validated')
                                <x-badge type="validated" label="Tervalidasi Pakar" />
                            @elseif(($dataset->status->value ?? 'pending') === 'rejected')
                                <x-badge type="rejected" label="Ditolak Pakar" />
                            @elseif(($dataset->status->value ?? 'pending') === 'failed')
                                <x-badge type="rejected" label="Gagal Validasi AI" />
                            @else
                                <x-badge type="pending" label="Menunggu Validasi Pakar" />
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('dataset.detail', $dataset->id) }}" class="text-blue-600 font-bold hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                            Anda belum pernah mengunggah berkas dataset. Klik tombol "Unggah Dataset Baru" untuk mulai berkontribusi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>
</x-app-layout>
