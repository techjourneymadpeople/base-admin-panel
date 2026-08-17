<x-layouts.admin title="Edit Kategori Artikel">
    <x-admin.breadcrumb 
        title="Edit Kategori Artikel" 
        :items="[
            'Article SEO' => '',
            'Article Category' => route('admin.article-categories.index'),
            'Edit Kategori' => ''
        ]" 
    />

    <div class="max-w-3xl space-y-6">
        <form action="{{ route('admin.article-categories.update', $articleCategory->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <x-admin.card 
                title="Edit Kategori: {{ $articleCategory->name }}" 
                subtitle="Perbarui data kategori artikel. Slug URL: /{{ $articleCategory->slug }}"
            >
                <div class="space-y-5">
                    <!-- Nama Kategori -->
                    <x-form.input
                        name="name"
                        label="Nama Kategori"
                        placeholder="Contoh: Bisnis, Teknologi, Keuangan..."
                        :value="old('name', $articleCategory->name)"
                        required
                    />

                    <!-- Deskripsi Kategori -->
                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                            Deskripsi Singkat (Opsional)
                        </label>
                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            placeholder="Deskripsi singkat mengenai topik kategori ini..."
                            class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/80 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/70 hover:bg-white/90 outline-none"
                        >{{ old('description', $articleCategory->description) }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Urutan Tampilan & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                        <x-form.input
                            type="number"
                            name="order"
                            label="Nomor Urut Tampilan"
                            placeholder="0"
                            :value="old('order', $articleCategory->order)"
                            min="0"
                        />

                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                Status Kategori
                            </label>
                            <div class="pt-2">
                                <x-form.checkbox
                                    name="is_active"
                                    id="is_active"
                                    label="Aktifkan Kategori Ini"
                                    :checked="old('is_active', $articleCategory->is_active)"
                                    value="1"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </x-admin.card>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3">
                <a 
                    href="{{ route('admin.article-categories.index') }}" 
                    class="px-5 py-2.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-600 font-bold text-xs transition-colors"
                >
                    Batal
                </a>

                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/15 transition-all"
                >
                    <i data-lucide="check" class="w-4 h-4 text-[#cca06e]"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
