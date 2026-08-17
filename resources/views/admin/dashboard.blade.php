<x-layouts.admin title="Dashboard Utama">
    <!-- Breadcrumb Header -->
    <x-admin.breadcrumb 
        title="Dashboard Utama" 
        :items="['Dashboard' => '']" 
    />

    <!-- 1. Viho Style Hero Welcome Banner -->
    <div class="relative rounded-3xl p-6 sm:p-8 text-white shadow-xl overflow-hidden mb-8 bg-gradient-to-r from-[#1d3e35] via-[#295c4d] to-[#784732] border border-white/20">
        <!-- Ambient decorative shapes -->
        <div class="absolute -top-20 -right-20 w-60 h-60 bg-[#cca06e]/30 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-[#428e75]/30 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold text-[#e2f0ea] mb-3">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5 text-[#cca06e]"></i>
                    <span>Selamat Datang di Workspace Administrasi</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Halo, {{ auth()->user()->name ?? 'Administrator' }}!
                </h2>
                <p class="mt-2 text-xs sm:text-sm text-[#e2f0ea]/85 leading-relaxed font-normal">
                    Anda masuk dengan hak akses <span class="font-bold text-[#fef3c7] underline decoration-[#cca06e] underline-offset-4">{{ auth()->user()->roles->pluck('name')->first() ?? 'Admin' }}</span>. Seluruh sistem beroperasi dengan normal dan stabil.
                </p>
            </div>
        </div>
    </div>
</x-layouts.admin>
