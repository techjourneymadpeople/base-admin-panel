@props([
    'type' => 'info',
    'title' => null,
    'message' => null,
    'dismissible' => true,
])

@php
    $types = [
        'success' => [
            'wrapper' => 'bg-[#e2f0ea]/90 border-[#68ad94]/60 text-[#1d3e35]',
            'icon' => 'check-circle-2',
            'iconColor' => 'text-[#31725e]',
        ],
        'error' => [
            'wrapper' => 'bg-red-50/90 border-red-300 text-red-900',
            'icon' => 'alert-octagon',
            'iconColor' => 'text-red-600',
        ],
        'warning' => [
            'wrapper' => 'bg-[#ead7be]/90 border-[#cca06e]/70 text-[#623c2c]',
            'icon' => 'alert-triangle',
            'iconColor' => 'text-[#b17042]',
        ],
        'info' => [
            'wrapper' => 'bg-[#f2f8f5]/90 border-[#99cab7]/60 text-[#1d3e35]',
            'icon' => 'info',
            'iconColor' => 'text-[#428e75]',
        ],
    ];

    $config = $types[$type] ?? $types['info'];
@endphp

<div 
    x-data="{ show: true }" 
    x-show="show" 
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    {{ $attributes->merge([
        'class' => 'rounded-2xl p-4 border backdrop-blur-md shadow-sm flex items-start gap-3 transition-all duration-200 ' . $config['wrapper']
    ]) }}
>
    <div class="shrink-0 pt-0.5">
        <i data-lucide="{{ $config['icon'] }}" class="w-5 h-5 {{ $config['iconColor'] }}"></i>
    </div>

    <div class="flex-1 text-sm leading-relaxed">
        @if($title)
            <h5 class="font-bold mb-0.5 tracking-tight">{{ $title }}</h5>
        @endif

        @if($message)
            <p>{{ $message }}</p>
        @else
            {{ $slot }}
        @endif
    </div>

    @if($dismissible)
        <button 
            type="button" 
            @click="show = false" 
            class="shrink-0 text-current opacity-60 hover:opacity-100 transition-opacity p-0.5 rounded-lg focus:outline-none"
            aria-label="Tutup Notifikasi"
        >
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    @endif
</div>
