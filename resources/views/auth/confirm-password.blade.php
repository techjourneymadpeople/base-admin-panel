<x-layouts.auth title="Konfirmasi Kata Sandi">
    <x-auth.container :split="false">
        <x-auth.card 
            badge="Area Terproteksi"
            icon="lock"
            title="Konfirmasi Kata Sandi"
            subtitle="Ini adalah area aman aplikasi. Untuk alasan keamanan, silakan konfirmasi kembali kata sandi Anda sebelum melanjutkan."
        >
            <form method="POST" action="{{ url('/user/confirm-password') }}" class="space-y-5">
                @csrf

                <!-- Password Input -->
                <x-form.password-input
                    name="password"
                    label="Kata Sandi Saat Ini"
                    icon="lock"
                    placeholder="Masukkan kata sandi Anda"
                    required
                    autofocus
                    autocomplete="current-password"
                />

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-form.button variant="primary" icon="shield-check">
                        Konfirmasi & Lanjutkan
                    </x-form.button>
                </div>
            </form>

            <x-slot:footer>
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-1.5 font-bold text-[#31725e] hover:text-[#1d3e35] hover:underline transition-colors text-xs sm:text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Halaman Sebelumnya
                </a>
            </x-slot:footer>
        </x-auth.card>
    </x-auth.container>
</x-layouts.auth>
