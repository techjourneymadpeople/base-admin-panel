<x-layouts.admin title="Detail Role - {{ $role->name }}">
    <x-admin.breadcrumb 
        title="Detail Role" 
        :items="[
            'Kelola Role' => route('admin.roles.index'),
            $role->name => ''
        ]" 
    />

    <div class="space-y-6 max-w-5xl">
        <!-- 1. Header Card -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#1d3e35] flex items-center justify-center text-white font-black text-xl shadow-md shrink-0">
                        <i data-lucide="shield" class="w-7 h-7 text-[#cca06e]"></i>
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl sm:text-2xl font-extrabold text-[#1d3e35]">{{ $role->name }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-semibold bg-stone-100 text-stone-600 border border-stone-200">
                                guard: {{ $role->guard_name }}
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-1 flex items-center gap-1.5">
                            <i data-lucide="users" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>Digunakan oleh <strong>{{ $role->users->count() }}</strong> pengguna</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a 
                        href="{{ route('admin.roles.permissions', $role->id) }}" 
                        class="px-4 py-2 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-xs inline-flex items-center gap-1.5 shadow-2xs transition-colors"
                    >
                        <i data-lucide="shield-check" class="w-4 h-4 text-[#b17042]"></i>
                        <span>Assign Permissions</span>
                    </a>

                    <a 
                        href="{{ route('admin.roles.edit', $role->id) }}" 
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md hover:shadow-lg transition-all"
                    >
                        <i data-lucide="edit-3" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Edit Role</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Assigned Permissions Breakdown -->
        <x-admin.card 
            title="Daftar Hak Akses (Permissions) Terpasang" 
            subtitle="Seluruh hak akses yang diberikan kepada pengguna dengan role ini."
        >
            @if($role->name === 'Super Admin')
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 font-semibold flex items-center gap-2">
                    <i data-lucide="zap" class="w-4 h-4 text-emerald-700 shrink-0"></i>
                    <span>Role <strong>Super Admin</strong> memiliki akses Bypass menyeluruh ke semua fitur dan modul sistem secara otomatis.</span>
                </div>
            @elseif($role->permissions->isEmpty())
                <div class="p-6 rounded-2xl bg-stone-50 border border-stone-200/60 text-center space-y-2">
                    <i data-lucide="shield-off" class="w-8 h-8 text-stone-400 mx-auto"></i>
                    <p class="text-xs text-stone-500">Belum ada permission yang dipasangkan ke role ini.</p>
                    <a href="{{ route('admin.roles.permissions', $role->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#31725e] hover:underline">
                        <span>Tambahkan Permission Sekarang &rarr;</span>
                    </a>
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($role->permissions as $perm)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#f2f8f5] border border-[#99cab7]/40 text-xs font-semibold text-[#295c4d]">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>{{ $perm->name }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </x-admin.card>

        <!-- 3. Users With This Role -->
        <x-admin.card 
            title="Daftar Pengguna dengan Role Ini" 
            subtitle="Akun pengguna yang saat ini memiliki role {{ $role->name }}."
        >
            @if($role->users->isEmpty())
                <p class="text-xs text-stone-500 italic py-2">Belum ada pengguna yang memiliki role ini.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($role->users as $u)
                        <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 hover:border-[#31725e] transition-colors flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-xl bg-[#1d3e35] text-white flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-[#1d3e35] truncate">{{ $u->name }}</p>
                                    <p class="text-[11px] text-stone-400 truncate">{{ $u->email }}</p>
                                </div>
                            </div>

                            <a href="{{ route('admin.users.show', $u->id) }}" class="p-1.5 rounded-lg text-stone-400 hover:text-[#31725e] hover:bg-[#f2f8f5] transition-colors" title="Lihat Pengguna">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-admin.card>
    </div>
</x-layouts.admin>
