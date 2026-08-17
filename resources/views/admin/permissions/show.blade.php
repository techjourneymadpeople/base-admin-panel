<x-layouts.admin title="Detail Permission - {{ $permission->name }}">
    <x-admin.breadcrumb 
        title="Detail Permission" 
        :items="[
            'Kelola Permission' => route('admin.permissions.index'),
            $permission->name => ''
        ]" 
    />

    <div class="space-y-6 max-w-4xl">
        <!-- 1. Header Hero Card -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#1d3e35] flex items-center justify-center text-white font-mono text-xl shadow-md shrink-0">
                        <i data-lucide="key" class="w-7 h-7 text-[#cca06e]"></i>
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl sm:text-2xl font-extrabold font-mono text-[#1d3e35]">{{ $permission->name }}</h2>
                        </div>
                        <p class="text-xs text-stone-500 mt-1 flex items-center gap-1.5">
                            <i data-lucide="shield" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>Dipasangkan pada <strong>{{ $permission->roles->count() }}</strong> role aktif</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a 
                        href="{{ route('admin.permissions.edit', $permission->id) }}" 
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md hover:shadow-lg transition-all"
                    >
                        <i data-lucide="edit-3" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Edit Permission</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Metadata Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Guard Name</p>
                <p class="text-xs font-mono font-bold text-[#1d3e35]">{{ $permission->guard_name }}</p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Kategori Modul</p>
                @php
                    $parts = explode('-', $permission->name, 2);
                    $module = count($parts) === 2 ? ucfirst($parts[1]) : 'General';
                @endphp
                <p class="text-xs font-bold text-[#1d3e35]">{{ $module }}</p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Dibuat Pada</p>
                <p class="text-xs font-bold text-stone-800">
                    {{ $permission->created_at ? $permission->created_at->translatedFormat('d F Y, H:i') : '-' }}
                </p>
            </div>
        </div>

        <!-- 3. Roles Having This Permission -->
        <x-admin.card 
            title="Daftar Role yang Memiliki Permission Ini" 
            subtitle="Tingkatan role yang diberikan izin untuk aksi ini."
        >
            @if($permission->roles->isEmpty())
                <p class="text-xs text-stone-500 italic py-2">Belum ada role yang menggunakan permission ini.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($permission->roles as $r)
                        <div class="p-4 rounded-2xl bg-[#f2f8f5]/60 border border-[#99cab7]/40 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <x-admin.role-badge :role="$r->name" />
                            </div>

                            <a href="{{ route('admin.roles.permissions', $r->id) }}" class="text-xs font-bold text-[#31725e] hover:underline inline-flex items-center gap-1">
                                <span>Kelola</span>
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-admin.card>
    </div>
</x-layouts.admin>
