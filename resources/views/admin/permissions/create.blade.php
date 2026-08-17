<x-layouts.admin title="Tambah Permission Baru">
    <x-admin.breadcrumb 
        title="Tambah Permission Baru" 
        :items="[
            'Kelola Permission' => route('admin.permissions.index'),
            'Tambah Baru' => ''
        ]" 
    />

    <div class="max-w-3xl" x-data="{
        mode: 'single', // 'single' or 'bulk'
        moduleName: '',
        selectedActions: ['view', 'create', 'edit', 'delete'],
        toggleAction(act) {
            if (this.selectedActions.includes(act)) {
                this.selectedActions = this.selectedActions.filter(a => a !== act);
            } else {
                this.selectedActions.push(act);
            }
        }
    }">
        <x-admin.card 
            title="Formulir Pembuatan Permission" 
            subtitle="Buat permission baru secara satuan atau sekaligus untuk seluruh aksi dalam modul fitur baru."
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

            <!-- Mode Selector Tabs -->
            <div class="p-1 rounded-2xl bg-stone-100/80 border border-stone-200/60 inline-flex items-center gap-1 w-full sm:w-auto">
                <button 
                    type="button" 
                    @click="mode = 'single'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex-1 sm:flex-initial flex items-center justify-center gap-2"
                    :class="mode === 'single' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
                >
                    <i data-lucide="key" class="w-3.5 h-3.5"></i>
                    <span>Permission Tunggal (Single)</span>
                </button>

                <button 
                    type="button" 
                    @click="mode = 'bulk'"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex-1 sm:flex-initial flex items-center justify-center gap-2"
                    :class="mode === 'bulk' ? 'bg-white text-[#1d3e35] shadow-2xs' : 'text-stone-500 hover:text-stone-800'"
                >
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-[#cca06e]"></i>
                    <span>Buat Sekaligus per Modul (Bulk)</span>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.permissions.store') }}" class="space-y-6 pt-2">
                @csrf
                <input type="hidden" name="creation_type" :value="mode">

                <!-- Mode 1: Single Creation -->
                <div x-show="mode === 'single'" class="space-y-4">
                    <x-form.input
                        name="name"
                        label="Nama Permission"
                        icon="key"
                        placeholder="Contoh: view-reports, approve-transactions, download-invoice"
                        :value="old('name')"
                        helper="Gunakan format huruf kecil dengan tanda minus (-) sebagai pemisah (slug format)."
                    />
                </div>

                <!-- Mode 2: Bulk Module Creation -->
                <div x-show="mode === 'bulk'" class="space-y-5" x-cloak>
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                            Nama Modul / Fitur <span class="text-[#b17042]">*</span>
                        </label>
                        <div class="relative rounded-2xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-[#428e75]">
                                <i data-lucide="folder" class="w-4 h-4 text-[#428e75]"></i>
                            </div>
                            <input 
                                type="text" 
                                name="module" 
                                x-model="moduleName"
                                placeholder="Contoh: products, transactions, reports, categories"
                                class="w-full rounded-2xl py-3 pl-10 pr-4 text-sm text-[#1d3e35] bg-white border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 hover:border-[#31725e]"
                            >
                        </div>
                        <p class="text-xs text-[#623c2c]/75">Nama modul akan digunakan sebagai akhiran nama permission (e.g. view-<em>products</em>).</p>
                    </div>

                    <!-- Checklist Aksi Modul -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-[#295c4d]">
                            Aksi yang Ingin Dibuat Otomatis
                        </label>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            @php
                                $standardActions = [
                                    'view' => 'Lihat / View',
                                    'create' => 'Tambah / Create',
                                    'edit' => 'Edit / Update',
                                    'delete' => 'Hapus / Delete',
                                    'export' => 'Export Data',
                                    'import' => 'Import Data',
                                    'manage' => 'Kelola / Manage',
                                ];
                            @endphp

                            @foreach($standardActions as $actKey => $actLabel)
                                <label 
                                    class="p-3 rounded-2xl border transition-all cursor-pointer select-none flex items-center gap-2"
                                    :class="selectedActions.includes('{{ $actKey }}') ? 'bg-[#e2f0ea]/70 border-[#428e75]/60 text-[#1d3e35]' : 'bg-white border-stone-200 text-stone-600 hover:bg-stone-50'"
                                >
                                    <input 
                                        type="checkbox" 
                                        name="actions[]" 
                                        value="{{ $actKey }}"
                                        x-model="selectedActions"
                                        class="w-4 h-4 rounded-md border-stone-300 text-[#1d3e35] focus:ring-[#31725e] accent-[#1d3e35]"
                                    >
                                    <div class="min-w-0">
                                        <span class="block text-xs font-bold">{{ $actLabel }}</span>
                                        <span class="block text-[10px] font-mono text-stone-400" x-text="moduleName ? '{{ $actKey }}-' + moduleName.toLowerCase().replace(/[^a-z0-9]/g, '-') : '{{ $actKey }}-modul'"></span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

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
                        icon="plus-circle"
                    >
                        Simpan Permission
                    </x-form.button>
                </div>
            </form>
        </x-admin.card>
    </div>
</x-layouts.admin>
