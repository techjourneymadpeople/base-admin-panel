<x-layouts.admin title="Tambah Menu Baru">
    <x-admin.breadcrumb 
        title="Tambah Menu Baru" 
        :items="[
            'Kelola Menu' => route('admin.menus.index'),
            'Tambah Baru' => ''
        ]" 
    />

    <div class="max-w-4xl" x-data="{
        menuType: '{{ old('type', 'link') }}'
    }">
        <x-admin.card 
            title="Formulir Menu Navigasi" 
            subtitle="Buat item menu baru untuk navigasi sidebar admin. Setelah disimpan, Anda dapat mengatur hak akses view permission untuk menu ini."
        >
            <x-slot:actions>
                <a 
                    href="{{ route('admin.menus.index') }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-1 transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.menus.store') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Judul Menu -->
                    <x-form.input
                        name="title"
                        label="Judul Menu"
                        icon="type"
                        placeholder="Contoh: Manajemen Pesanan, Laporan Keuangan"
                        :required="true"
                        :value="old('title')"
                        autofocus
                    />

                    <!-- Tipe Menu -->
                    <x-form.select
                        name="type"
                        label="Tipe Menu"
                        icon="layers"
                        :required="true"
                        :selected="old('type', 'link')"
                        :options="[
                            'link' => 'Menu Link Biasa (Mengarah ke halaman rute)',
                            'dropdown' => 'Menu Dropdown (Memiliki sub-menu anak)',
                            'header' => 'Section Header (Judul pembatas kelompok menu)',
                        ]"
                        x-model="menuType"
                        helper="Pilih tipe elemen navigasi yang sesuai."
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" x-show="menuType !== 'header'">
                    <!-- Parent Menu (Dropdown) -->
                    <x-form.select
                        name="parent_id"
                        label="Parent Menu (Opsional)"
                        icon="corner-down-right"
                        placeholder="-- Pilih Menu Induk (Jika Sub-Menu) --"
                        :selected="old('parent_id')"
                        :options="$parentOptions"
                        helper="Kosongkan jika menu ini berada di tingkat teratas (Top Level)."
                    />

                    <!-- Icon Lucide -->
                    <x-form.input
                        name="icon"
                        label="Nama Icon Lucide"
                        icon="smile"
                        placeholder="Contoh: shopping-cart, file-text, settings"
                        :value="old('icon')"
                        helper="Lihat daftar icon lengkap pada website lucide.dev."
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" x-show="menuType === 'link'">
                    <!-- Route Name -->
                    <x-form.input
                        name="route"
                        label="Nama Route Laravel"
                        icon="navigation"
                        placeholder="Contoh: admin.users.index, admin.dashboard"
                        :value="old('route')"
                        helper="Prioritas utama untuk navigasi halaman internal."
                    />

                    <!-- External URL -->
                    <x-form.input
                        name="url"
                        label="URL Eksternal / Kustom (Opsional)"
                        icon="link"
                        placeholder="Contoh: https://example.com atau /admin/custom"
                        :value="old('url')"
                        helper="Digunakan jika tidak menggunakan route name Laravel."
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5" x-show="menuType !== 'header'">
                    <!-- Badge Text -->
                    <x-form.input
                        name="badge"
                        label="Teks Badge (Opsional)"
                        placeholder="Contoh: Baru, Hot, 5"
                        :value="old('badge')"
                    />

                    <!-- Badge Color -->
                    <x-form.select
                        name="badge_color"
                        label="Warna Badge"
                        :selected="old('badge_color', 'emerald')"
                        :options="[
                            'emerald' => 'Hijau Emerald',
                            'amber' => 'Kuning Amber',
                            'red' => 'Merah Crimson',
                            'blue' => 'Biru Royal',
                            'stone' => 'Abu-abu Stone',
                        ]"
                    />

                    <!-- Target Link -->
                    <x-form.select
                        name="target"
                        label="Target Link"
                        :selected="old('target', '_self')"
                        :options="[
                            '_self' => 'Buka di Tab yang Sama (_self)',
                            '_blank' => 'Buka di Tab Baru (_blank)',
                        ]"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-stone-100">
                    <!-- Urutan (Order) -->
                    <x-form.input
                        type="number"
                        name="order"
                        label="Nomor Urutan Tampilan"
                        icon="list-ordered"
                        :required="true"
                        :value="old('order', 10)"
                        helper="Angka lebih kecil akan tampil lebih dulu di sidebar."
                    />

                    <!-- Checkbox Status Aktif -->
                    <div class="flex items-center pt-6">
                        <x-form.checkbox
                            name="is_active"
                            label="Tampilkan Menu Ini (Status Aktif)"
                            :checked="old('is_active', true)"
                        />
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('admin.menus.index') }}" 
                        class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs transition-colors"
                    >
                        Batal
                    </a>

                    <x-form.button 
                        type="submit" 
                        variant="primary" 
                        :fullWidth="false" 
                        icon="arrow-right"
                    >
                        Simpan & Atur Permission
                    </x-form.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-layouts.admin>
