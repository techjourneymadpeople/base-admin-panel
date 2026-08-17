<x-layouts.admin title="Edit Permission - {{ $permission->name }}">
    <x-admin.breadcrumb 
        title="Edit Permission" 
        :items="[
            'Kelola Permission' => route('admin.permissions.index'),
            $permission->name => route('admin.permissions.show', $permission->id),
            'Edit' => ''
        ]" 
    />

    <div class="max-w-2xl">
        <x-admin.card 
            title="Edit Nama Permission" 
            subtitle="Perbarui nama permission. Perubahan nama akan otomatis sinkron ke seluruh role dan menu yang menggunakannya."
        >
            <x-slot:actions>
                <a 
                    href="{{ route('admin.permissions.index') }}" 
                    class="px-3.5 py-1.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs inline-flex items-center gap-1 transition-colors"
                >
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.permissions.update', $permission->id) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Nama Permission -->
                <x-form.input
                    name="name"
                    label="Nama Permission"
                    icon="key"
                    :required="true"
                    :value="old('name', $permission->name)"
                    helper="Gunakan format slug huruf kecil dengan tanda hubung (-) (contoh: view-users)."
                />

                <!-- Form Action Buttons -->
                <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-3">
                    <a 
                        href="{{ route('admin.permissions.index') }}" 
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
