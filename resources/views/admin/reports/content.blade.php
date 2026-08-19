<x-layouts.admin title="Laporan Produksi Konten & Kinerja Tim">
    <!-- Breadcrumb Header -->
    <x-admin.breadcrumb 
        title="Laporan Content" 
        :items="[
            'Laporan' => '',
            'Laporan Content' => ''
        ]" 
    />

    <!-- Header Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Audit & Team Productivity</span>
                <h3 class="text-base font-extrabold text-[#1d3e35]">Laporan Produksi Konten & Aktivitas Tim</h3>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button 
                type="button" 
                onclick="window.print()" 
                class="px-3.5 py-2 rounded-2xl bg-white border border-[#99cab7]/60 hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-1.5 transition-colors shadow-2xs cursor-pointer"
                title="Cetak atau simpan sebagai PDF"
            >
                <i data-lucide="printer" class="w-3.5 h-3.5 text-[#31725e]"></i>
                <span>Cetak Laporan</span>
            </button>
            <a 
                href="{{ route('admin.articles.index') }}" 
                class="px-4 py-2 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#1d3e35]/15 transition-all"
            >
                <i data-lucide="file-text" class="w-4 h-4 text-[#cca06e]"></i>
                <span>Kelola Artikel</span>
            </a>
        </div>
    </div>

    <!-- 1. Primary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Stat 1: Total Konten Keseluruhan -->
        <x-admin.stat-card
            title="Total Konten Tersimpan"
            :value="number_format($totalAllContent)"
            icon="layers"
            description="Akumulasi seluruh modul konten"
        />

        <!-- Stat 2: Konten Bulan Ini -->
        <x-admin.stat-card
            title="Produksi Bulan Ini"
            :value="number_format($totalThisMonth)"
            icon="calendar"
            color="emerald"
            description="Periode {{ now()->translatedFormat('F Y') }}"
        />

        <!-- Stat 3: Konten Minggu Ini -->
        <x-admin.stat-card
            title="Produksi Minggu Ini"
            :value="number_format($totalThisWeek)"
            icon="trending-up"
            color="amber"
            description="{{ number_format($totalToday) }} konten dibuat hari ini"
        />

        <!-- Stat 4: Total Pembaca / Views -->
        <x-admin.stat-card
            title="Total Pembaca (Views)"
            :value="number_format($totalArticleViews)"
            icon="eye"
            color="stone"
            description="Total tayangan pembaca artikel"
        />
    </div>

    <!-- 2. Breakdown per Modul (Pill Badges) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <div class="min-w-0">
                <span class="block text-[10px] font-bold text-stone-400 uppercase truncate">Artikel SEO</span>
                <span class="text-sm font-extrabold text-stone-800">{{ number_format($totalArticles) }}</span>
            </div>
        </div>

        <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i data-lucide="images" class="w-4 h-4"></i>
            </div>
            <div class="min-w-0">
                <span class="block text-[10px] font-bold text-stone-400 uppercase truncate">Galeri Foto</span>
                <span class="text-sm font-extrabold text-stone-800">{{ number_format($totalGalleries) }}</span>
            </div>
        </div>

        <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                <i data-lucide="help-circle" class="w-4 h-4"></i>
            </div>
            <div class="min-w-0">
                <span class="block text-[10px] font-bold text-stone-400 uppercase truncate">FAQ Tanya Jawab</span>
                <span class="text-sm font-extrabold text-stone-800">{{ number_format($totalFaqs) }}</span>
            </div>
        </div>

        <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <i data-lucide="handshake" class="w-4 h-4"></i>
            </div>
            <div class="min-w-0">
                <span class="block text-[10px] font-bold text-stone-400 uppercase truncate">Brand Mitra</span>
                <span class="text-sm font-extrabold text-stone-800">{{ number_format($totalPartners) }}</span>
            </div>
        </div>

        <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <i data-lucide="quote" class="w-4 h-4"></i>
            </div>
            <div class="min-w-0">
                <span class="block text-[10px] font-bold text-stone-400 uppercase truncate">Testimoni</span>
                <span class="text-sm font-extrabold text-stone-800">{{ number_format($totalTestimonials) }}</span>
            </div>
        </div>

        <div class="p-3.5 rounded-2xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-stone-100 text-stone-600 flex items-center justify-center shrink-0">
                <i data-lucide="image" class="w-4 h-4"></i>
            </div>
            <div class="min-w-0">
                <span class="block text-[10px] font-bold text-stone-400 uppercase truncate">Media Library</span>
                <span class="text-sm font-extrabold text-stone-800">{{ number_format($totalMedia) }}</span>
            </div>
        </div>
    </div>

    <!-- 3. Charts Grid (Tren Pembuatan Konten & Distribusi Modul) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        <!-- Chart 1: Tren Produksi Bulanan -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-[#99cab7]/30 shadow-2xs">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-sm sm:text-base font-extrabold text-[#1d3e35]">Grafik Tren Produksi Konten (6 Bulan Terakhir)</h3>
                    <p class="text-xs text-stone-500 mt-0.5">Statistik kuantitas konten yang diproduksi oleh tim setiap bulan.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center shrink-0">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                </div>
            </div>

            <div class="h-72 w-full relative">
                <canvas id="contentTrendChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Distribusi Modul Konten -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-[#99cab7]/30 shadow-2xs flex flex-col justify-between">
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h3 class="text-sm sm:text-base font-extrabold text-[#1d3e35]">Proporsi Modul</h3>
                    <p class="text-xs text-stone-500 mt-0.5">Distribusi konten aktif.</p>
                </div>
                <div class="w-8 h-8 rounded-xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center shrink-0">
                    <i data-lucide="pie-chart" class="w-4 h-4"></i>
                </div>
            </div>

            <div class="h-60 w-full relative flex items-center justify-center">
                <canvas id="contentDistributionChart"></canvas>
            </div>

            <div class="pt-4 mt-2 border-t border-stone-100 grid grid-cols-2 gap-2 text-center text-xs">
                <div class="p-2 rounded-xl bg-stone-50 border border-stone-100">
                    <span class="text-stone-400 block text-[10px] font-bold">Terbesar</span>
                    <strong class="text-[#1d3e35] text-xs">Artikel SEO</strong>
                </div>
                <div class="p-2 rounded-xl bg-[#e2f0ea]/40 border border-[#99cab7]/30">
                    <span class="text-[#31725e] block text-[10px] font-bold">Total Entri</span>
                    <strong class="text-[#1d3e35] text-xs">{{ number_format($totalAllContent) }} Konten</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Table 1: Rekap Kinerja & Aktivitas Kontributor Tim (Admin & Editor) -->
    <div class="mb-6">
        <x-admin.card
            title="Kinerja & Aktivitas Tim Kontributor (Admin, Editor, & Staf)"
            subtitle="Ringkasan hasil kerja pembuatan konten, artikel terbit/draft, dan tayangan views dari masing-masing akun staf."
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500 text-[11px] uppercase tracking-wider bg-stone-50/50">
                            <th class="py-3.5 px-4 font-bold">Pengguna / Kontributor</th>
                            <th class="py-3.5 px-4 font-bold">Hak Akses (Role)</th>
                            <th class="py-3.5 px-4 font-bold text-center">Total Artikel</th>
                            <th class="py-3.5 px-4 font-bold text-center">Terbit / Draft</th>
                            <th class="py-3.5 px-4 font-bold text-center">Featured</th>
                            <th class="py-3.5 px-4 font-bold text-center">Bulan Ini</th>
                            <th class="py-3.5 px-4 font-bold text-center">Total Views</th>
                            <th class="py-3.5 px-4 font-bold text-right">Aktivitas Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-stone-700">
                        @forelse($contributors as $c)
                            <tr class="hover:bg-[#f2f8f5]/40 transition-colors">
                                <!-- User info -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        @if($c['avatar'])
                                            <img src="{{ $c['avatar'] }}" alt="{{ $c['name'] }}" class="w-8 h-8 rounded-full object-cover border border-stone-200 shrink-0">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#1d3e35] to-[#31725e] text-white flex items-center justify-center text-xs font-extrabold shrink-0 shadow-2xs">
                                                {{ strtoupper(substr($c['name'], 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <strong class="text-xs font-bold text-stone-900 block truncate">{{ $c['name'] }}</strong>
                                            <span class="text-[10px] text-stone-400 font-mono block truncate">{{ $c['email'] }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Roles -->
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1 flex-wrap">
                                        @foreach($c['roles'] as $role)
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $role === 'Owner' ? 'bg-amber-50 text-amber-800 border-amber-300' : ($role === 'Super Admin' ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : ($role === 'Editor' ? 'bg-blue-50 text-blue-800 border-blue-300' : 'bg-stone-100 text-stone-700 border-stone-200')) }}">
                                                {{ $role }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Total Articles -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-extrabold text-stone-900 text-sm">{{ number_format($c['total_articles']) }}</span>
                                </td>

                                <!-- Published / Draft -->
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex items-center gap-1 text-[11px]">
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">{{ $c['published_articles'] }}</span>
                                        <span class="text-stone-300">/</span>
                                        <span class="px-1.5 py-0.5 rounded bg-stone-100 text-stone-600 font-bold border border-stone-200">{{ $c['draft_articles'] }}</span>
                                    </div>
                                </td>

                                <!-- Featured Articles -->
                                <td class="py-3.5 px-4 text-center">
                                    @if($c['featured_articles'] > 0)
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 font-bold border border-amber-200 text-[10px]">
                                            ★ {{ $c['featured_articles'] }}
                                        </span>
                                    @else
                                        <span class="text-stone-300 text-xs">—</span>
                                    @endif
                                </td>

                                <!-- This Month -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-bold text-xs {{ $c['articles_this_month'] > 0 ? 'text-[#31725e]' : 'text-stone-400' }}">
                                        +{{ number_format($c['articles_this_month']) }}
                                    </span>
                                </td>

                                <!-- Total Views -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="font-bold text-xs text-stone-700 inline-flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3 text-stone-400"></i>
                                        {{ number_format($c['total_views']) }}
                                    </span>
                                </td>

                                <!-- Last Activity -->
                                <td class="py-3.5 px-4 text-right">
                                    @if($c['last_activity'])
                                        <span class="text-xs text-stone-600 font-medium block">{{ $c['last_activity']->diffForHumans() }}</span>
                                        <span class="text-[10px] text-stone-400 font-mono">{{ $c['last_activity']->translatedFormat('d M Y, H:i') }}</span>
                                    @else
                                        <span class="text-stone-400 italic text-xs">Belum ada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-stone-400 italic">
                                    Belum ada data kontributor tim yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>
    </div>

    <!-- 5. Table 2: Log / Stream Riwayat Pembuatan Konten Terbaru -->
    <div>
        <x-admin.card
            title="Riwayat Entri Konten Terbaru"
            subtitle="Daftar konten terbaru yang diinputkan oleh tim di seluruh modul website."
        >
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500 text-[11px] uppercase tracking-wider bg-stone-50/50">
                            <th class="py-3.5 px-4 font-bold">Modul</th>
                            <th class="py-3.5 px-4 font-bold">Judul / Topik Konten</th>
                            <th class="py-3.5 px-4 font-bold">Pembuat / Penulis</th>
                            <th class="py-3.5 px-4 font-bold text-center">Status</th>
                            <th class="py-3.5 px-4 font-bold text-center">Views</th>
                            <th class="py-3.5 px-4 font-bold">Tanggal Dibuat</th>
                            <th class="py-3.5 px-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-stone-700">
                        @forelse($recentActivities as $item)
                            <tr class="hover:bg-[#f2f8f5]/40 transition-colors">
                                <!-- Module -->
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $item['module_badge'] }}">
                                        {{ $item['module'] }}
                                    </span>
                                </td>

                                <!-- Title -->
                                <td class="py-3.5 px-4">
                                    <a href="{{ $item['url'] }}" class="font-bold text-xs text-[#1d3e35] hover:text-[#31725e] line-clamp-1 transition-colors">
                                        {{ $item['title'] }}
                                    </a>
                                </td>

                                <!-- Creator -->
                                <td class="py-3.5 px-4">
                                    <span class="text-xs text-stone-700 font-medium">{{ $item['creator'] }}</span>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $item['status_class'] }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>

                                <!-- Views -->
                                <td class="py-3.5 px-4 text-center">
                                    <span class="text-xs font-semibold text-stone-600">
                                        {{ is_numeric($item['views']) ? number_format($item['views']) : $item['views'] }}
                                    </span>
                                </td>

                                <!-- Created at -->
                                <td class="py-3.5 px-4">
                                    <div class="text-xs text-stone-600 font-medium">{{ $item['created_at']->translatedFormat('d M Y') }}</div>
                                    <div class="text-[10px] text-stone-400">{{ $item['created_at']->format('H:i') }} WIB</div>
                                </td>

                                <!-- Action -->
                                <td class="py-3.5 px-4 text-right">
                                    <a 
                                        href="{{ $item['url'] }}" 
                                        class="p-1.5 rounded-xl text-stone-500 hover:text-[#1d3e35] hover:bg-[#e2f0ea] inline-flex items-center gap-1 transition-colors"
                                        title="Buka / Periksa Konten"
                                    >
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-stone-400 italic">
                                    Belum ada konten yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.card>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Chart) {
                // 1. Monthly Trend Bar Chart
                const trendCtx = document.getElementById('contentTrendChart').getContext('2d');
                new window.Chart(trendCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($months),
                        datasets: [
                            {
                                label: 'Artikel SEO',
                                data: @json($articlesTrend),
                                backgroundColor: '#31725e',
                                borderRadius: 6,
                            },
                            {
                                label: 'Galeri Kegiatan',
                                data: @json($galleriesTrend),
                                backgroundColor: '#99cab7',
                                borderRadius: 6,
                            },
                            {
                                label: 'FAQ',
                                data: @json($faqsTrend),
                                backgroundColor: '#cca06e',
                                borderRadius: 6,
                            },
                            {
                                label: 'Mitra & Testimoni',
                                data: @json($otherTrend),
                                backgroundColor: '#784732',
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    boxHeight: 12,
                                    font: { size: 11, weight: 'bold' }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                padding: 10,
                                cornerRadius: 10,
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0, font: { size: 11 } },
                                grid: { color: '#f3f4f6' }
                            }
                        }
                    }
                });

                // 2. Module Distribution Doughnut Chart
                const distCtx = document.getElementById('contentDistributionChart').getContext('2d');
                new window.Chart(distCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($distributionData['labels']),
                        datasets: [{
                            data: @json($distributionData['data']),
                            backgroundColor: [
                                '#1d3e35',
                                '#31725e',
                                '#cca06e',
                                '#99cab7',
                                '#784732'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 10,
                                    boxHeight: 10,
                                    font: { size: 10 }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.admin>
