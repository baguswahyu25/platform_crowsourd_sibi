<div class="w-full overflow-hidden rounded-2xl border border-slate-200 shadow-xs bg-white">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'w-full text-left border-collapse text-sm']) }}>
            {{ $slot }}
        </table>
    </div>
</div>
