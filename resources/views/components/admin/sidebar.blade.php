<!-- Desktop & Mobile Sidebar Container -->
<aside 
    class="fixed inset-y-0 left-0 z-40 flex flex-col transition-all duration-300 ease-in-out bg-gradient-to-b from-[#1d3e35] via-[#234b3f] to-[#1d3e35] text-white shadow-2xl border-r border-[#428e75]/20 overflow-hidden"
    :class="{
        'w-64': !$store.sidebar.collapsed,
        'w-20': $store.sidebar.collapsed,
        'translate-x-0': $store.sidebar.mobileOpen,
        '-translate-x-full lg:translate-x-0': !$store.sidebar.mobileOpen
    }"
>
    <!-- Brand Header -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-[#428e75]/25 bg-black/10 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group transition-transform duration-200">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#31725e] via-[#428e75] to-[#cca06e] p-0.5 shadow-md flex items-center justify-center shrink-0">
                <div class="w-full h-full bg-[#1d3e35] rounded-[10px] flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-4 h-4 text-[#c5e1d5]"></i>
                </div>
            </div>

            <div 
                class="flex flex-col transition-all duration-200"
                :class="$store.sidebar.collapsed ? 'opacity-0 w-0 hidden' : 'opacity-100 block'"
            >
                <span class="text-base font-extrabold tracking-tight text-white leading-tight">
                    {{ config('app.name', 'Admin Panel') }}
                </span>
                <span class="text-[10px] font-semibold text-[#cca06e] uppercase tracking-wider -mt-0.5">
                    Workspace
                </span>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button 
            type="button" 
            @click="$store.sidebar.closeMobile()"
            class="lg:hidden p-1.5 rounded-xl text-[#c5e1d5] hover:text-white hover:bg-white/10"
            aria-label="Tutup Menu"
        >
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Mini User Profile Card (Viho Style) -->
    <div 
        class="px-4 py-3 mx-2 my-3 rounded-2xl bg-white/10 border border-white/10 backdrop-blur-md transition-all duration-200 shrink-0"
        :class="$store.sidebar.collapsed ? 'p-2 mx-1' : 'p-3 mx-2'"
    >
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#428e75] to-[#cca06e] flex items-center justify-center text-white font-bold text-sm shadow-md shrink-0">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div 
                class="flex-1 min-w-0 transition-all duration-200"
                :class="$store.sidebar.collapsed ? 'hidden' : 'block'"
            >
                <h4 class="text-xs font-bold text-white truncate">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </h4>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="text-[11px] text-[#c5e1d5]/80 font-medium truncate">
                        {{ auth()->user()->roles->pluck('name')->first() ?? 'Admin' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @php
        // Fetch active top-level menus with active children
        try {
            $sidebarMenus = \App\Models\Menu::with('children')->topLevel()->active()->get();
        } catch (\Throwable $e) {
            $sidebarMenus = collect();
        }
    @endphp

    <!-- Dynamic Navigation Menu List (Database Driven) -->
    <nav class="flex-1 overflow-y-auto px-1 py-2 space-y-1 scrollbar-thin scrollbar-thumb-[#428e75]/40">
        <ul class="space-y-0.5">
            @forelse($sidebarMenus as $menu)
                @if($menu->isVisibleForUser(auth()->user()))
                    @if($menu->type === 'header')
                        <!-- Section Heading -->
                        <x-admin.sidebar-heading :title="$menu->title" />

                    @elseif($menu->type === 'dropdown' || ($menu->children && $menu->children->isNotEmpty()))
                        <!-- Dropdown Menu Item -->
                        <x-admin.sidebar-dropdown 
                            :title="$menu->title" 
                            :icon="$menu->icon" 
                            :active="$menu->isActive()"
                            :badge="$menu->badge"
                            :badgeColor="$menu->badge_color"
                        >
                            @foreach($menu->children as $child)
                                @if($child->isVisibleForUser(auth()->user()))
                                    <x-admin.sidebar-dropdown-link 
                                        :href="$child->getUrl()" 
                                        :active="$child->isActive()"
                                        :badge="$child->badge"
                                    >
                                        {{ $child->title }}
                                    </x-admin.sidebar-dropdown-link>
                                @endif
                            @endforeach
                        </x-admin.sidebar-dropdown>

                    @else
                        <!-- Direct Single Link Item -->
                        <x-admin.sidebar-link 
                            :href="$menu->getUrl()" 
                            :active="$menu->isActive()" 
                            :icon="$menu->icon" 
                            :badge="$menu->badge"
                            :badgeColor="$menu->badge_color"
                        >
                            {{ $menu->title }}
                        </x-admin.sidebar-link>
                    @endif
                @endif
            @empty
                <!-- Fallback Dashboard link if database not yet migrated/seeded -->
                <x-admin.sidebar-heading title="General" />
                <x-admin.sidebar-link 
                    :href="route('admin.dashboard')" 
                    :active="request()->routeIs('admin.dashboard')" 
                    icon="layout-dashboard" 
                    badge="Utama"
                    badgeColor="emerald"
                >
                    Dashboard
                </x-admin.sidebar-link>
            @endforelse
        </ul>
    </nav>

    <!-- Bottom Footer Brand info -->
    <div class="p-3 border-t border-[#428e75]/25 bg-black/15 shrink-0 text-center">
        <div 
            class="text-[11px] text-[#c5e1d5]/70 flex items-center justify-center gap-1.5 transition-all duration-200"
            :class="$store.sidebar.collapsed ? 'hidden' : 'flex'"
        >
            <i data-lucide="leaf" class="w-3.5 h-3.5 text-[#cca06e]"></i>
            <span>{{ config('app.name', 'Admin Panel') }} v1.0</span>
        </div>
        <div 
            class="transition-all duration-200 text-center"
            :class="$store.sidebar.collapsed ? 'block' : 'hidden'"
            x-cloak
        >
            <i data-lucide="leaf" class="w-4 h-4 text-[#cca06e] mx-auto"></i>
        </div>
    </div>
</aside>

<!-- Mobile Overlay Backdrop -->
<div 
    x-show="$store.sidebar.mobileOpen" 
    @click="$store.sidebar.closeMobile()"
    x-transition:enter="transition-opacity ease-linear duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm lg:hidden"
    x-cloak
></div>
