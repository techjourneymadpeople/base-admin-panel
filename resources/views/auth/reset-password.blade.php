<x-layouts.auth title="Atur Ulang Kata Sandi">
    <x-auth.container :split="false">
        <x-auth.card 
            badge="Pembaruan Kredensial"
            icon="lock-keyhole"
            title="Atur Ulang Kata Sandi"
            subtitle="Silakan tentukan kata sandi baru yang kuat untuk mengamankan akun Lentera Pasar Anda."
        >
            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <!-- Hidden Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <x-form.input
                    name="email"
                    type="email"
                    label="Alamat Email"
                    icon="mail"
                    :value="old('email', $request->email)"
                    required
                    autofocus
                    autocomplete="username"
                />

                <!-- New Password Input -->
                <x-form.password-input
                    name="password"
                    label="Kata Sandi Baru"
                    icon="lock"
                    placeholder="Minimal 8 karakter"
                    required
                    autocomplete="new-password"
                    helper="Kombinasikan huruf, angka, dan karakter khusus."
                />

                <!-- Confirm New Password Input -->
                <x-form.password-input
                    name="password_confirmation"
                    label="Konfirmasi Kata Sandi Baru"
                    icon="shield-check"
                    placeholder="Ulangi kata sandi baru"
                    required
                    autocomplete="new-password"
                />

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-form.button variant="primary" icon="check-circle-2">
                        Simpan Kata Sandi Baru
                    </x-form.button>
                </div>
            </form>

            <!-- Card Footer -->
            <x-slot:footer>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 font-bold text-[#31725e] hover:text-[#1d3e35] hover:underline transition-colors text-xs sm:text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Batal & Kembali ke Halaman Masuk
                </a>
            </x-slot:footer>
        </x-auth.card>
    </x-auth.container>
</x-layouts.auth>
