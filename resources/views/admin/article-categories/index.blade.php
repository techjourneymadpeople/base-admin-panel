<x-layouts.admin title="Kelola Kategori Artikel">
    <x-admin.breadcrumb 
        title="Kategori Artikel" 
        :items="[
            'Article SEO' => '',
            'Article Category' => ''
        ]" 
    />

    <div class="space-y-6" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        categoryNameToDelete: '',
        openDelete(data) {
            this.deleteUrl = data.url;
            this.categoryNameToDelete = data.name;
            this.deleteModalOpen = true;
        }
    }" @open-delete-modal.window="openDelete($event.detail)">
        <x-admin.card 
            title="Daftar Kategori Artikel" 
            subtitle="Kelola struktur kategori untuk mengelompokkan artikel dan mempermudah navigasi SEO."
        >
            <x-slot:actions>
                @can('create-article-categories')
                    <a 
                        href="{{ route('admin.article-categories.create') }}" 
                        class="px-4 py-2 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#1d3e35]/15 transition-all"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Tambah Kategori Baru</span>
                    </a>
                @endcan
            </x-slot:actions>

            <!-- Yajra DataTable Component -->
            <x-admin.data-table
                id="article-categories-table"
                :ajaxUrl="route('admin.article-categories.index')"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                    ['data' => 'name_info', 'name' => 'name', 'title' => 'Nama Kategori & Slug', 'width' => '30%'],
                    ['data' => 'description_text', 'name' => 'description', 'title' => 'Deskripsi', 'width' => '30%'],
                    ['data' => 'articles_count_badge', 'name' => 'articles_count', 'title' => 'Jumlah Artikel', 'width' => '15%'],
                    ['data' => 'status_badge', 'name' => 'is_active', 'title' => 'Status', 'width' => '10%'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '10%'],
                ]"
            />
        </x-admin.card>

        <!-- Modal Konfirmasi Hapus Kategori -->
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
                            <h3 class="text-base font-bold text-stone-900">Hapus Kategori Artikel</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>

                    <p class="text-xs text-stone-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus kategori <strong class="text-red-600" x-text="categoryNameToDelete"></strong>? Kategori yang masih memiliki artikel terkait tidak dapat dihapus.
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
                            <span>Hapus Kategori</span>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-layouts.admin>
