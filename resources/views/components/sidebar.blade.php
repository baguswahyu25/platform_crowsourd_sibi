@props(['role' => 'contributor'])

@php
    $currentRoute = request()->route()->getName() ?? '';
    $userRole = is_object($role) ? $role->value : $role;
@endphp

<!-- Mobile Backdrop -->
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-xs lg:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar Container -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0">
    <!-- Brand Header -->
    <div class="h-16 flex items-center px-6 border-b border-slate-100 justify-between">
        <a href="{{ route('landing') }}" class="flex items-center space-x-3">
            <img src="{{ asset('storage/logo.jpg') }}" alt="SIBI Logo" class="h-9 w-auto rounded-lg shadow-sm object-cover"/>
            <div>
                <span class="font-bold text-slate-900 text-sm block tracking-tight">SIBI Platform</span>
                <span class="text-[10px] text-blue-600 font-semibold uppercase tracking-wider block">
                    {{ $userRole === 'admin' ? 'Administrator' : ($userRole === 'validator' ? 'Validator SIBI' : 'Kontributor') }}
                </span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded-md text-slate-400 hover:text-slate-600">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <!-- Navigation Items -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">

        @if($userRole === 'admin')
            <!-- Admin Navigation -->
            <div>
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3 mb-2">Panel Admin</div>
                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'admin.dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">dashboard</span> Dashboard Overview
                    </a>
                    <a href="{{ route('admin.kebutuhan.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'admin.kebutuhan') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">assignment_add</span> Kelola Kebutuhan
                    </a>
                    <a href="{{ route('admin.pengguna.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'admin.pengguna') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">group</span> Kelola Pengguna
                    </a>
                    <a href="{{ route('admin.laporan.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'admin.laporan') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">analytics</span> Laporan & Analitik
                    </a>
                </nav>
            </div>
        @elseif($userRole === 'validator')
            <!-- Validator Navigation -->
            <div>
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3 mb-2">Panel Validator SIBI</div>
                <nav class="space-y-1">
                    <a href="{{ route('validator.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'validator.dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">verified_user</span> Dashboard Validator
                    </a>
                    <a href="{{ route('validator.antrean') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'validator.antrean') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">pending_actions</span> Antrean Validasi SIBI
                    </a>
                    <a href="{{ route('validator.status') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'validator.status') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">rate_review</span> Status & Catatan
                    </a>
                    <a href="{{ route('validator.riwayat') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'validator.riwayat') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">history</span> Riwayat Validasi
                    </a>
                </nav>
            </div>
        @else
            <!-- Contributor Navigation -->
            <div>
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3 mb-2">Panel Kontributor</div>
                <nav class="space-y-1">
                    <a href="{{ route('contributor.dashboard') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'contributor.dashboard') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">dashboard</span> Dashboard Kontributor
                    </a>
                    <a href="{{ route('contributor.dataset.upload') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'contributor.dataset.upload') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">cloud_upload</span> Unggah Dataset SIBI
                    </a>
                    <a href="{{ route('contributor.dataset.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'contributor.dataset.index') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">folder_open</span> Dataset Saya
                    </a>
                    <a href="{{ route('contributor.kebutuhan.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'contributor.kebutuhan') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                        <span class="material-symbols-outlined text-sm mr-3">checklist</span> Kebutuhan Dataset
                    </a>
                </nav>
            </div>
        @endif

        <!-- General Account Section -->
        <div>
            <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3 mb-2">Akun Saya</div>
            <nav class="space-y-1">
                <a href="{{ route('profile.index') }}" class="flex items-center px-3 py-2.5 rounded-xl text-xs font-semibold transition {{ Str::startsWith($currentRoute, 'profile') ? 'bg-blue-50 text-blue-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                    <span class="material-symbols-outlined text-sm mr-3">person</span> Profil Pengguna
                </a>
            </nav>
        </div>

    </div>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
        <div class="bg-blue-50/80 rounded-xl p-3 border border-blue-100/60">
            <div class="flex items-center space-x-2 text-blue-800">
                <span class="material-symbols-outlined text-sm">support_agent</span>
                <span class="text-xs font-bold">Butuh Bantuan?</span>
            </div>
            <p class="text-[11px] text-blue-600 mt-1">Lihat dokumentasi atau hubungi tim bantuan platform SIBI.</p>
        </div>
    </div>
</aside>
