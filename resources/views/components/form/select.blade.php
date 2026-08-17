@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'icon' => null,
    'placeholder' => '-- Pilih Opsi --',
    'helper' => null,
    'multiple' => false,
    'searchable' => true,
    'tomSelect' => true,
])

@php
    $id = $attributes->get('id', $name . '_' . uniqid());
    $hasError = $errors->has($name);
    $currentValue = old($name, $selected);

    // Normalize array for multiple selection
    if ($multiple && !is_array($currentValue)) {
        $currentValue = $currentValue ? [$currentValue] : [];
    }
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $id }}" class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
            {{ $label }}
            @if($required)
                <span class="text-[#b17042] ml-0.5">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-2xl {{ $hasError ? 'has-error' : '' }} {{ $icon ? 'has-icon' : '' }}">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#428e75] z-10">
                <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $hasError ? 'text-red-500' : 'text-[#428e75]' }}"></i>
            </div>
        @endif

        <select
            name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            id="{{ $id }}"
            {{ $required ? 'required' : '' }}
            {{ $multiple ? 'multiple' : '' }}
            @if($tomSelect) style="display: none;" @endif
            {{ $attributes->merge([
                'class' => 'w-full rounded-2xl py-3 text-sm text-[#1d3e35] transition-all duration-200 cursor-pointer appearance-none bg-white border ' .
                ($icon ? 'pl-10 ' : 'pl-4 ') .
                'pr-10 ' .
                ($hasError 
                    ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-400/20 bg-red-50/40 text-red-900' 
                    : 'border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 hover:border-[#31725e]')
            ]) }}
        >
            @if(!$multiple && $placeholder)
                <option value="" {{ empty($currentValue) ? 'selected' : '' }}>{{ $placeholder }}</option>
            @endif

            @foreach($options as $key => $display)
                @php
                    $optValue = is_numeric($key) && !is_array($display) ? $display : $key;
                    $optLabel = is_array($display) ? ($display['label'] ?? $optValue) : $display;
                    $isSelected = $multiple 
                        ? in_array((string)$optValue, array_map('strval', (array)$currentValue))
                        : (string)$currentValue === (string)$optValue;
                @endphp
                <option value="{{ $optValue }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach

            {{ $slot }}
        </select>
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

@if($tomSelect)
<script>
    (function () {
        function initTom_{{ str_replace('-', '_', $id) }}() {
            const el = document.getElementById('{{ $id }}');
            if (!el || el.tomselect) return;

            if (typeof window.TomSelect !== 'undefined') {
                new window.TomSelect(el, {
                    plugins: {{ $multiple ? "['remove_button']" : '[]' }},
                    create: false,
                    allowEmptyOption: true,
                    placeholder: '{{ addslashes($placeholder) }}',
                    maxItems: {{ $multiple ? 'null' : '1' }},
                    controlInput: {{ $searchable ? 'null' : 'false' }}
                });
            } else {
                setTimeout(initTom_{{ str_replace('-', '_', $id) }}, 50);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTom_{{ str_replace('-', '_', $id) }});
        } else {
            initTom_{{ str_replace('-', '_', $id) }}();
        }
    })();
</script>
@endif
