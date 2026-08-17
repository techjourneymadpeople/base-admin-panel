<x-layouts.admin title="Detail Menu - {{ $menu->title }}">
    <x-admin.breadcrumb 
        title="Detail Menu" 
        :items="[
            'Kelola Menu' => route('admin.menus.index'),
            $menu->title => ''
        ]" 
    />

    <div class="space-y-6 max-w-5xl">
        <!-- 1. Hero Header Card -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#1d3e35] flex items-center justify-center text-white text-xl shadow-md shrink-0">
                        <i data-lucide="{{ $menu->icon ?: 'menu' }}" class="w-7 h-7 text-[#cca06e]"></i>
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl sm:text-2xl font-extrabold text-[#1d3e35]">{{ $menu->title }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border uppercase tracking-wider
                                {{ $menu->type === 'header' ? 'bg-stone-100 text-stone-700 border-stone-200' : ($menu->type === 'link' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200') }}">
                                Tipe: {{ ucfirst($menu->type) }}
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-1 flex items-center gap-2">
                            <span>Urutan: <strong>#{{ $menu->order }}</strong></span>
                            <span>&bull;</span>
                            <span>Status: <strong>{{ $menu->is_active ? 'Aktif' : 'Non-Aktif' }}</strong></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a 
                        href="{{ route('admin.menus.permissions', $menu->id) }}" 
                        class="px-4 py-2 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-xs inline-flex items-center gap-1.5 shadow-2xs transition-colors"
                    >
                        <i data-lucide="key" class="w-4 h-4 text-[#b17042]"></i>
                        <span>Assign View Permissions</span>
                    </a>

                    <a 
                        href="{{ route('admin.menus.edit', $menu->id) }}" 
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md hover:shadow-lg transition-all"
                    >
                        <i data-lucide="edit-3" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Edit Menu</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Metadata Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Parent Menu</p>
                <p class="text-xs font-bold text-[#1d3e35]">{{ $menu->parent ? $menu->parent->title : 'Top Level (Tanpa Parent)' }}</p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Target Rute / URL</p>
                <p class="text-xs font-mono font-bold text-[#1d3e35] truncate">{{ $menu->route ?: ($menu->url ?: '—') }}</p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Target Jendela</p>
                <p class="text-xs font-mono font-bold text-stone-800">{{ $menu->target }}</p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Badge Menu</p>
                <p class="text-xs font-bold text-stone-800">{{ $menu->badge ?: 'Tidak Ada' }}</p>
            </div>
        </div>

        <!-- 3. Assigned View Permissions -->
        <x-admin.card 
            title="Hak Akses Visibilitas (View Permissions)" 
            subtitle="Hanya user dengan role yang memiliki salah satu permission di bawah ini yang dapat melihat menu ini di sidebar."
        >
            @if($menu->permissions->isEmpty())
                <div class="p-5 rounded-2xl bg-[#f2f8f5]/60 border border-[#99cab7]/30 text-xs text-[#295c4d] flex items-center gap-2.5">
                    <i data-lucide="unlock" class="w-5 h-5 text-[#31725e] shrink-0"></i>
                    <span>Menu ini bersifat <strong>Terbuka untuk Seluruh Pengguna Login</strong> (Belum ada permission khusus yang dibatasi).</span>
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($menu->permissions as $perm)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#e2f0ea] border border-[#99cab7]/40 text-xs font-semibold font-mono text-[#1d3e35]">
                            <i data-lucide="key" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>{{ $perm->name }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </x-admin.card>

        <!-- 4. Sub-Menu Children (If Dropdown) -->
        @if($menu->type === 'dropdown')
            <x-admin.card 
                title="Daftar Sub-Menu Anak" 
                subtitle="Item menu yang berada langsung di dalam grup dropdown ini."
            >
                @if($menu->children->isEmpty())
                    <p class="text-xs text-stone-500 italic py-2">Belum ada sub-menu yang terdaftar di bawah menu ini.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($menu->children as $child)
                            <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center text-xs shrink-0">
                                        <i data-lucide="{{ $child->icon ?: 'circle' }}" class="w-4 h-4"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-[#1d3e35] truncate">{{ $child->title }}</p>
                                        <p class="text-[11px] font-mono text-stone-400 truncate">{{ $child->route ?: $child->url }}</p>
                                    </div>
                                </div>

                                <a href="{{ route('admin.menus.show', $child->id) }}" class="p-1.5 rounded-lg text-stone-400 hover:text-[#31725e] hover:bg-[#f2f8f5] transition-colors">
                                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-admin.card>
        @endif
    </div>
</x-layouts.admin>
