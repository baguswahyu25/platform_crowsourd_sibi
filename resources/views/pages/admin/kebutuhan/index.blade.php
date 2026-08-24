<x-app-layout title="Kelola Kebutuhan Dataset - Administrator" role="admin">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Admin' => route('admin.dashboard'), 'Kelola Kebutuhan' => '']" />

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Target Kebutuhan Dataset</h1>
                <p class="text-xs text-slate-500 mt-1">Tentukan prioritas dan target pengumpulan kata/isyarat SIBI yang dibutuhkan platform.</p>
            </div>
            <x-button variant="primary" icon="add" x-on:click="$dispatch('open-modal', 'add-need-modal')">Tambah Target Kebutuhan</x-button>
        </div>

        <x-table>
            <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Judul Target</th>
                    <th class="px-5 py-3.5">Kategori</th>
                    <th class="px-5 py-3.5">Target / Terkumpul</th>
                    <th class="px-5 py-3.5">Prioritas</th>
                    <th class="px-5 py-3.5">Status</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-5 py-4 font-bold text-slate-900">Abjad SIBI Komplit (A-Z)</td>
                    <td class="px-5 py-4 text-slate-600">Abjad SIBI</td>
                    <td class="px-5 py-4 text-slate-700 font-semibold">150 / 200 Sampel</td>
                    <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-[10px] font-extrabold">TINGGI</span></td>
                    <td class="px-5 py-4"><x-badge type="active" label="Aktif" /></td>
                    <td class="px-5 py-4 text-right">
                        <button class="text-blue-600 font-bold hover:underline">Edit</button>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-5 py-4 font-bold text-slate-900">Isyarat Kosakata Profesi</td>
                    <td class="px-5 py-4 text-slate-600">Profesi & Kerja</td>
                    <td class="px-5 py-4 text-slate-700 font-semibold">40 / 100 Sampel</td>
                    <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-[10px] font-extrabold">SEDANG</span></td>
                    <td class="px-5 py-4"><x-badge type="active" label="Aktif" /></td>
                    <td class="px-5 py-4 text-right">
                        <button class="text-blue-600 font-bold hover:underline">Edit</button>
                    </td>
                </tr>
            </tbody>
        </x-table>

        <!-- Alpine Modal for Adding Need -->
        <x-modal name="add-need-modal" title="Tambah Target Kebutuhan Dataset">
            <form action="#" method="POST" class="space-y-4">
                @csrf
                <x-input label="Judul Target Kebutuhan" name="title" placeholder="Contoh: Isyarat Kosa Kata Kesehatan" required />
                <x-input label="Kategori" name="category" placeholder="Contoh: Kesehatan / Profesi" required />
                <x-input label="Jumlah Target Sampel" name="target_count" type="number" value="100" required />
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase">Tingkat Prioritas</label>
                    <select name="priority" class="w-full rounded-xl border-slate-300 text-sm h-11 px-3">
                        <option value="high">Tinggi</option>
                        <option value="medium">Sedang</option>
                        <option value="low">Rendah</option>
                    </select>
                </div>
                <x-button type="submit" variant="primary" class="w-full text-center mt-2">Simpan Target Kebutuhan</x-button>
            </form>
        </x-modal>
    </div>
</x-app-layout>
