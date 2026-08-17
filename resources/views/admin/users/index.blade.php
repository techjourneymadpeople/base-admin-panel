<x-layouts.admin title="Manajemen Pengguna">
    <!-- Page Header & Breadcrumbs -->
    <x-admin.breadcrumb 
        title="Manajemen Pengguna" 
        :items="['Pengguna' => route('admin.users.index'), 'Daftar Pengguna' => '']" 
    />

    <!-- Flash Alerts -->
    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-6" />
    @endif

    @if(session('error'))
        <x-alert type="error" :message="session('error')" class="mb-6" />
    @endif

    @if($errors->any())
        <x-alert type="error" message="Terdapat kesalahan pada input data Anda. Silakan periksa kembali." class="mb-6" />
    @endif

    <!-- Main Table Container Card -->
    <x-admin.card 
        title="Daftar Akun Pengguna" 
        subtitle="Kelola seluruh data akun pengguna, status verifikasi, dan hak akses sistem."
        :padding="false"
    >
        <x-slot:actions>
            <!-- Export Excel Button -->
            <a 
                href="{{ route('admin.users.export') }}" 
                class="px-3.5 py-2 rounded-xl border border-[#99cab7]/50 bg-white hover:bg-[#f2f8f5] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-1.5 shadow-2xs transition-all duration-150 hover:-translate-y-0.5"
                title="Unduh Data Excel"
            >
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-700"></i>
                <span>Export Excel</span>
            </a>

            <!-- Import Excel Button (Triggers Alpine Modal) -->
            <button 
                type="button" 
                @click="$dispatch('open-modal-import-excel')"
                class="px-3.5 py-2 rounded-xl border border-[#99cab7]/50 bg-white hover:bg-[#f2f8f5] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-1.5 shadow-2xs transition-all duration-150 hover:-translate-y-0.5 cursor-pointer"
                title="Impor Pengguna dari Excel"
            >
                <i data-lucide="upload" class="w-4 h-4 text-[#31725e]"></i>
                <span>Import Excel</span>
            </button>

            <!-- Create User Button -->
            <a 
                href="{{ route('admin.users.create') }}" 
                class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md hover:shadow-lg transition-all duration-150 hover:-translate-y-0.5"
            >
                <i data-lucide="user-plus" class="w-4 h-4 text-[#cca06e]"></i>
                <span>Tambah Pengguna</span>
            </a>
        </x-slot:actions>

        <!-- Yajra DataTables Server-Side Component -->
        <div class="p-6">
            <x-admin.data-table 
                id="usersTable"
                :ajaxUrl="route('admin.users.index')"
                :columns="[
                    ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false, 'width' => '5%'],
                    ['data' => 'user_info', 'name' => 'name', 'orderable' => true, 'searchable' => true],
                    ['data' => 'role_badge', 'name' => 'roles.name', 'orderable' => false, 'searchable' => false],
                    ['data' => 'status_badge', 'name' => 'status', 'orderable' => true, 'searchable' => false],
                    ['data' => 'email_status', 'name' => 'email_verified_at', 'orderable' => true, 'searchable' => false],
                    ['data' => 'created_at_formatted', 'name' => 'created_at', 'orderable' => true, 'searchable' => false],
                    ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false, 'width' => '12%'],
                ]"
                :order="[[1, 'asc']]"
            >
                <th class="py-3 px-4 w-12 text-center">No</th>
                <th class="py-3 px-4">Pengguna</th>
                <th class="py-3 px-4">Hak Akses / Role</th>
                <th class="py-3 px-4">Status Akun</th>
                <th class="py-3 px-4">Status Email</th>
                <th class="py-3 px-4">Bergabung</th>
                <th class="py-3 px-6 text-right">Aksi</th>
            </x-admin.data-table>
        </div>
    </x-admin.card>

    <!-- Modal 1: Import Excel (Maatwebsite) -->
    <x-admin.modal name="import-excel" title="Impor Data Pengguna dari Excel" maxWidth="lg">
        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="p-4 rounded-2xl bg-[#f2f8f5] border border-[#99cab7]/30 text-xs text-[#295c4d] space-y-2">
                <div class="flex items-center gap-2 font-bold text-[#1d3e35]">
                    <i data-lucide="info" class="w-4 h-4 text-[#31725e]"></i>
                    <span>Panduan Format Berkas Excel</span>
                </div>
                <p>
                    Pastikan berkas Excel memiliki format kolom: <strong>nama_lengkap</strong>, <strong>alamat_email</strong>, <strong>password</strong> (opsional), dan <strong>hak_akses</strong> (opsional).
                </p>
                <div class="pt-1">
                    <a 
                        href="{{ route('admin.users.import.template') }}" 
                        class="inline-flex items-center gap-1 font-bold text-[#31725e] hover:underline"
                    >
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        <span>Unduh Contoh Template Excel (.xlsx)</span>
                    </a>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                    Pilih Berkas Excel (.xlsx, .xls, .csv) <span class="text-[#b17042]">*</span>
                </label>
                <input 
                    type="file" 
                    name="file" 
                    accept=".xlsx,.xls,.csv"
                    required
                    class="w-full text-xs text-stone-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#1d3e35] file:text-white hover:file:bg-[#31725e] file:cursor-pointer cursor-pointer border border-[#99cab7]/50 rounded-2xl p-2 bg-white"
                />
            </div>

            <div class="pt-3 border-t border-stone-100 flex items-center justify-end gap-2">
                <button 
                    type="button" 
                    @click="$dispatch('close-modal-import-excel')" 
                    class="px-4 py-2 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-100 transition-colors cursor-pointer"
                >
                    Batal
                </button>
                <button 
                    type="submit" 
                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white font-bold text-xs shadow-md hover:shadow-lg transition-all cursor-pointer inline-flex items-center gap-1.5"
                >
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                    <span>Mulai Unggah & Impor</span>
                </button>
            </div>
        </form>
    </x-admin.modal>
</x-layouts.admin>
