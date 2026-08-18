<x-layouts.admin title="Profile Business Identity (Identitas Bisnis Perusahaan)">
    <x-admin.breadcrumb 
        title="Profile Business Identity" 
        :items="[
            'Pengaturan' => '',
            'Business Identity' => ''
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

    <div class="space-y-6" x-data="{
        currentTab: 'identity',
        logoLightMediaId: '{{ old('logo_light_media_id', $identity->logo_light_media_id) }}',
        logoLightUrl: '{{ old('logo_light_url', $identity->getLogoLight()) }}',
        logoDarkMediaId: '{{ old('logo_dark_media_id', $identity->logo_dark_media_id) }}',
        logoDarkUrl: '{{ old('logo_dark_url', $identity->getLogoDark()) }}',
        faviconMediaId: '{{ old('favicon_media_id', $identity->favicon_media_id) }}',
        faviconUrl: '{{ old('favicon_url', $identity->getFavicon()) }}',
        heroBannerMediaId: '{{ old('hero_banner_media_id', $identity->hero_banner_media_id) }}',
        heroBannerUrl: '{{ old('hero_banner_url', $identity->getHeroBanner()) }}',

        handleMediaSelected(event) {
            const detail = event.detail;
            if (!detail.media) return;

            if (detail.targetField === 'identity_logo_light') {
                this.logoLightMediaId = detail.media.id;
                this.logoLightUrl = detail.media.url;
            } else if (detail.targetField === 'identity_logo_dark') {
                this.logoDarkMediaId = detail.media.id;
                this.logoDarkUrl = detail.media.url;
            } else if (detail.targetField === 'identity_favicon') {
                this.faviconMediaId = detail.media.id;
                this.faviconUrl = detail.media.url;
            } else if (detail.targetField === 'identity_hero_banner') {
                this.heroBannerMediaId = detail.media.id;
                this.heroBannerUrl = detail.media.url;
            }
        },

        removeAsset(type) {
            if (type === 'logo_light') {
                this.logoLightMediaId = '';
                this.logoLightUrl = '';
            } else if (type === 'logo_dark') {
                this.logoDarkMediaId = '';
                this.logoDarkUrl = '';
            } else if (type === 'favicon') {
                this.faviconMediaId = '';
                this.faviconUrl = '';
            } else if (type === 'hero_banner') {
                this.heroBannerMediaId = '';
                this.heroBannerUrl = '';
            }
        }
    }" @media-selected.window="handleMediaSelected($event)">
        <form action="{{ route('admin.business-identity.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Top Action Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Corporate Identity Management</span>
                        <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">{{ $identity->company_name }}</h3>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>

            <!-- Tab Navigation Bar -->
            <div class="flex items-center gap-2 p-1.5 rounded-2xl bg-white/80 border border-[#99cab7]/30 shadow-2xs overflow-x-auto">
                <button 
                    type="button" 
                    @click="currentTab = 'identity'"
                    :class="currentTab === 'identity' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                    class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
                >
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                    <span>1. Identitas & Legalitas</span>
                </button>

                <button 
                    type="button" 
                    @click="currentTab = 'banking'"
                    :class="currentTab === 'banking' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                    class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
                >
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    <span>2. Perbankan & Rekening</span>
                </button>

                <button 
                    type="button" 
                    @click="currentTab = 'profile'"
                    :class="currentTab === 'profile' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                    class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
                >
                    <i data-lucide="book-open" class="w-4 h-4"></i>
                    <span>3. Tentang, Visi & Misi</span>
                </button>

                <button 
                    type="button" 
                    @click="currentTab = 'media'"
                    :class="currentTab === 'media' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                    class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
                >
                    <i data-lucide="images" class="w-4 h-4"></i>
                    <span>4. Aset Visual & Logo</span>
                </button>

                <button 
                    type="button" 
                    @click="currentTab = 'contact'"
                    :class="currentTab === 'contact' ? 'bg-[#1d3e35] text-white shadow-xs' : 'text-stone-600 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/50'"
                    class="px-4 py-2 rounded-xl font-bold text-xs transition-all whitespace-nowrap inline-flex items-center gap-2 cursor-pointer"
                >
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <span>5. Kontak & Lokasi</span>
                </button>
            </div>

            <!-- ==================================================== -->
            <!-- TAB 1: IDENTITAS & LEGALITAS USAHA                   -->
            <!-- ==================================================== -->
            <div x-show="currentTab === 'identity'" class="space-y-6">
                <x-admin.card 
                    title="Identitas Resmi & Legalitas Usaha" 
                    subtitle="Informasi badan hukum, merek dagang, dan perizinan usaha perusahaan."
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Resmi Perusahaan -->
                        <div class="space-y-2">
                            <label for="company_name" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Nama Resmi Perusahaan / Entitas <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="company_name"
                                id="company_name"
                                placeholder="Contoh: PT Lentera Digital Nusantara"
                                value="{{ old('company_name', $identity->company_name) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-bold transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                required
                            />
                            @error('company_name')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Brand / Merek Komersial -->
                        <div class="space-y-2">
                            <label for="brand_name" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Nama Brand / Merek Komersial
                            </label>
                            <input
                                type="text"
                                name="brand_name"
                                id="brand_name"
                                placeholder="Contoh: Lentera Pasar"
                                value="{{ old('brand_name', $identity->brand_name) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('brand_name')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slogan / Tagline Bisnis -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="tagline" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Slogan / Tagline Bisnis
                            </label>
                            <input
                                type="text"
                                name="tagline"
                                id="tagline"
                                placeholder="Contoh: Solusi Digital & Ekosistem Bisnis Terpercaya"
                                value="{{ old('tagline', $identity->tagline) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('tagline')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bentuk Badan Usaha -->
                        <div class="space-y-2">
                            <label for="legal_type" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Bentuk Badan Usaha
                            </label>
                            <input
                                type="text"
                                name="legal_type"
                                id="legal_type"
                                placeholder="Contoh: Perseroan Terbatas (PT) / CV / Yayasan"
                                value="{{ old('legal_type', $identity->legal_type) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('legal_type')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sektor / Kategori Bisnis -->
                        <div class="space-y-2">
                            <label for="business_category" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Sektor Industri / Kategori Bisnis
                            </label>
                            <input
                                type="text"
                                name="business_category"
                                id="business_category"
                                placeholder="Contoh: Teknologi Informasi & Software"
                                value="{{ old('business_category', $identity->business_category) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('business_category')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NPWP -->
                        <div class="space-y-2">
                            <label for="npwp" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                NPWP Perusahaan
                            </label>
                            <input
                                type="text"
                                name="npwp"
                                id="npwp"
                                placeholder="Contoh: 01.234.567.8-901.000"
                                value="{{ old('npwp', $identity->npwp) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                            />
                            @error('npwp')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIB (Nomor Induk Berusaha) -->
                        <div class="space-y-2">
                            <label for="nib" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                NIB (Nomor Induk Berusaha)
                            </label>
                            <input
                                type="text"
                                name="nib"
                                id="nib"
                                placeholder="Contoh: 1234567890123"
                                value="{{ old('nib', $identity->nib) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                            />
                            @error('nib')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun Berdiri -->
                        <div class="space-y-2">
                            <label for="founded_year" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Tahun Berdiri
                            </label>
                            <input
                                type="text"
                                name="founded_year"
                                id="founded_year"
                                placeholder="Contoh: 2024"
                                value="{{ old('founded_year', $identity->founded_year) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                            />
                            @error('founded_year')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Direktur / Pimpinan -->
                        <div class="space-y-2">
                            <label for="director_name" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Nama Direktur / Pimpinan Utama
                            </label>
                            <input
                                type="text"
                                name="director_name"
                                id="director_name"
                                placeholder="Contoh: Ahmad Faisal, S.Kom"
                                value="{{ old('director_name', $identity->director_name) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('director_name')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-admin.card>
            </div>

            <!-- ==================================================== -->
            <!-- TAB 2: REKENING & PERBANKAN RESMI                   -->
            <!-- ==================================================== -->
            <div x-show="currentTab === 'banking'" class="space-y-6">
                <x-admin.card 
                    title="Informasi Perbankan & Rekening Resmi" 
                    subtitle="Data nomor rekening bank resmi untuk transaksi dan pembayaran bisnis."
                    icon="credit-card"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Bank -->
                        <div class="space-y-2">
                            <label for="bank_name" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Nama Bank
                            </label>
                            <input
                                type="text"
                                name="bank_name"
                                id="bank_name"
                                placeholder="Contoh: Bank Central Asia (BCA) / Bank Mandiri / BRI / BNI"
                                value="{{ old('bank_name', $identity->bank_name) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('bank_name')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Rekening -->
                        <div class="space-y-2">
                            <label for="bank_account_number" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Nomor Rekening
                            </label>
                            <input
                                type="text"
                                name="bank_account_number"
                                id="bank_account_number"
                                placeholder="Contoh: 8830123456"
                                value="{{ old('bank_account_number', $identity->bank_account_number) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-mono font-bold transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('bank_account_number')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Atas Nama Rekening -->
                        <div class="space-y-2">
                            <label for="bank_account_holder" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Atas Nama Pemilik Rekening
                            </label>
                            <input
                                type="text"
                                name="bank_account_holder"
                                id="bank_account_holder"
                                placeholder="Contoh: PT LENTERA DIGITAL NUSANTARA"
                                value="{{ old('bank_account_holder', $identity->bank_account_holder) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-bold transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none uppercase"
                            />
                            @error('bank_account_holder')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kantor Cabang Bank -->
                        <div class="space-y-2">
                            <label for="bank_branch" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Kantor Cabang Bank
                            </label>
                            <input
                                type="text"
                                name="bank_branch"
                                id="bank_branch"
                                placeholder="Contoh: KCU Sudirman Jakarta"
                                value="{{ old('bank_branch', $identity->bank_branch) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('bank_branch')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-admin.card>
            </div>

            <!-- ==================================================== -->
            <!-- TAB 3: TENTANG, VISI, MISI & BUDAYA                 -->
            <!-- ==================================================== -->
            <div x-show="currentTab === 'profile'" class="space-y-6">
                <x-admin.card 
                    title="Tentang Perusahaan, Visi, Misi & Budaya" 
                    subtitle="Kisah latar belakang, arah tujuan jangka panjang, dan nilai kerja perusahaan."
                >
                    <div class="space-y-6">
                        <!-- Ringkasan Profil Singkat -->
                        <div class="space-y-2">
                            <label for="about_summary" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Ringkasan Profil Singkat (About Summary)
                            </label>
                            <textarea
                                name="about_summary"
                                id="about_summary"
                                rows="3"
                                placeholder="Tuliskan ringkasan 1-2 paragraf mengenai perusahaan untuk cuplikan homepage..."
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                            >{{ old('about_summary', $identity->about_summary) }}</textarea>
                            @error('about_summary')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sejarah & Kisah Lengkap -->
                        <div class="space-y-2">
                            <label for="about_story" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Kisah & Sejarah Lengkap Perusahaan
                            </label>
                            <textarea
                                name="about_story"
                                id="about_story"
                                rows="6"
                                placeholder="Jelaskan perjalanan awal mula berdirinya perusahaan, tonggak pencapaian, dan komitmen bisnis..."
                                class="w-full rounded-2xl p-4 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                            >{{ old('about_story', $identity->about_story) }}</textarea>
                            @error('about_story')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Grid: Visi & Misi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Visi -->
                            <div class="space-y-2">
                                <label for="vision" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Visi Perusahaan
                                </label>
                                <textarea
                                    name="vision"
                                    id="vision"
                                    rows="4"
                                    placeholder="Tujuan jangka panjang perusahaan..."
                                    class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                                >{{ old('vision', $identity->vision) }}</textarea>
                                @error('vision')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Misi -->
                            <div class="space-y-2">
                                <label for="mission" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Misi Perusahaan
                                </label>
                                <textarea
                                    name="mission"
                                    id="mission"
                                    rows="4"
                                    placeholder="Langkah-langkah strategis dalam mencapai visi..."
                                    class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                                >{{ old('mission', $identity->mission) }}</textarea>
                                @error('mission')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nilai Inti / Core Values -->
                        <div class="space-y-2">
                            <label for="core_values" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Nilai-Nilai Utama / Budaya Kerja (Core Values)
                            </label>
                            <textarea
                                name="core_values"
                                id="core_values"
                                rows="3"
                                placeholder="Contoh: Integritas, Inovasi Tanpa Henti, Kepuasan Pelanggan, Kolaborasi..."
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                            >{{ old('core_values', $identity->core_values) }}</textarea>
                            @error('core_values')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-admin.card>
            </div>

            <!-- ==================================================== -->
            <!-- TAB 4: ASET VISUAL & LOGO (MEDIA LIBRARY)           -->
            <!-- ==================================================== -->
            <div x-show="currentTab === 'media'" class="space-y-6">
                <x-admin.card 
                    title="Aset Visual & Logo Perusahaan (Media Library)" 
                    subtitle="Pilih aset logo versi terang/gelap, favicon browser, dan foto banner utama kantor."
                >
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- 1. Logo Terang / Light Logo -->
                        <div class="space-y-3 p-4 rounded-2xl bg-[#f2f8f5]/40 border border-[#99cab7]/40 flex flex-col justify-between">
                            <input type="hidden" name="logo_light_media_id" :value="logoLightMediaId">
                            <input type="hidden" name="logo_light_url" :value="logoLightUrl">

                            <div>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Logo Versi Terang</h4>
                                <p class="text-[10px] text-stone-400">Untuk background putih / terang</p>
                            </div>

                            <div class="w-full h-24 rounded-xl bg-white border border-stone-200 p-2 flex items-center justify-center overflow-hidden shadow-2xs">
                                <template x-if="logoLightUrl">
                                    <img :src="logoLightUrl" alt="Logo Light" class="max-h-full max-w-full object-contain">
                                </template>
                                <template x-if="!logoLightUrl">
                                    <span class="text-[11px] text-stone-400 italic">Belum ada logo</span>
                                </template>
                            </div>

                            <div class="flex items-center gap-2">
                                <button 
                                    type="button"
                                    @click="$dispatch('open-media-picker', { targetField: 'identity_logo_light', multiple: false })"
                                    class="flex-1 py-1.5 rounded-xl bg-[#1d3e35] text-white hover:bg-[#31725e] text-[11px] font-bold text-center transition-colors cursor-pointer"
                                >
                                    Pilih Media
                                </button>
                                <button 
                                    type="button"
                                    x-show="logoLightUrl"
                                    @click="removeAsset('logo_light')"
                                    class="p-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer"
                                    title="Hapus"
                                >
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 2. Logo Gelap / Dark Logo -->
                        <div class="space-y-3 p-4 rounded-2xl bg-[#f2f8f5]/40 border border-[#99cab7]/40 flex flex-col justify-between">
                            <input type="hidden" name="logo_dark_media_id" :value="logoDarkMediaId">
                            <input type="hidden" name="logo_dark_url" :value="logoDarkUrl">

                            <div>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Logo Versi Gelap</h4>
                                <p class="text-[10px] text-stone-400">Untuk background gelap / dark mode</p>
                            </div>

                            <div class="w-full h-24 rounded-xl bg-stone-900 border border-stone-800 p-2 flex items-center justify-center overflow-hidden shadow-2xs">
                                <template x-if="logoDarkUrl">
                                    <img :src="logoDarkUrl" alt="Logo Dark" class="max-h-full max-w-full object-contain">
                                </template>
                                <template x-if="!logoDarkUrl">
                                    <span class="text-[11px] text-stone-500 italic">Belum ada logo</span>
                                </template>
                            </div>

                            <div class="flex items-center gap-2">
                                <button 
                                    type="button"
                                    @click="$dispatch('open-media-picker', { targetField: 'identity_logo_dark', multiple: false })"
                                    class="flex-1 py-1.5 rounded-xl bg-[#1d3e35] text-white hover:bg-[#31725e] text-[11px] font-bold text-center transition-colors cursor-pointer"
                                >
                                    Pilih Media
                                </button>
                                <button 
                                    type="button"
                                    x-show="logoDarkUrl"
                                    @click="removeAsset('logo_dark')"
                                    class="p-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer"
                                    title="Hapus"
                                >
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 3. Favicon -->
                        <div class="space-y-3 p-4 rounded-2xl bg-[#f2f8f5]/40 border border-[#99cab7]/40 flex flex-col justify-between">
                            <input type="hidden" name="favicon_media_id" :value="faviconMediaId">
                            <input type="hidden" name="favicon_url" :value="faviconUrl">

                            <div>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Favicon Browser</h4>
                                <p class="text-[10px] text-stone-400">Ikon tab browser (32x32 px)</p>
                            </div>

                            <div class="w-full h-24 rounded-xl bg-white border border-stone-200 p-2 flex items-center justify-center overflow-hidden shadow-2xs">
                                <template x-if="faviconUrl">
                                    <img :src="faviconUrl" alt="Favicon" class="w-10 h-10 object-contain">
                                </template>
                                <template x-if="!faviconUrl">
                                    <span class="text-[11px] text-stone-400 italic">Belum ada favicon</span>
                                </template>
                            </div>

                            <div class="flex items-center gap-2">
                                <button 
                                    type="button"
                                    @click="$dispatch('open-media-picker', { targetField: 'identity_favicon', multiple: false })"
                                    class="flex-1 py-1.5 rounded-xl bg-[#1d3e35] text-white hover:bg-[#31725e] text-[11px] font-bold text-center transition-colors cursor-pointer"
                                >
                                    Pilih Media
                                </button>
                                <button 
                                    type="button"
                                    x-show="faviconUrl"
                                    @click="removeAsset('favicon')"
                                    class="p-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer"
                                    title="Hapus"
                                >
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>

                        <!-- 4. Hero Banner / Foto Kantor -->
                        <div class="space-y-3 p-4 rounded-2xl bg-[#f2f8f5]/40 border border-[#99cab7]/40 flex flex-col justify-between">
                            <input type="hidden" name="hero_banner_media_id" :value="heroBannerMediaId">
                            <input type="hidden" name="hero_banner_url" :value="heroBannerUrl">

                            <div>
                                <h4 class="text-xs font-bold text-[#1d3e35]">Banner Hero Profil</h4>
                                <p class="text-[10px] text-stone-400">Foto gedung / kantor utama</p>
                            </div>

                            <div class="w-full h-24 rounded-xl bg-white border border-stone-200 p-1 flex items-center justify-center overflow-hidden shadow-2xs">
                                <template x-if="heroBannerUrl">
                                    <img :src="heroBannerUrl" alt="Hero Banner" class="w-full h-full object-cover rounded-lg">
                                </template>
                                <template x-if="!heroBannerUrl">
                                    <span class="text-[11px] text-stone-400 italic">Belum ada foto</span>
                                </template>
                            </div>

                            <div class="flex items-center gap-2">
                                <button 
                                    type="button"
                                    @click="$dispatch('open-media-picker', { targetField: 'identity_hero_banner', multiple: false })"
                                    class="flex-1 py-1.5 rounded-xl bg-[#1d3e35] text-white hover:bg-[#31725e] text-[11px] font-bold text-center transition-colors cursor-pointer"
                                >
                                    Pilih Media
                                </button>
                                <button 
                                    type="button"
                                    x-show="heroBannerUrl"
                                    @click="removeAsset('hero_banner')"
                                    class="p-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors cursor-pointer"
                                    title="Hapus"
                                >
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </x-admin.card>
            </div>

            <!-- ==================================================== -->
            <!-- TAB 5: KONTAK & LOKASI KANTOR                       -->
            <!-- ==================================================== -->
            <div x-show="currentTab === 'contact'" class="space-y-6">
                <x-admin.card 
                    title="Kontak Resmi & Lokasi Kantor Pusat" 
                    subtitle="Informasi alamat fisik, saluran komunikasi resmi, dan navigasi Google Maps."
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email Resmi -->
                        <div class="space-y-2">
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Email Resmi Perusahaan
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400">
                                    <i data-lucide="mail" class="w-4 h-4"></i>
                                </span>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    placeholder="info@lenteradigital.co.id"
                                    value="{{ old('email', $identity->email) }}"
                                    class="w-full rounded-2xl p-3.5 pl-10 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon Kantor -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Telepon Kantor
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </span>
                                <input
                                    type="text"
                                    name="phone"
                                    id="phone"
                                    placeholder="(021) 1234567"
                                    value="{{ old('phone', $identity->phone) }}"
                                    class="w-full rounded-2xl p-3.5 pl-10 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                                />
                            </div>
                            @error('phone')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp Hotline -->
                        <div class="space-y-2">
                            <label for="whatsapp" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                WhatsApp Hotline / Customer Care
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                </span>
                                <input
                                    type="text"
                                    name="whatsapp"
                                    id="whatsapp"
                                    placeholder="081234567890"
                                    value="{{ old('whatsapp', $identity->whatsapp) }}"
                                    class="w-full rounded-2xl p-3.5 pl-10 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                                />
                            </div>
                            @error('whatsapp')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Operasional -->
                        <div class="space-y-2">
                            <label for="operational_hours" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Jam Operasional Kantor
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-stone-400">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                </span>
                                <input
                                    type="text"
                                    name="operational_hours"
                                    id="operational_hours"
                                    placeholder="Senin - Jumat: 08:00 - 17:00 WIB"
                                    value="{{ old('operational_hours', $identity->operational_hours) }}"
                                    class="w-full rounded-2xl p-3.5 pl-10 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                />
                            </div>
                            @error('operational_hours')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="address" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Alamat Lengkap Kantor Pusat
                            </label>
                            <textarea
                                name="address"
                                id="address"
                                rows="3"
                                placeholder="Gedung Menara Lentera Lt. 8, Jl. Jenderal Sudirman No. 45..."
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                            >{{ old('address', $identity->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kota / Kabupaten -->
                        <div class="space-y-2">
                            <label for="city" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Kota / Kabupaten
                            </label>
                            <input
                                type="text"
                                name="city"
                                id="city"
                                placeholder="Jakarta Selatan"
                                value="{{ old('city', $identity->city) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('city')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Provinsi -->
                        <div class="space-y-2">
                            <label for="province" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Provinsi
                            </label>
                            <input
                                type="text"
                                name="province"
                                id="province"
                                placeholder="DKI Jakarta"
                                value="{{ old('province', $identity->province) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('province')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode Pos -->
                        <div class="space-y-2">
                            <label for="postal_code" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Kode Pos
                            </label>
                            <input
                                type="text"
                                name="postal_code"
                                id="postal_code"
                                placeholder="12190"
                                value="{{ old('postal_code', $identity->postal_code) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                            />
                            @error('postal_code')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Link Google Maps Langsung -->
                        <div class="space-y-2">
                            <label for="google_maps_url" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Link Google Maps (Share Link)
                            </label>
                            <input
                                type="url"
                                name="google_maps_url"
                                id="google_maps_url"
                                placeholder="https://maps.app.goo.gl/..."
                                value="{{ old('google_maps_url', $identity->google_maps_url) }}"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                            />
                            @error('google_maps_url')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Embed Google Maps HTML / Iframe -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="google_maps_embed" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Embed Google Maps (Iframe HTML)
                            </label>
                            <textarea
                                name="google_maps_embed"
                                id="google_maps_embed"
                                rows="3"
                                placeholder="<iframe src='https://www.google.com/maps/embed?...' width='100%' height='450' style='border:0;'></iframe>"
                                class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] font-mono transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                            >{{ old('google_maps_embed', $identity->google_maps_embed) }}</textarea>
                            @error('google_maps_embed')
                                <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-admin.card>
            </div>
        </form>
    </div>

    <!-- Reusable Media Library Modal -->
    <x-admin.media-picker-modal />
</x-layouts.admin>
