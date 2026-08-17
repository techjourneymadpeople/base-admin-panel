@props([
    'title' => null,
    'subtitle' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-3xl shadow-sm border border-[#99cab7]/30 overflow-hidden transition-all duration-200 hover:shadow-md']) }}>
    @if($title || isset($actions))
        <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between gap-4">
            <div>
                @if($title)
                    <h3 class="text-sm sm:text-base font-bold text-[#1d3e35] tracking-tight">
                        {{ $title }}
                    </h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-stone-500 mt-0.5 font-normal">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            @if(isset($actions))
                <div class="flex items-center gap-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-3.5 bg-[#f2f8f5]/60 border-t border-stone-100 text-xs text-stone-500">
            {{ $footer }}
        </div>
    @endif
</div>
