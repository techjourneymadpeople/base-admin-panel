<x-layouts.admin title="Edit Profil Pengguna">
    <x-admin.breadcrumb 
        title="Edit Profil Pengguna" 
        :items="[
            'Pengguna' => route('admin.users.index'),
            $user->name => route('admin.users.show', $user->id),
            'Edit' => ''
        ]" 
    />

    <div class="max-w-3xl">
        <x-admin.card 
            title="Edit Data Pengguna" 
            subtitle="Perbarui nama, alamat email, dan pengaturan status kata sandi akun pengguna."
        >
            <x-slot:actions>
                <a 
                    href="{{ route('admin.users.roles.edit', $user->id) }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-xs inline-flex items-center gap-1.5 transition-colors"
                >
                    <i data-lucide="shield-alert" class="w-3.5 h-3.5 text-[#b17042]"></i>
                    <span>Kelola Role User</span>
                </a>

                <a 
                    href="{{ route('admin.users.index') }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-1 transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

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

                <!-- Section: Ganti Kata Sandi (Opsional) -->
                <div class="p-5 rounded-2xl bg-[#f2f8f5]/60 border border-[#99cab7]/30 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-[#1d3e35] uppercase tracking-wider">Perbarui Kata Sandi</h4>
                        <p class="text-xs text-stone-500 mt-0.5">Biarkan kolom sandi kosong jika Anda tidak ingin mengubah kata sandi pengguna.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.password-input
                            name="password"
                            label="Kata Sandi Baru"
                            placeholder="Kosongkan jika tidak diubah"
                        />

                        <x-form.password-input
                            name="password_confirmation"
                            label="Konfirmasi Sandi Baru"
                            placeholder="Ulangi kata sandi baru"
                        />
                    </div>
                </div>

                <!-- Status Akun -->
                <x-form.select
                    name="status"
                    label="Status Akun"
                    icon="activity"
                    :required="true"
                    :selected="old('status', $user->status ?? 'active')"
                    :options="[
                        'active' => 'Aktif (Dapat Masuk)',
                        'nonactive' => 'Non-Aktif (Ditolak Masuk)',
                        'suspended' => 'Ditangguhkan / Suspended (Ditolak Masuk)',
                        'banned' => 'Diblokir / Banned (Ditolak Masuk)',
                    ]"
                    helper="Pengguna dengan status selain 'Aktif' akan otomatis ditolak saat login dan menerima pesan pemberitahuan sesuai statusnya."
                />

                <!-- Checkbox Verifikasi Email -->
                <div class="pt-2 border-t border-stone-100">
                    <x-form.checkbox
                        name="email_verified"
                        label="Status Email Terverifikasi"
                        :checked="old('email_verified', !empty($user->email_verified_at))"
                    />
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
                        icon="save"
                    >
                        Simpan Perubahan
                    </x-form.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-layouts.admin>
