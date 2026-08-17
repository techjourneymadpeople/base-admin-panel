@props([
    'title' => '',
    'items' => [],
])

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-[#1d3e35] tracking-tight">
            {{ $title }}
        </h1>
    </div>

    @if(count($items) > 0)
        <nav class="flex items-center gap-1.5 text-xs text-stone-500 font-medium" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-1 text-[#428e75] hover:text-[#1d3e35] transition-colors">
                <i data-lucide="home" class="w-3.5 h-3.5"></i>
                <span>Admin</span>
            </a>

            @foreach($items as $label => $url)
                <i data-lucide="chevron-right" class="w-3 h-3 text-stone-400"></i>
                @if($loop->last || !$url)
                    <span class="text-stone-700 font-semibold truncate max-w-xs">{{ $label }}</span>
                @else
                    <a href="{{ $url }}" class="text-[#428e75] hover:text-[#1d3e35] transition-colors truncate max-w-xs">
                        {{ $label }}
                    </a>
                @endif
            @endforeach
        </nav>
    @endif
</div>
