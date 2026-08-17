<div class="relative rounded-3xl overflow-hidden p-8 text-white shadow-2xl border border-white/40 bg-gradient-to-br from-[#1d3e35] via-[#295c4d] to-[#784732]">
    <!-- Ambient Inner Background Accents -->
    <div class="absolute -top-24 -right-24 w-60 h-60 bg-[#cca06e]/30 rounded-full blur-2xl pointer-events-none"></div>
    <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-[#428e75]/40 rounded-full blur-2xl pointer-events-none"></div>
    <div class="absolute inset-0 bg-[radial-gradient(#c5e1d5_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none"></div>

    <div class="relative z-10 flex flex-col justify-between min-h-[460px]">
        <!-- Top Badge & Title -->
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-medium text-[#e2f0ea] mb-6">
                <i data-lucide="trees" class="w-3.5 h-3.5 text-[#99cab7]"></i>
                <span>Harmoni &bull; Produktivitas &bull; Keandalan</span>
            </div>

            <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-white leading-tight">
                Kelola Workspace Anda dengan Tenang & Efisien
            </h2>

            <p class="mt-3 text-sm text-[#e2f0ea]/85 leading-relaxed font-normal">
                Platform administrasi terpadu untuk monitoring operasional, manajemen data, dan pengelolaan hak akses secara terpusat dan aman.
            </p>
        </div>

        <!-- Serene Features / Stats Card in Glass -->
        <div class="my-6 space-y-3">
            <div class="p-3.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#428e75]/40 flex items-center justify-center text-[#c5e1d5] shrink-0 border border-white/10">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white">Autentikasi Terproteksi</h4>
                    <p class="text-xs text-[#e2f0ea]/75">Dilengkapi enkripsi standar tinggi & 2FA</p>
                </div>
            </div>

            <div class="p-3.5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-[#cca06e]/40 flex items-center justify-center text-[#f4ebdE] shrink-0 border border-white/10">
                    <i data-lucide="sparkles" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white">Nuansa Nyaman & Santai</h4>
                    <p class="text-xs text-[#e2f0ea]/75">Dirancang ramah mata untuk kenyamanan kerja harian</p>
                </div>
            </div>
        </div>

        <!-- Bottom Quote -->
        <div class="pt-4 border-t border-white/15 flex items-center justify-between text-xs text-[#e2f0ea]/70">
            <span class="flex items-center gap-1.5">
                <i data-lucide="coffee" class="w-4 h-4 text-[#cca06e]"></i>
                Kerja fokus, suasana teduh
            </span>
            <span class="font-medium text-[#c5e1d5]">{{ config('app.name', 'Admin Panel') }}</span>
        </div>
    </div>
</div>
