@props([
    'title',
    'icon' => null,
    'active' => false,
    'badge' => null,
    'badgeColor' => 'amber',
])

@php
    $badgeClasses = [
        'emerald' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
        'amber' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
        'rose' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
        'blue' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
    ][$badgeColor] ?? 'bg-amber-500/20 text-amber-300 border-amber-500/30';
@endphp

<li class="px-2 my-0.5" x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button 
        type="button" 
        @click="open = !open" 
        class="w-full group relative flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-medium transition-all duration-200 cursor-pointer select-none {{ $active ? 'bg-white/15 text-white font-semibold' : 'text-[#e2f0ea]/80 hover:text-white hover:bg-white/10' }}"
        :title="$store.sidebar.collapsed ? '{{ addslashes($title) }}' : ''"
    >
        <div class="flex items-center gap-3 truncate">
            @if($icon)
                <i data-lucide="{{ $icon }}" class="w-4 h-4 shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $active ? 'text-[#c5e1d5]' : 'text-[#99cab7]' }}"></i>
            @endif

            <span 
                class="truncate transition-all duration-200"
                :class="$store.sidebar.collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100 block'"
            >
                {{ $title }}
            </span>
        </div>

        <div class="flex items-center gap-2" :class="$store.sidebar.collapsed ? 'hidden' : 'flex'">
            @if($badge)
                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full border uppercase tracking-wider {{ $badgeClasses }}">
                    {{ $badge }}
                </span>
            @endif

            <svg 
                class="w-4 h-4 text-[#99cab7] transition-transform duration-200" 
                :class="open ? 'rotate-180 text-white' : ''"
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                stroke-width="2"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </button>

    <!-- Dropdown Sub-links List -->
    <ul 
        x-show="open && !$store.sidebar.collapsed" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="mt-1 space-y-0.5 pl-4 pr-1 border-l-2 border-[#428e75]/30 ml-4 my-1"
        x-cloak
    >
        {{ $slot }}
    </ul>
</li>
