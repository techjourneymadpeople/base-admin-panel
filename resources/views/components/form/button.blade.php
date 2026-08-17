@props([
    'variant' => 'primary',
    'type' => 'submit',
    'icon' => null,
    'iconPosition' => 'right',
    'fullWidth' => true,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 rounded-2xl font-semibold text-sm transition-all duration-300 focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed select-none px-6 py-3.5 ';
    
    $variants = [
        'primary' => 'bg-gradient-to-r from-[#1d3e35] via-[#295c4d] to-[#31725e] hover:from-[#0f241f] hover:via-[#1d3e35] hover:to-[#295c4d] text-white shadow-xl shadow-[#1d3e35]/20 hover:shadow-2xl hover:shadow-[#1d3e35]/30 hover:-translate-y-0.5 active:translate-y-0 focus:ring-[#428e75]/30',
        'secondary' => 'bg-gradient-to-r from-[#945838] to-[#b17042] hover:from-[#784732] hover:to-[#945838] text-white shadow-lg shadow-[#945838]/20 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 focus:ring-[#cca06e]/30',
        'outline' => 'bg-white/60 hover:bg-white/95 text-[#1d3e35] border border-[#99cab7]/60 hover:border-[#31725e] shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 focus:ring-[#99cab7]/30 backdrop-blur-md',
        'ghost' => 'bg-transparent hover:bg-white/40 text-[#295c4d] hover:text-[#1d3e35] focus:ring-[#99cab7]/20',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white shadow-lg shadow-red-600/20 hover:-translate-y-0.5 active:translate-y-0 focus:ring-red-500/30',
    ];

    $classes = $baseClasses . ($variants[$variant] ?? $variants['primary']) . ($fullWidth ? ' w-full' : '');
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($icon && $iconPosition === 'left')
        <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0 transition-transform group-hover:-translate-x-0.5"></i>
    @endif

    <span>{{ $slot }}</span>

    @if($icon && $iconPosition === 'right')
        <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0 transition-transform group-hover:translate-x-0.5"></i>
    @endif
</button>
