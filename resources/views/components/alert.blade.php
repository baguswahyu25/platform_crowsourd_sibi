@props(['type' => 'success', 'message' => ''])

@php
    $classes = match($type) {
        'error' => 'bg-rose-50 text-rose-800 border-rose-200 icon-error',
        'warning' => 'bg-amber-50 text-amber-800 border-amber-200 icon-warning',
        default => 'bg-emerald-50 text-emerald-800 border-emerald-200 icon-check_circle',
    };
    $icon = match($type) {
        'error' => 'error',
        'warning' => 'warning',
        default => 'check_circle',
    };
@endphp

<div class="mb-6 p-4 rounded-2xl border flex items-center justify-between shadow-2xs {{ $classes }}" x-data="{ open: true }" x-show="open">
    <div class="flex items-center space-x-3">
        <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
        <span class="text-xs font-semibold">{{ $message }}</span>
    </div>
    <button @click="open = false" class="opacity-70 hover:opacity-100">
        <span class="material-symbols-outlined text-sm">close</span>
    </button>
</div>
