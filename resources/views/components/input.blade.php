@props(['label' => null, 'name', 'type' => 'text', 'placeholder' => '', 'value' => ''])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
            {{ $label }}
        </label>
    @endif

    @if($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-2xs p-3 text-slate-800']) }}>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-2xs h-11 px-3.5 text-slate-800']) }} />
    @endif

    @error($name)
        <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
    @enderror
</div>
