@props([
    'name' => null,
    'id' => null,
    'title' => null,
    'maxWidth' => 'md',
])

@php
    $modalName = $name ?? $id ?? $attributes->get('id', 'modal_' . uniqid());
    $maxWidthClass = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ][$maxWidth] ?? 'max-w-md';
@endphp

<div 
    x-data="{ show: false, data: {} }"
    x-on:open-modal-{{ $modalName }}.window="show = true; data = $event.detail || {}"
    x-on:close-modal-{{ $modalName }}.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
    x-cloak
>
    <!-- Background Backdrop -->
    <div 
        x-show="show" 
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity"
        @click="show = false"
    ></div>

    <!-- Modal Panel Box -->
    <div 
        x-show="show" 
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="relative w-full {{ $maxWidthClass }} bg-white rounded-3xl shadow-2xl border border-[#99cab7]/40 overflow-hidden transform transition-all z-10"
        @click.stop
    >
        @if($title || isset($header))
            <div class="px-6 py-4 border-b border-stone-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-[#1d3e35] tracking-tight">
                    {{ $title ?? $header }}
                </h3>
                <button 
                    type="button" 
                    @click="show = false" 
                    class="p-1 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition-colors"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        @endif

        <div class="p-6">
            {{ $slot }}
        </div>

        @if(isset($footer))
            <div class="px-6 py-3.5 bg-[#f2f8f5]/70 border-t border-stone-100 flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
