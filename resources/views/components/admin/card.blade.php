@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
    'collapsible' => false,
    'open' => true,
    'icon' => null,
    'badge' => null,
])

<div 
    {{ $attributes->merge(['class' => 'bg-white rounded-3xl shadow-sm border border-[#99cab7]/30 overflow-hidden transition-all duration-200 hover:shadow-md']) }}
    @if($collapsible)
        x-data="{ isOpen: {{ $open ? 'true' : 'false' }} }"
    @endif
>
    @if($title || isset($actions))
        <div 
            @if($collapsible)
                @click="isOpen = !isOpen"
                role="button"
                tabindex="0"
                @keydown.space.prevent="isOpen = !isOpen"
                @keydown.enter.prevent="isOpen = !isOpen"
                class="px-6 py-4 border-b border-stone-100 flex items-center justify-between gap-4 cursor-pointer select-none hover:bg-[#f4f8f6]/60 transition-colors"
            @else
                class="px-6 py-4 border-b border-stone-100 flex items-center justify-between gap-4"
            @endif
        >
            <div class="flex items-center gap-3 min-w-0">
                @if($icon)
                    <div class="w-8 h-8 rounded-xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center shrink-0">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                    </div>
                @endif
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($title)
                            <h3 class="text-sm sm:text-base font-bold text-[#1d3e35] tracking-tight">
                                {{ $title }}
                            </h3>
                        @endif
                        @if($badge)
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-[#e2f0ea] text-[#295c4d]">
                                {{ $badge }}
                            </span>
                        @endif
                    </div>
                    @if($subtitle)
                        <p class="text-xs text-stone-500 mt-0.5 font-normal">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if(isset($actions))
                    <div @if($collapsible) @click.stop @endif class="flex items-center gap-2">
                        {{ $actions }}
                    </div>
                @endif

                @if($collapsible)
                    <div 
                        class="w-7 h-7 rounded-xl bg-[#f2f8f5] text-[#295c4d] flex items-center justify-center transition-transform duration-200"
                        :class="{ 'rotate-180': isOpen }"
                    >
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div 
        @if($collapsible)
            x-show="isOpen" 
            x-collapse
        @endif
    >
        <div class="{{ $padding ? 'p-6' : '' }}">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="px-6 py-3.5 bg-[#f2f8f5]/60 border-t border-stone-100 text-xs text-stone-500">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
