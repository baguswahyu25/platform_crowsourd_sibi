@props(['items' => []])

<nav class="flex items-center text-xs font-medium text-slate-500 mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 sm:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ route('landing') }}" class="text-slate-500 hover:text-blue-600 inline-flex items-center">
                <span class="material-symbols-outlined text-sm mr-1">home</span> Beranda
            </a>
        </li>
        @foreach($items as $label => $link)
            <li>
                <div class="flex items-center">
                    <span class="material-symbols-outlined text-sm text-slate-400 mx-1">chevron_right</span>
                    @if($link)
                        <a href="{{ $link }}" class="text-slate-500 hover:text-blue-600">{{ $label }}</a>
                    @else
                        <span class="text-slate-800 font-semibold">{{ $label }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
