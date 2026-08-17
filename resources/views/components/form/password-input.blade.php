@props([
    'name' => 'password',
    'label' => 'Kata Sandi',
    'icon' => 'lock',
    'placeholder' => '••••••••',
    'required' => false,
    'autofocus' => false,
    'autocomplete' => 'current-password',
    'helper' => null,
])

@php
    $hasError = isset($errors) && $errors->has($name);
@endphp

<div class="space-y-1.5" x-data="{ show: false }">
    <div class="flex items-center justify-between">
        @if($label)
            <label for="{{ $name }}" class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                {{ $label }}
                @if($required)
                    <span class="text-[#b17042] ml-0.5">*</span>
                @endif
            </label>
        @endif

        {{ $extraLabel ?? '' }}
    </div>

    <div class="relative rounded-2xl transition-all duration-200">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#428e75]">
                <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $hasError ? 'text-red-500' : 'text-[#428e75]' }}"></i>
            </div>
        @endif

        <input
            :type="show ? 'text' : 'password'"
            name="{{ $name }}"
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $autofocus ? 'autofocus' : '' }}
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            {{ $attributes->merge([
                'class' => 'w-full rounded-2xl py-3 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/80 transition-all duration-200 ' .
                ($icon ? 'pl-10 ' : 'pl-4 ') .
                'pr-11 glass-input ' .
                ($hasError 
                    ? 'border-red-400/80 focus:border-red-500 focus:ring-4 focus:ring-red-400/20 bg-red-50/40 text-red-900' 
                    : 'border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/70 hover:bg-white/90')
            ]) }}
        />

        <!-- Password Visibility Toggle Button -->
        <button 
            type="button" 
            @click="show = !show" 
            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-[#68ad94] hover:text-[#1d3e35] transition-colors focus:outline-none cursor-pointer"
            tabindex="-1"
            aria-label="Tampilkan / Sembunyikan Kata Sandi"
        >
            <!-- Eye Open Icon (When hidden) -->
            <span x-show="!show" class="inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </span>

            <!-- Eye Off Icon (When shown) -->
            <span x-show="show" x-cloak class="inline-flex items-center text-[#b17042]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                    <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                    <line x1="2" x2="22" y1="2" y2="22"/>
                </svg>
            </span>
        </button>
    </div>

    @if($helper && !$hasError)
        <p class="text-xs text-[#623c2c]/75">{{ $helper }}</p>
    @endif

    @error($name)
        <p class="text-xs text-red-600 font-medium flex items-center gap-1 mt-1">
            <i data-lucide="alert-circle" class="w-3.5 h-3.5 shrink-0"></i>
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
