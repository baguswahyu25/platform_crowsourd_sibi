<x-app-layout title="Dashboard Administrator - SIBI Dataset Platform" role="admin">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview Administrator</h1>
                <p class="text-xs text-slate-500 mt-1">Ringkasan statistik pengguna, throughput validasi, dan kesehatan sistem SIBI.</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-button variant="secondary" icon="download">Unduh Laporan PDF</x-button>
                <x-button variant="primary" icon="add" href="{{ route('admin.kebutuhan.index') }}">Tambah Target Kebutuhan</x-button>
            </div>
        </div>

        <!-- 4 Column Responsive Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <x-card title="Total Dataset SIBI" value="1,248" icon="folder" trend="+14% bulan ini" :trendUp="true" />
            <x-card title="Total Kontributor" value="384" icon="groups" trend="+28 pengguna baru" :trendUp="true" />
            <x-card title="Validator Aktif" value="12" icon="verified_user" trend="Status Normal" :trendUp="true" />
            <x-card title="Antrean Validasi" value="45" icon="pending_actions" trend="Perlu Tindakan" :trendUp="false" />
        </div>

        <!-- Charts Grid (2 columns laptop/desktop) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-900">Grafik Pertumbuhan Dataset SIBI (2026)</h3>
                    <span class="text-xs text-slate-400 font-medium">Diperbarui 1 jam lalu</span>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="adminDatasetChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Distribusi Status Validasi</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="validationDoughnutChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Datasets Management Table -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Aktivitas Dataset Terbaru</h2>
                <a href="{{ route('admin.laporan.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Laporan &rarr;</a>
            </div>

            <x-table>
                <thead class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">ID & Title</th>
                        <th class="px-5 py-3.5">Kontributor</th>
                        <th class="px-5 py-3.5">Kategori</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-slate-900">#DS-1092 - Isyarat "SELAMAT PAGI"</td>
                        <td class="px-5 py-4 text-slate-600">Ahmad Risyad</td>
                        <td class="px-5 py-4 font-medium text-slate-700">Frasa Harian</td>
                        <td class="px-5 py-4"><x-badge type="pending" label="Pending" /></td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('dataset.detail', 1092) }}" class="text-blue-600 hover:underline font-bold">Detail</a>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-5 py-4 font-bold text-slate-900">#DS-1091 - Abjad SIBI "A" s/d "Z"</td>
                        <td class="px-5 py-4 text-slate-600">Siti Nurhaliza</td>
                        <td class="px-5 py-4 font-medium text-slate-700">Abjad SIBI</td>
                        <td class="px-5 py-4"><x-badge type="validated" label="Tervalidasi" /></td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('dataset.detail', 1091) }}" class="text-blue-600 hover:underline font-bold">Detail</a>
                        </td>
                    </tr>
                </tbody>
            </x-table>
        </div>
    </div>

    <!-- Chart.js Init Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx1 = document.getElementById('adminDatasetChart')?.getContext('2d');
            if(ctx1) {
                new Chart(ctx1, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                        datasets: [{
                            label: 'Jumlah Dataset',
                            data: [120, 210, 340, 480, 720, 950, 1248],
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            const ctx2 = document.getElementById('validationDoughnutChart')?.getContext('2d');
            if(ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: ['Tervalidasi', 'Pending', 'Ditolak'],
                        datasets: [{
                            data: [820, 340, 88],
                            backgroundColor: ['#10b981', '#f59e0b', '#f43f5e']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        });
    </script>
</x-app-layout>
