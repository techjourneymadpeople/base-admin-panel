<x-layouts.admin title="Tambah Pengguna Baru">
    <x-admin.breadcrumb 
        title="Tambah Pengguna Baru" 
        :items="[
            'Pengguna' => route('admin.users.index'),
            'Tambah Baru' => ''
        ]" 
    />

    <div class="max-w-3xl">
        <x-admin.card 
            title="Formulir Pendaftaran Pengguna" 
            subtitle="Buat akun pengguna baru. Akun baru secara default akan diberikan role 'User' dan dapat disesuaikan pada modul Assign Role."
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

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <x-form.input
                    name="name"
                    label="Nama Lengkap"
                    icon="user"
                    placeholder="Contoh: Ahmad Fauzi"
                    :required="true"
                    :value="old('name')"
                    autofocus
                />

                <!-- Alamat Email -->
                <x-form.input
                    type="email"
                    name="email"
                    label="Alamat Email"
                    icon="mail"
                    placeholder="nama@domain.com"
                    :required="true"
                    :value="old('email')"
                />

                <!-- Kata Sandi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <x-form.password-input
                        name="password"
                        label="Kata Sandi"
                        placeholder="Minimal 8 karakter"
                        :required="true"
                        helper="Gunakan kombinasi huruf, angka & simbol."
                    />

                    <x-form.password-input
                        name="password_confirmation"
                        label="Konfirmasi Sandi"
                        placeholder="Ulangi kata sandi"
                        :required="true"
                    />
                </div>

                <!-- Status Akun -->
                <x-form.select
                    name="status"
                    label="Status Akun"
                    icon="activity"
                    :required="true"
                    :selected="old('status', 'active')"
                    :options="[
                        'active' => 'Aktif (Dapat Masuk)',
                        'nonactive' => 'Non-Aktif (Ditolak Masuk)',
                        'suspended' => 'Ditangguhkan / Suspended (Ditolak Masuk)',
                        'banned' => 'Diblokir / Banned (Ditolak Masuk)',
                    ]"
                    helper="Pengguna dengan status selain 'Aktif' akan otomatis ditolak saat login."
                />

                <!-- Checkbox Verifikasi Email Langsung -->
                <div class="pt-2 border-t border-stone-100">
                    <x-form.checkbox
                        name="email_verified"
                        label="Tandai email sebagai sudah terverifikasi (Pengguna langsung dapat masuk tanpa perlu verifikasi email)"
                        :checked="old('email_verified', true)"
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
                        icon="user-plus"
                    >
                        Simpan Pengguna Baru
                    </x-form.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-layouts.admin>
