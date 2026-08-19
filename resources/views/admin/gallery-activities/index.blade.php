<x-layouts.admin title="Gallery Activity (Foto Kegiatan)">
    <x-admin.breadcrumb 
        title="Gallery Activity" 
        :items="[
            'Article SEO' => '',
            'Gallery Activity' => ''
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

    <div class="space-y-6">
        <!-- Top Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- 1. Total Galeri -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Total Kegiatan</span>
                    <h3 class="text-2xl font-black text-[#1d3e35] mt-1">{{ number_format($stats['total']) }}</h3>
                    <span class="text-[10px] text-stone-400">Dokumentasi kegiatan</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                    <i data-lucide="camera" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 2. Galeri Diterbitkan -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Diterbitkan</span>
                    <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($stats['published']) }}</h3>
                    <span class="text-[10px] text-emerald-600 font-semibold">Tampil di publik</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 3. Draft -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Draft</span>
                    <h3 class="text-2xl font-black text-amber-600 mt-1">{{ number_format($stats['draft']) }}</h3>
                    <span class="text-[10px] text-amber-600 font-semibold">Belum dipublikasikan</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="file-edit" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 4. Total Foto Kegiatan -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Total Foto</span>
                    <h3 class="text-2xl font-black text-sky-700 mt-1">{{ number_format($stats['total_photos']) }}</h3>
                    <span class="text-[10px] text-sky-600 font-semibold">Dari Media Library</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <i data-lucide="images" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Main Card: DataTable Gallery Activities -->
        <x-admin.card 
            title="Daftar Foto Kegiatan (Gallery Activity)" 
            subtitle="Kelola galeri dokumentasi foto-foto kegiatan perusahaan dengan integrasi media library & SEO."
        >
            <x-slot:actions>
                <div class="flex items-center gap-2.5">
                    <a 
                        href="{{ route('admin.gallery-activities.create') }}" 
                        class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Tambah Kegiatan Baru</span>
                    </a>
                </div>
            </x-slot:actions>

            <!-- Filters Bar -->
            <div class="p-4 rounded-2xl bg-[#f2f8f5]/50 border border-[#99cab7]/30 mb-6 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Status Filter -->
                    <div class="w-48">
                        <select 
                            id="filter_status" 
                            class="w-full rounded-xl p-2 text-xs border border-stone-200 bg-white font-semibold text-stone-700 focus:border-[#31725e] outline-none"
                        >
                            <option value="">Semua Status</option>
                            <option value="published">Diterbitkan (Published)</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Arsip</option>
                        </select>
                    </div>
                </div>

                <button 
                    type="button" 
                    id="btn_reset_filters" 
                    class="text-xs font-bold text-stone-500 hover:text-[#1d3e35] transition-colors"
                >
                    Reset Filter
                </button>
            </div>

            <!-- DataTable Container -->
            <div class="overflow-x-auto w-full">
                <table id="galleriesTable" class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-stone-200 text-[#295c4d] font-bold uppercase tracking-wider text-[11px] bg-[#f2f8f5]/30">
                            <th class="py-3 px-3 w-10">No</th>
                            <th class="py-3 px-4">Info Kegiatan</th>
                            <th class="py-3 px-3 text-center">Jumlah Foto</th>
                            <th class="py-3 px-3">Penulis</th>
                            <th class="py-3 px-3 text-center">Status</th>
                            <th class="py-3 px-3">Waktu Terbit</th>
                            <th class="py-3 px-3 text-center">Views</th>
                            <th class="py-3 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 text-stone-700">
                        <!-- Loaded via AJAX DataTables -->
                    </tbody>
                </table>
            </div>
        </x-admin.card>
    </div>

    @push('scripts')
    <script>
        (function() {
            function initGalleryTable() {
                if (typeof window.$ === 'undefined' || typeof window.$.fn.DataTable === 'undefined') {
                    setTimeout(initGalleryTable, 50);
                    return;
                }

                if ($.fn.DataTable.isDataTable('#galleryActivitiesTable')) {
                    return;
                }

                const table = $('#galleryActivitiesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.gallery-activities.index') }}",
                        data: function(d) {
                            d.status = $('#filter_status').val();
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-bold text-stone-400' },
                        { data: 'gallery_info', name: 'title', orderable: true, searchable: true },
                        { data: 'photos_badge', name: 'photos_count', orderable: false, searchable: false, className: 'text-center' },
                        { data: 'author_info', name: 'author.name', orderable: false, searchable: false },
                        { data: 'status_badge', name: 'status', orderable: true, searchable: false, className: 'text-center' },
                        { data: 'published_date', name: 'published_at', orderable: true, searchable: false },
                        { data: 'views_info', name: 'views_count', orderable: true, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
                    ],
                    order: [[5, 'desc']],
                    pageLength: 10,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari kegiatan...",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ kegiatan",
                        infoEmpty: "Belum ada kegiatan",
                        zeroRecords: "Tidak ada kegiatan yang cocok",
                        paginate: {
                            first: "«",
                            last: "»",
                            next: "›",
                            previous: "‹"
                        }
                    },
                    drawCallback: function() {
                        if (window.refreshIcons) window.refreshIcons();
                    }
                });

                let tsStatus = null;

                if (window.TomSelect) {
                    tsStatus = new window.TomSelect('#filter_status', {
                        create: false,
                        allowEmptyOption: true,
                        placeholder: 'Semua Status',
                    });
                }

                $('#filter_status').on('change', () => table.draw());

                $('#btn_reset_filters').on('click', () => {
                    if (tsStatus) tsStatus.clear(); else $('#filter_status').val('');
                    table.draw();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initGalleryTable);
            } else {
                initGalleryTable();
            }
        })();

        window.confirmDeleteGallery = function(deleteUrl, title) {
            if (confirm(`Apakah Anda yakin ingin menghapus dokumentasi kegiatan "${title}"? Tindakan ini tidak dapat dibatalkan.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        };
    </script>
    @endpush
</x-layouts.admin>
