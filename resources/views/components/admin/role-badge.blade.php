@props([
    'role' => 'User',
])

@php
    $roleName = is_string($role) ? $role : ($role->name ?? 'User');

    $styles = [
        'Super Admin' => 'bg-[#1d3e35] text-white border-[#1d3e35]',
        'Owner' => 'bg-[#784732] text-white border-[#784732]',
        'Admin' => 'bg-[#295c4d] text-white border-[#295c4d]',
        'Support' => 'bg-[#b17042] text-white border-[#b17042]',
        'Editor' => 'bg-[#31725e] text-white border-[#31725e]',
        'User' => 'bg-[#e2f0ea] text-[#1d3e35] border-[#99cab7]/40',
    ];

    $badgeStyle = $styles[$roleName] ?? 'bg-stone-100 text-stone-800 border-stone-200';
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider shadow-2xs {{ $badgeStyle }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-75"></span>
    {{ $roleName }}
</span>
