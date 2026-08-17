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
            <div class="max-w-xl">
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

            <div class="flex items-center gap-3 shrink-0">
                <a 
                    href="#" 
                    class="px-5 py-3 rounded-2xl bg-white text-[#1d3e35] font-bold text-xs shadow-lg hover:bg-[#e2f0ea] hover:shadow-xl transition-all duration-200 inline-flex items-center gap-2 hover:-translate-y-0.5"
                >
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#31725e]"></i>
                    <span>Buat Data Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. KPI Metric Stat Cards (4 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <x-admin.stat-card
            title="Total Pengguna"
            value="1.420"
            icon="users"
            trend="+12.5%"
            :trendUp="true"
            trendLabel="dari bulan lalu"
            color="emerald"
        />

        <x-admin.stat-card
            title="Aktivitas Harian"
            value="3.850"
            icon="activity"
            trend="+8.2%"
            :trendUp="true"
            trendLabel="interaksi aktif"
            color="blue"
        />

        <x-admin.stat-card
            title="Tiket Selesai"
            value="98.4%"
            icon="check-circle-2"
            trend="+2.1%"
            :trendUp="true"
            trendLabel="tingkat kepuasan"
            color="amber"
        />

        <x-admin.stat-card
            title="Keandalan Sistem"
            value="99.9%"
            icon="shield-check"
            trend="Stabil"
            :trendUp="true"
            trendLabel="server uptime"
            color="earth"
        />
    </div>

    <!-- 3. Interactive Analytics Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        <!-- Main Line/Area Activity Chart (8 Cols) -->
        <div class="lg:col-span-8">
            <x-admin.card 
                title="Ringkasan Aktivitas Sistem & Pengguna" 
                subtitle="Monitoring tren aktivitas harian dan traffic pengguna dalam 7 bulan terakhir."
            >
                <x-slot:actions>
                    <div class="flex items-center gap-1.5 p-1 rounded-xl bg-stone-100 text-xs">
                        <button type="button" class="px-2.5 py-1 rounded-lg bg-white font-bold text-[#1d3e35] shadow-xs">Bulanan</button>
                        <button type="button" class="px-2.5 py-1 rounded-lg text-stone-500 hover:text-stone-800">Mingguan</button>
                    </div>
                </x-slot:actions>

                <div class="relative h-72 w-full">
                    <canvas id="activityChart"></canvas>
                </div>
            </x-admin.card>
        </div>

        <!-- Doughnut Role Distribution Chart (4 Cols) -->
        <div class="lg:col-span-4">
            <x-admin.card 
                title="Distribusi Role Pengguna" 
                subtitle="Komposisi akun terdaftar berdasarkan level hak akses."
            >
                <div class="relative h-56 w-full flex items-center justify-center">
                    <canvas id="roleDoughnutChart"></canvas>
                </div>

                <div class="mt-4 pt-3 border-t border-stone-100 grid grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#1d3e35]"></span>
                        <span class="text-stone-600">Super Admin (5%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#31725e]"></span>
                        <span class="text-stone-600">Admin (15%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#cca06e]"></span>
                        <span class="text-stone-600">Support (10%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#428e75]"></span>
                        <span class="text-stone-600">User (70%)</span>
                    </div>
                </div>
            </x-admin.card>
        </div>
    </div>

    <!-- 4. Data Table & Quick Feeds Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Recent Users Table (8 Cols) -->
        <div class="lg:col-span-8">
            <x-admin.card 
                title="Pengguna Terdaftar Terbaru" 
                subtitle="Daftar akun dan verifikasi identitas sistem."
                :padding="false"
            >
                <x-slot:actions>
                    <a href="#" class="text-xs font-bold text-[#31725e] hover:underline flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </x-slot:actions>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-stone-50/80 border-b border-stone-100 text-stone-500 uppercase tracking-wider font-semibold">
                                <th class="py-3 px-6">Pengguna</th>
                                <th class="py-3 px-4">Role</th>
                                <th class="py-3 px-4">Status Email</th>
                                <th class="py-3 px-4">Tanggal Bergabung</th>
                                <th class="py-3 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-stone-700">
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-100 text-[#1d3e35] font-bold text-xs flex items-center justify-center shrink-0">
                                            SA
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#1d3e35]">Super Admin</div>
                                            <div class="text-[11px] text-stone-400">superadmin@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#1d3e35] text-white">
                                        Super Admin
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                        Terverifikasi
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-stone-500">Hari ini</td>
                                <td class="py-3.5 px-6 text-right">
                                    <button type="button" class="p-1 rounded-lg text-stone-400 hover:text-stone-800 hover:bg-stone-100">
                                        <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-amber-100 text-[#784732] font-bold text-xs flex items-center justify-center shrink-0">
                                            OW
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#1d3e35]">Platform Owner</div>
                                            <div class="text-[11px] text-stone-400">owner@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#784732] text-white">
                                        Owner
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                        Terverifikasi
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-stone-500">Hari ini</td>
                                <td class="py-3.5 px-6 text-right">
                                    <button type="button" class="p-1 rounded-lg text-stone-400 hover:text-stone-800 hover:bg-stone-100">
                                        <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl bg-teal-100 text-[#295c4d] font-bold text-xs flex items-center justify-center shrink-0">
                                            AD
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#1d3e35]">Administrator</div>
                                            <div class="text-[11px] text-stone-400">admin@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#295c4d] text-white">
                                        Admin
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                        Terverifikasi
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-stone-500">Hari ini</td>
                                <td class="py-3.5 px-6 text-right">
                                    <button type="button" class="p-1 rounded-lg text-stone-400 hover:text-stone-800 hover:bg-stone-100">
                                        <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
        </div>

        <!-- System Log & Status Feed (4 Cols) -->
        <div class="lg:col-span-4">
            <x-admin.card 
                title="Log Sistem Terbaru" 
                subtitle="Aktivitas keamanan & autentikasi sistem."
            >
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-[#31725e] flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="text-xs flex-1">
                            <p class="font-bold text-[#1d3e35]">Login Berhasil</p>
                            <p class="text-stone-500 text-[11px]">Sesi login baru via Web Browser.</p>
                            <span class="text-[10px] text-stone-400 font-medium">Baru saja</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg bg-amber-100 text-[#b17042] flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="database" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="text-xs flex-1">
                            <p class="font-bold text-[#1d3e35]">Database Synced</p>
                            <p class="text-stone-500 text-[11px]">Seeding 6 role pengguna sukses.</p>
                            <span class="text-[10px] text-stone-400 font-medium">10 menit lalu</span>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg bg-teal-100 text-[#295c4d] flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                        </div>
                        <div class="text-xs flex-1">
                            <p class="font-bold text-[#1d3e35]">Resend SMTP Siap</p>
                            <p class="text-stone-500 text-[11px]">Koneksi email autentikasi terhubung.</p>
                            <span class="text-[10px] text-stone-400 font-medium">1 jam lalu</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-stone-100">
                    <div class="flex items-center justify-between text-xs text-stone-500 mb-1.5">
                        <span class="font-semibold">Penyimpanan Server</span>
                        <span class="font-bold text-[#1d3e35]">24% Digunakan</span>
                    </div>
                    <div class="w-full bg-stone-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-[#31725e] to-[#cca06e] h-full rounded-full w-[24%]"></div>
                    </div>
                </div>
            </x-admin.card>
        </div>
    </div>

    <!-- Chart.js Initializer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Activity Line Chart
            const activityCtx = document.getElementById('activityChart');
            if (activityCtx) {
                const gradient = activityCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(49, 114, 94, 0.35)');
                gradient.addColorStop(1, 'rgba(49, 114, 94, 0.0)');

                new Chart(activityCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                        datasets: [
                            {
                                label: 'Pengunjung Aktif',
                                data: [1200, 1900, 2400, 2100, 2800, 3400, 3850],
                                borderColor: '#31725e',
                                backgroundColor: gradient,
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#31725e',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            },
                            {
                                label: 'Aktivitas Data',
                                data: [800, 1100, 1500, 1400, 1900, 2200, 2600],
                                borderColor: '#cca06e',
                                backgroundColor: 'transparent',
                                borderWidth: 2,
                                borderDash: [4, 4],
                                tension: 0.4,
                                pointRadius: 0,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    font: { family: 'Plus Jakarta Sans', size: 11 }
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1d3e35',
                                titleFont: { family: 'Plus Jakarta Sans', weight: 'bold' },
                                bodyFont: { family: 'Plus Jakarta Sans' },
                                padding: 10,
                                cornerRadius: 10,
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#78716c' }
                            },
                            y: {
                                grid: { color: '#f5f5f4' },
                                ticks: { font: { family: 'Plus Jakarta Sans', size: 10 }, color: '#78716c' }
                            }
                        }
                    }
                });
            }

            // 2. Role Doughnut Chart
            const roleCtx = document.getElementById('roleDoughnutChart');
            if (roleCtx) {
                new Chart(roleCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Super Admin', 'Admin', 'Support', 'User'],
                        datasets: [{
                            data: [5, 15, 10, 70],
                            backgroundColor: ['#1d3e35', '#31725e', '#cca06e', '#428e75'],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.admin>
