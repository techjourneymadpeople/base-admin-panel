<header class="h-16 bg-white/80 dark:bg-stone-900/80 backdrop-blur-xl border-b border-[#99cab7]/30 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-20 shadow-xs">
    <!-- Left Section: Toggle & Search -->
    <div class="flex items-center gap-3">
        <!-- Desktop Sidebar Toggle Button -->
        <button 
            type="button" 
            @click="$store.sidebar.toggle()"
            class="hidden lg:inline-flex p-2 rounded-xl text-[#295c4d] hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors cursor-pointer"
            aria-label="Toggle Sidebar"
        >
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <!-- Mobile Sidebar Toggle Button -->
        <button 
            type="button" 
            @click="$store.sidebar.toggleMobile()"
            class="lg:hidden p-2 rounded-xl text-[#295c4d] hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors cursor-pointer"
            aria-label="Open Mobile Menu"
        >
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <!-- Search Bar (Viho style) -->
        <div class="relative hidden sm:block w-64 md:w-80">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#428e75]">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <input 
                type="text" 
                placeholder="Cari menu, data, atau aksi... (Ctrl+K)"
                class="w-full pl-9 pr-4 py-1.5 text-xs rounded-xl bg-[#f2f8f5]/80 border border-[#99cab7]/40 focus:bg-white focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 text-[#1d3e35] placeholder:text-[#99cab7] transition-all outline-none"
            />
        </div>
    </div>

    <!-- Right Section: Notification, Messages, User Profile -->
    <div class="flex items-center gap-2 sm:gap-3">
        <!-- Notification Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button 
                type="button" 
                @click="open = !open" 
                class="relative p-2 rounded-xl text-[#295c4d] hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors cursor-pointer"
                aria-label="Notifikasi"
            >
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-emerald-500 ring-2 ring-white"></span>
            </button>

            <!-- Dropdown Notification Menu -->
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="absolute right-0 mt-2 w-80 rounded-2xl bg-white shadow-2xl border border-[#99cab7]/30 p-4 z-50"
                x-cloak
            >
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h5 class="text-xs font-bold text-[#1d3e35] uppercase tracking-wider">Notifikasi (3)</h5>
                    <a href="#" class="text-[11px] font-semibold text-[#31725e] hover:underline">Tandai Dibaca</a>
                </div>

                <div class="divide-y divide-stone-100 max-h-64 overflow-y-auto my-2">
                    <div class="py-2.5 flex items-start gap-3 hover:bg-stone-50 rounded-xl px-2 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-[#e2f0ea] text-[#31725e] flex items-center justify-center shrink-0">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 text-xs">
                            <p class="font-semibold text-stone-800">Pengguna Baru Terdaftar</p>
                            <p class="text-[11px] text-stone-500">Rian Pratama baru saja mendaftar.</p>
                            <span class="text-[10px] text-[#cca06e] font-medium">5 menit lalu</span>
                        </div>
                    </div>

                    <div class="py-2.5 flex items-start gap-3 hover:bg-stone-50 rounded-xl px-2 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-[#ead7be] text-[#945838] flex items-center justify-center shrink-0">
                            <i data-lucide="shield" class="w-4 h-4"></i>
                        </div>
                        <div class="flex-1 text-xs">
                            <p class="font-semibold text-stone-800">Verifikasi Email Berhasil</p>
                            <p class="text-[11px] text-stone-500">Email akun Anda telah aktif.</p>
                            <span class="text-[10px] text-[#cca06e] font-medium">1 jam lalu</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2 border-t border-stone-100 text-center">
                    <a href="#" class="text-xs font-bold text-[#31725e] hover:underline block py-1">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button 
                type="button" 
                @click="open = !open" 
                class="flex items-center gap-2.5 p-1.5 rounded-2xl hover:bg-[#e2f0ea]/70 transition-colors cursor-pointer select-none"
            >
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#1d3e35] via-[#295c4d] to-[#cca06e] p-0.5 shadow-sm shrink-0">
                    <div class="w-full h-full bg-[#1d3e35] rounded-[10px] flex items-center justify-center text-white font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>

                <div class="hidden md:flex flex-col text-left">
                    <span class="text-xs font-bold text-[#1d3e35] leading-tight">
                        {{ auth()->user()->name ?? 'Pengguna' }}
                    </span>
                    <span class="text-[10px] text-[#784732] font-semibold">
                        {{ auth()->user()->roles->pluck('name')->first() ?? 'Admin' }}
                    </span>
                </div>

                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-[#295c4d] hidden md:block"></i>
            </button>

            <!-- Dropdown Profile Menu -->
            <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-2xl border border-[#99cab7]/30 p-2 z-50"
                x-cloak
            >
                <div class="px-3 py-2 border-b border-stone-100">
                    <p class="text-xs font-bold text-[#1d3e35] truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="text-[10px] text-stone-500 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                </div>

                <div class="py-1">
                    <a href="#" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-stone-700 hover:bg-[#e2f0ea]/70 hover:text-[#1d3e35] rounded-xl transition-colors">
                        <i data-lucide="user" class="w-4 h-4 text-[#428e75]"></i>
                        Profil Saya
                    </a>

                    <a href="#" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-stone-700 hover:bg-[#e2f0ea]/70 hover:text-[#1d3e35] rounded-xl transition-colors">
                        <i data-lucide="settings" class="w-4 h-4 text-[#428e75]"></i>
                        Pengaturan Akun
                    </a>

                    <a href="#" class="flex items-center gap-2.5 px-3 py-2 text-xs font-medium text-stone-700 hover:bg-[#e2f0ea]/70 hover:text-[#1d3e35] rounded-xl transition-colors">
                        <i data-lucide="shield" class="w-4 h-4 text-[#428e75]"></i>
                        Keamanan & 2FA
                    </a>
                </div>

                <div class="pt-1 border-t border-stone-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-xl transition-colors cursor-pointer">
                            <i data-lucide="log-out" class="w-4 h-4 text-red-500"></i>
                            Keluar dari Sesi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
