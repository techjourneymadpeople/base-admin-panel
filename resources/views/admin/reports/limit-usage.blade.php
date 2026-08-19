<x-layouts.admin title="Laporan Penggunaan Limit & Kuota Sistem">
    <!-- Breadcrumb Header -->
    <x-admin.breadcrumb 
        title="Penggunaan Limit" 
        :items="[
            'Laporan' => '',
            'Penggunaan Limit' => ''
        ]" 
    />

    <!-- Header Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                <i data-lucide="gauge" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Audit & Resource Monitoring</span>
                <h3 class="text-base font-extrabold text-[#1d3e35]">Penggunaan Kuota & Limit Sistem</h3>
            </div>
        </div>

        @can('edit-settings')
            <div class="flex items-center gap-2">
                <a 
                    href="{{ route('admin.settings.edit') }}" 
                    class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all"
                >
                    <i data-lucide="sliders" class="w-4 h-4 text-[#cca06e]"></i>
                    <span>Sesuaikan Kuota</span>
                </a>
            </div>
        @endcan
    </div>

    <!-- Executive KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Storage -->
        <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Media Storage</span>
                <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center">
                    <i data-lucide="hard-drive" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-extrabold text-[#1d3e35]">{{ $reportItems[0]['current'] }}</span>
                    <span class="text-xs text-stone-400 font-bold">/ {{ $reportItems[0]['limit_formatted'] }}</span>
                </div>
                <div class="w-full h-2 rounded-full bg-stone-100 overflow-hidden mt-2">
                    <div 
                        class="h-full rounded-full transition-all duration-500 {{ $reportItems[0]['percentage'] >= 90 ? 'bg-red-500' : ($reportItems[0]['percentage'] >= 70 ? 'bg-amber-500' : 'bg-[#31725e]') }}" 
                        style="width: {{ min(100, $reportItems[0]['percentage']) }}%"
                    ></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-stone-500 font-semibold pt-1">
                    <span>Terpakai: {{ $reportItems[0]['percentage'] }}%</span>
                    <span>Sisa: {{ $reportItems[0]['remaining'] }}</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Pengguna -->
        <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Pengguna</span>
                <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-extrabold text-[#1d3e35]">{{ $reportItems[1]['current'] }}</span>
                    <span class="text-xs text-stone-400 font-bold">/ {{ $reportItems[1]['limit_formatted'] }}</span>
                </div>
                <div class="w-full h-2 rounded-full bg-stone-100 overflow-hidden mt-2">
                    <div 
                        class="h-full rounded-full transition-all duration-500 {{ $reportItems[1]['percentage'] >= 90 ? 'bg-red-500' : ($reportItems[1]['percentage'] >= 70 ? 'bg-amber-500' : 'bg-[#31725e]') }}" 
                        style="width: {{ min(100, $reportItems[1]['percentage']) }}%"
                    ></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-stone-500 font-semibold pt-1">
                    <span>Terpakai: {{ $reportItems[1]['percentage'] }}%</span>
                    <span>Sisa: {{ $reportItems[1]['remaining'] }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Artikel & Galeri -->
        <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Artikel SEO</span>
                <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-extrabold text-[#1d3e35]">{{ $reportItems[2]['current'] }}</span>
                    <span class="text-xs text-stone-400 font-bold">/ {{ $reportItems[2]['limit_formatted'] }}</span>
                </div>
                <div class="w-full h-2 rounded-full bg-stone-100 overflow-hidden mt-2">
                    <div 
                        class="h-full rounded-full transition-all duration-500 {{ $reportItems[2]['percentage'] >= 90 ? 'bg-red-500' : ($reportItems[2]['percentage'] >= 70 ? 'bg-amber-500' : 'bg-[#31725e]') }}" 
                        style="width: {{ min(100, $reportItems[2]['percentage']) }}%"
                    ></div>
                </div>
                <div class="flex justify-between items-center text-[10px] text-stone-500 font-semibold pt-1">
                    <span>Terpakai: {{ $reportItems[2]['percentage'] }}%</span>
                    <span>Sisa: {{ $reportItems[2]['remaining'] }}</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Status Kuota Global -->
        <div class="p-5 rounded-3xl bg-gradient-to-br from-[#1d3e35] via-[#295c4d] to-[#1d3e35] text-white shadow-md relative overflow-hidden">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-bold text-[#e2f0ea]/70 uppercase tracking-wider">Rata-rata Kuota Terpakai</span>
                <div class="w-8 h-8 rounded-xl bg-white/15 text-[#cca06e] flex items-center justify-center">
                    <i data-lucide="activity" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-extrabold text-[#fef3c7]">{{ $avgUsagePercent }}%</span>
                    <span class="text-xs text-[#e2f0ea]/80 font-bold">Kapasitas Sistem</span>
                </div>
                <div class="w-full h-2 rounded-full bg-white/20 overflow-hidden mt-2">
                    <div 
                        class="h-full rounded-full bg-[#cca06e] transition-all duration-500" 
                        style="width: {{ min(100, $avgUsagePercent) }}%"
                    ></div>
                </div>
                <p class="text-[10px] text-[#e2f0ea]/80 font-medium pt-1">
                    @if($avgUsagePercent < 70)
                        🟢 Kapasitas server & database dalam kondisi sehat (Aman).
                    @elseif($avgUsagePercent < 90)
                        🟡 Pemakaian moderat, perhatikan kuota yang mendekati batas.
                    @else
                        🔴 Kuota mendekati batas maksimal, segera pertimbangkan perluasan.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Detailed Resource Limit Audit Table -->
    <x-admin.card 
        title="Tabel Rincian Penggunaan Batas & Kuota" 
        subtitle="Rincian metrik kuota yang dikonfigurasi pada sistem, pemakaian aktual, dan sisa alokasi."
        icon="file-bar-chart-2"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-stone-200 bg-stone-50/60 text-stone-500 font-bold uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Modul / Sumber Daya</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4 text-center">Pemakaian Saat Ini</th>
                        <th class="py-3.5 px-4 text-center">Batas Kuota</th>
                        <th class="py-3.5 px-4 text-center">Sisa Kuota</th>
                        <th class="py-3.5 px-4 w-44">Tingkat Penggunaan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @foreach($reportItems as $item)
                        @php
                            $isUnlimited = $item['limit'] == 0;
                            $pct = $item['percentage'];
                            $badgeClass = $isUnlimited 
                                ? 'bg-blue-50 text-blue-700 border-blue-200' 
                                : ($pct >= 90 
                                    ? 'bg-red-50 text-red-700 border-red-200' 
                                    : ($pct >= 70 
                                        ? 'bg-amber-50 text-amber-700 border-amber-200' 
                                        : 'bg-emerald-50 text-emerald-700 border-emerald-200'));
                            $statusLabel = $isUnlimited 
                                ? 'Tidak Terbatas' 
                                : ($pct >= 100 
                                    ? 'Penuh (100%)' 
                                    : ($pct >= 90 
                                        ? 'Kritis' 
                                        : ($pct >= 70 
                                            ? 'Perhatian' 
                                            : 'Aman (Normal)')));
                        @endphp
                        <tr class="hover:bg-stone-50/70 transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center shrink-0">
                                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-[#1d3e35] text-xs block">{{ $item['name'] }}</span>
                                        <span class="text-[11px] text-stone-400">{{ $item['details'] }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-lg bg-stone-100 text-stone-600 font-semibold text-[10px]">
                                    {{ $item['category'] }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-center font-extrabold text-stone-800">
                                {{ $item['current_formatted'] }}
                            </td>

                            <td class="py-4 px-4 text-center font-bold text-stone-600">
                                {{ $item['limit_formatted'] }}
                            </td>

                            <td class="py-4 px-4 text-center font-extrabold {{ $item['remaining'] === '0' || $item['remaining'] === '0 MB' || $item['remaining'] === '0 Akun' || $item['remaining'] === '0 Artikel' ? 'text-red-600' : 'text-[#31725e]' }}">
                                {{ $item['remaining'] }}
                            </td>

                            <td class="py-4 px-4">
                                @if($isUnlimited)
                                    <span class="text-[11px] text-stone-400 italic">Tanpa Batasan</span>
                                @else
                                    <div class="space-y-1">
                                        <div class="flex justify-between items-center text-[10px] font-bold text-stone-600">
                                            <span>{{ $pct }}%</span>
                                            <span>{{ $item['current'] }}/{{ $item['limit'] }}</span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-stone-100 overflow-hidden">
                                            <div 
                                                class="h-full rounded-full transition-all duration-500 {{ $pct >= 90 ? 'bg-red-500' : ($pct >= 70 ? 'bg-amber-500' : 'bg-[#31725e]') }}" 
                                                style="width: {{ min(100, $pct) }}%"
                                            ></div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-admin.card>

    <!-- Additional Operational Resource Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <x-admin.card 
            title="Saran & Masukan Pengunjung" 
            subtitle="Ringkasan arus pesan masuk melalui form kontak publik."
            icon="message-square"
        >
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 text-center space-y-1">
                    <span class="block text-[10px] font-bold text-stone-400 uppercase">Total Pesan</span>
                    <span class="text-xl font-extrabold text-stone-800">{{ $totalFeedbacks }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200 text-center space-y-1">
                    <span class="block text-[10px] font-bold text-amber-700 uppercase">Ditandai Bintang</span>
                    <span class="text-xl font-extrabold text-amber-800">{{ $starredFeedbacks }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-200 text-center space-y-1">
                    <span class="block text-[10px] font-bold text-blue-700 uppercase">Menunggu Review</span>
                    <span class="text-xl font-extrabold text-blue-800">{{ $pendingFeedbacks }}</span>
                </div>
                <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200 text-center space-y-1">
                    <span class="block text-[10px] font-bold text-emerald-700 uppercase">Telah Diproses</span>
                    <span class="text-xl font-extrabold text-emerald-800">{{ $reviewedFeedbacks }}</span>
                </div>
            </div>
        </x-admin.card>

        <x-admin.card 
            title="Kebijakan & Ketentuan Kuota" 
            subtitle="Informasi operasional terkait penambahan atau perubahan alokasi batas kuota."
            icon="info"
        >
            <div class="space-y-3 text-xs text-stone-600 leading-relaxed">
                <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-[#e2f0ea]/40 border border-[#99cab7]/30">
                    <i data-lucide="check-circle" class="w-4 h-4 text-[#31725e] shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="text-[#1d3e35]">Nilai Kuota 0 = Tanpa Batas:</strong>
                        <p class="text-[11px] text-stone-500 mt-0.5">Jika limit diatur ke nilai 0 pada Web Konfigurasi, sistem akan mengizinkan penambahan data tanpa batasan kuota.</p>
                    </div>
                </div>

                <div class="flex items-start gap-2.5 p-3 rounded-2xl bg-amber-50/50 border border-amber-200/50">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                    <div>
                        <strong class="text-stone-800">Proteksi Penolakan Otomatis:</strong>
                        <p class="text-[11px] text-stone-500 mt-0.5">Saat kuota suatu modul mencapai batas maksimal, tombol pembuatan atau unggahan akan ditolak otomatis oleh sistem demi menjaga stabilitas server.</p>
                    </div>
                </div>
            </div>
        </x-admin.card>
    </div>
</x-layouts.admin>
