<x-layouts.auth title="Daftar Akun Baru">
    <x-auth.container :split="true">
        <x-auth.card 
            badge="Pendaftaran Pengelola"
            icon="user-plus"
            title="Daftar Akun Baru"
            subtitle="Bergabunglah untuk membangun pasar tradisional yang modern, bersih, dan berdaya saing."
        >
            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Full Name Input -->
                <x-form.input
                    name="name"
                    type="text"
                    label="Nama Lengkap"
                    icon="user"
                    placeholder="Contoh: Rian Pratama"
                    required
                    autofocus
                    autocomplete="name"
                />

                <!-- Email Input -->
                <x-form.input
                    name="email"
                    type="email"
                    label="Alamat Email"
                    icon="mail"
                    placeholder="nama@lenterapasar.id"
                    required
                    autocomplete="username"
                />

                <!-- Password Input -->
                <x-form.password-input
                    name="password"
                    label="Kata Sandi"
                    icon="lock"
                    placeholder="Minimal 8 karakter"
                    required
                    autocomplete="new-password"
                    helper="Gunakan kombinasi huruf besar, kecil, angka, dan simbol."
                />

                <!-- Confirm Password Input -->
                <x-form.password-input
                    name="password_confirmation"
                    label="Konfirmasi Kata Sandi"
                    icon="shield-check"
                    placeholder="Ulangi kata sandi Anda"
                    required
                    autocomplete="new-password"
                />

                <!-- Terms & Privacy Policy Checkbox (if applicable) -->
                <div class="pt-1">
                    <x-form.checkbox name="terms" required>
                        <span>Saya menyetujui <a href="#" class="text-[#31725e] font-semibold underline hover:text-[#1d3e35]">Ketentuan Layanan</a> dan <a href="#" class="text-[#31725e] font-semibold underline hover:text-[#1d3e35]">Kebijakan Privasi</a></span>
                    </x-form.checkbox>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-form.button variant="primary" icon="check">
                        Selesaikan Pendaftaran
                    </x-form.button>
                </div>
            </form>

            <!-- Card Footer -->
            <x-slot:footer>
                <p class="text-xs sm:text-sm">
                    Sudah memiliki akun pengelola? 
                    <a href="{{ route('login') }}" class="font-bold text-[#31725e] hover:text-[#1d3e35] hover:underline transition-colors ml-1">
                        Masuk di Sini
                    </a>
                </p>
            </x-slot:footer>
        </x-auth.card>
    </x-auth.container>
</x-layouts.auth>
