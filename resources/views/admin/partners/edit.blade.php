<x-layouts.admin title="Edit Brand / Partner: {{ $partner->name }}">
    <x-admin.breadcrumb 
        title="Edit Partner" 
        :items="[
            'Konten' => '',
            'Brand & Partner' => route('admin.partners.index'),
            'Edit Partner' => ''
        ]" 
    />

    <div class="space-y-6" x-data="{
        logoMediaId: '{{ old('logo_media_id', $partner->logo_media_id) }}',
        logoUrl: '{{ old('logo_url', $partner->getLogo()) }}',
        handleMediaSelected(event) {
            const detail = event.detail;
            if (detail.targetField === 'partner_logo' && detail.media) {
                this.logoMediaId = detail.media.id;
                this.logoUrl = detail.media.url;
            }
        },
        removeLogo() {
            this.logoMediaId = '';
            this.logoUrl = '';
        }
    }" @media-selected.window="handleMediaSelected($event)">
        <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Top Action Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs">
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('admin.partners.index') }}" 
                        class="p-2.5 rounded-2xl bg-stone-100 text-stone-600 hover:bg-stone-200 transition-colors"
                        title="Kembali ke Daftar Partner"
                    >
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </a>
                    <div>
                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Perbarui Brand / Mitra</span>
                        <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">{{ $partner->name }}</h3>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.partners.index') }}" 
                        class="px-4 py-2.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-600 font-bold text-xs transition-colors"
                    >
                        Batal
                    </a>

                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full">
                <!-- ==================================================== -->
                <!-- LEFT COLUMN: PARTNER INFORMATION                     -->
                <!-- ==================================================== -->
                <div class="md:col-span-8 space-y-6">
                    <x-admin.card 
                        title="Informasi Brand / Partner" 
                        subtitle="Lengkapi data nama entitas, kategori kemitraan, dan tautan website resmi."
                    >
                        <div class="space-y-6">
                            <!-- Nama Partner -->
                            <div class="space-y-2">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Nama Brand / Partner <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    placeholder="Nama brand atau mitra..."
                                    value="{{ old('name', $partner->name) }}"
                                    class="w-full rounded-2xl p-4 text-base font-extrabold text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none shadow-2xs"
                                    required
                                />
                                @error('name')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Website URL -->
                            <div class="space-y-2">
                                <label for="website_url" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Link Website Resmi <span class="text-stone-400 font-normal lowercase">(opsional)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-stone-400">
                                        <i data-lucide="globe" class="w-4 h-4"></i>
                                    </span>
                                    <input
                                        type="url"
                                        name="website_url"
                                        id="website_url"
                                        placeholder="https://example.com"
                                        value="{{ old('website_url', $partner->website_url) }}"
                                        class="w-full rounded-2xl p-3.5 pl-11 text-xs text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                    />
                                </div>
                                <p class="text-[10px] text-stone-400">Gunakan format lengkap dengan https:// atau http://</p>
                                @error('website_url')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori Topik / Jenis Kemitraan -->
                            <div class="space-y-2">
                                <label for="category_select" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Kategori Kemitraan
                                </label>
                                <select 
                                    name="category" 
                                    id="category_select" 
                                    class="w-full"
                                    placeholder="Pilih atau ketik kategori baru..."
                                >
                                    <option value="">-- Pilih / Ketik Kategori Kemitraan --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category', $partner->category) == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                    <option value="Mitra Strategis" {{ old('category', $partner->category) == 'Mitra Strategis' ? 'selected' : '' }}>Mitra Strategis</option>
                                    <option value="Klien" {{ old('category', $partner->category) == 'Klien' ? 'selected' : '' }}>Klien</option>
                                    <option value="Sponsor" {{ old('category', $partner->category) == 'Sponsor' ? 'selected' : '' }}>Sponsor</option>
                                    <option value="Media Partner" {{ old('category', $partner->category) == 'Media Partner' ? 'selected' : '' }}>Media Partner</option>
                                    <option value="Vendor" {{ old('category', $partner->category) == 'Vendor' ? 'selected' : '' }}>Vendor</option>
                                </select>
                                <p class="text-[10px] text-stone-400">Pilih dari rekomendasi atau ketik kategori baru lalu tekan Enter.</p>
                                @error('category')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-admin.card>
                </div>

                <!-- ==================================================== -->
                <!-- RIGHT COLUMN: LOGO & SETTINGS                        -->
                <!-- ==================================================== -->
                <div class="md:col-span-4 space-y-6">
                    <!-- Logo Card (Media Library) -->
                    <x-admin.card 
                        title="Logo Brand / Partner" 
                        subtitle="Pilih logo resmi dari Media Library."
                        icon="image"
                    >
                        <div class="space-y-4">
                            <!-- Hidden Inputs -->
                            <input type="hidden" name="logo_media_id" :value="logoMediaId">
                            <input type="hidden" name="logo_url" :value="logoUrl">

                            <!-- Logo Preview Box -->
                            <div class="rounded-2xl border-2 border-dashed border-[#99cab7]/60 bg-[#f2f8f5]/40 p-4 text-center">
                                <template x-if="logoUrl">
                                    <div class="space-y-3">
                                        <div class="w-full h-32 rounded-xl bg-white border border-stone-200 p-2 flex items-center justify-center shadow-xs overflow-hidden">
                                            <img :src="logoUrl" alt="Logo Preview" class="max-h-full max-w-full object-contain">
                                        </div>
                                        <div class="flex items-center justify-center gap-2">
                                            <button 
                                                type="button"
                                                @click="$dispatch('open-media-picker', { targetField: 'partner_logo', multiple: false })"
                                                class="px-3 py-1.5 rounded-xl bg-[#1d3e35] text-white hover:bg-[#31725e] text-xs font-bold transition-colors cursor-pointer"
                                            >
                                                Ganti Logo
                                            </button>
                                            <button 
                                                type="button"
                                                @click="removeLogo()"
                                                class="px-3 py-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold transition-colors cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!logoUrl">
                                    <div class="py-6 space-y-3">
                                        <div class="w-14 h-14 mx-auto rounded-2xl bg-white border border-[#99cab7]/40 flex items-center justify-center text-[#31725e] shadow-2xs">
                                            <i data-lucide="image" class="w-7 h-7"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-xs font-bold text-[#1d3e35]">Belum ada logo dipilih</p>
                                            <p class="text-[11px] text-stone-400">Pilih logo berformat PNG, SVG, atau WebP</p>
                                        </div>
                                        <button 
                                            type="button"
                                            @click="$dispatch('open-media-picker', { targetField: 'partner_logo', multiple: false })"
                                            class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 text-xs font-bold shadow-xs transition-all cursor-pointer inline-flex items-center gap-1.5"
                                        >
                                            <i data-lucide="folder-open" class="w-4 h-4 text-[#cca06e]"></i>
                                            <span>Buka Media Library</span>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </x-admin.card>

                    <!-- Card Pengaturan Partner -->
                    <x-admin.card 
                        title="Pengaturan Publikasi" 
                        subtitle="Atur prioritas urutan tampil dan status aktif."
                        icon="settings"
                    >
                        <div class="space-y-5">
                            <!-- Urutan Tampil -->
                            <div class="space-y-1.5">
                                <label for="order" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Urutan Tampil
                                </label>
                                <input
                                    type="number"
                                    name="order"
                                    id="order"
                                    min="0"
                                    value="{{ old('order', $partner->order) }}"
                                    class="w-full rounded-2xl p-3 text-xs text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-mono"
                                />
                                <p class="text-[10px] text-stone-400">Angka lebih kecil tampil lebih awal (0, 1, 2, dst).</p>
                                @error('order')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status Aktif / Publikasi -->
                            <div class="pt-4 border-t border-stone-100 flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-bold text-[#1d3e35]">Status Aktif</h4>
                                    <p class="text-[11px] text-stone-400">Tampilkan logo di website</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        value="1" 
                                        {{ old('is_active', $partner->is_active ? '1' : '0') == '1' ? 'checked' : '' }} 
                                        class="sr-only peer"
                                    >
                                    <div class="w-11 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#31725e]"></div>
                                </label>
                            </div>
                        </div>
                    </x-admin.card>
                </div>
            </div>
        </form>
    </div>

    <!-- Reusable Media Library Modal -->
    <x-admin.media-picker-modal />

    <!-- Script TomSelect -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.TomSelect) {
                new window.TomSelect('#category_select', {
                    create: true,
                    persist: false,
                    placeholder: 'Pilih atau ketik kategori baru...',
                });
            }
        });
    </script>
</x-layouts.admin>
