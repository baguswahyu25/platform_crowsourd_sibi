@props(['name', 'title' => 'Modal Title'])

<div x-data="{ show: false }" x-on:open-modal.window="if ($event.detail === '{{ $name }}') show = true" x-on:close-modal.window="if ($event.detail === '{{ $name }}') show = false" x-show="show" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="show = false"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl border border-slate-100">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">{{ $title }}</h3>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
            <div class="py-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
