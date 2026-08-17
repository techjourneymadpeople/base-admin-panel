@props([
    'name',
    'label' => null,
    'checked' => false,
    'required' => false,
])

<label for="{{ $name }}" class="relative inline-flex items-center gap-2.5 cursor-pointer group select-none">
    <input
        type="checkbox"
        name="{{ $name }}"
        id="{{ $name }}"
        value="1"
        {{ old($name, $checked) ? 'checked' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-4 h-4 rounded-md text-[#31725e] bg-white/70 border-[#99cab7] focus:ring-2 focus:ring-[#428e75]/30 focus:ring-offset-0 transition duration-150 cursor-pointer accent-[#31725e]'
        ]) }}
    />
    @if($label || isset($slot))
        <span class="text-xs sm:text-sm text-[#295c4d] group-hover:text-[#1d3e35] transition-colors leading-tight">
            {{ $label ?? $slot }}
        </span>
    @endif
</label>
