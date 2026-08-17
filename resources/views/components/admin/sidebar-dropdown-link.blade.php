@props([
    'href' => '#',
    'active' => false,
    'badge' => null,
])

<li>
    <a 
        href="{{ $href }}" 
        {{ $attributes->merge([
            'class' => 'group flex items-center justify-between gap-2 px-3 py-2 rounded-xl text-xs font-medium transition-all duration-150 ' .
            ($active 
                ? 'bg-[#428e75]/30 text-white font-semibold shadow-sm' 
                : 'text-[#c5e1d5]/75 hover:text-white hover:bg-white/10')
        ]) }}
    >
        <div class="flex items-center gap-2.5 truncate">
            <span class="w-1.5 h-1.5 rounded-full transition-all duration-150 {{ $active ? 'bg-[#cca06e] scale-125' : 'bg-[#99cab7]/60 group-hover:bg-white' }}"></span>
            <span class="truncate">{{ $slot }}</span>
        </div>

        @if($badge)
            <span class="px-1.5 py-0.5 text-[9px] font-bold rounded-full bg-white/20 text-white">
                {{ $badge }}
            </span>
        @endif
    </a>
</li>
