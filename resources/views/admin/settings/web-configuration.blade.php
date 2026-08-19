<x-layouts.admin title="Web Konfigurasi">
    <x-admin.breadcrumb 
        title="Web Konfigurasi" 
        :items="[
            'Pengaturan' => '',
            'Web Konfigurasi' => ''
        ]" 
    />

    <!-- Flash Messages Notification -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="p-4 rounded-2xl bg-[#e2f0ea] border border-[#99cab7] text-[#1d3e35] flex items-center justify-between shadow-xs mb-6 transition-all duration-300"
        >
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-[#31725e]"></i>
                <span class="text-xs font-bold">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-stone-400 hover:text-stone-700">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    @endif

    <form 
        method="POST" 
        action="{{ route('admin.settings.update') }}" 
        enctype="multipart/form-data"
        class="space-y-6"
        x-data="{
            activeTab: 'system', // 'system', 'limits', 'seo', 'scripts'
            maintenanceMode: {{ $config->maintenance_mode ? 'true' : 'false' }},
            registrationEnabled: {{ $config->registration_enabled ?? true ? 'true' : 'false' }},
            articleModuleEnabled: {{ $config->article_module_enabled ?? true ? 'true' : 'false' }},
            testimonialModuleEnabled: {{ $config->testimonial_module_enabled ?? true ? 'true' : 'false' }},
            partnerModuleEnabled: {{ $config->partner_module_enabled ?? true ? 'true' : 'false' }},
            faqModuleEnabled: {{ $config->faq_module_enabled ?? true ? 'true' : 'false' }},
            cookieConsent: {{ $config->cookie_consent_enabled ? 'true' : 'false' }},
            robotsIndexing: {{ $config->robots_indexing ?? true ? 'true' : 'false' }},
        }"
    >
        @csrf
        @method('PUT')

        <!-- Top Action Header Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                    <i data-lucide="settings-2" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Pengaturan Teknis & Sistem Web</span>
                    <h3 class="text-base font-extrabold text-[#1d3e35]">Web Konfigurasi</h3>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                >
                    <i data-lucide="check-circle" class="w-4 h-4 text-[#cca06e]"></i>
                    <span>Simpan Pengaturan Web</span>
                </button>
            </div>
        </div>

        <!-- Tab Navigation Bar -->
        <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-white/80 border border-[#99cab7]/30 shadow-2xs overflow-x-auto">
            <button 
                type="button" 
                @click="activeTab = 'system'"
                :class="activeTab === 'system' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
            >
                <i data-lucide="cpu" class="w-4 h-4"></i>
                <span>1. Sistem & Operasional</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'limits'"
                :class="activeTab === 'limits' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
            >
                <i data-lucide="gauge" class="w-4 h-4"></i>
                <span>2. Batasan & Kuota Sistem</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'seo'"
                :class="activeTab === 'seo' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
            >
                <i data-lucide="globe" class="w-4 h-4"></i>
                <span>3. SEO & Mesin Pencari</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'scripts'"
                :class="activeTab === 'scripts' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
            >
                <i data-lucide="code-2" class="w-4 h-4"></i>
                <span>4. Integrasi Skrip & Pelacak</span>
            </button>
        </div>

        <!-- ==================================================== -->
        <!-- TAB 1: SISTEM & OPERASIONAL                          -->
        <!-- ==================================================== -->
        <div x-show="activeTab === 'system'" class="space-y-6">
            <x-admin.card 
                title="Status Sistem & Operasional Web" 
                subtitle="Kontrol mode pemeliharaan, buka/tutup pendaftaran akun publik, banner cookie, dan copyright teks footer."
                icon="shield-alert"
            >
                <div class="space-y-6">
                    <!-- Maintenance Mode Switch -->
                    <div class="p-5 rounded-2xl border border-amber-200/80 bg-amber-50/40 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="tool" class="w-4 h-4 text-amber-600"></i>
                                <h4 class="text-xs font-bold text-stone-800">Mode Pemeliharaan (Maintenance Mode)</h4>
                            </div>
                            <p class="text-[11px] text-stone-500 max-w-xl">
                                Jika diaktifkan, pengunjung umum website publik akan melihat halaman pemeliharaan sementara, sedangkan administrator tetap dapat login dan mengakses admin panel.
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input 
                                type="checkbox" 
                                name="maintenance_mode" 
                                value="1" 
                                x-model="maintenanceMode"
                                class="sr-only peer"
                            >
                            <div class="w-12 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-600"></div>
                        </label>
                    </div>

                    <!-- Buka / Tutup Register Switch -->
                    <div class="p-5 rounded-2xl border border-[#99cab7]/50 bg-[#f2f8f5]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="user-plus" class="w-4 h-4 text-[#31725e]"></i>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Buka / Tutup Pendaftaran Pengguna (User Registration)</h4>
                            </div>
                            <p class="text-[11px] text-stone-500 max-w-xl">
                                Jika <strong>Dibuka (Aktif)</strong>, pengunjung umum dapat mendaftarkan akun baru secara mandiri. Jika <strong>Ditutup (Nonaktif)</strong>, pendaftaran akun baru dimatikan dan siapa pun yang mengakses tautan pendaftaran akan langsung diarahkan ke halaman Login.
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input 
                                type="checkbox" 
                                name="registration_enabled" 
                                value="1" 
                                x-model="registrationEnabled"
                                class="sr-only peer"
                            >
                            <div class="w-12 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    </div>

                    <!-- Buka / Tutup Modul Artikel Switch -->
                    <div class="p-5 rounded-2xl border border-[#99cab7]/50 bg-[#f2f8f5]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="newspaper" class="w-4 h-4 text-[#31725e]"></i>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Buka / Tutup Modul Artikel (Article SEO Module)</h4>
                            </div>
                            <p class="text-[11px] text-stone-500 max-w-xl">
                                Jika <strong>Dibuka (Aktif)</strong>, menu Article SEO beserta submenu Kategori dan Tag akan muncul di navigasi sidebar dan seluruh fiturnya dapat diakses. Jika <strong>Ditutup (Nonaktif)</strong>, menu artikel akan disembunyikan dan seluruh akses halaman terkait artikel (Artikel, Kategori, Tag) otomatis ditutup/diblokir sistem.
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input 
                                type="checkbox" 
                                name="article_module_enabled" 
                                value="1" 
                                x-model="articleModuleEnabled"
                                class="sr-only peer"
                            >
                            <div class="w-12 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    </div>

                    <!-- Buka / Tutup Modul Testimonial Switch -->
                    <div class="p-5 rounded-2xl border border-[#99cab7]/50 bg-[#f2f8f5]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="quote" class="w-4 h-4 text-[#31725e]"></i>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Buka / Tutup Modul Testimonial (Testimonial Module)</h4>
                            </div>
                            <p class="text-[11px] text-stone-500 max-w-xl">
                                Jika <strong>Dibuka (Aktif)</strong>, menu Testimonial akan muncul di navigasi sidebar dan fitur kelola ulasan testimoni dapat diakses. Jika <strong>Ditutup (Nonaktif)</strong>, menu testimoni akan disembunyikan dan seluruh akses halaman terkait testimoni otomatis ditutup/diblokir sistem.
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input 
                                type="checkbox" 
                                name="testimonial_module_enabled" 
                                value="1" 
                                x-model="testimonialModuleEnabled"
                                class="sr-only peer"
                            >
                            <div class="w-12 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    </div>

                    <!-- Buka / Tutup Modul Brand / Partner Switch -->
                    <div class="p-5 rounded-2xl border border-[#99cab7]/50 bg-[#f2f8f5]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="handshake" class="w-4 h-4 text-[#31725e]"></i>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Buka / Tutup Modul Brand / Mitra (Partner Module)</h4>
                            </div>
                            <p class="text-[11px] text-stone-500 max-w-xl">
                                Jika <strong>Dibuka (Aktif)</strong>, menu Brand / Partner akan muncul di navigasi sidebar dan fitur kelola logo mitra/brand dapat diakses. Jika <strong>Ditutup (Nonaktif)</strong>, menu mitra akan disembunyikan dan seluruh akses halaman terkait mitra otomatis ditutup/diblokir sistem.
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input 
                                type="checkbox" 
                                name="partner_module_enabled" 
                                value="1" 
                                x-model="partnerModuleEnabled"
                                class="sr-only peer"
                            >
                            <div class="w-12 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    </div>

                    <!-- Buka / Tutup Modul FAQ Switch -->
                    <div class="p-5 rounded-2xl border border-[#99cab7]/50 bg-[#f2f8f5]/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <i data-lucide="help-circle" class="w-4 h-4 text-[#31725e]"></i>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Buka / Tutup Modul FAQ (Tanya Jawab)</h4>
                            </div>
                            <p class="text-[11px] text-stone-500 max-w-xl">
                                Jika <strong>Dibuka (Aktif)</strong>, menu FAQ akan muncul di navigasi sidebar dan fitur kelola tanya jawab publik dapat diakses. Jika <strong>Ditutup (Nonaktif)</strong>, menu FAQ akan disembunyikan dan seluruh akses halaman terkait FAQ otomatis ditutup/diblokir sistem.
                            </p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input 
                                type="checkbox" 
                                name="faq_module_enabled" 
                                value="1" 
                                x-model="faqModuleEnabled"
                                class="sr-only peer"
                            >
                            <div class="w-12 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    </div>

                    <!-- Cookie Consent Banner -->
                    <div class="p-5 rounded-2xl border border-[#99cab7]/40 bg-[#f2f8f5]/40 space-y-4">
                        <div class="flex items-center justify-between gap-4">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="cookie" class="w-4 h-4 text-[#31725e]"></i>
                                    <h4 class="text-xs font-bold text-[#1d3e35]">Banner Persetujuan Cookie (GDPR / Cookie Consent)</h4>
                                </div>
                                <p class="text-[11px] text-stone-500">
                                    Tampilkan bar notifikasi persetujuan cookie privasi kepada pengunjung saat pertama kali membuka website.
                                </p>
                            </div>

                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input 
                                    type="checkbox" 
                                    name="cookie_consent_enabled" 
                                    value="1" 
                                    x-model="cookieConsent"
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                            </label>
                        </div>

                        <div x-show="cookieConsent" class="pt-3 border-t border-[#99cab7]/30 space-y-2">
                            <label for="cookie_consent_text" class="block text-[11px] font-bold text-[#295c4d] uppercase">
                                Teks Pesan Notifikasi Cookie
                            </label>
                            <input
                                type="text"
                                name="cookie_consent_text"
                                id="cookie_consent_text"
                                placeholder="Kami menggunakan cookie untuk meningkatkan pengalaman navigasi Anda di situs ini..."
                                value="{{ old('cookie_consent_text', $config->cookie_consent_text) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white outline-none"
                            />
                        </div>
                    </div>

                    <!-- Teks Copyright & Footer -->
                    <div class="space-y-2">
                        <label for="footer_text" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                            Teks Hak Cipta Footer (Copyright Notice)
                        </label>
                        <input
                            type="text"
                            name="footer_text"
                            id="footer_text"
                            placeholder="© 2026 Lentera Pasar. All Rights Reserved."
                            value="{{ old('footer_text', $config->footer_text) }}"
                            class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-medium"
                        />
                        @error('footer_text')
                            <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>
        </div>

        <!-- ==================================================== -->
        <!-- TAB 2: BATASAN & KUOTA SISTEM (SYSTEM LIMITS)        -->
        <!-- ==================================================== -->
        <div x-show="activeTab === 'limits'" class="space-y-6">
            <!-- Header Info Card -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/40 shadow-2xs space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center shrink-0">
                        <i data-lucide="gauge" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-[#1d3e35]">Pengaturan Kapasitas & Batasan Kuota Data</h4>
                        <p class="text-xs text-stone-500">
                            Atur batas maksimal kapasitas penyimpanan berkas dan jumlah entitas data di dalam sistem. Masukkan angka <code class="px-1.5 py-0.5 rounded bg-stone-100 font-mono text-[#31725e] font-bold">0</code> jika ingin kuota tidak terbatas (unlimited).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Limit 1: Penyimpanan Media Library (Gudang Gambar) -->
            <x-admin.card 
                title="1. Batasan Penyimpanan Media Library" 
                subtitle="Atur kuota total kapasitas media penyimpanan gambar/foto di Gudang Media."
                icon="hard-drive"
            >
                <div class="space-y-4">
                    <div class="p-4 rounded-2xl bg-[#f2f8f5]/60 border border-[#99cab7]/40 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Status Pemakaian Saat Ini</span>
                                <div class="text-sm font-extrabold text-[#1d3e35] flex items-center gap-2 mt-0.5">
                                    <span>{{ $currentUsage['media_storage_mb'] }} MB</span>
                                    <span class="text-stone-400 font-normal">/</span>
                                    <span>{{ $config->limit_media_storage_mb > 0 ? $config->limit_media_storage_mb . ' MB (' . round($config->limit_media_storage_mb / 1024, 2) . ' GB)' : 'Unlimited (Tidak Terbatas)' }}</span>
                                </div>
                            </div>

                            @if($config->limit_media_storage_mb > 0)
                                @php
                                    $mediaPercent = min(100, round(($currentUsage['media_storage_mb'] / $config->limit_media_storage_mb) * 100, 1));
                                    $barColor = $mediaPercent > 90 ? 'bg-red-500' : ($mediaPercent > 70 ? 'bg-amber-500' : 'bg-[#31725e]');
                                @endphp
                                <div class="text-right">
                                    <span class="text-xs font-bold {{ $mediaPercent > 90 ? 'text-red-600' : 'text-[#31725e]' }}">{{ $mediaPercent }}% Terpakai</span>
                                </div>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-[#e2f0ea] text-[#295c4d] text-xs font-bold">Kapasitas Unlimited</span>
                            @endif
                        </div>

                        @if($config->limit_media_storage_mb > 0)
                            <div class="w-full h-2.5 bg-stone-200/80 rounded-full overflow-hidden">
                                <div class="h-full {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ $mediaPercent }}%;"></div>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label for="limit_media_storage_mb" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                            Batas Maksimal Penyimpanan Media (MB) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                name="limit_media_storage_mb"
                                id="limit_media_storage_mb"
                                min="0"
                                placeholder="1024"
                                value="{{ old('limit_media_storage_mb', $config->limit_media_storage_mb) }}"
                                class="w-full rounded-2xl p-3.5 pr-16 text-xs text-[#1d3e35] font-bold transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-stone-400">
                                MB
                            </div>
                        </div>
                        <p class="text-[11px] text-stone-500">
                            Panduan konversi: <strong class="text-[#31725e]">512 MB</strong> = 0.5 GB, <strong class="text-[#31725e]">1024 MB</strong> = 1 GB, <strong class="text-[#31725e]">2048 MB</strong> = 2 GB, <strong class="text-[#31725e]">5120 MB</strong> = 5 GB. Masukkan <strong class="text-[#31725e]">0</strong> untuk Unlimited.
                        </p>
                        @error('limit_media_storage_mb')
                            <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </x-admin.card>

            <!-- Limit 2: Kuota Modul Utama & Konten Web -->
            <x-admin.card 
                title="2. Batasan Kuota Pengguna & Konten Dinamis" 
                subtitle="Atur jumlah maksimal data yang diizinkan untuk setiap entitas."
                icon="layers"
            >
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Limit Pengguna -->
                    <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/40 space-y-3 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i data-lucide="users" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs font-extrabold text-[#1d3e35]">Jumlah Pengguna</span>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2 py-0.5 rounded-md">
                                Saat Ini: {{ $currentUsage['users_count'] }}
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <label for="limit_users_count" class="block text-[11px] font-bold uppercase text-[#295c4d]">
                                Batas Pengguna (Akun)
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="limit_users_count"
                                    id="limit_users_count"
                                    min="0"
                                    placeholder="50"
                                    value="{{ old('limit_users_count', $config->limit_users_count) }}"
                                    class="w-full rounded-xl p-2.5 pr-14 text-xs text-[#1d3e35] font-bold border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 bg-white outline-none"
                                />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-semibold text-stone-400">User</span>
                            </div>
                        </div>
                    </div>

                    <!-- Limit Artikel -->
                    <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/40 space-y-3 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs font-extrabold text-[#1d3e35]">Limit Artikel</span>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2 py-0.5 rounded-md">
                                Saat Ini: {{ $currentUsage['articles_count'] }}
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <label for="limit_articles_count" class="block text-[11px] font-bold uppercase text-[#295c4d]">
                                Batas Artikel (Pos)
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="limit_articles_count"
                                    id="limit_articles_count"
                                    min="0"
                                    placeholder="100"
                                    value="{{ old('limit_articles_count', $config->limit_articles_count) }}"
                                    class="w-full rounded-xl p-2.5 pr-16 text-xs text-[#1d3e35] font-bold border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 bg-white outline-none"
                                />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-semibold text-stone-400">Artikel</span>
                            </div>
                        </div>
                    </div>

                    <!-- Limit Galeri Kegiatan -->
                    <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/40 space-y-3 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                    <i data-lucide="image" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs font-extrabold text-[#1d3e35]">Limit Galeri Kegiatan</span>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2 py-0.5 rounded-md">
                                Saat Ini: {{ $currentUsage['gallery_activities_count'] }}
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <label for="limit_gallery_activities_count" class="block text-[11px] font-bold uppercase text-[#295c4d]">
                                Batas Galeri Kegiatan
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="limit_gallery_activities_count"
                                    id="limit_gallery_activities_count"
                                    min="0"
                                    placeholder="50"
                                    value="{{ old('limit_gallery_activities_count', $config->limit_gallery_activities_count) }}"
                                    class="w-full rounded-xl p-2.5 pr-16 text-xs text-[#1d3e35] font-bold border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 bg-white outline-none"
                                />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-semibold text-stone-400">Galeri</span>
                            </div>
                        </div>
                    </div>

                    <!-- Limit FAQ -->
                    <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/40 space-y-3 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                    <i data-lucide="help-circle" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs font-extrabold text-[#1d3e35]">Limit FAQ</span>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2 py-0.5 rounded-md">
                                Saat Ini: {{ $currentUsage['faqs_count'] }}
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <label for="limit_faqs_count" class="block text-[11px] font-bold uppercase text-[#295c4d]">
                                Batas Tanya Jawab (FAQ)
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="limit_faqs_count"
                                    id="limit_faqs_count"
                                    min="0"
                                    placeholder="50"
                                    value="{{ old('limit_faqs_count', $config->limit_faqs_count) }}"
                                    class="w-full rounded-xl p-2.5 pr-14 text-xs text-[#1d3e35] font-bold border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 bg-white outline-none"
                                />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-semibold text-stone-400">FAQ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Limit Brand / Partner -->
                    <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/40 space-y-3 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs font-extrabold text-[#1d3e35]">Limit Brand / Partner</span>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2 py-0.5 rounded-md">
                                Saat Ini: {{ $currentUsage['partners_count'] }}
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <label for="limit_partners_count" class="block text-[11px] font-bold uppercase text-[#295c4d]">
                                Batas Mitra / Partner
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="limit_partners_count"
                                    id="limit_partners_count"
                                    min="0"
                                    placeholder="50"
                                    value="{{ old('limit_partners_count', $config->limit_partners_count) }}"
                                    class="w-full rounded-xl p-2.5 pr-16 text-xs text-[#1d3e35] font-bold border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 bg-white outline-none"
                                />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-semibold text-stone-400">Mitra</span>
                            </div>
                        </div>
                    </div>

                    <!-- Limit Testimonial -->
                    <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/40 space-y-3 shadow-2xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                                    <i data-lucide="message-square-quote" class="w-4 h-4"></i>
                                </div>
                                <span class="text-xs font-extrabold text-[#1d3e35]">Limit Testimonial</span>
                            </div>
                            <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2 py-0.5 rounded-md">
                                Saat Ini: {{ $currentUsage['testimonials_count'] }}
                            </span>
                        </div>
                        <div class="space-y-1.5">
                            <label for="limit_testimonials_count" class="block text-[11px] font-bold uppercase text-[#295c4d]">
                                Batas Ulasan Testimoni
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    name="limit_testimonials_count"
                                    id="limit_testimonials_count"
                                    min="0"
                                    placeholder="50"
                                    value="{{ old('limit_testimonials_count', $config->limit_testimonials_count) }}"
                                    class="w-full rounded-xl p-2.5 pr-16 text-xs text-[#1d3e35] font-bold border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 bg-white outline-none"
                                />
                                <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-[11px] font-semibold text-stone-400">Testimoni</span>
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <!-- ==================================================== -->
        <!-- TAB 3: SEO GLOBAL & MESIN PENCARI                    -->
        <!-- ==================================================== -->
        <div x-show="activeTab === 'seo'" class="space-y-6">
            <x-admin.card 
                title="SEO Meta Global & Indexing Mesin Pencari" 
                subtitle="Optimasi meta tag default, kata kunci, dan izin pengindeksan Google/Bing."
                icon="globe"
            >
                <div class="space-y-6">
                    <!-- Robots Indexing Switch -->
                    <div class="p-4 rounded-2xl bg-[#f2f8f5]/50 border border-[#99cab7]/40 flex items-center justify-between gap-4">
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-[#1d3e35]">Izin Pengindeksan Mesin Pencari (Search Engine Indexing)</h4>
                            <p class="text-[11px] text-stone-500">
                                Mengizinkan Google, Bing, dan bot mesin pencari lainnya untuk mengindeks halaman website (`robots: index, follow`).
                            </p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer shrink-0">
                            <input 
                                type="checkbox" 
                                name="robots_indexing" 
                                value="1" 
                                x-model="robotsIndexing"
                                class="sr-only peer"
                            >
                            <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    </div>

                    <!-- Judul & Tagline Default Website -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="site_name" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Default Meta Title Website <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="site_name"
                                id="site_name"
                                placeholder="Lentera Pasar"
                                value="{{ old('site_name', $config->site_name) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-bold transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                required
                            />
                        </div>

                        <div class="space-y-2">
                            <label for="site_tagline" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Tagline / Subtitle Default
                            </label>
                            <input
                                type="text"
                                name="site_tagline"
                                id="site_tagline"
                                placeholder="Sistem Informasi Manajemen & Administrasi Terpadu"
                                value="{{ old('site_tagline', $config->site_tagline) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                        </div>
                    </div>

                    <!-- Meta Description -->
                    <div class="space-y-2">
                        <label for="site_description" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                            Default Meta Description
                        </label>
                        <textarea
                            name="site_description"
                            id="site_description"
                            rows="3"
                            placeholder="Deskripsi singkat website yang tampil pada hasil pencarian Google (150-160 karakter disarankan)..."
                            class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                        >{{ old('site_description', $config->site_description) }}</textarea>
                    </div>

                    <!-- Meta Keywords & Author -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="meta_keywords" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Meta Keywords (Dipisahkan Koma)
                            </label>
                            <input
                                type="text"
                                name="meta_keywords"
                                id="meta_keywords"
                                placeholder="lentera pasar, admin panel, manajemen pasar, portal berita"
                                value="{{ old('meta_keywords', $config->meta_keywords) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                        </div>

                        <div class="space-y-2">
                            <label for="meta_author" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Meta Author
                            </label>
                            <input
                                type="text"
                                name="meta_author"
                                id="meta_author"
                                placeholder="Lentera Pasar Tech Team"
                                value="{{ old('meta_author', $config->meta_author) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                        </div>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <!-- ==================================================== -->
        <!-- TAB 3: INTEGRASI SKRIP & PELACAK (CUSTOM SCRIPTS)    -->
        <!-- ==================================================== -->
        <div x-show="activeTab === 'scripts'" class="space-y-6">
            <x-admin.card 
                title="Integrasi Skrip & Pelacak Analitik" 
                subtitle="Pasang ID Google Analytics/GTM dan skrip pelacak eksternal (Facebook Pixel, Live Chat widget, dll)."
                icon="code-2"
            >
                <div class="space-y-6">
                    <!-- Google Analytics ID -->
                    <div class="space-y-2">
                        <label for="google_analytics_id" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                            Google Analytics Measurement ID / GTM ID
                        </label>
                        <input
                            type="text"
                            name="google_analytics_id"
                            id="google_analytics_id"
                            placeholder="Contoh: G-XXXXXXXXXX atau GTM-XXXXXX"
                            value="{{ old('google_analytics_id', $config->google_analytics_id) }}"
                            class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-mono font-bold transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                        />
                        <p class="text-[10px] text-stone-400">Masukkan ID Google Analytics 4 (GA4) atau Google Tag Manager.</p>
                    </div>

                    <!-- Custom Header Scripts -->
                    <div class="space-y-2">
                        <label for="custom_head_scripts" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                            Custom Header Scripts (Disuntikkan ke dalam tag &lt;head&gt;)
                        </label>
                        <textarea
                            name="custom_head_scripts"
                            id="custom_head_scripts"
                            rows="5"
                            placeholder="<!-- Contoh: Skrip Facebook Meta Pixel, Microsoft Clarity, dsb -->&#10;<script>...</script>"
                            class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-mono transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                        >{{ old('custom_head_scripts', $config->custom_head_scripts) }}</textarea>
                    </div>

                    <!-- Custom Body / Footer Scripts -->
                    <div class="space-y-2">
                        <label for="custom_body_scripts" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                            Custom Footer Scripts (Disuntikkan sebelum &lt;/body&gt;)
                        </label>
                        <textarea
                            name="custom_body_scripts"
                            id="custom_body_scripts"
                            rows="5"
                            placeholder="<!-- Contoh: Widget Tawk.to, WhatsApp Floating Button, Crisp Live Chat -->&#10;<script>...</script>"
                            class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-mono transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                        >{{ old('custom_body_scripts', $config->custom_body_scripts) }}</textarea>
                    </div>
                </div>
            </x-admin.card>
        </div>
    </form>
</x-layouts.admin>
