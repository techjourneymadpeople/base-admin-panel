@php
    $bizIdentity = \App\Models\BusinessIdentity::current();
    $webConfig = \App\Models\WebConfiguration::current();
    $registrationEnabled = $webConfig->registration_enabled ?? true;
    $pageTitle = $bizIdentity->getBrandDisplayName() . ' - Home';
    $faviconUrl = $bizIdentity->getFavicon() ?: asset('favicon.ico');
    $ogImageUrl = $bizIdentity->getOgImage();
    $authLogo = $bizIdentity->getLogoLight() ?: $bizIdentity->getLogoDark();
    $brandName = $bizIdentity->getBrandDisplayName();
    $brandInitials = $bizIdentity->getBrandInitials();
    $tagline = $bizIdentity->tagline ?: 'Sistem Manajemen & Portal Administrasi Terintegrasi';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">

    <!-- OpenGraph Meta Tags -->
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $tagline }}">
    @if($ogImageUrl)
        <meta property="og:image" content="{{ $ogImageUrl }}">
    @endif
    <meta property="og:type" content="website">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Critical Alpine Cloak Style -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-[#eef5f1] text-[#1d3e35] selection:bg-[#31725e] selection:text-white relative overflow-x-hidden flex flex-col justify-between">
    <!-- Ambient Glowing Orbs Background -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 -left-32 w-[520px] h-[520px] rounded-full bg-gradient-to-br from-emerald-400/20 via-[#428e75]/15 to-transparent blur-3xl transform -rotate-12 animate-pulse" style="animation-duration: 9s;"></div>
        <div class="absolute -bottom-32 -right-32 w-[580px] h-[580px] rounded-full bg-gradient-to-tl from-[#cca06e]/25 via-[#b17042]/15 to-transparent blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[450px] rounded-full bg-gradient-to-r from-[#99cab7]/15 via-[#ead7be]/20 to-[#68ad94]/15 blur-[110px]"></div>
        <svg class="absolute inset-0 w-full h-full opacity-[0.035]" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="nature-grid" width="48" height="48" patternUnits="userSpaceOnUse">
                    <path d="M 48 0 L 0 0 0 48" fill="none" stroke="#1d3e35" stroke-width="1" />
                    <circle cx="24" cy="24" r="1.5" fill="#31725e" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#nature-grid)" />
        </svg>
    </div>

    <!-- Header Navigation -->
    <header class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 flex items-center justify-between">
        <a href="{{ url('/') }}" class="group flex items-center gap-3 transition-transform duration-300 hover:scale-[1.02]">
            @if($authLogo)
                <img src="{{ $authLogo }}" alt="{{ $brandName }}" class="h-10 sm:h-11 max-h-11 max-w-[170px] object-contain">
            @else
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#31725e] to-[#cca06e] p-0.5 shadow-lg shadow-[#1d3e35]/15 flex items-center justify-center">
                    <div class="w-full h-full bg-[#1d3e35]/90 rounded-[14px] flex items-center justify-center backdrop-blur-sm text-xs sm:text-sm font-black text-[#c5e1d5]">
                        {{ $brandInitials }}
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-base sm:text-lg font-bold tracking-tight text-[#1d3e35] group-hover:text-[#31725e] transition-colors">
                        {{ $brandName }}
                    </span>
                    <span class="text-[10px] sm:text-[11px] font-medium text-[#784732] -mt-1 tracking-wider uppercase">
                        Admin Workspace
                    </span>
                </div>
            @endif
        </a>

        <!-- Top Right Action / Badge -->
        <div class="flex items-center gap-3">
            @auth
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/70 border border-[#c5e1d5]/70 shadow-sm backdrop-blur-md text-xs font-semibold text-[#1d3e35]">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-[#1d3e35] to-[#31725e] shadow-md shadow-[#1d3e35]/15 hover:shadow-lg hover:from-[#163029] hover:to-[#275b4b] transition-all transform active:scale-95">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span>Dashboard</span>
                </a>
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-[#1d3e35] bg-white/80 border border-[#99cab7]/40 shadow-sm hover:bg-white hover:border-[#31725e]/40 transition-all">
                        <i data-lucide="log-in" class="w-3.5 h-3.5 text-[#31725e]"></i>
                        <span>Masuk</span>
                    </a>
                @endif
                @if (Route::has('register') && $registrationEnabled)
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-[#31725e] to-[#428e75] shadow-md shadow-[#31725e]/20 hover:from-[#295c4d] hover:to-[#367561] transition-all">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                        <span>Daftar</span>
                    </a>
                @endif
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-center py-10 sm:py-16 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold text-[#295c4d] bg-white/75 border border-[#c5e1d5] shadow-sm backdrop-blur-md mb-6">
            <span class="w-2 h-2 rounded-full bg-[#31725e] animate-ping"></span>
            <span class="w-2 h-2 rounded-full bg-[#31725e] -ml-4"></span>
            <span>Selamat Datang di Portal Resmi</span>
        </div>

        <!-- Headline -->
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-[#1d3e35] tracking-tight leading-tight sm:leading-tight max-w-2xl mb-4">
            Kelola Ekosistem & Layanan <span class="bg-gradient-to-r from-[#31725e] via-[#428e75] to-[#b17042] bg-clip-text text-transparent">{{ $brandName }}</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-sm sm:text-base md:text-lg text-[#623c2c]/85 max-w-xl mb-8 sm:mb-10 font-normal leading-relaxed">
            {{ $tagline }}
        </p>

        <!-- Dynamic Action Card based on Auth State -->
        <div class="w-full max-w-md bg-white/80 border border-white/90 shadow-xl shadow-[#1d3e35]/8 rounded-3xl p-6 sm:p-8 backdrop-blur-xl relative overflow-hidden">
            <!-- Decorative corner accent -->
            <div class="absolute -top-12 -right-12 w-28 h-28 rounded-full bg-gradient-to-br from-[#c5e1d5]/40 to-transparent pointer-events-none"></div>

            @auth
                <!-- Authenticated User Section -->
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#e2f0ea] border border-[#99cab7]/50 flex items-center justify-center text-[#1d3e35] shadow-inner">
                        <i data-lucide="user-check" class="w-7 h-7 text-[#31725e]"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-[#1d3e35]">
                            Halo, {{ Auth::user()->name }}!
                        </h2>
                        <p class="text-xs text-[#784732] mt-0.5">
                            Akun Anda saat ini sedang aktif dalam sesi login.
                        </p>
                    </div>

                    <div class="w-full pt-2 flex flex-col gap-2.5">
                        <a href="{{ route('admin.dashboard') }}" class="w-full inline-flex items-center justify-center gap-2.5 py-3.5 px-6 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-[#1d3e35] via-[#31725e] to-[#295c4d] shadow-lg shadow-[#1d3e35]/20 hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                            <span>Buka Dashboard Admin</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>

                        <div class="flex items-center justify-between pt-2 px-1 text-xs text-[#623c2c]/80">
                            <a href="{{ route('admin.profile.edit') }}" class="hover:text-[#1d3e35] hover:underline font-medium inline-flex items-center gap-1">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                <span>Profil Saya</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="hover:text-red-700 hover:underline font-medium inline-flex items-center gap-1 text-stone-500">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <!-- Guest Section -->
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#e2f0ea] border border-[#99cab7]/50 flex items-center justify-center text-[#1d3e35] shadow-inner">
                        <i data-lucide="shield-check" class="w-7 h-7 text-[#31725e]"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-[#1d3e35]">
                            Akses Pengelola Workspace
                        </h2>
                        <p class="text-xs text-[#784732] mt-0.5">
                            @if($registrationEnabled)
                                Silakan masuk ke akun Anda atau daftarkan akun baru untuk mulai mengelola.
                            @else
                                Silakan masuk ke akun Anda untuk mulai mengelola.
                            @endif
                        </p>
                    </div>

                    <div class="w-full pt-2 flex flex-col gap-2.5">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center gap-2.5 py-3.5 px-6 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-[#1d3e35] via-[#31725e] to-[#295c4d] shadow-lg shadow-[#1d3e35]/20 hover:shadow-xl hover:scale-[1.01] active:scale-[0.99] transition-all">
                                <i data-lucide="log-in" class="w-4 h-4"></i>
                                <span>Masuk ke Akun (Login)</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        @endif

                        @if (Route::has('register') && $registrationEnabled)
                            <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center gap-2 py-3 px-6 rounded-2xl text-sm font-semibold text-[#1d3e35] bg-white border border-[#99cab7]/60 hover:bg-[#f2f8f5] hover:border-[#31725e] shadow-sm transition-all">
                                <i data-lucide="user-plus" class="w-4 h-4 text-[#31725e]"></i>
                                <span>Daftar Akun Baru</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endauth
        </div>

        <!-- Quick 3 Pillars / Feature Highlights (Clean & Minimal) -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-2xl mt-12 text-left">
            <div class="p-4 rounded-2xl bg-white/50 border border-[#c5e1d5]/40 backdrop-blur-sm">
                <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] flex items-center justify-center text-[#31725e] mb-2.5">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                </div>
                <h3 class="text-xs font-bold text-[#1d3e35]">Aman & Terproteksi</h3>
                <p class="text-[11px] text-[#623c2c]/75 mt-0.5">Sistem autentikasi berlapis dan hak akses role-based teruji.</p>
            </div>

            <div class="p-4 rounded-2xl bg-white/50 border border-[#c5e1d5]/40 backdrop-blur-sm">
                <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] flex items-center justify-center text-[#31725e] mb-2.5">
                    <i data-lucide="sliders" class="w-4 h-4"></i>
                </div>
                <h3 class="text-xs font-bold text-[#1d3e35]">Kontrol Terpusat</h3>
                <p class="text-[11px] text-[#623c2c]/75 mt-0.5">Manajemen artikel, konfigurasi web, media & konten dinamis.</p>
            </div>

            <div class="p-4 rounded-2xl bg-white/50 border border-[#c5e1d5]/40 backdrop-blur-sm">
                <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] flex items-center justify-center text-[#31725e] mb-2.5">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
                <h3 class="text-xs font-bold text-[#1d3e35]">Cepat & Responsif</h3>
                <p class="text-[11px] text-[#623c2c]/75 mt-0.5">Dirancang dengan arsitektur modern dan performa optimal.</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-xs text-[#623c2c]/70 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-[#c5e1d5]/40">
        <p>&copy; {{ date('Y') }} <span class="font-bold text-[#1d3e35]">{{ $brandName }}</span>. Seluruh hak cipta dilindungi.</p>
        <div class="inline-flex items-center gap-2 text-[#295c4d]">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
            <span>Portal Sistem Aktif & Terlindungi</span>
        </div>
    </footer>
</body>
</html>
