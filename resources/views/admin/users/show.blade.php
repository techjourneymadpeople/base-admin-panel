<x-layouts.admin title="Detail Pengguna - {{ $user->name }}">
    <x-admin.breadcrumb 
        title="Detail Pengguna" 
        :items="[
            'Pengguna' => route('admin.users.index'),
            $user->name => ''
        ]" 
    />

    <div class="space-y-6 max-w-4xl">
        <!-- 1. Profile Hero Summary Card -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#295c4d] to-[#cca06e] p-1 shadow-md shrink-0">
                        <div class="w-full h-full bg-[#1d3e35] rounded-[14px] flex items-center justify-center text-white font-extrabold text-2xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-xl sm:text-2xl font-extrabold text-[#1d3e35]">{{ $user->name }}</h2>
                            @foreach($user->roles as $role)
                                <x-admin.role-badge :role="$role->name" />
                            @endforeach

                            @php
                                $statusStyles = [
                                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                                    'nonactive' => 'bg-stone-100 text-stone-700 border-stone-200',
                                    'suspended' => 'bg-amber-50 text-amber-700 border-amber-200/80',
                                    'banned' => 'bg-red-50 text-red-700 border-red-200/80',
                                ];
                                $statusLabels = [
                                    'active' => 'Aktif',
                                    'nonactive' => 'Non-Aktif',
                                    'suspended' => 'Suspended',
                                    'banned' => 'Banned',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusStyles[$user->status] ?? $statusStyles['active'] }}">
                                {{ $statusLabels[$user->status] ?? 'Aktif' }}
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-1 flex items-center gap-1.5">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>{{ $user->email }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a 
                        href="{{ route('admin.users.roles.edit', $user->id) }}" 
                        class="px-4 py-2 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-xs inline-flex items-center gap-1.5 shadow-2xs transition-colors"
                    >
                        <i data-lucide="shield-alert" class="w-4 h-4 text-[#b17042]"></i>
                        <span>Kelola Role</span>
                    </a>

                    <a 
                        href="{{ route('admin.users.edit', $user->id) }}" 
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md hover:shadow-lg transition-all"
                    >
                        <i data-lucide="edit-3" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Edit Profil & Status</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Account Metadata Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">ID Pengguna (ULID)</p>
                <p class="text-xs font-mono font-bold text-[#1d3e35] truncate select-all">{{ $user->id }}</p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Status Akun</p>
                <p class="text-xs font-bold capitalize text-stone-800">
                    {{ $statusLabels[$user->status] ?? 'Aktif' }}
                </p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Verifikasi Email</p>
                <p class="text-xs font-bold">
                    @if($user->email_verified_at)
                        <span class="text-emerald-600 inline-flex items-center gap-1">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                            Terverifikasi
                        </span>
                    @else
                        <span class="text-amber-600 inline-flex items-center gap-1">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                            Belum Verifikasi
                        </span>
                    @endif
                </p>
            </div>

            <div class="bg-white rounded-3xl p-5 border border-[#99cab7]/30 shadow-2xs space-y-1">
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Tanggal Bergabung</p>
                <p class="text-xs font-bold text-stone-800">
                    {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                </p>
            </div>
        </div>

        <!-- 3. Assigned Permissions Matrix Card -->
        <x-admin.card 
            title="Daftar Hak Akses & Permission Aktif" 
            subtitle="Rincian hak akses yang dimiliki pengguna berdasarkan kombinasi role yang aktif."
        >
            @php
                $allPermissions = $user->getAllPermissions();
            @endphp

            @if($user->hasRole('Super Admin'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 font-semibold flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-700 shrink-0"></i>
                    <span>Pengguna ini memiliki role <strong>Super Admin</strong> dengan hak akses penuh (*Bypass*) ke seluruh fitur sistem.</span>
                </div>
            @elseif($allPermissions->isEmpty())
                <div class="p-4 rounded-2xl bg-stone-50 text-xs text-stone-500 text-center">
                    Tidak ada permission khusus yang terpasang pada akun ini.
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($allPermissions as $perm)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#f2f8f5] border border-[#99cab7]/40 text-xs font-medium text-[#295c4d]">
                            <i data-lucide="check" class="w-3 h-3 text-[#31725e]"></i>
                            <span>{{ $perm->name }}</span>
                        </span>
                    @endforeach
                </div>
            @endif
        </x-admin.card>
    </div>
</x-layouts.admin>
