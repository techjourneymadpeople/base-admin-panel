<x-layouts.auth title="Verifikasi Email">
    <x-auth.container :split="false">
        <x-auth.card 
            badge="Konfirmasi Identitas"
            icon="mail-check"
            title="Verifikasi Email Anda"
            subtitle="Terima kasih telah mendaftar! Sebelum melangkah lebih jauh, silakan buka email Anda dan klik tautan verifikasi yang baru kami kirimkan."
        >
            <!-- Status Alert if link re-sent -->
            @if (session('status') == 'verification-link-sent')
                <x-alert type="success" title="Tautan Baru Terkirim!">
                    Tautan verifikasi baru telah berhasil dikirimkan ke alamat email yang Anda daftarkan. Silakan periksa kotak masuk atau folder spam Anda.
                </x-alert>
            @endif

            <div class="p-4 rounded-2xl bg-[#e2f0ea]/70 border border-[#99cab7]/50 text-xs sm:text-sm text-[#295c4d] leading-relaxed flex items-start gap-3">
                <i data-lucide="info" class="w-5 h-5 text-[#31725e] shrink-0 mt-0.5"></i>
                <div>
                    Jika Anda tidak menerima email dalam beberapa menit, Anda dapat meminta sistem untuk mengirimkan tautan verifikasi kembali.
                </div>
            </div>

            <!-- Resend Verification Email Form -->
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-form.button variant="primary" icon="refresh-cw">
                    Kirim Ulang Tautan Verifikasi
                </x-form.button>
            </form>

            <!-- Card Footer: Logout action -->
            <x-slot:footer>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 font-bold text-[#b17042] hover:text-[#784732] hover:underline transition-colors text-xs sm:text-sm">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Keluar dari Sesi Ini
                    </button>
                </form>
            </x-slot:footer>
        </x-auth.card>
    </x-auth.container>
</x-layouts.auth>
