<x-layouts.admin title="Web Konfigurasi">
    <x-admin.breadcrumb 
        title="Web Konfigurasi" 
        :items="[
            'Pengaturan' => '',
            'Web Konfigurasi' => ''
        ]" 
    />

    <form 
        method="POST" 
        action="{{ route('admin.settings.update') }}" 
        enctype="multipart/form-data"
        class="space-y-6 max-w-6xl"
        x-data="{
            activeTab: 'branding', // 'branding', 'contact', 'social', 'seo'
            logoPreview: '{{ $config->logo_url }}',
            faviconPreview: '{{ $config->favicon_url }}',
            handleLogo(e) {
                const file = e.target.files[0];
                if (file) {
                    this.logoPreview = URL.createObjectURL(file);
                }
            },
            handleFavicon(e) {
                const file = e.target.files[0];
                if (file) {
                    this.faviconPreview = URL.createObjectURL(file);
                }
            }
        }"
    >
        @csrf
        @method('PUT')

        <!-- 1. Hero Header Card -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#295c4d] to-[#cca06e] p-1 shadow-md shrink-0">
                    <div class="w-full h-full bg-[#1d3e35] rounded-[14px] flex items-center justify-center text-white text-xl">
                        <i data-lucide="settings" class="w-7 h-7 text-[#cca06e]"></i>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-[#1d3e35]">Web Konfigurasi</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Pengaturan Terpusat
                        </span>
                    </div>
                    <p class="text-xs text-stone-500 mt-1">
                        Kelola identitas website, logo, nomor kontak, tautan sosial media, dan parameter SEO publik sistem.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <x-form.button 
                    type="submit" 
                    variant="primary" 
                    :fullWidth="false" 
                    icon="save"
                >
                    Simpan Konfigurasi
                </x-form.button>
            </div>
        </div>

        <!-- 2. Navigation Tabs -->
        <div class="p-1.5 rounded-2xl bg-stone-100/80 border border-stone-200/60 inline-flex items-center gap-1.5 w-full sm:w-auto overflow-x-auto scrollbar-none">
            <button 
                type="button" 
                @click="activeTab = 'branding'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer"
                :class="activeTab === 'branding' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
            >
                <i data-lucide="globe" class="w-4 h-4 text-[#31725e]"></i>
                <span>Identitas & Branding</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'contact'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer"
                :class="activeTab === 'contact' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
            >
                <i data-lucide="phone-call" class="w-4 h-4 text-[#cca06e]"></i>
                <span>Kontak & Lokasi</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'social'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer"
                :class="activeTab === 'social' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
            >
                <i data-lucide="share-2" class="w-4 h-4 text-[#428e75]"></i>
                <span>Media Sosial</span>
            </button>

            <button 
                type="button" 
                @click="activeTab = 'seo'"
                class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shrink-0 cursor-pointer"
                :class="activeTab === 'seo' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
            >
                <i data-lucide="search" class="w-4 h-4 text-[#784732]"></i>
                <span>SEO & Sistem</span>
            </button>
        </div>

        <!-- 3. Tab Contents -->

        <!-- Tab 1: Identitas & Branding -->
        <div x-show="activeTab === 'branding'" class="space-y-6">
            <x-admin.card 
                title="Identitas & Branding Website" 
                subtitle="Konfigurasi nama utama, slogan, deskripsi, dan aset grafis logo situs."
            >
                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-form.input
                            name="site_name"
                            label="Nama Website"
                            icon="type"
                            :required="true"
                            :value="old('site_name', $config->site_name)"
                            placeholder="Contoh: Lentera Pasar"
                        />

                        <x-form.input
                            name="site_tagline"
                            label="Slogan / Tagline"
                            icon="sparkles"
                            :value="old('site_tagline', $config->site_tagline)"
                            placeholder="Contoh: Sistem Informasi Manajemen & Administrasi Terpadu"
                        />
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="space-y-1.5">
                        <label for="site_description" class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                            Deskripsi Singkat Website
                        </label>
                        <textarea
                            name="site_description"
                            id="site_description"
                            rows="3"
                            placeholder="Deskripsi singkat mengenai platform atau perusahaan..."
                            class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/80 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/70 hover:bg-white/90 outline-none"
                        >{{ old('site_description', $config->site_description) }}</textarea>
                    </div>

                    <!-- Upload Logo & Favicon Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-stone-100">
                        <!-- Upload Logo -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                                Logo Utama Website
                            </label>
                            <p class="text-[11px] text-stone-400">Format: PNG, JPG, WEBP, SVG (Maks. 2MB). Rekomendasi rasio horizontal.</p>

                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-[#f2f8f5]/60 border border-[#99cab7]/40">
                                <div class="w-20 h-20 rounded-2xl bg-white border border-[#99cab7]/30 flex items-center justify-center p-2 shrink-0 overflow-hidden shadow-2xs">
                                    <template x-if="logoPreview">
                                        <img :src="logoPreview" alt="Logo Preview" class="max-h-full max-w-full object-contain">
                                    </template>
                                    <template x-if="!logoPreview">
                                        <div class="text-stone-300 flex flex-col items-center gap-1">
                                            <i data-lucide="image" class="w-6 h-6"></i>
                                            <span class="text-[9px]">Belum ada</span>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex-1 space-y-1">
                                    <input 
                                        type="file" 
                                        name="logo" 
                                        id="logo_input"
                                        accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                        @change="handleLogo"
                                        class="hidden"
                                    />
                                    <label 
                                        for="logo_input" 
                                        class="px-3.5 py-2 rounded-xl bg-white border border-[#99cab7]/60 hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs"
                                    >
                                        <i data-lucide="upload-cloud" class="w-3.5 h-3.5 text-[#31725e]"></i>
                                        <span>Pilih File Logo</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Favicon -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                                Favicon Browser
                            </label>
                            <p class="text-[11px] text-stone-400">Format: ICO, PNG, SVG (Maks. 1MB). Rekomendasi ukuran 32x32 atau 64x64 px.</p>

                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-[#f2f8f5]/60 border border-[#99cab7]/40">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-[#99cab7]/30 flex items-center justify-center p-2 shrink-0 overflow-hidden shadow-2xs">
                                    <template x-if="faviconPreview">
                                        <img :src="faviconPreview" alt="Favicon Preview" class="w-8 h-8 object-contain">
                                    </template>
                                    <template x-if="!faviconPreview">
                                        <div class="text-stone-300 flex flex-col items-center">
                                            <i data-lucide="globe" class="w-5 h-5"></i>
                                        </div>
                                    </template>
                                </div>

                                <div class="flex-1 space-y-1">
                                    <input 
                                        type="file" 
                                        name="favicon" 
                                        id="favicon_input"
                                        accept="image/x-icon,image/png,image/svg+xml,image/jpeg"
                                        @change="handleFavicon"
                                        class="hidden"
                                    />
                                    <label 
                                        for="favicon_input" 
                                        class="px-3.5 py-2 rounded-xl bg-white border border-[#99cab7]/60 hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-1.5 transition-colors cursor-pointer shadow-2xs"
                                    >
                                        <i data-lucide="upload-cloud" class="w-3.5 h-3.5 text-[#31725e]"></i>
                                        <span>Pilih File Favicon</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <!-- Tab 2: Kontak & Lokasi -->
        <div x-show="activeTab === 'contact'" class="space-y-6" x-cloak>
            <x-admin.card 
                title="Informasi Kontak & Lokasi Kantor" 
                subtitle="Data kontak resmi yang ditampilkan pada halaman publik dan footer aplikasi."
            >
                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <x-form.input
                            type="email"
                            name="contact_email"
                            label="Email Resmi / Support"
                            icon="mail"
                            :value="old('contact_email', $config->contact_email)"
                            placeholder="support@lenterapasar.id"
                        />

                        <x-form.input
                            name="contact_phone"
                            label="Nomor Telepon Kantor"
                            icon="phone"
                            :value="old('contact_phone', $config->contact_phone)"
                            placeholder="+62 812-3456-7890"
                        />

                        <x-form.input
                            name="contact_whatsapp"
                            label="Nomor WhatsApp Hotline"
                            icon="message-square"
                            :value="old('contact_whatsapp', $config->contact_whatsapp)"
                            placeholder="+62 812-3456-7890"
                        />
                    </div>

                    <!-- Alamat Kantor -->
                    <div class="space-y-1.5">
                        <label for="contact_address" class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                            Alamat Kantor / Operasional
                        </label>
                        <textarea
                            name="contact_address"
                            id="contact_address"
                            rows="3"
                            placeholder="Alamat lengkap gedung kantor atau pusat operasional..."
                            class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/80 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/70 hover:bg-white/90 outline-none"
                        >{{ old('contact_address', $config->contact_address) }}</textarea>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <!-- Tab 3: Media Sosial -->
        <div x-show="activeTab === 'social'" class="space-y-6" x-cloak>
            <x-admin.card 
                title="Tautan Akun Media Sosial" 
                subtitle="Masukkan URL tautan profil media sosial resmi untuk dihubungkan ke footer dan landing page."
            >
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <x-form.input
                        name="social_facebook"
                        label="Facebook URL"
                        icon="facebook"
                        :value="old('social_facebook', $config->social_facebook)"
                        placeholder="https://facebook.com/lenterapasar"
                    />

                    <x-form.input
                        name="social_instagram"
                        label="Instagram URL"
                        icon="instagram"
                        :value="old('social_instagram', $config->social_instagram)"
                        placeholder="https://instagram.com/lenterapasar"
                    />

                    <x-form.input
                        name="social_twitter"
                        label="Twitter / X URL"
                        icon="twitter"
                        :value="old('social_twitter', $config->social_twitter)"
                        placeholder="https://x.com/lenterapasar"
                    />

                    <x-form.input
                        name="social_youtube"
                        label="YouTube URL"
                        icon="youtube"
                        :value="old('social_youtube', $config->social_youtube)"
                        placeholder="https://youtube.com/@lenterapasar"
                    />

                    <x-form.input
                        name="social_linkedin"
                        label="LinkedIn URL"
                        icon="linkedin"
                        :value="old('social_linkedin', $config->social_linkedin)"
                        placeholder="https://linkedin.com/company/lenterapasar"
                    />
                </div>
            </x-admin.card>
        </div>

        <!-- Tab 4: SEO & Sistem -->
        <div x-show="activeTab === 'seo'" class="space-y-6" x-cloak>
            <x-admin.card 
                title="Optimasi Mesin Pencari (SEO) & Sistem" 
                subtitle="Konfigurasi meta tag, hak cipta footer, dan kontrol status pemeliharaan situs."
            >
                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-form.input
                            name="meta_keywords"
                            label="Meta Keywords (Kata Kunci)"
                            icon="key"
                            :value="old('meta_keywords', $config->meta_keywords)"
                            placeholder="admin panel, pasar, manajemen transaksi"
                            helper="Pisahkan kata kunci dengan tanda koma."
                        />

                        <x-form.input
                            name="meta_author"
                            label="Meta Author (Penulis / Pengembang)"
                            icon="user"
                            :value="old('meta_author', $config->meta_author)"
                            placeholder="Lentera Pasar Tech Team"
                        />
                    </div>

                    <x-form.input
                        name="footer_text"
                        label="Teks Hak Cipta Footer"
                        icon="copyright"
                        :value="old('footer_text', $config->footer_text)"
                        placeholder="© 2026 Lentera Pasar. All Rights Reserved."
                    />

                    <!-- Maintenance Mode Switch -->
                    <div class="pt-4 border-t border-stone-100 flex items-center justify-between p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                        <div class="space-y-0.5">
                            <h4 class="text-xs font-bold text-amber-950 flex items-center gap-1.5">
                                <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-700"></i>
                                <span>Mode Pemeliharaan (Maintenance Mode)</span>
                            </h4>
                            <p class="text-[11px] text-amber-800">Jika diaktifkan, pengguna umum akan melihat tampilan pemeliharaan sistem saat mengakses landing page.</p>
                        </div>

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="maintenance_mode" 
                                value="1" 
                                class="sr-only peer"
                                {{ old('maintenance_mode', $config->maintenance_mode) ? 'checked' : '' }}
                            >
                            <div class="w-11 h-6 bg-stone-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1d3e35]"></div>
                        </label>
                    </div>
                </div>
            </x-admin.card>
        </div>

        <!-- Sticky Bottom Save Bar -->
        <div class="p-4 rounded-2xl bg-white border border-[#99cab7]/30 shadow-md flex items-center justify-between gap-4">
            <p class="text-xs text-stone-500">
                Terakhir diperbarui: <strong class="text-[#1d3e35]">{{ $config->updated_at ? $config->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : 'Belum pernah' }}</strong>
            </p>

            <x-form.button 
                type="submit" 
                variant="primary" 
                :fullWidth="false" 
                icon="save"
            >
                Simpan Seluruh Perubahan
            </x-form.button>
        </div>
    </form>
</x-layouts.admin>
