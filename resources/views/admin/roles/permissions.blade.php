<x-layouts.admin title="Assign Permissions - {{ $role->name }}">
    <x-admin.breadcrumb 
        title="Assign Permissions" 
        :items="[
            'Kelola Role' => route('admin.roles.index'),
            $role->name => route('admin.roles.show', $role->id),
            'Assign Permissions' => ''
        ]" 
    />

    <form 
        method="POST" 
        action="{{ route('admin.roles.permissions.update', $role->id) }}" 
        x-data="{
            selected: {{ json_encode($rolePermissionNames) }},
            toggleGroup(groupPerms) {
                const allSelected = groupPerms.every(p => this.selected.includes(p));
                if (allSelected) {
                    this.selected = this.selected.filter(p => !groupPerms.includes(p));
                } else {
                    const newSet = new Set([...this.selected, ...groupPerms]);
                    this.selected = Array.from(newSet);
                }
            },
            isGroupAllSelected(groupPerms) {
                return groupPerms.length > 0 && groupPerms.every(p => this.selected.includes(p));
            },
            selectAll(allPerms) {
                this.selected = [...allPerms];
            },
            deselectAll() {
                this.selected = [];
            }
        }"
        class="space-y-6 max-w-6xl"
    >
        @csrf
        @method('PUT')

        <!-- 1. Header Banner -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#295c4d] to-[#cca06e] p-1 shadow-md shrink-0">
                    <div class="w-full h-full bg-[#1d3e35] rounded-[14px] flex items-center justify-center text-white font-extrabold text-xl">
                        <i data-lucide="shield-check" class="w-7 h-7 text-[#cca06e]"></i>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-[#1d3e35]">Assign Permissions: {{ $role->name }}</h2>
                    </div>
                    <p class="text-xs text-stone-500 mt-1">
                        Pilih hak akses permission yang diizinkan untuk peran ini. Perubahan akan langsung berdampak pada seluruh pengguna terkait.
                    </p>
                </div>
            </div>

            <!-- Global Action Controls -->
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                @php
                    $allPermissionNames = $groupedPermissions->flatten()->pluck('name')->toArray();
                @endphp
                <button 
                    type="button" 
                    @click="selectAll({{ json_encode($allPermissionNames) }})"
                    class="px-3.5 py-2 rounded-xl border border-[#99cab7]/50 hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-1.5 transition-colors"
                >
                    <i data-lucide="check-square" class="w-3.5 h-3.5 text-[#31725e]"></i>
                    <span>Pilih Semua</span>
                </button>

                <button 
                    type="button" 
                    @click="deselectAll()"
                    class="px-3.5 py-2 rounded-xl border border-stone-200 hover:bg-stone-50 text-stone-600 font-bold text-xs inline-flex items-center gap-1.5 transition-colors"
                >
                    <i data-lucide="square" class="w-3.5 h-3.5"></i>
                    <span>Kosongkan</span>
                </button>

                <x-form.button 
                    type="submit" 
                    variant="primary" 
                    :fullWidth="false" 
                    icon="save"
                >
                    Simpan Hak Akses
                </x-form.button>
            </div>
        </div>

        @if($role->name === 'Super Admin')
            <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-[#784732] font-semibold flex items-center gap-2.5">
                <i data-lucide="info" class="w-5 h-5 text-[#b17042] shrink-0"></i>
                <span><strong>Catatan:</strong> Role <strong>Super Admin</strong> memiliki izin Bypass sistem bawaan sehingga secara default memiliki semua akses tanpa bergantung pada checklist ini.</span>
            </div>
        @endif

        <!-- 2. Grouped Permissions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($groupedPermissions as $moduleName => $permissions)
                @php
                    $modulePermNames = $permissions->pluck('name')->toArray();
                @endphp
                <div class="rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between overflow-hidden">
                    <!-- Module Header -->
                    <div class="p-4 sm:p-5 bg-[#f2f8f5]/70 border-b border-[#99cab7]/30 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-white border border-[#99cab7]/40 flex items-center justify-center text-[#31725e] shrink-0">
                                <i data-lucide="folder-lock" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-extrabold text-[#1d3e35] truncate">Modul {{ $moduleName }}</h3>
                                <p class="text-[10px] text-stone-400 font-medium">{{ count($permissions) }} permissions</p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="toggleGroup({{ json_encode($modulePermNames) }})"
                            class="px-2.5 py-1 rounded-lg text-[11px] font-bold border transition-colors shrink-0"
                            :class="isGroupAllSelected({{ json_encode($modulePermNames) }}) 
                                ? 'bg-emerald-100 text-emerald-800 border-emerald-300' 
                                : 'bg-white text-stone-600 border-stone-200 hover:bg-stone-50'"
                        >
                            <span x-text="isGroupAllSelected({{ json_encode($modulePermNames) }}) ? 'Batal Semua' : 'Pilih Semua'"></span>
                        </button>
                    </div>

                    <!-- Module Checkbox List -->
                    <div class="p-4 sm:p-5 space-y-3 flex-1">
                        @foreach($permissions as $perm)
                            <label class="flex items-start gap-3 p-2.5 rounded-2xl border transition-all cursor-pointer select-none"
                                :class="selected.includes('{{ $perm->name }}') 
                                    ? 'bg-[#e2f0ea]/50 border-[#428e75]/50 shadow-2xs' 
                                    : 'bg-white border-stone-100 hover:border-stone-200 hover:bg-stone-50/50'"
                            >
                                <input 
                                    type="checkbox" 
                                    name="permissions[]" 
                                    value="{{ $perm->name }}"
                                    x-model="selected"
                                    class="mt-0.5 w-4 h-4 rounded-md border-stone-300 text-[#1d3e35] focus:ring-[#31725e] accent-[#1d3e35]"
                                >
                                <div class="min-w-0">
                                    <span class="block text-xs font-bold font-mono text-[#1d3e35] truncate">{{ $perm->name }}</span>
                                    <span class="block text-[11px] text-stone-400">Guard: {{ $perm->guard_name }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- 3. Bottom Sticky Action Bar -->
        <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/30 shadow-md flex items-center justify-between gap-4">
            <div class="text-xs text-stone-500">
                Total permission terpilih: <strong class="text-[#1d3e35]" x-text="selected.length"></strong> dari {{ count($allPermissionNames) }}
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('admin.roles.index') }}" 
                    class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs transition-colors"
                >
                    Kembali
                </a>

                <x-form.button 
                    type="submit" 
                    variant="primary" 
                    :fullWidth="false" 
                    icon="save"
                >
                    Simpan Hak Akses Role
                </x-form.button>
            </div>
        </div>
    </form>
</x-layouts.admin>
