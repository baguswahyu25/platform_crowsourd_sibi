<header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Mobile Sidebar Toggle & Brand -->
        <div class="flex items-center space-x-3">
            <button @click="sidebarOpen = !sidebarOpen" type="button" class="lg:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 min-h-[44px] min-w-[44px] flex items-center justify-center">
                <span class="material-symbols-outlined">menu</span>
            </button>
            <a href="{{ route('landing') }}" class="flex items-center space-x-2 lg:hidden">
                <img src="{{ asset('storage/logo.jpg') }}" alt="SIBI Logo" class="h-9 w-auto rounded-md shadow-xs object-cover"/>
                <span class="font-bold text-slate-900 tracking-tight">SIBI Platform</span>
            </a>
        </div>

        <!-- Header Actions & Profile Dropdown -->
        <div class="flex items-center space-x-3 ml-auto" x-data="{ profileOpen: false }">
            <button type="button" class="p-2 text-slate-500 hover:bg-slate-100 rounded-lg relative min-h-[44px] min-w-[44px] flex items-center justify-center" title="Notifikasi">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-2 right-2 h-2 w-2 bg-rose-500 rounded-full"></span>
            </button>

            <!-- Profile Menu -->
            <div class="relative">
                <button @click="profileOpen = !profileOpen" type="button" class="flex items-center space-x-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition min-h-[44px]">
                    @if(auth()->check() && auth()->user()->avatar)
                        <img src="{{ asset('storage/' . ltrim(str_replace(['public/', 'storage/'], '', auth()->user()->avatar), '/')) }}" alt="{{ auth()->user()->name }}" class="h-9 w-9 rounded-full object-cover shadow-sm border border-slate-200" />
                    @else
                        <div class="h-9 w-9 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <div class="text-xs font-semibold text-slate-800 leading-none">{{ auth()->user()->name ?? 'Pengguna' }}</div>
                        <div class="text-[10px] font-medium text-slate-500 mt-1 capitalize">{{ auth()->user()->role->value ?? auth()->user()->role ?? 'Contributor' }}</div>
                    </div>
                    <span class="material-symbols-outlined text-slate-400 text-sm">expand_more</span>
                </button>

                <!-- Dropdown Card -->
                <div x-show="profileOpen" @click.outside="profileOpen = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="text-xs font-bold text-slate-800">{{ auth()->user()->name ?? 'Pengguna Demo' }}</p>
                        <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? 'demo@sibi.id' }}</p>
                    </div>
                    <a href="{{ route('profile.index') }}" class="flex items-center px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                        <span class="material-symbols-outlined text-sm mr-2 text-slate-400">person</span> Profil Saya
                    </a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50">
                            <span class="material-symbols-outlined text-sm mr-2">logout</span> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
