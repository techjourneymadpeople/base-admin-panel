<x-layouts.admin title="Kelola Role & Hak Akses - {{ $user->name }}">
    <x-admin.breadcrumb 
        title="Penugasan Role Pengguna" 
        :items="[
            'Pengguna' => route('admin.users.index'),
            $user->name => route('admin.users.show', $user->id),
            'Kelola Role' => ''
        ]" 
    />

    @if(session('error'))
        <x-alert type="error" :message="session('error')" class="mb-6 max-w-4xl" />
    @endif

    <div class="max-w-4xl space-y-6">
        <!-- User Info Bar -->
        <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#295c4d] to-[#cca06e] p-0.5 shadow-xs shrink-0">
                    <div class="w-full h-full bg-[#1d3e35] rounded-[13px] flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#1d3e35]">{{ $user->name }}</h3>
                    <p class="text-xs text-stone-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-stone-400">Role Saat Ini:</span>
                @foreach($user->roles as $r)
                    <x-admin.role-badge :role="$r->name" />
                @endforeach
            </div>
        </div>

        <!-- Role Assignment Form Card -->
        <x-admin.card 
            title="Pilih Hak Akses / Role untuk Pengguna" 
            subtitle="Centang role yang ingin diberikan kepada pengguna. Pengguna dapat memiliki satu atau beberapa role sekaligus."
        >
            <x-slot:actions>
                <a 
                    href="{{ route('admin.users.index') }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-1 transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.users.roles.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                @error('roles')
                    <p class="text-xs text-red-600 font-bold flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror

                <!-- Roles Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($roles as $role)
                        @php
                            $isChecked = in_array($role->name, old('roles', $userRoleNames));
                        @endphp

                        <label 
                            class="relative flex flex-col p-5 rounded-2xl border-2 transition-all duration-200 cursor-pointer select-none {{ $isChecked ? 'border-[#31725e] bg-[#f2f8f5]/80 shadow-xs' : 'border-stone-200 hover:border-[#99cab7] bg-white' }}"
                            x-data="{ checked: {{ $isChecked ? 'true' : 'false' }} }"
                        >
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <input 
                                        type="checkbox" 
                                        name="roles[]" 
                                        value="{{ $role->name }}"
                                        {{ $isChecked ? 'checked' : '' }}
                                        @change="checked = $el.checked"
                                        class="w-4 h-4 rounded-md text-[#31725e] border-[#99cab7] focus:ring-[#428e75]/25 cursor-pointer"
                                    />
                                    <x-admin.role-badge :role="$role->name" />
                                </div>

                                <span class="text-[11px] font-semibold text-stone-400">
                                    {{ $role->permissions->count() }} Permission
                                </span>
                            </div>

                            <!-- Role Permissions Preview -->
                            <div class="mt-3 pt-2.5 border-t border-stone-100 flex flex-wrap gap-1">
                                @if($role->name === 'Super Admin')
                                    <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md">
                                        ⚡ Akses Penuh (Bypass Seluruh Permission)
                                    </span>
                                @else
                                    @foreach($role->permissions->take(6) as $perm)
                                        <span class="text-[10px] font-medium text-stone-600 bg-stone-100 px-2 py-0.5 rounded-md">
                                            {{ $perm->name }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->count() > 6)
                                        <span class="text-[10px] font-semibold text-[#b17042] bg-amber-50 px-1.5 py-0.5 rounded-md">
                                            +{{ $role->permissions->count() - 6 }} lainnya
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('admin.users.index') }}" 
                        class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs transition-colors"
                    >
                        Batal
                    </a>

                    <x-form.button 
                        type="submit" 
                        variant="primary" 
                        :fullWidth="false" 
                        icon="shield-check"
                    >
                        Simpan Penugasan Role
                    </x-form.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-layouts.admin>
