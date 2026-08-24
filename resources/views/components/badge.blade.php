@props(['type' => 'pending', 'label' => null])

@php
    $statusClasses = match(strtolower($type)) {
        'validated', 'approved', 'active', 'tervalidasi', 'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
        'rejected', 'ditolak', 'closed' => 'bg-rose-50 text-rose-700 border-rose-200/60',
        'revision', 'revisi', 'minta revisi' => 'bg-blue-50 text-blue-700 border-blue-200/60',
        default => 'bg-amber-50 text-amber-700 border-amber-200/60',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border ' . $statusClasses]) }}>
    <span class="h-1.5 w-1.5 rounded-full mr-1.5 fill-current"></span>
    {{ $label ?? ucfirst($type) }}
</span>
