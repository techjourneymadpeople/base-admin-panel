@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
    'badge' => null,
    'badgeColor' => 'emerald',
])

@php
    $badgeClasses = [
        'emerald' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
        'amber' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
        'rose' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
        'blue' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
    ][$badgeColor] ?? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
@endphp

<li class="px-2 my-0.5">
    <a 
        href="{{ $href }}" 
        {{ $attributes->merge([
            'class' => 'group relative flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 ' . 
            ($active 
                ? 'bg-gradient-to-r from-[#295c4d] to-[#31725e] text-white shadow-lg shadow-[#1d3e35]/30 font-semibold' 
                : 'text-[#e2f0ea]/80 hover:text-white hover:bg-white/10')
        ]) }}
        :title="$store.sidebar.collapsed ? '{{ addslashes($slot) }}' : ''"
    >
        @if($active)
            <!-- Active indicator dot/bar -->
            <span class="absolute left-1 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#cca06e] rounded-r-full"></span>
        @endif

        @if($icon)
            <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $active ? 'text-[#c5e1d5]' : 'text-[#99cab7]' }}"></i>
        @endif

        <span 
            class="flex-1 truncate transition-all duration-200"
            :class="$store.sidebar.collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100 block'"
        >
            {{ $slot }}
        </span>

        @if($badge)
            <span 
                class="px-2 py-0.5 text-[10px] font-bold rounded-full border uppercase tracking-wider transition-all duration-200 {{ $badgeClasses }}"
                :class="$store.sidebar.collapsed ? 'hidden' : 'inline-block'"
            >
                {{ $badge }}
            </span>
        @endif
    </a>
</li>
