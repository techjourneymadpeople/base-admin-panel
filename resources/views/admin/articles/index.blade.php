<x-layouts.admin title="Kelola Artikel SEO">
    <x-admin.breadcrumb 
        title="Daftar Artikel" 
        :items="[
            'Article SEO' => '',
            'Article' => ''
        ]" 
    />

    <div class="space-y-6" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        articleTitleToDelete: '',
        filterCategory: '',
        filterStatus: '',
        openDelete(data) {
            this.deleteUrl = data.url;
            this.articleTitleToDelete = data.name;
            this.deleteModalOpen = true;
        },
        applyFilters() {
            const table = window.$('#articles-table').DataTable();
            table.ajax.url('{{ route('admin.articles.index') }}?category_id=' + this.filterCategory + '&status=' + this.filterStatus).load();
        }
    }" @open-delete-modal.window="openDelete($event.detail)">

        <!-- Top Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Stat 1: Total Artikel -->
            <x-admin.stat-card
                title="Total Artikel"
                :value="$totalArticles"
                icon="file-text"
                description="Semua artikel dalam database"
            />

            <!-- Stat 2: Artikel Terbit -->
            <x-admin.stat-card
                title="Artikel Terbit"
                :value="$publishedArticles"
                icon="globe"
                color="emerald"
                description="Terindeks di Sitemap.xml"
            />

            <!-- Stat 3: Draft Artikel -->
            <x-admin.stat-card
                title="Draft Artikel"
                :value="$draftArticles"
                icon="edit-2"
                color="amber"
                description="Belum dipublikasikan"
            />

            <!-- Stat 4: Sitemap Status Card -->
            <div class="rounded-3xl p-5 bg-gradient-to-tr from-[#1d3e35] via-[#234b3f] to-[#31725e] text-white shadow-xl flex flex-col justify-between border border-[#99cab7]/30">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-[#c5e1d5] uppercase tracking-wider">Sitemap XML</span>
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-[#cca06e]">
                        <i data-lucide="network" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="my-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-sm font-extrabold text-white">Auto Synchronized</span>
                    </div>
                    <p class="text-[11px] text-[#c5e1d5]/80 mt-0.5">Otomatis update setiap buat artikel</p>
                </div>
                <div class="pt-2 border-t border-white/10 flex items-center justify-between">
                    <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-[11px] font-bold text-[#cca06e] hover:underline flex items-center gap-1">
                        <span>Lihat sitemap.xml</span>
                        <i data-lucide="external-link" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <x-admin.card 
            title="Daftar Artikel SEO" 
            subtitle="Kelola artikel, konten SEO friendly, integrasi thumbnail Media Library, dan monitoring sitemap."
        >
            <x-slot:actions>
                <div class="flex items-center gap-2 flex-wrap">
                    <!-- Regenerate Sitemap button -->
                    <form action="{{ route('admin.articles.regenerate-sitemap') }}" method="POST" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            class="px-3.5 py-2 rounded-2xl bg-white border border-[#99cab7]/60 hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-1.5 transition-colors shadow-2xs cursor-pointer"
                            title="Perbarui manual file public/sitemap.xml"
                        >
                            <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-[#31725e]"></i>
                            <span>Sync Sitemap</span>
                        </button>
                    </form>

                    @can('create-articles')
                        <a 
                            href="{{ route('admin.articles.create') }}" 
                            class="px-4 py-2 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#1d3e35]/15 transition-all"
                        >
                            <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                            <span>Tulis Artikel Baru</span>
                        </a>
                    @endcan
                </div>
            </x-slot:actions>

            <!-- Filter Controls -->
            <div class="p-4 mb-4 rounded-2xl bg-[#f2f8f5]/60 border border-[#99cab7]/30 flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4 text-[#31725e]"></i>
                    <span class="text-xs font-bold text-[#1d3e35] uppercase tracking-wider">Filter Data:</span>
                </div>

                <!-- Category Filter -->
                <div class="w-48">
                    <select 
                        id="filter_category_select"
                        x-model="filterCategory" 
                        @change="applyFilters()"
                        class="w-full"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="w-40">
                    <select 
                        id="filter_status_select"
                        x-model="filterStatus" 
                        @change="applyFilters()"
                        class="w-full"
                    >
                        <option value="">Semua Status</option>
                        <option value="published">Terbit (Published)</option>
                        <option value="draft">Draft</option>
                        <option value="archived">Arsip</option>
                    </select>
                </div>
            </div>

            <!-- Yajra DataTable Component -->
            <x-admin.data-table
                id="articles-table"
                :ajaxUrl="route('admin.articles.index')"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '4%'],
                    ['data' => 'article_info', 'name' => 'title', 'title' => 'Artikel & Kategori', 'width' => '36%'],
                    ['data' => 'author_info', 'name' => 'author.name', 'title' => 'Penulis', 'width' => '14%'],
                    ['data' => 'status_badge', 'name' => 'status', 'title' => 'Status', 'width' => '10%'],
                    ['data' => 'published_date', 'name' => 'published_at', 'title' => 'Tanggal Terbit', 'width' => '14%'],
                    ['data' => 'views_info', 'name' => 'views_count', 'title' => 'Views', 'width' => '8%'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '14%'],
                ]"
            />
        </x-admin.card>

        <!-- Modal Konfirmasi Hapus Artikel -->
        <template x-teleport="body">
            <div 
                x-show="deleteModalOpen" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/50 backdrop-blur-xs flex items-center justify-center p-4"
            >
                <div 
                    @click.outside="deleteModalOpen = false"
                    class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-[#99cab7]/30"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-stone-900">Hapus Artikel</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>

                    <p class="text-xs text-stone-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus artikel <strong class="text-red-600" x-text="articleTitleToDelete"></strong>? URL artikel akan otomatis dihapus dari sitemap.xml.
                    </p>

                    <form :action="deleteUrl" method="POST" class="flex items-center justify-end gap-3 pt-3 border-t border-stone-100">
                        @csrf
                        @method('DELETE')

                        <button 
                            type="button" 
                            @click="deleteModalOpen = false"
                            class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs transition-colors"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit" 
                            class="px-4 py-2.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-red-600/20 transition-all"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                            <span>Hapus Artikel</span>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.TomSelect) {
                new window.TomSelect('#filter_category_select', {
                    create: false,
                    allowEmptyOption: true,
                    placeholder: 'Semua Kategori',
                });
                new window.TomSelect('#filter_status_select', {
                    create: false,
                    allowEmptyOption: true,
                    placeholder: 'Semua Status',
                });
            }
        });
    </script>
    @endpush
</x-layouts.admin>
