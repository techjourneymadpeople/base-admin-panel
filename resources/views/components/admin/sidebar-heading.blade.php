@props(['title'])

<li class="px-4 pt-5 pb-1.5 first:pt-2">
    <span 
        class="text-[11px] font-bold uppercase tracking-wider text-[#99cab7]/90 transition-all duration-200"
        :class="$store.sidebar.collapsed ? 'opacity-0 h-0 hidden' : 'opacity-100 block'"
    >
        {{ $title }}
    </span>
    <div 
        x-show="$store.sidebar.collapsed" 
        class="w-full h-px bg-[#31725e]/30 my-2" 
        x-cloak
    ></div>
</li>
