<x-layouts.admin title="Kelola Menu Navigasi">
    <x-admin.breadcrumb 
        title="Kelola Menu Navigasi" 
        :items="[
            'Pengguna & Akses' => '',
            'Kelola Menu' => ''
        ]" 
    />

    <div class="space-y-6" x-data="{
        deleteModalOpen: false,
        deleteUrl: '',
        menuTitleToDelete: '',
        openDelete(data) {
            this.deleteUrl = data.url;
            this.menuTitleToDelete = data.name;
            this.deleteModalOpen = true;
        }
    }" @open-delete-modal.window="openDelete($event.detail)">
        <x-admin.card 
            title="Daftar Menu Navigasi Dinamis" 
            subtitle="Atur struktur hierarki menu sidebar, urutan tampilan, dan kontrol hak akses visibilitas menu."
        >
            <x-slot:actions>
                @can('create-menus')
                    <a 
                        href="{{ route('admin.menus.create') }}" 
                        class="px-4 py-2 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#1d3e35]/15 transition-all"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Tambah Menu Baru</span>
                    </a>
                @endcan
            </x-slot:actions>

            <!-- Yajra DataTable Component -->
            <x-admin.data-table
                id="menus-table"
                :ajaxUrl="route('admin.menus.index')"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '4%'],
                    ['data' => 'menu_title', 'name' => 'title', 'title' => 'Judul Menu & Icon', 'width' => '22%'],
                    ['data' => 'type_badge', 'name' => 'type', 'title' => 'Tipe', 'width' => '10%'],
                    ['data' => 'parent_title', 'name' => 'parent.title', 'title' => 'Parent Menu', 'width' => '14%'],
                    ['data' => 'destination', 'name' => 'route', 'title' => 'Tujuan Rute / URL', 'width' => '16%'],
                    ['data' => 'order_num', 'name' => 'order', 'title' => 'Urutan', 'width' => '8%'],
                    ['data' => 'status_badge', 'name' => 'is_active', 'title' => 'Status', 'width' => '10%'],
                    ['data' => 'permissions_count', 'name' => 'permissions_count', 'title' => 'Hak Akses Visibilitas', 'width' => '16%'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '10%'],
                ]"
            />
        </x-admin.card>

        <!-- Modal Konfirmasi Hapus Menu -->
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
                            <h3 class="text-base font-bold text-stone-900">Hapus Menu</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                    </div>

                    <p class="text-xs text-stone-600 leading-relaxed">
                        Apakah Anda yakin ingin menghapus menu <strong class="text-red-600" x-text="menuTitleToDelete"></strong>? Jika menu ini memiliki sub-menu, hierarkinya akan terputus.
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
                            <span>Hapus Menu</span>
                        </button>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-layouts.admin>
