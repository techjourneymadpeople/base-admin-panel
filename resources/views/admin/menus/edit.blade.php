<x-layouts.admin title="Edit Menu - {{ $menu->title }}">
    <x-admin.breadcrumb 
        title="Edit Menu" 
        :items="[
            'Kelola Menu' => route('admin.menus.index'),
            $menu->title => route('admin.menus.show', $menu->id),
            'Edit' => ''
        ]" 
    />

    <div class="max-w-4xl" x-data="{
        menuType: '{{ old('type', $menu->type) }}'
    }">
        <x-admin.card 
            title="Edit Item Menu" 
            subtitle="Perbarui konfigurasi rute, hierarki, dan pengaturan visual menu sidebar."
        >
            <x-slot:actions>
                <a 
                    href="{{ route('admin.menus.permissions', $menu->id) }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-xs inline-flex items-center gap-1.5 transition-colors"
                >
                    <i data-lucide="key" class="w-3.5 h-3.5 text-[#b17042]"></i>
                    <span>Atur View Permission</span>
                </a>

                <a 
                    href="{{ route('admin.menus.index') }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-1 transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.menus.update', $menu->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Judul Menu -->
                    <x-form.input
                        name="title"
                        label="Judul Menu"
                        icon="type"
                        :required="true"
                        :value="old('title', $menu->title)"
                    />

                    <!-- Tipe Menu -->
                    <x-form.select
                        name="type"
                        label="Tipe Menu"
                        icon="layers"
                        :required="true"
                        :selected="old('type', $menu->type)"
                        :options="[
                            'link' => 'Menu Link Biasa (Mengarah ke halaman rute)',
                            'dropdown' => 'Menu Dropdown (Memiliki sub-menu anak)',
                            'header' => 'Section Header (Judul pembatas kelompok menu)',
                        ]"
                        x-model="menuType"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" x-show="menuType !== 'header'">
                    <!-- Parent Menu (Dropdown) -->
                    <x-form.select
                        name="parent_id"
                        label="Parent Menu (Opsional)"
                        icon="corner-down-right"
                        placeholder="-- Pilih Menu Induk (Jika Sub-Menu) --"
                        :selected="old('parent_id', $menu->parent_id)"
                        :options="$parentOptions"
                        helper="Kosongkan jika menu ini berada di tingkat teratas (Top Level)."
                    />

                    <!-- Icon Lucide -->
                    <x-form.input
                        name="icon"
                        label="Nama Icon Lucide"
                        icon="smile"
                        placeholder="Contoh: shopping-cart, file-text, settings"
                        :value="old('icon', $menu->icon)"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" x-show="menuType === 'link'">
                    <!-- Route Name -->
                    <x-form.input
                        name="route"
                        label="Nama Route Laravel"
                        icon="navigation"
                        placeholder="Contoh: admin.users.index, admin.dashboard"
                        :value="old('route', $menu->route)"
                    />

                    <!-- External URL -->
                    <x-form.input
                        name="url"
                        label="URL Eksternal / Kustom (Opsional)"
                        icon="link"
                        placeholder="Contoh: https://example.com atau /admin/custom"
                        :value="old('url', $menu->url)"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5" x-show="menuType !== 'header'">
                    <!-- Badge Text -->
                    <x-form.input
                        name="badge"
                        label="Teks Badge (Opsional)"
                        placeholder="Contoh: Baru, Hot, 5"
                        :value="old('badge', $menu->badge)"
                    />

                    <!-- Badge Color -->
                    <x-form.select
                        name="badge_color"
                        label="Warna Badge"
                        :selected="old('badge_color', $menu->badge_color ?? 'emerald')"
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
                        :selected="old('target', $menu->target ?? '_self')"
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
                        :value="old('order', $menu->order)"
                    />

                    <!-- Checkbox Status Aktif -->
                    <div class="flex items-center pt-6">
                        <x-form.checkbox
                            name="is_active"
                            label="Tampilkan Menu Ini (Status Aktif)"
                            :checked="old('is_active', (bool)$menu->is_active)"
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
                        icon="save"
                    >
                        Simpan Perubahan
                    </x-form.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-layouts.admin>
