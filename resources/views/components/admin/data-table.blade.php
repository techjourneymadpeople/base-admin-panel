@props([
    'id' => 'dataTable',
    'ajaxUrl' => '',
    'columns' => [],
    'order' => [[0, 'desc']],
    'pageLength' => 10,
])

<div class="overflow-x-auto w-full">
    <table id="{{ $id }}" class="w-full text-left border-collapse text-xs">
        <thead>
            <tr class="bg-[#f2f8f5]/80 border-b border-[#99cab7]/40 text-[#295c4d] uppercase tracking-wider font-bold">
                {{ $slot }}
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100 text-stone-700">
            <!-- Populated via Yajra DataTables AJAX -->
        </tbody>
    </table>
</div>

<script>
    (function () {
        function initDT_{{ str_replace('-', '_', $id) }}() {
            const el = document.getElementById('{{ $id }}');
            if (!el) return;

            // Prevent double initialization
            if (el.dataset.dtInitialized === 'true') return;

            if (typeof window.DataTable !== 'undefined') {
                el.dataset.dtInitialized = 'true';
                new window.DataTable(el, {
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: '{{ $ajaxUrl }}',
                        type: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        error: function (xhr, error, code) {
                            console.error('DataTables AJAX error:', error, code, xhr.responseText);
                        }
                    },
                    columns: @json($columns),
                    order: @json($order),
                    pageLength: {{ $pageLength }},
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari data...",
                        lengthMenu: "Tampilkan _MENU_ baris",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Tidak ditemukan data yang cocok",
                        processing: "<div class='inline-flex items-center gap-2 text-xs font-semibold text-[#31725e] py-2'><span class='animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full'></span> Memuat data...</div>",
                        paginate: {
                            first: "«",
                            last: "»",
                            next: "›",
                            previous: "‹"
                        }
                    },
                    drawCallback: function () {
                        if (window.refreshIcons) {
                            window.refreshIcons();
                        }
                    }
                });
            } else if (typeof window.$ !== 'undefined' && typeof window.$.fn.dataTable !== 'undefined') {
                el.dataset.dtInitialized = 'true';
                $('#{{ $id }}').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: '{{ $ajaxUrl }}',
                        type: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        error: function (xhr, error, code) {
                            console.error('DataTables AJAX error:', error, code, xhr.responseText);
                        }
                    },
                    columns: @json($columns),
                    order: @json($order),
                    pageLength: {{ $pageLength }},
                    lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari data...",
                        lengthMenu: "Tampilkan _MENU_ baris",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Tidak ditemukan data yang cocok",
                        processing: "<div class='inline-flex items-center gap-2 text-xs font-semibold text-[#31725e] py-2'><span class='animate-spin inline-block w-4 h-4 border-2 border-current border-t-transparent rounded-full'></span> Memuat data...</div>",
                        paginate: {
                            first: "«",
                            last: "»",
                            next: "›",
                            previous: "‹"
                        }
                    },
                    drawCallback: function () {
                        if (window.refreshIcons) {
                            window.refreshIcons();
                        }
                    }
                });
            } else {
                // Retry after Vite module finishes loading
                setTimeout(initDT_{{ str_replace('-', '_', $id) }}, 50);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDT_{{ str_replace('-', '_', $id) }});
        } else {
            initDT_{{ str_replace('-', '_', $id) }}();
        }
    })();
</script>
