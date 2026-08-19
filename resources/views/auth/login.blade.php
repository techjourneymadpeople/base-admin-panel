<x-layouts.auth title="Masuk">
    <x-auth.container :split="true">
        <x-auth.card 
            badge="Portal Administrasi"
            icon="shield-check"
            title="Selamat Datang"
            subtitle="Masuk ke akun Anda untuk mengelola workspace dengan nyaman dan teratur."
        >
            <!-- Status / Error Notification -->
            @if (session('status'))
                <x-alert type="success" :message="session('status')" />
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address Input -->
                <x-form.input
                    name="email"
                    type="email"
                    label="Alamat Email"
                    icon="mail"
                    placeholder="nama@email.com"
                    required
                    autofocus
                    autocomplete="username"
                />

                <!-- Password Input with Forgot Password link -->
                <x-form.password-input
                    name="password"
                    label="Kata Sandi"
                    icon="lock"
                    placeholder="Masukkan kata sandi Anda"
                    required
                    autocomplete="current-password"
                >
                    <x-slot:extraLabel>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#b17042] hover:text-[#945838] hover:underline transition-colors">
                                Lupa sandi?
                            </a>
                        @endif
                    </x-slot:extraLabel>
                </x-form.password-input>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <x-form.checkbox
                        name="remember"
                        label="Ingat saya di perangkat ini"
                    />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <x-form.button variant="primary" icon="arrow-right">
                        Masuk ke Workspace
                    </x-form.button>
                </div>
            </form>

            <!-- Card Footer -->
            @if (Route::has('register') && \App\Models\WebConfiguration::current()->registration_enabled)
                <x-slot:footer>
                    <p class="text-xs sm:text-sm">
                        Belum memiliki akun pengelola? 
                        <a href="{{ route('register') }}" class="font-bold text-[#31725e] hover:text-[#1d3e35] hover:underline transition-colors ml-1">
                            Daftar Akun Baru
                        </a>
                    </p>
                </x-slot:footer>
            @endif
        </x-auth.card>
    </x-auth.container>
</x-layouts.auth>
