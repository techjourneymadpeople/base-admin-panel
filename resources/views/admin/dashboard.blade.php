<x-layouts.admin title="Dashboard Utama">
    @php
        $identity = $identity ?? \App\Models\BusinessIdentity::current();
        $authLogo = $identity->getLogoLight() ?: $identity->getLogoDark();
    @endphp

    <!-- Breadcrumb Header -->
    <x-admin.breadcrumb 
        title="Dashboard Utama" 
        :items="['Dashboard' => '']" 
    />

    <!-- 1. Hero Welcome Banner -->
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
                    Anda masuk dengan hak akses <span class="font-bold text-[#fef3c7] underline decoration-[#cca06e] underline-offset-4">{{ auth()->user()->roles->pluck('name')->first() ?? 'Admin' }}</span>. Berikut adalah rangkuman resmi data profil identitas bisnis dan operasional perusahaan Anda.
                </p>
            </div>

            @can('edit-business-identity')
                <div class="shrink-0">
                    <a 
                        href="{{ route('admin.business-identity.edit') }}" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white/90 hover:bg-white text-[#1d3e35] font-bold text-xs shadow-lg backdrop-blur-md transition-all hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i data-lucide="edit-3" class="w-4 h-4 text-[#31725e]"></i>
                        <span>Edit Business Identity</span>
                    </a>
                </div>
            @endcan
        </div>
    </div>

    <!-- 2. Profile Business Identity Overview Grid -->
    <div class="space-y-6">
        <!-- Top Header Card: Brand Showcase & Quick Header -->
        <div class="p-6 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                @if($authLogo)
                    <div class="w-16 h-16 rounded-2xl bg-stone-50 border border-stone-200 p-2 flex items-center justify-center shrink-0 shadow-inner">
                        <img src="{{ $authLogo }}" alt="{{ $identity->getBrandDisplayName() }}" class="max-h-full max-w-full object-contain">
                    </div>
                @else
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#1d3e35] to-[#31725e] text-white font-extrabold text-xl flex items-center justify-center shrink-0 shadow-md">
                        {{ $identity->getBrandInitials() }}
                    </div>
                @endif

                <div class="space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-xl font-extrabold text-[#1d3e35]">
                            {{ $identity->brand_name ?: 'Belum Diisi' }}
                        </h3>
                        @if($identity->legal_type)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#e2f0ea] text-[#1d3e35] border border-[#99cab7]/40">
                                {{ $identity->legal_type }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-stone-500 font-medium">
                        {{ $identity->company_name ?: 'Belum Diisi' }}
                    </p>
                    <p class="text-xs text-[#784732] italic">
                        "{{ $identity->tagline ?: 'Belum Diisi' }}"
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2 md:pt-0 border-t md:border-t-0 border-stone-100">
                <div class="px-4 py-2 rounded-2xl bg-[#f2f8f5] border border-[#99cab7]/30 text-center">
                    <span class="block text-[10px] uppercase font-bold text-stone-400">Tahun Berdiri</span>
                    <span class="text-xs font-extrabold text-[#1d3e35]">
                        {{ $identity->founded_year ?: 'Belum Diisi' }}
                    </span>
                </div>
                <div class="px-4 py-2 rounded-2xl bg-[#f2f8f5] border border-[#99cab7]/30 text-center">
                    <span class="block text-[10px] uppercase font-bold text-stone-400">Bidang Usaha</span>
                    <span class="text-xs font-extrabold text-[#1d3e35] line-clamp-1 max-w-[140px]">
                        {{ $identity->business_category ?: 'Belum Diisi' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2-Column Grid for Detailed Identity Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- SECTION 1: Identitas & Legalitas Usaha -->
            <x-admin.card 
                title="Identitas & Legalitas Usaha" 
                subtitle="Data resmi entitas legal, kepemimpinan, dan perpajakan badan usaha."
                icon="building-2"
            >
                <div class="divide-y divide-stone-100">
                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Nama Brand / Komersial</span>
                        <span class="text-xs font-bold {{ $identity->brand_name ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->brand_name ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Nama Resmi Perusahaan</span>
                        <span class="text-xs font-bold {{ $identity->company_name ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->company_name ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Slogan / Tagline</span>
                        <span class="text-xs font-bold {{ $identity->tagline ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->tagline ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Bentuk Badan Hukum</span>
                        <span class="text-xs font-bold {{ $identity->legal_type ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->legal_type ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Kategori & Bidang Usaha</span>
                        <span class="text-xs font-bold {{ $identity->business_category ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->business_category ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Direktur / Pimpinan</span>
                        <span class="text-xs font-bold {{ $identity->director_name ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->director_name ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Nomor Induk Berusaha (NIB)</span>
                        <span class="text-xs font-bold font-mono {{ $identity->nib ? 'text-stone-800' : 'text-stone-400 italic font-sans' }}">
                            {{ $identity->nib ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500">Nomor NPWP Perusahaan</span>
                        <span class="text-xs font-bold font-mono {{ $identity->npwp ? 'text-stone-800' : 'text-stone-400 italic font-sans' }}">
                            {{ $identity->npwp ?: 'Belum Diisi' }}
                        </span>
                    </div>
                </div>
            </x-admin.card>

            <!-- SECTION 2: Kontak & Kantor Operasional -->
            <x-admin.card 
                title="Kontak & Kantor Operasional" 
                subtitle="Alamat kantor resmi, saluran komunikasi, dan jam operasional."
                icon="map-pin"
            >
                <div class="divide-y divide-stone-100">
                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500 inline-flex items-center gap-1.5">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>Email Resmi</span>
                        </span>
                        @if($identity->email)
                            <a href="mailto:{{ $identity->email }}" class="text-xs font-bold text-[#1d3e35] hover:underline">
                                {{ $identity->email }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500 inline-flex items-center gap-1.5">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>Telepon Kantor</span>
                        </span>
                        @if($identity->phone)
                            <a href="tel:{{ $identity->phone }}" class="text-xs font-bold text-[#1d3e35] hover:underline">
                                {{ $identity->phone }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500 inline-flex items-center gap-1.5">
                            <i data-lucide="message-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Nomor WhatsApp</span>
                        </span>
                        @if($identity->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $identity->whatsapp) }}" target="_blank" class="text-xs font-bold text-emerald-700 hover:underline">
                                {{ $identity->whatsapp }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <div class="py-3 flex items-center justify-between gap-4">
                        <span class="text-xs font-medium text-stone-500 inline-flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-[#cca06e]"></i>
                            <span>Jam Operasional</span>
                        </span>
                        <span class="text-xs font-bold {{ $identity->operational_hours ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->operational_hours ?: 'Belum Diisi' }}
                        </span>
                    </div>

                    <div class="py-3 space-y-1">
                        <span class="text-xs font-medium text-stone-500">Alamat Lengkap</span>
                        <p class="text-xs font-bold leading-relaxed {{ $identity->address ? 'text-stone-800' : 'text-stone-400 italic' }}">
                            {{ $identity->address ?: 'Belum Diisi' }}
                        </p>
                    </div>

                    <div class="py-3 grid grid-cols-3 gap-2">
                        <div>
                            <span class="block text-[10px] text-stone-400 font-bold uppercase">Kota</span>
                            <span class="text-xs font-bold {{ $identity->city ? 'text-stone-800' : 'text-stone-400 italic' }}">
                                {{ $identity->city ?: 'Belum Diisi' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-stone-400 font-bold uppercase">Provinsi</span>
                            <span class="text-xs font-bold {{ $identity->province ? 'text-stone-800' : 'text-stone-400 italic' }}">
                                {{ $identity->province ?: 'Belum Diisi' }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-stone-400 font-bold uppercase">Kode Pos</span>
                            <span class="text-xs font-bold {{ $identity->postal_code ? 'text-stone-800' : 'text-stone-400 italic' }}">
                                {{ $identity->postal_code ?: 'Belum Diisi' }}
                            </span>
                        </div>
                    </div>
                </div>
            </x-admin.card>

            <!-- SECTION 3: Rekening Resmi Perbankan -->
            <x-admin.card 
                title="Informasi Rekening Perbankan" 
                subtitle="Rekening bank resmi untuk transaksi dan pembayaran bisnis."
                icon="credit-card"
            >
                <div class="p-5 rounded-2xl bg-gradient-to-br from-[#1d3e35] via-[#295c4d] to-[#1d3e35] text-white space-y-4 shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="landmark" class="w-5 h-5 text-[#cca06e]"></i>
                            <span class="text-sm font-extrabold tracking-wide uppercase">
                                {{ $identity->bank_name ?: 'Belum Diisi' }}
                            </span>
                        </div>
                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-md bg-white/15 border border-white/20 text-[#cca06e]">
                            Akun Resmi
                        </span>
                    </div>

                    <div class="space-y-0.5">
                        <span class="text-[10px] uppercase text-[#e2f0ea]/70 font-semibold tracking-wider">Nomor Rekening</span>
                        <p class="text-lg font-mono font-extrabold tracking-widest text-[#fef3c7]">
                            {{ $identity->bank_account_number ?: 'Belum Diisi' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-white/15 text-xs">
                        <div>
                            <span class="block text-[10px] text-[#e2f0ea]/70 font-medium">Atas Nama</span>
                            <span class="font-bold text-white uppercase">
                                {{ $identity->bank_account_holder ?: 'Belum Diisi' }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="block text-[10px] text-[#e2f0ea]/70 font-medium">Kantor Cabang</span>
                            <span class="font-bold text-white">
                                {{ $identity->bank_branch ?: 'Belum Diisi' }}
                            </span>
                        </div>
                    </div>
                </div>
            </x-admin.card>

            <!-- SECTION 4: Sosial Media Resmi -->
            <x-admin.card 
                title="Sosial Media Resmi" 
                subtitle="Tautan ke akun media sosial publik milik perusahaan."
                icon="share-2"
            >
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Instagram -->
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="instagram" class="w-4 h-4 text-pink-600 shrink-0"></i>
                            <span class="text-xs font-bold text-stone-700">Instagram</span>
                        </div>
                        @if($identity->social_instagram)
                            <a href="{{ $identity->social_instagram }}" target="_blank" class="text-xs font-bold text-[#31725e] hover:underline truncate max-w-[150px]">
                                {{ $identity->social_instagram }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <!-- TikTok -->
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="music-2" class="w-4 h-4 text-stone-900 shrink-0"></i>
                            <span class="text-xs font-bold text-stone-700">TikTok</span>
                        </div>
                        @if($identity->social_tiktok)
                            <a href="{{ $identity->social_tiktok }}" target="_blank" class="text-xs font-bold text-[#31725e] hover:underline truncate max-w-[150px]">
                                {{ $identity->social_tiktok }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <!-- YouTube -->
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="youtube" class="w-4 h-4 text-red-600 shrink-0"></i>
                            <span class="text-xs font-bold text-stone-700">YouTube</span>
                        </div>
                        @if($identity->social_youtube)
                            <a href="{{ $identity->social_youtube }}" target="_blank" class="text-xs font-bold text-[#31725e] hover:underline truncate max-w-[150px]">
                                {{ $identity->social_youtube }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <!-- LinkedIn -->
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="linkedin" class="w-4 h-4 text-blue-700 shrink-0"></i>
                            <span class="text-xs font-bold text-stone-700">LinkedIn</span>
                        </div>
                        @if($identity->social_linkedin)
                            <a href="{{ $identity->social_linkedin }}" target="_blank" class="text-xs font-bold text-[#31725e] hover:underline truncate max-w-[150px]">
                                {{ $identity->social_linkedin }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <!-- Facebook -->
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="facebook" class="w-4 h-4 text-blue-600 shrink-0"></i>
                            <span class="text-xs font-bold text-stone-700">Facebook</span>
                        </div>
                        @if($identity->social_facebook)
                            <a href="{{ $identity->social_facebook }}" target="_blank" class="text-xs font-bold text-[#31725e] hover:underline truncate max-w-[150px]">
                                {{ $identity->social_facebook }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <!-- Twitter / X -->
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="twitter" class="w-4 h-4 text-sky-500 shrink-0"></i>
                            <span class="text-xs font-bold text-stone-700">Twitter / X</span>
                        </div>
                        @if($identity->social_twitter)
                            <a href="{{ $identity->social_twitter }}" target="_blank" class="text-xs font-bold text-[#31725e] hover:underline truncate max-w-[150px]">
                                {{ $identity->social_twitter }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>

                    <!-- Threads -->
                    <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-3 sm:col-span-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="at-sign" class="w-4 h-4 text-stone-800 shrink-0"></i>
                            <span class="text-xs font-bold text-stone-700">Threads</span>
                        </div>
                        @if($identity->social_threads)
                            <a href="{{ $identity->social_threads }}" target="_blank" class="text-xs font-bold text-[#31725e] hover:underline truncate max-w-[200px]">
                                {{ $identity->social_threads }}
                            </a>
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>
                </div>
            </x-admin.card>

        </div>

        <!-- SECTION 5: Tentang Perusahaan, Visi & Misi (Full Width Card) -->
        <x-admin.card 
            title="Tentang Perusahaan, Visi & Misi" 
            subtitle="Ringkasan profil perusahaan, visi jangka panjang, misi kerja, dan nilai-nilai inti."
            icon="compass"
        >
            <div class="space-y-6">
                <!-- Ringkasan Tentang Bisnis -->
                <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200/80 space-y-2">
                    <span class="text-xs font-bold text-[#1d3e35] uppercase tracking-wider block">Ringkasan Profil Bisnis</span>
                    <p class="text-xs leading-relaxed {{ $identity->about_summary ? 'text-stone-700 font-normal' : 'text-stone-400 italic' }}">
                        {{ $identity->about_summary ?: 'Belum Diisi' }}
                    </p>
                </div>

                <!-- Grid Visi & Misi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-5 rounded-2xl bg-[#e2f0ea]/40 border border-[#99cab7]/40 space-y-2">
                        <div class="flex items-center gap-2 text-[#1d3e35]">
                            <i data-lucide="eye" class="w-4 h-4 text-[#31725e]"></i>
                            <h4 class="text-xs font-bold uppercase tracking-wider">Visi Perusahaan</h4>
                        </div>
                        <p class="text-xs leading-relaxed {{ $identity->vision ? 'text-stone-700 font-normal' : 'text-stone-400 italic' }}">
                            {{ $identity->vision ?: 'Belum Diisi' }}
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-[#e2f0ea]/40 border border-[#99cab7]/40 space-y-2">
                        <div class="flex items-center gap-2 text-[#1d3e35]">
                            <i data-lucide="target" class="w-4 h-4 text-[#31725e]"></i>
                            <h4 class="text-xs font-bold uppercase tracking-wider">Misi Perusahaan</h4>
                        </div>
                        <p class="text-xs leading-relaxed whitespace-pre-line {{ $identity->mission ? 'text-stone-700 font-normal' : 'text-stone-400 italic' }}">
                            {{ $identity->mission ?: 'Belum Diisi' }}
                        </p>
                    </div>
                </div>

                <!-- Nilai Inti (Core Values) -->
                <div class="p-5 rounded-2xl bg-amber-50/40 border border-amber-200/60 space-y-2">
                    <div class="flex items-center gap-2 text-stone-800">
                        <i data-lucide="award" class="w-4 h-4 text-amber-600"></i>
                        <h4 class="text-xs font-bold uppercase tracking-wider">Nilai-Nilai Inti (Core Values)</h4>
                    </div>
                    <p class="text-xs leading-relaxed whitespace-pre-line {{ $identity->core_values ? 'text-stone-700 font-normal' : 'text-stone-400 italic' }}">
                        {{ $identity->core_values ?: 'Belum Diisi' }}
                    </p>
                </div>
            </div>
        </x-admin.card>

        <!-- SECTION 6: Aset Visual & Logo Perusahaan -->
        <x-admin.card 
            title="Aset Visual & Logo Perusahaan" 
            subtitle="Pratinjau logo resmi dan aset grafis identitas visual yang tersimpan di sistem."
            icon="image"
        >
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Logo Light -->
                <div class="p-4 rounded-2xl bg-white border border-stone-200 text-center space-y-2">
                    <span class="block text-[11px] font-bold text-stone-500">Logo Versi Terang</span>
                    <div class="h-24 rounded-xl bg-stone-100/70 border border-stone-200 flex items-center justify-center p-2">
                        @if($identity->getLogoLight())
                            <img src="{{ $identity->getLogoLight() }}" alt="Logo Light" class="max-h-full max-w-full object-contain">
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>
                </div>

                <!-- Logo Dark -->
                <div class="p-4 rounded-2xl bg-white border border-stone-200 text-center space-y-2">
                    <span class="block text-[11px] font-bold text-stone-500">Logo Versi Gelap</span>
                    <div class="h-24 rounded-xl bg-[#1d3e35] border border-[#1d3e35] flex items-center justify-center p-2">
                        @if($identity->getLogoDark())
                            <img src="{{ $identity->getLogoDark() }}" alt="Logo Dark" class="max-h-full max-w-full object-contain">
                        @else
                            <span class="text-xs text-[#e2f0ea]/50 italic">Belum Diisi</span>
                        @endif
                    </div>
                </div>

                <!-- Favicon -->
                <div class="p-4 rounded-2xl bg-white border border-stone-200 text-center space-y-2">
                    <span class="block text-[11px] font-bold text-stone-500">Favicon Browser</span>
                    <div class="h-24 rounded-xl bg-stone-100/70 border border-stone-200 flex items-center justify-center p-2">
                        @if($identity->getFavicon())
                            <img src="{{ $identity->getFavicon() }}" alt="Favicon" class="w-10 h-10 object-contain">
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>
                </div>

                <!-- Hero Banner -->
                <div class="p-4 rounded-2xl bg-white border border-stone-200 text-center space-y-2">
                    <span class="block text-[11px] font-bold text-stone-500">Hero Banner</span>
                    <div class="h-24 rounded-xl bg-stone-100/70 border border-stone-200 flex items-center justify-center p-1 overflow-hidden">
                        @if($identity->getHeroBanner())
                            <img src="{{ $identity->getHeroBanner() }}" alt="Hero Banner" class="h-full w-full object-cover rounded-lg">
                        @else
                            <span class="text-xs text-stone-400 italic">Belum Diisi</span>
                        @endif
                    </div>
                </div>
            </div>
        </x-admin.card>

    </div>
</x-layouts.admin>
