<x-app-layout title="Dashboard Administrator - SIBI Dataset Platform" role="admin">
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview Administrator</h1>
                <p class="text-xs text-slate-500 mt-1">Ringkasan statistik pengguna, throughput validasi, dan kesehatan sistem SIBI.</p>
            </div>
            <div class="flex items-center space-x-3">
                <x-button variant="primary" icon="add" href="{{ route('admin.kebutuhan.index') }}">Tambah Kebutuhan Dataset</x-button>
            </div>
        </div>

        @php
            $contributorCount = $users->filter(fn($u) => ($u->role->value ?? $u->role) === 'contributor')->count();
            $validatorCount = $users->filter(fn($u) => ($u->role->value ?? $u->role) === 'validator')->count();
            $pendingCount = $datasets->filter(fn($d) => $d->auto_validation_status === 'passed' && ($d->status->value ?? $d->status) === 'waiting_expert_validation')->count();
            $validatedCount = $datasets->filter(fn($d) => ($d->status->value ?? $d->status) === 'validated')->count();
            $rejectedCount = $datasets->filter(fn($d) => in_array($d->status->value ?? $d->status, ['rejected', 'failed']))->count();
        @endphp

        <!-- 4 Column Responsive Cards Grid (REAL DATA FROM DATABASE) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <x-card title="Total Dataset SIBI" value="{{ $datasets->count() }} Berkas" icon="folder" trend="Dataset Real DB" :trendUp="true" />
            <x-card title="Total Kontributor" value="{{ $contributorCount }} Pengguna" icon="groups" trend="Kontributor Terdaftar" :trendUp="true" />
            <x-card title="Validator Pakar" value="{{ $validatorCount }} Pakar" icon="verified_user" trend="Pakar SIBI" :trendUp="true" />
            <x-card title="Antrean Validasi" value="{{ $pendingCount }} Antrean" icon="pending_actions" trend="Sedang Ditinjau" :trendUp="false" />
        </div>

        <!-- Charts Grid (Real Dynamic Chart Data) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-8 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-900">Distribusi Kategori Dataset SIBI Real</h3>
                    <span class="text-xs text-slate-400 font-medium">Real-time Database Metric</span>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="adminCategoryChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Distribusi Status Validasi Real</h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="validationDoughnutChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Datasets Management Table (REAL DATA FROM DATABASE) -->
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
                    @forelse($datasets->take(5) as $dataset)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-5 py-4 font-bold text-slate-900">
                                #DS-{{ $dataset->id }} - {{ $dataset->title }}
                                <span class="block text-[11px] text-slate-500 font-normal">Label: "{{ $dataset->sign_label }}"</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $dataset->user->name ?? 'Kontributor' }}</td>
                            <td class="px-5 py-4 font-medium text-slate-700">{{ $dataset->category }}</td>
                            <td class="px-5 py-4">
                                @if(($dataset->status->value ?? $dataset->status) === 'validated')
                                    <x-badge type="validated" label="Tervalidasi" />
                                @elseif(($dataset->status->value ?? $dataset->status) === 'waiting_expert_validation')
                                    <x-badge type="pending" label="Menunggu Pakar" />
                                @else
                                    <x-badge type="inactive" label="Ditolak / Gagal" />
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('dataset.detail', $dataset->id) }}" class="text-blue-600 hover:underline font-bold">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500">Belum ada data dataset yang terdaftar di database.</td>
                        </tr>
                    @endforelse
                </tbody>
            </x-table>
        </div>
    </div>

    @php
        $abjadCount = $datasets->filter(fn($d) => in_array(strtolower($d->category), ['abjad', 'alphabet']))->count();
        $kataCount = $datasets->filter(fn($d) => in_array(strtolower($d->category), ['kata', 'word']))->count();
        $imbuhanCount = $datasets->filter(fn($d) => in_array(strtolower($d->category), ['kata imbuhan', 'kata_imbuhan', 'idiom_expression']))->count();
        $sentenceCount = $datasets->filter(fn($d) => in_array(strtolower($d->category), ['kalimat', 'sentence']))->count();
        $storyCount = $datasets->filter(fn($d) => in_array(strtolower($d->category), ['cerita pendek', 'short story', 'short_story']))->count();
    @endphp

    <!-- Real Dynamic Chart.js Init Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx1 = document.getElementById('adminCategoryChart')?.getContext('2d');
            if(ctx1) {
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: ['Abjad', 'Kata', 'Kata Imbuhan', 'Kalimat', 'Cerita Pendek'],
                        datasets: [{
                            label: 'Jumlah Video Real',
                            data: [{{ $abjadCount }}, {{ $kataCount }}, {{ $imbuhanCount }}, {{ $sentenceCount }}, {{ $storyCount }}],
                            backgroundColor: ['#2563eb', '#10b981', '#6366f1', '#14b8a6', '#a855f7'],
                            borderRadius: 8
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
                        labels: ['Tervalidasi', 'Menunggu Pakar', 'Ditolak / Gagal'],
                        datasets: [{
                            data: [{{ $validatedCount }}, {{ $pendingCount }}, {{ $rejectedCount }}],
                            backgroundColor: ['#10b981', '#f59e0b', '#f43f5e']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        });
    </script>
</x-app-layout>
