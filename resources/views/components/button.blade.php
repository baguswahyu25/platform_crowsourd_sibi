@props(['variant' => 'primary', 'type' => 'button', 'icon' => null])

@php
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white shadow-xs focus:ring-blue-500',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-800 focus:ring-slate-300',
        'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
        'danger' => 'bg-rose-600 hover:bg-rose-700 text-white focus:ring-rose-500',
        'outline' => 'border border-slate-300 text-slate-700 hover:bg-slate-50 focus:ring-blue-500',
    ];

    $classes = "inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 min-h-[44px] cursor-pointer " . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <span class="material-symbols-outlined text-sm mr-2">{{ $icon }}</span> @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon) <span class="material-symbols-outlined text-sm mr-2">{{ $icon }}</span> @endif
        {{ $slot }}
    </button>
@endif
