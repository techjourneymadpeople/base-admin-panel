@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' . config('app.name', 'Admin Panel') : config('app.name', 'Admin Panel') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Critical Alpine Cloak Style (Guarantees zero modal/element flashing before JS initializes) -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-[#eef5f1] text-[#1d3e35] selection:bg-[#31725e] selection:text-white relative overflow-x-hidden">
    <!-- Ambient Mountain Green & Earthy Brown Glowing Orbs (Relaxed Atmosphere) -->
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden" aria-hidden="true">
        <!-- Top Left Mountain Mist Green Glow -->
        <div class="absolute -top-40 -left-40 w-[550px] h-[550px] rounded-full bg-gradient-to-br from-emerald-400/25 via-[#428e75]/20 to-transparent blur-3xl transform -rotate-12 animate-pulse" style="animation-duration: 8s;"></div>
        
        <!-- Bottom Right Earth Warm Clay Glow -->
        <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] rounded-full bg-gradient-to-tl from-[#cca06e]/30 via-[#b17042]/20 to-transparent blur-3xl"></div>
        
        <!-- Center Floating Nature Orb -->
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[500px] rounded-full bg-gradient-to-r from-[#99cab7]/20 via-[#ead7be]/25 to-[#68ad94]/20 blur-[100px]"></div>

        <!-- Subtle Topographic & Organic Grid Pattern -->
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

    <!-- Main Content Container -->
    <div class="min-h-full flex flex-col justify-between py-6 px-4 sm:px-6 lg:px-8">
        <!-- Top Header Navigation -->
        <header class="w-full max-w-7xl mx-auto flex items-center justify-between py-2">
            <a href="{{ url('/') }}" class="group flex items-center gap-3 transition-transform duration-300 hover:scale-[1.02]">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#31725e] to-[#cca06e] p-0.5 shadow-lg shadow-[#1d3e35]/15 flex items-center justify-center">
                    <div class="w-full h-full bg-[#1d3e35]/90 rounded-[14px] flex items-center justify-center backdrop-blur-sm">
                        <i data-lucide="shield-check" class="w-5 h-5 text-[#c5e1d5] group-hover:rotate-12 transition-transform duration-300"></i>
                    </div>
                </div>
                <div class="flex flex-col">
                    <span class="text-lg font-bold tracking-tight text-[#1d3e35] group-hover:text-[#31725e] transition-colors">
                        {{ config('app.name', 'Admin Panel') }}
                    </span>
                    <span class="text-[11px] font-medium text-[#784732] -mt-1 tracking-wider uppercase">
                        Admin Workspace
                    </span>
                </div>
            </a>

            <!-- Relaxed Support / Status Badge -->
            <div class="flex items-center gap-2">
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium text-[#295c4d] bg-white/60 border border-[#c5e1d5]/60 shadow-sm backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-[#428e75] animate-ping"></span>
                    <span class="w-2 h-2 rounded-full bg-[#428e75] -ml-3.5"></span>
                    <span>Sistem Aman & Aktif</span>
                </div>
            </div>
        </header>

        <!-- Dynamic Body Slot -->
        <main class="w-full max-w-7xl mx-auto flex-1 flex items-center justify-center my-6">
            {{ $slot }}
        </main>

        <!-- Relaxed Earthy Footer -->
        <footer class="w-full max-w-7xl mx-auto text-center py-4">
            <p class="text-xs text-[#623c2c]/75 flex items-center justify-center gap-1.5">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'Admin Panel') }}</span>
                <span>&bull;</span>
                <span class="inline-flex items-center gap-1 text-[#295c4d]">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    Platform Administrasi Modern & Terpercaya
                </span>
            </p>
        </footer>
    </div>
</body>
</html>
