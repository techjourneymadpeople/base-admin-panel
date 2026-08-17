<x-layouts.auth title="Dashboard Utama">
    <div class="w-full max-w-4xl mx-auto">
        <div class="glass-panel rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
            <!-- Decorative Bar -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#1d3e35] via-[#428e75] to-[#cca06e]"></div>

            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-[#99cab7]/30">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1d3e35] to-[#428e75] flex items-center justify-center text-white shadow-lg shadow-[#1d3e35]/20 font-bold text-xl">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-extrabold text-[#1d3e35]">
                                Halo, {{ auth()->user()->name ?? 'Pengelola' }}!
                            </h1>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#e2f0ea] text-[#295c4d] border border-[#99cab7]/60">
                                <i data-lucide="check-circle" class="w-3 h-3 text-[#31725e]"></i>
                                Aktif
                            </span>
                        </div>
                        <p class="text-xs text-[#623c2c]/80 mt-0.5">
                            {{ auth()->user()->email ?? 'admin@lenterapasar.id' }} &bull; Sesi Pengelola Terverifikasi
                        </p>
                    </div>
                </div>

                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-form.button variant="secondary" :fullWidth="false" icon="log-out">
                        Keluar
                    </x-form.button>
                </form>
            </div>

            <!-- Welcome Cards / Quick Insights -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 my-8">
                <div class="p-5 rounded-2xl bg-white/60 border border-[#99cab7]/40 shadow-sm backdrop-blur-md">
                    <div class="w-10 h-10 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center mb-3">
                        <i data-lucide="store" class="w-5 h-5"></i>
                    </div>
                    <div class="text-2xl font-bold text-[#1d3e35]">24 Unit</div>
                    <div class="text-xs text-[#623c2c]/75 mt-0.5">Pasar Rakyat Terpantau</div>
                </div>

                <div class="p-5 rounded-2xl bg-white/60 border border-[#99cab7]/40 shadow-sm backdrop-blur-md">
                    <div class="w-10 h-10 rounded-xl bg-[#ead7be] text-[#945838] flex items-center justify-center mb-3">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div class="text-2xl font-bold text-[#1d3e35]">1.420</div>
                    <div class="text-xs text-[#623c2c]/75 mt-0.5">Pedagang & Pedagang Kaki Lima</div>
                </div>

                <div class="p-5 rounded-2xl bg-white/60 border border-[#99cab7]/40 shadow-sm backdrop-blur-md">
                    <div class="w-10 h-10 rounded-xl bg-[#e2f0ea] text-[#428e75] flex items-center justify-center mb-3">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <div class="text-2xl font-bold text-[#1d3e35]">99.8%</div>
                    <div class="text-xs text-[#623c2c]/75 mt-0.5">Keandalan Sistem & Retribusi</div>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-[#e2f0ea]/70 border border-[#99cab7]/50 text-xs sm:text-sm text-[#295c4d] flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i data-lucide="leaf" class="w-4 h-4 text-[#31725e]"></i>
                    Sistem autentikasi Laravel Fortify telah siap digunakan sepenuhnya.
                </span>
                <span class="text-xs font-semibold text-[#784732]">v1.0 Relaxed Edition</span>
            </div>
        </div>
    </div>
</x-layouts.auth>
