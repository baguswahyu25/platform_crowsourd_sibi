<x-app-layout title="Kelola Pengguna - Administrator" role="admin">
    <div class="space-y-6">
        <x-breadcrumb :items="['Panel Admin' => route('admin.dashboard'), 'Kelola Pengguna' => '']" />

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Pengguna Platform</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola data dan hak akses Kontributor, Validator SIBI, serta Administrator.</p>
            </div>
            <x-button variant="primary" icon="person_add">Tambah Pengguna Baru</x-button>
        </div>

        <x-table>
            <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-5 py-3.5">Nama & Email</th>
                    <th class="px-5 py-3.5">Role Pengguna</th>
                    <th class="px-5 py-3.5">Institusi</th>
                    <th class="px-5 py-3.5">Status Akun</th>
                    <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-5 py-4 font-bold text-slate-900">
                        <div>Ahmad Risyad</div>
                        <div class="text-[11px] text-slate-400 font-normal">ahmad@sibi.id</div>
                    </td>
                    <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 font-bold text-[10px]">KONTRIBUTOR</span></td>
                    <td class="px-5 py-4 text-slate-600">Universitas Indonesia</td>
                    <td class="px-5 py-4"><x-badge type="active" label="Aktif" /></td>
                    <td class="px-5 py-4 text-right">
                        <button class="text-blue-600 font-bold hover:underline mr-3">Edit Role</button>
                        <button class="text-rose-600 font-bold hover:underline">Nonaktifkan</button>
                    </td>
                </tr>
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-5 py-4 font-bold text-slate-900">
                        <div>Dr. Hendra Wijaya</div>
                        <div class="text-[11px] text-slate-400 font-normal">hendra@validator.sibi.id</div>
                    </td>
                    <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px]">VALIDATOR SIBI</span></td>
                    <td class="px-5 py-4 text-slate-600">Pusat Bahasa Isyarat ID</td>
                    <td class="px-5 py-4"><x-badge type="active" label="Aktif" /></td>
                    <td class="px-5 py-4 text-right">
                        <button class="text-blue-600 font-bold hover:underline mr-3">Edit Role</button>
                        <button class="text-rose-600 font-bold hover:underline">Nonaktifkan</button>
                    </td>
                </tr>
            </tbody>
        </x-table>
    </div>
</x-app-layout>
