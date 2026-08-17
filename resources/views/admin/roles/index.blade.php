<x-layouts.admin title="Kelola Role & Hak Akses">
    <x-admin.breadcrumb 
        title="Kelola Role & Hak Akses" 
        :items="[
            'Pengguna & Akses' => '',
            'Kelola Role' => ''
        ]" 
    />

    <div class="space-y-6">
        <x-admin.card 
            title="Daftar Role Sistem" 
            subtitle="Kelola tingkatan role dan hak akses permissions yang terhubung dengan akun pengguna."
        >
            <x-slot:actions>
                @can('create-roles')
                    <a 
                        href="{{ route('admin.roles.create') }}" 
                        class="px-4 py-2 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-1.5 shadow-md shadow-[#1d3e35]/15 transition-all"
                    >
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Tambah Role Baru</span>
                    </a>
                @endcan
            </x-slot:actions>

            <!-- Yajra DataTable Component -->
            <x-admin.data-table
                id="roles-table"
                :ajaxUrl="route('admin.roles.index')"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => 'No', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                    ['data' => 'role_name', 'name' => 'name', 'title' => 'Nama Role', 'width' => '25%'],
                    ['data' => 'permissions_count', 'name' => 'permissions_count', 'title' => 'Hak Akses / Permission', 'width' => '30%'],
                    ['data' => 'users_count', 'name' => 'users_count', 'title' => 'Jumlah Pengguna', 'width' => '20%'],
                    ['data' => 'guard_name', 'name' => 'guard_name', 'title' => 'Guard', 'width' => '10%'],
                    ['data' => 'action', 'name' => 'action', 'title' => 'Aksi', 'orderable' => false, 'searchable' => false, 'width' => '10%'],
                ]"
            />
        </x-admin.card>
    </div>
</x-layouts.admin>
