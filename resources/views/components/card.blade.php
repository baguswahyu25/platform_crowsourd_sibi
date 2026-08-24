@props(['title' => null, 'icon' => null, 'value' => null, 'trend' => null, 'trendUp' => true])

<div {{ $attributes->merge(['class' => 'bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs transition hover:shadow-md']) }}>
    @if($title && $value !== null)
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $title }}</span>
            @if($icon)
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
                </div>
            @endif
        </div>
        <div class="mt-3 flex items-baseline justify-between">
            <h3 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $value }}</h3>
            @if($trend)
                <span class="inline-flex items-center text-xs font-bold {{ $trendUp ? 'text-emerald-600' : 'text-rose-600' }}">
                    <span class="material-symbols-outlined text-sm mr-0.5">{{ $trendUp ? 'trending_up' : 'trending_down' }}</span>
                    {{ $trend }}
                </span>
            @endif
        </div>
    @else
        {{ $slot }}
    @endif
</div>
