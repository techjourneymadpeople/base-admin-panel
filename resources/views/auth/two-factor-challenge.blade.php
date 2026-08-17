<x-layouts.auth title="Tantangan Dua Faktor">
    <x-auth.container :split="false">
        <div x-data="{ recovery: false }">
            <x-auth.card 
                badge="Keamanan Tingkat Lanjut"
                icon="shield-alert"
                title="Autentikasi Dua Faktor"
                subtitle="Akun Anda dilindungi dengan lapisan keamanan ekstra. Masukkan kode verifikasi untuk melanjutkan akses ke workspace."
            >
                <form method="POST" action="{{ url('/two-factor-challenge') }}" class="space-y-5">
                    @csrf

                    <!-- Mode 1: TOTP Code from Authenticator App -->
                    <div x-show="!recovery">
                        <x-form.input
                            name="code"
                            type="text"
                            label="Kode Autentikasi 6-Digit"
                            icon="smartphone"
                            placeholder="Contoh: 123456"
                            autofocus
                            autocomplete="one-time-code"
                            helper="Buka aplikasi autentikator (Google Authenticator / Authy) pada ponsel Anda."
                        />
                    </div>

                    <!-- Mode 2: Emergency Recovery Code -->
                    <div x-show="recovery" x-cloak>
                        <x-form.input
                            name="recovery_code"
                            type="text"
                            label="Kode Pemulihan Darurat"
                            icon="key"
                            placeholder="Contoh: abcde-fghij-klmno"
                            autocomplete="one-time-code"
                            helper="Gunakan salah satu kode pemulihan cadangan yang pernah Anda simpan."
                        />
                    </div>

                    <!-- Submit Action Button -->
                    <div class="pt-2">
                        <x-form.button variant="primary" icon="shield-check">
                            Verifikasi & Masuk
                        </x-form.button>
                    </div>
                </form>

                <!-- Switch Mode Footer Toggle -->
                <x-slot:footer>
                    <button 
                        type="button" 
                        @click="recovery = !recovery"
                        class="inline-flex items-center gap-1.5 font-bold text-[#b17042] hover:text-[#784732] hover:underline transition-colors text-xs sm:text-sm cursor-pointer"
                    >
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        <span x-show="!recovery">Gunakan Kode Pemulihan Cadangan</span>
                        <span x-show="recovery" x-cloak>Gunakan Kode Aplikasi Otentikator</span>
                    </button>
                </x-slot:footer>
            </x-auth.card>
        </div>
    </x-auth.container>
</x-layouts.auth>
