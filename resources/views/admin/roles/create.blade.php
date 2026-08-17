<x-layouts.admin title="Tambah Role Baru">
    <x-admin.breadcrumb 
        title="Tambah Role Baru" 
        :items="[
            'Kelola Role' => route('admin.roles.index'),
            'Tambah Baru' => ''
        ]" 
    />

    <div class="max-w-2xl">
        <x-admin.card 
            title="Formulir Role Baru" 
            subtitle="Buat tingkatan role baru. Setelah dibuat, Anda akan diarahkan untuk memilih hak akses permission untuk role ini."
        >
            <x-slot:actions>
                <a 
                    href="{{ route('admin.roles.index') }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-1 transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-5">
                @csrf

                <!-- Nama Role -->
                <x-form.input
                    name="name"
                    label="Nama Role"
                    icon="shield"
                    placeholder="Contoh: Manager Operasional, Keuangan, Kasir"
                    :required="true"
                    :value="old('name')"
                    autofocus
                    helper="Gunakan nama role yang deskriptif dan unik."
                />

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('admin.roles.index') }}" 
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
