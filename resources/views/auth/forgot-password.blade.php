<x-layouts.auth title="Lupa Kata Sandi">
    <x-auth.container :split="false">
        <x-auth.card 
            badge="Pemulihan Akun"
            icon="key-round"
            title="Lupa Kata Sandi?"
            subtitle="Tenang, masukkan alamat email yang terdaftar pada akun Anda. Kami akan mengirimkan instruksi untuk membuat kata sandi baru."
        >
            <!-- Status Alert -->
            @if (session('status'))
                <x-alert type="success" :message="session('status')" />
            @endif

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email Input -->
                <x-form.input
                    name="email"
                    type="email"
                    label="Alamat Email Terdaftar"
                    icon="mail"
                    placeholder="nama@email.com"
                    required
                    autofocus
                    autocomplete="username"
                />

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-form.button variant="primary" icon="send">
                        Kirim Tautan Atur Ulang
                    </x-form.button>
                </div>
            </form>

            <!-- Card Footer -->
            <x-slot:footer>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 font-bold text-[#31725e] hover:text-[#1d3e35] hover:underline transition-colors text-xs sm:text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Halaman Masuk
                </a>
            </x-slot:footer>
        </x-auth.card>
    </x-auth.container>
</x-layouts.auth>
