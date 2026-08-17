@props([
    'title',
    'value',
    'icon' => 'activity',
    'trend' => null,
    'trendUp' => true,
    'trendLabel' => 'dari bulan lalu',
    'color' => 'emerald',
])

@php
    $colorConfigs = [
        'emerald' => [
            'iconBg' => 'bg-emerald-500/15 text-[#31725e]',
            'accent' => 'from-[#1d3e35] to-[#31725e]',
        ],
        'earth' => [
            'iconBg' => 'bg-amber-600/15 text-[#945838]',
            'accent' => 'from-[#784732] to-[#cca06e]',
        ],
        'amber' => [
            'iconBg' => 'bg-amber-500/15 text-[#b17042]',
            'accent' => 'from-[#945838] to-[#cca06e]',
        ],
        'blue' => [
            'iconBg' => 'bg-teal-500/15 text-[#295c4d]',
            'accent' => 'from-[#295c4d] to-[#428e75]',
        ],
    ];

    $config = $colorConfigs[$color] ?? $colorConfigs['emerald'];
@endphp

<div class="bg-white rounded-3xl p-6 shadow-sm border border-[#99cab7]/30 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 relative overflow-hidden group">
    <!-- Top accent highlight line on hover -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $config['accent'] }} opacity-0 group-hover:opacity-100 transition-opacity"></div>

    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-stone-500 mb-1">
                {{ $title }}
            </p>
            <h3 class="text-2xl sm:text-3xl font-extrabold text-[#1d3e35] tracking-tight">
                {{ $value }}
            </h3>
        </div>

        <div class="w-12 h-12 rounded-2xl {{ $config['iconBg'] }} flex items-center justify-center shrink-0 shadow-xs transition-transform group-hover:scale-110">
            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
        </div>
    </div>

    @if($trend)
        <div class="mt-4 pt-3 border-t border-stone-100 flex items-center gap-2 text-xs">
            <span class="inline-flex items-center gap-0.5 font-bold {{ $trendUp ? 'text-emerald-600' : 'text-rose-600' }}">
                <i data-lucide="{{ $trendUp ? 'trending-up' : 'trending-down' }}" class="w-3.5 h-3.5"></i>
                {{ $trend }}
            </span>
            <span class="text-stone-400 text-[11px] font-medium">{{ $trendLabel }}</span>
        </div>
    @endif
</div>
