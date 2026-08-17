@props([
    'title' => '',
    'subtitle' => '',
    'badge' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'glass-panel rounded-3xl p-6 sm:p-10 shadow-2xl relative overflow-hidden transition-all duration-300']) }}>
    <!-- Soft Decorative Glass Accent Border Top -->
    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#31725e] via-[#428e75] to-[#cca06e]"></div>

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between gap-4 mb-3">
            @if($badge)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold tracking-wide text-[#295c4d] bg-[#e2f0ea]/80 border border-[#99cab7]/50 backdrop-blur-md">
                    @if($icon)
                        <i data-lucide="{{ $icon }}" class="w-3.5 h-3.5 text-[#31725e]"></i>
                    @endif
                    {{ $badge }}
                </span>
            @endif
        </div>

        @if($title)
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1d3e35] tracking-tight">
                {{ $title }}
            </h1>
        @endif

        @if($subtitle)
            <p class="mt-2 text-sm text-[#623c2c]/80 leading-relaxed">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <!-- Main Form Slot -->
    <div class="space-y-6">
        {{ $slot }}
    </div>

    <!-- Optional Footer Slot -->
    @if(isset($footer))
        <div class="mt-8 pt-6 border-t border-[#99cab7]/30 text-center text-sm text-[#623c2c]">
            {{ $footer }}
        </div>
    @endif
</div>
