<x-layouts.admin title="Kelola FAQ (Pertanyaan Umum)">
    <x-admin.breadcrumb 
        title="FAQ" 
        :items="[
            'Konten' => '',
            'Kelola FAQ' => ''
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
            <!-- 1. Total FAQ -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Total FAQ</span>
                    <h3 class="text-2xl font-black text-[#1d3e35] mt-1">{{ number_format($stats['total']) }}</h3>
                    <span class="text-[10px] text-stone-400">Pertanyaan terdaftar</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                    <i data-lucide="help-circle" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 2. FAQ Aktif -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">FAQ Aktif</span>
                    <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($stats['active']) }}</h3>
                    <span class="text-[10px] text-emerald-600 font-semibold">Tampil di website</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 3. FAQ Nonaktif -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Nonaktif</span>
                    <h3 class="text-2xl font-black text-stone-600 mt-1">{{ number_format($stats['inactive']) }}</h3>
                    <span class="text-[10px] text-stone-400">Disembunyikan</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-stone-100 text-stone-500 flex items-center justify-center">
                    <i data-lucide="eye-off" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 4. Total Kategori Topik -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Kategori Topik</span>
                    <h3 class="text-2xl font-black text-sky-700 mt-1">{{ number_format($stats['categories']) }}</h3>
                    <span class="text-[10px] text-sky-600 font-semibold">Pengelompokan</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center">
                    <i data-lucide="folder" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Main Card: DataTable FAQs -->
        <x-admin.card 
            title="Daftar Pertanyaan & Jawaban (FAQ)" 
            subtitle="Kelola konten pertanyaan umum (Frequently Asked Questions) untuk membantu pengunjung website."
        >
            <x-slot:actions>
                <div class="flex items-center gap-2.5">
                    <a 
                        href="{{ route('admin.faqs.create') }}" 
                        class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Tambah FAQ Baru</span>
                    </a>
                </div>
            </x-slot:actions>

            <!-- Filters Bar -->
            <div class="p-4 rounded-2xl bg-[#f2f8f5]/50 border border-[#99cab7]/30 mb-6 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Kategori Filter -->
                    <div class="w-48">
                        <select 
                            id="filter_category" 
                            class="w-full rounded-xl p-2 text-xs border border-stone-200 bg-white font-semibold text-stone-700 focus:border-[#31725e] outline-none"
                        >
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="w-40">
                        <select 
                            id="filter_status" 
                            class="w-full rounded-xl p-2 text-xs border border-stone-200 bg-white font-semibold text-stone-700 focus:border-[#31725e] outline-none"
                        >
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
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
                <table id="faqsTable" class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-stone-200 text-[#295c4d] font-bold uppercase tracking-wider text-[11px] bg-[#f2f8f5]/30">
                            <th class="py-3 px-3 w-10">No</th>
                            <th class="py-3 px-4">Pertanyaan & Jawaban</th>
                            <th class="py-3 px-3">Kategori</th>
                            <th class="py-3 px-3 text-center">Urutan</th>
                            <th class="py-3 px-3 text-center">Status Aktif</th>
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
            function initFaqsTable() {
                if (typeof window.$ === 'undefined' || typeof window.$.fn.DataTable === 'undefined') {
                    setTimeout(initFaqsTable, 50);
                    return;
                }

                if ($.fn.DataTable.isDataTable('#faqsTable')) {
                    return;
                }

                const table = $('#faqsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.faqs.index') }}",
                        data: function(d) {
                            d.category = $('#filter_category').val();
                            d.is_active = $('#filter_status').val();
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-bold text-stone-400' },
                        { data: 'faq_info', name: 'question', orderable: true, searchable: true },
                        { data: 'category_badge', name: 'category', orderable: true, searchable: true },
                        { data: 'order_badge', name: 'order', orderable: true, searchable: false, className: 'text-center' },
                        { data: 'status_toggle', name: 'is_active', orderable: true, searchable: false, className: 'text-center' },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
                    ],
                    order: [[3, 'asc']],
                    pageLength: 10,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari pertanyaan FAQ...",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ FAQ",
                        infoEmpty: "Belum ada FAQ",
                        zeroRecords: "Tidak ada FAQ yang cocok",
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

                let tsCategory = null;
                let tsStatus = null;

                if (window.TomSelect) {
                    tsCategory = new window.TomSelect('#filter_category', {
                        create: false,
                        allowEmptyOption: true,
                        placeholder: 'Semua Kategori',
                    });
                    tsStatus = new window.TomSelect('#filter_status', {
                        create: false,
                        allowEmptyOption: true,
                        placeholder: 'Semua Status',
                    });
                }

                $('#filter_category, #filter_status').on('change', () => table.draw());

                $('#btn_reset_filters').on('click', () => {
                    if (tsCategory) tsCategory.clear(); else $('#filter_category').val('');
                    if (tsStatus) tsStatus.clear(); else $('#filter_status').val('');
                    table.draw();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initFaqsTable);
            } else {
                initFaqsTable();
            }
        })();

        window.toggleFaqStatus = function(toggleUrl, checkbox) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(toggleUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    checkbox.checked = !checkbox.checked;
                }
            })
            .catch(() => {
                checkbox.checked = !checkbox.checked;
            });
        };

        window.confirmDeleteFaq = function(deleteUrl, question) {
            if (confirm(`Apakah Anda yakin ingin menghapus FAQ "${question}"?`)) {
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
