<x-layouts.admin title="Kelola Saran & Masukan">
    <x-admin.breadcrumb 
        title="Saran & Masukan" 
        :items="[
            'Konten' => '',
            'Saran & Masukan' => ''
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
            <!-- 1. Total Masukan -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Total Masukan</span>
                    <h3 class="text-2xl font-black text-[#1d3e35] mt-1">{{ number_format($stats['total']) }}</h3>
                    <span class="text-[10px] text-stone-400">Pesan diterima</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                    <i data-lucide="inbox" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 2. Belum Dibaca -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Belum Dibaca</span>
                    <h3 class="text-2xl font-black text-rose-600 mt-1">{{ number_format($stats['unread']) }}</h3>
                    <span class="text-[10px] text-rose-600 font-semibold">Perlu ditinjau</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="mail-warning" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 3. Dalam Proses -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Sedang Diproses</span>
                    <h3 class="text-2xl font-black text-amber-600 mt-1">{{ number_format($stats['in_progress']) }}</h3>
                    <span class="text-[10px] text-amber-600 font-semibold">Dalam tindak lanjut</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- 4. Selesai Ditindaklanjuti -->
            <div class="p-5 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Selesai / Terjawab</span>
                    <h3 class="text-2xl font-black text-emerald-700 mt-1">{{ number_format($stats['resolved']) }}</h3>
                    <span class="text-[10px] text-emerald-600 font-semibold">Telah ditindaklanjuti</span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Main Card: DataTable Feedbacks -->
        <x-admin.card 
            title="Daftar Saran, Kritik & Masukan Pengguna" 
            subtitle="Pantau aspirasi, pertanyaan, serta keluhan layanan dari pengguna dan klien untuk evaluasi layanan."
        >
            <x-slot:actions>
                <div class="flex items-center gap-2.5">
                    <a 
                        href="{{ route('admin.feedbacks.create') }}" 
                        class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Catat Masukan Baru</span>
                    </a>
                </div>
            </x-slot:actions>

            <!-- Filters Bar -->
            <div class="p-4 rounded-2xl bg-[#f2f8f5]/50 border border-[#99cab7]/30 mb-6 flex items-center justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-3 flex-wrap">
                    <!-- Jenis Masukan Filter -->
                    <div class="w-44">
                        <select 
                            id="filter_type" 
                            class="w-full rounded-xl p-2 text-xs border border-stone-200 bg-white font-semibold text-stone-700 focus:border-[#31725e] outline-none"
                        >
                            <option value="">Semua Jenis</option>
                            @foreach($types as $key => $meta)
                                <option value="{{ $key }}">{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Tindak Lanjut Filter -->
                    <div class="w-44">
                        <select 
                            id="filter_status" 
                            class="w-full rounded-xl p-2 text-xs border border-stone-200 bg-white font-semibold text-stone-700 focus:border-[#31725e] outline-none"
                        >
                            <option value="">Semua Status</option>
                            @foreach($statuses as $key => $meta)
                                <option value="{{ $key }}">{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Prioritas Bintang Filter -->
                    <div class="w-40">
                        <select 
                            id="filter_starred" 
                            class="w-full rounded-xl p-2 text-xs border border-stone-200 bg-white font-semibold text-stone-700 focus:border-[#31725e] outline-none"
                        >
                            <option value="">Semua Prioritas</option>
                            <option value="1">★ Ditandai Prioritas</option>
                            <option value="0">Standar</option>
                        </select>
                    </div>
                </div>

                <button 
                    type="button" 
                    id="btn_reset_filters" 
                    class="text-xs font-bold text-stone-500 hover:text-[#1d3e35] transition-colors cursor-pointer"
                >
                    Reset Filter
                </button>
            </div>

            <!-- DataTable Container -->
            <div class="overflow-x-auto w-full">
                <table id="feedbacksTable" class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-stone-200 text-[#295c4d] font-bold uppercase tracking-wider text-[11px] bg-[#f2f8f5]/30">
                            <th class="py-3 px-2 w-8 text-center">★</th>
                            <th class="py-3 px-3 w-10">No</th>
                            <th class="py-3 px-4">Pengirim</th>
                            <th class="py-3 px-3">Jenis</th>
                            <th class="py-3 px-4">Subjek & Pesan</th>
                            <th class="py-3 px-3">Rating</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3">Tanggal</th>
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
            function initFeedbacksTable() {
                if (typeof window.$ === 'undefined' || typeof window.$.fn.DataTable === 'undefined') {
                    setTimeout(initFeedbacksTable, 50);
                    return;
                }

                if ($.fn.DataTable.isDataTable('#feedbacksTable')) {
                    return;
                }

                const table = $('#feedbacksTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.feedbacks.index') }}",
                        data: function(d) {
                            d.type = $('#filter_type').val();
                            d.status = $('#filter_status').val();
                            d.is_starred = $('#filter_starred').val();
                        }
                    },
                    columns: [
                        { data: 'star', name: 'is_starred', orderable: true, searchable: false, className: 'text-center' },
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center font-bold text-stone-400' },
                        { data: 'sender', name: 'name', orderable: true, searchable: true },
                        { data: 'type_badge', name: 'type', orderable: true, searchable: false },
                        { data: 'subject_message', name: 'message', orderable: false, searchable: true },
                        { data: 'rating_display', name: 'rating', orderable: true, searchable: false, className: 'text-center' },
                        { data: 'status_badge', name: 'status', orderable: true, searchable: false },
                        { data: 'created_date', name: 'created_at', orderable: true, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right' }
                    ],
                    order: [[7, 'desc']],
                    pageLength: 10,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari pengirim, email, atau isi masukan...",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ masukan",
                        infoEmpty: "Belum ada saran/masukan",
                        zeroRecords: "Tidak ada masukan yang cocok",
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

                $('#filter_type, #filter_status, #filter_starred').on('change', () => table.draw());

                $('#btn_reset_filters').on('click', () => {
                    $('#filter_type').val('');
                    $('#filter_status').val('');
                    $('#filter_starred').val('');
                    table.draw();
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initFeedbacksTable);
            } else {
                initFeedbacksTable();
            }
        })();

        window.toggleFeedbackStar = function(toggleUrl, btn) {
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
                if (data.success) {
                    const icon = btn.querySelector('i, svg');
                    if (data.is_starred) {
                        icon.classList.add('text-amber-400', 'fill-amber-400');
                        icon.classList.remove('text-stone-300');
                    } else {
                        icon.classList.remove('text-amber-400', 'fill-amber-400');
                        icon.classList.add('text-stone-300');
                    }
                }
            });
        };

        window.updateFeedbackStatus = function(updateUrl, status, select) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(updateUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const colorMap = {
                        'unread': ['text-rose-700', 'bg-rose-50', 'border-rose-200'],
                        'read': ['text-sky-700', 'bg-sky-50', 'border-sky-200'],
                        'in_progress': ['text-amber-700', 'bg-amber-50', 'border-amber-200'],
                        'resolved': ['text-emerald-700', 'bg-emerald-50', 'border-emerald-200'],
                        'archived': ['text-stone-700', 'bg-stone-100', 'border-stone-200']
                    };
                    select.className = `text-[11px] font-bold rounded-xl px-2 py-1 border outline-none cursor-pointer ${(colorMap[status] || []).join(' ')}`;
                }
            });
        };

        window.confirmDeleteFeedback = function(deleteUrl, name) {
            if (confirm(`Apakah Anda yakin ingin menghapus masukan dari "${name}"?`)) {
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
