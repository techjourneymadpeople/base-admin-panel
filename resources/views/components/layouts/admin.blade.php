@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f4f8f6]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - ' . config('app.name', 'Admin Panel') : config('app.name', 'Admin Panel') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Critical Alpine Cloak Style (Guarantees zero modal flashing before JS initializes) -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-[#f4f8f6] text-[#1d3e35] selection:bg-[#31725e] selection:text-white">
    <div class="min-h-full flex flex-col" x-data>
        <!-- Sidebar Navigation -->
        <x-admin.sidebar />

        <!-- Main Wrapper (Dynamic left padding based on sidebar collapse) -->
        <div 
            class="flex-1 flex flex-col transition-all duration-300 ease-in-out"
            :class="$store.sidebar.collapsed ? 'lg:pl-20' : 'lg:pl-64'"
        >
            <!-- Top Header Navbar -->
            <x-admin.header />

            <!-- Page Main Content Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                {{ $slot }}
            </main>

            <!-- Admin Footer -->
            <footer class="mt-auto py-4 px-6 border-t border-[#99cab7]/30 bg-white/50 text-xs text-stone-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>&copy; {{ date('Y') }} <span class="font-bold text-[#1d3e35]">{{ config('app.name', 'Admin Panel') }}</span>. All rights reserved.</p>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1 text-[#31725e]">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        Status Sistem: Normal & Aman
                    </span>
                    <span>&bull;</span>
                    <span class="text-[#784732]">v1.0 Viho Edition</span>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
