<x-layouts.admin title="Profil Saya">
    <x-admin.breadcrumb 
        title="Profil Saya" 
        :items="[
            'Pengaturan Akun' => '',
            'Profil Saya' => ''
        ]" 
    />

    <div class="space-y-6 max-w-5xl" x-data="{
        activeTab: 'info' // 'info', 'password', 'permissions'
    }">
        <!-- 1. Profile Hero Summary Card -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm relative overflow-hidden">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#295c4d] to-[#cca06e] p-1 shadow-md shrink-0">
                        <div class="w-full h-full bg-[#1d3e35] rounded-[14px] flex items-center justify-center text-white font-black text-2xl">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
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
                                    'active' => 'Akun Aktif',
                                    'nonactive' => 'Non-Aktif',
                                    'suspended' => 'Suspended',
                                    'banned' => 'Banned',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusStyles[$user->status] ?? $statusStyles['active'] }}">
                                {{ $statusLabels[$user->status] ?? 'Akun Aktif' }}
                            </span>
                        </div>
                        <p class="text-xs text-stone-500 mt-1 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="mail" class="w-3.5 h-3.5 text-[#31725e]"></i>
                                <span>{{ $user->email }}</span>
                            </span>
                            <span>&bull;</span>
                            <span class="inline-flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#31725e]"></i>
                                <span>Bergabung sejak {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 rounded-xl bg-[#f2f8f5] border border-[#99cab7]/40 text-xs font-mono text-[#295c4d] truncate max-w-[200px]" title="ID Pengguna: {{ $user->id }}">
                        ID: {{ substr($user->id, 0, 10) }}...
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Navigation Tabs -->
        <div class="p-1.5 rounded-2xl bg-stone-100/80 border border-stone-200/60 inline-flex items-center gap-1.5 w-full sm:w-auto">
            <button 
                type="button" 
                @click="activeTab = 'info'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex-1 sm:flex-initial flex items-center justify-center gap-2"
                :class="activeTab === 'info' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
            >
                <i data-lucide="user" class="w-4 h-4 text-[#31725e]"></i>
                <span>Informasi Profil</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'password'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex-1 sm:flex-initial flex items-center justify-center gap-2"
                :class="activeTab === 'password' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
            >
                <i data-lucide="lock" class="w-4 h-4 text-[#cca06e]"></i>
                <span>Keamanan & Kata Sandi</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'permissions'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex-1 sm:flex-initial flex items-center justify-center gap-2"
                :class="activeTab === 'permissions' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
            >
                <i data-lucide="shield-check" class="w-4 h-4 text-[#428e75]"></i>
                <span>Hak Akses & Role</span>
            </button>
        </div>

        <!-- 3. Tab Content Area -->

        <!-- Tab 1: Informasi Profil -->
        <div x-show="activeTab === 'info'" class="space-y-6">
            <x-admin.card 
                title="Perbarui Data Pribadi" 
                subtitle="Pastikan nama lengkap dan alamat email Anda selalu aktif dan valid."
            >
                <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Nama Lengkap -->
                        <x-form.input
                            name="name"
                            label="Nama Lengkap"
                            icon="user"
                            :required="true"
                            :value="old('name', $user->name)"
                        />

                        <!-- Alamat Email -->
                        <x-form.input
                            type="email"
                            name="email"
                            label="Alamat Email"
                            icon="mail"
                            :required="true"
                            :value="old('email', $user->email)"
                        />
                    </div>

                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end">
                        <x-form.button 
                            type="submit" 
                            variant="primary" 
                            :fullWidth="false" 
                            icon="save"
                        >
                            Simpan Informasi Profil
                        </x-form.button>
                    </div>
                </form>
            </x-admin.card>
        </div>

        <!-- Tab 2: Keamanan & Kata Sandi -->
        <div x-show="activeTab === 'password'" class="space-y-6" x-cloak>
            <x-admin.card 
                title="Ganti Kata Sandi Akun" 
                subtitle="Untuk menjaga keamanan akun, gunakan kata sandi yang kuat dengan kombinasi huruf besar, kecil, angka, dan simbol."
            >
                <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Kata Sandi Saat Ini -->
                    <div class="max-w-md">
                        <x-form.password-input
                            name="current_password"
                            label="Kata Sandi Saat Ini"
                            placeholder="Masukkan sandi lama Anda"
                            :required="true"
                            helper="Wajib diisi untuk memverifikasi kepemilikan akun."
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-stone-100">
                        <!-- Kata Sandi Baru -->
                        <x-form.password-input
                            name="password"
                            label="Kata Sandi Baru"
                            placeholder="Minimal 8 karakter"
                            :required="true"
                            helper="Gunakan minimal 8 karakter (kombinasi huruf, angka, simbol)."
                        />

                        <!-- Konfirmasi Sandi Baru -->
                        <x-form.password-input
                            name="password_confirmation"
                            label="Konfirmasi Kata Sandi Baru"
                            placeholder="Ulangi kata sandi baru"
                            :required="true"
                        />
                    </div>

                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end">
                        <x-form.button 
                            type="submit" 
                            variant="primary" 
                            :fullWidth="false" 
                            icon="key"
                        >
                            Perbarui Kata Sandi
                        </x-form.button>
                    </div>
                </form>
            </x-admin.card>
        </div>

        <!-- Tab 3: Hak Akses & Role -->
        <div x-show="activeTab === 'permissions'" class="space-y-6" x-cloak>
            <x-admin.card 
                title="Rincian Hak Akses Akun Saya" 
                subtitle="Daftar role dan seluruh hak akses permissions yang aktif pada akun Anda."
            >
                @if($user->hasRole('Super Admin'))
                    <div class="p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 font-semibold flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-sm text-emerald-950">Akses Penuh Sistem (Super Admin)</h4>
                            <p class="text-xs text-emerald-800 font-normal mt-0.5">Akun Anda memiliki izin *Bypass* menyeluruh ke semua fitur, modul, rute, dan pengaturan sistem.</p>
                        </div>
                    </div>
                @elseif($allPermissions->isEmpty())
                    <div class="p-6 rounded-2xl bg-stone-50 text-xs text-stone-500 text-center">
                        Tidak ada hak akses permission khusus yang terpasang pada akun Anda.
                    </div>
                @else
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-stone-700">Role Anda:</span>
                            @foreach($user->roles as $role)
                                <x-admin.role-badge :role="$role->name" />
                            @endforeach
                        </div>

                        <div class="pt-3 border-t border-stone-100">
                            <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-3">Daftar Permission Aktif ({{ $allPermissions->count() }}):</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($allPermissions as $perm)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#f2f8f5] border border-[#99cab7]/40 text-xs font-mono font-medium text-[#295c4d]">
                                        <i data-lucide="check" class="w-3 h-3 text-[#31725e]"></i>
                                        <span>{{ $perm->name }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </x-admin.card>
        </div>
    </div>
</x-layouts.admin>
