<x-layouts.admin title="Edit Role - {{ $role->name }}">
    <x-admin.breadcrumb 
        title="Edit Role" 
        :items="[
            'Kelola Role' => route('admin.roles.index'),
            $role->name => route('admin.roles.show', $role->id),
            'Edit' => ''
        ]" 
    />

    <div class="max-w-2xl">
        <x-admin.card 
            title="Edit Data Role" 
            subtitle="Perbarui nama tingkatan role. Hak akses permission dapat disesuaikan pada halaman Assign Permission."
        >
            <x-slot:actions>
                <a 
                    href="{{ route('admin.roles.permissions', $role->id) }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-amber-300 bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-xs inline-flex items-center gap-1.5 transition-colors"
                >
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-[#b17042]"></i>
                    <span>Atur Permission Role</span>
                </a>

                <a 
                    href="{{ route('admin.roles.index') }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-1 transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama Role -->
                <x-form.input
                    name="name"
                    label="Nama Role"
                    icon="shield"
                    :required="true"
                    :value="old('name', $role->name)"
                    :disabled="$role->name === 'Super Admin'"
                    helper="{{ $role->name === 'Super Admin' ? 'Nama role Super Admin dikunci oleh sistem dan tidak dapat diubah.' : 'Gunakan nama role yang unik dan jelas.' }}"
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
                        icon="save"
                        :disabled="$role->name === 'Super Admin'"
                    >
                        Simpan Perubahan
                    </x-form.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-layouts.admin>
