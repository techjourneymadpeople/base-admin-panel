<x-layouts.admin title="Kelola Tag Artikel">
    <x-admin.breadcrumb 
        title="Tag Artikel" 
        :items="[
            'Article SEO' => '',
            'Article Tag' => ''
        ]" 
    />

    <div class="space-y-6" x-data="{
        createModalOpen: false,
        editModalOpen: false,
        deleteModalOpen: false,
        deleteUrl: '',
        tagNameToDelete: '',
        editTag: { id: '', name: '' },

        openCreate() {
            this.createModalOpen = true;
        },
        openEdit(tag) {
            this.editTag = { ...tag };
            this.editModalOpen = true;
        },
        openDelete(data) {
            this.deleteUrl = data.url;
            this.tagNameToDelete = data.name;
            this.deleteModalOpen = true;
        }
    }" 
    @open-edit-modal.window="openEdit($event.detail)"
    @open-delete-modal.window="openDelete($event.detail)">

        <x-admin.card 
            title="Daftar Tag Artikel" 
            subtitle="Tag digunakan untuk memberi kata kunci label pada artikel guna mempermudah pengelompokkan dan crawling SEO."
        >
            <x-slot:actions>
                @can('create-article-tags')
                    <button 
                        type="button"
                        @click="openCreate()" 
                        class="px-4 py-2 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#1d3e35]/15 transition-all cursor-pointer"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Tambah Tag Baru</span>
                    </button>
                @endcan
            </x-slot:actions>

            <!-- Yajra DataTable Component -->
            <x-admin.data-table
                id="article-tags-table"
                :ajaxUrl="route('admin.article-tags.index')"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                    ['data' => 'name_info', 'name' => 'name', 'title' => 'Nama Tag & Slug', 'width' => '45%'],
                    ['data' => 'articles_count_badge', 'name' => 'articles_count', 'title' => 'Jumlah Artikel Terkait', 'width' => '25%'],
                    ['data' => 'created_formatted', 'name' => 'created_at', 'title' => 'Dibuat Pada', 'width' => '15%'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '10%'],
                ]"
            />
        </x-admin.card>

        <!-- Modal Tambah Tag Baru -->
        <template x-teleport="body">
            <div 
                x-show="createModalOpen" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/50 backdrop-blur-xs flex items-center justify-center p-4"
            >
                <div 
                    @click.outside="createModalOpen = false"
                    class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-[#99cab7]/30"
                >
                    <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center">
                                <i data-lucide="tag" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-stone-900">Tambah Tag Baru</h3>
                                <p class="text-xs text-stone-500">Slug akan otomatis dibuat oleh sistem</p>
                            </div>
                        </div>
                        <button type="button" @click="createModalOpen = false" class="text-stone-400 hover:text-stone-700">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form action="{{ route('admin.article-tags.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <x-form.input
                            name="name"
                            label="Nama Tag"
                            placeholder="Contoh: Laravel, AI, Bisnis 2026..."
                            required
                        />

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-stone-100">
                            <button 
                                type="button" 
                                @click="createModalOpen = false"
                                class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs transition-colors"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-2xl bg-[#31725e] hover:bg-[#295c4d] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#31725e]/20 transition-all"
                            >
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Simpan Tag</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- Modal Edit Tag -->
        <template x-teleport="body">
            <div 
                x-show="editModalOpen" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/50 backdrop-blur-xs flex items-center justify-center p-4"
            >
                <div 
                    @click.outside="editModalOpen = false"
                    class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-[#99cab7]/30"
                >
                    <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-stone-900">Edit Tag</h3>
                                <p class="text-xs text-stone-500">Perbarui nama label tag artikel</p>
                            </div>
                        </div>
                        <button type="button" @click="editModalOpen = false" class="text-stone-400 hover:text-stone-700">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/article-tags') }}/' + editTag.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">Nama Tag</label>
                            <input 
                                type="text" 
                                name="name" 
                                x-model="editTag.name" 
                                required
                                class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/80 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/70 hover:bg-white/90 outline-none"
                            />
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-stone-100">
                            <button 
                                type="button" 
                                @click="editModalOpen = false"
                                class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs transition-colors"
                            >
                                Batal
                            </button>
                            <button 
                                type="submit" 
                                class="px-5 py-2.5 rounded-2xl bg-[#31725e] hover:bg-[#295c4d] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#31725e]/20 transition-all"
                            >
                                <i data-lucide="check" class="w-4 h-4"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <!-- Modal Konfirmasi Hapus Tag -->
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
                            <h3 class="text-base font-bold text-stone-900">Hapus Tag Artikel</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>

                    <p class="text-xs text-stone-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus tag <strong class="text-red-600" x-text="tagNameToDelete"></strong>? Tag akan dilepaskan dari semua artikel yang menggunakannya.
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
                            <span>Hapus Tag</span>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-layouts.admin>
