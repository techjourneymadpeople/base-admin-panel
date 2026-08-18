<x-layouts.admin title="Tambah Testimonial Baru">
    <x-admin.breadcrumb 
        title="Tambah Testimonial" 
        :items="[
            'Konten' => '',
            'Testimonial' => route('admin.testimonials.index'),
            'Tambah Testimonial' => ''
        ]" 
    />

    <div class="space-y-6" x-data="{
        avatarMediaId: '{{ old('avatar_media_id') }}',
        avatarUrl: '{{ old('avatar_url') }}',
        rating: {{ old('rating', 5) }},
        handleMediaSelected(event) {
            const detail = event.detail;
            if (detail.targetField === 'testimonial_avatar' && detail.media) {
                this.avatarMediaId = detail.media.id;
                this.avatarUrl = detail.media.url;
            }
        },
        removeAvatar() {
            this.avatarMediaId = '';
            this.avatarUrl = '';
        }
    }" @media-selected.window="handleMediaSelected($event)">
        <form action="{{ route('admin.testimonials.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Top Action Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs">
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('admin.testimonials.index') }}" 
                        class="p-2.5 rounded-2xl bg-stone-100 text-stone-600 hover:bg-stone-200 transition-colors"
                        title="Kembali ke Daftar Testimonial"
                    >
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </a>
                    <div>
                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Ulasan & Kepuasan</span>
                        <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">Tambah Testimonial Baru</h3>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.testimonials.index') }}" 
                        class="px-4 py-2.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-600 font-bold text-xs transition-colors"
                    >
                        Batal
                    </a>

                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Simpan Testimonial</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full">
                <!-- ==================================================== -->
                <!-- LEFT COLUMN: CLIENT INFO & TESTIMONIAL CONTENT       -->
                <!-- ==================================================== -->
                <div class="md:col-span-8 space-y-6">
                    <x-admin.card 
                        title="Informasi Pemberi Testimoni" 
                        subtitle="Lengkapi data profil klien dan isi ulasan pengalaman mereka."
                    >
                        <div class="space-y-6">
                            <!-- Nama Klien -->
                            <div class="space-y-2">
                                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Nama Klien / Pelanggan <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    placeholder="Contoh: Rian Anggara..."
                                    value="{{ old('name') }}"
                                    class="w-full rounded-2xl p-4 text-base font-extrabold text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none shadow-2xs"
                                    required
                                    autofocus
                                />
                                @error('name')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Row: Jabatan & Perusahaan -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Jabatan / Title -->
                                <div class="space-y-2">
                                    <label for="role_or_title" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                        Jabatan / Profesi
                                    </label>
                                    <input
                                        type="text"
                                        name="role_or_title"
                                        id="role_or_title"
                                        placeholder="Contoh: Chief Marketing Officer"
                                        value="{{ old('role_or_title') }}"
                                        class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                    />
                                    @error('role_or_title')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Perusahaan -->
                                <div class="space-y-2">
                                    <label for="company" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                        Perusahaan / Instansi
                                    </label>
                                    <input
                                        type="text"
                                        name="company"
                                        id="company"
                                        placeholder="Contoh: PT Lentera Digital"
                                        value="{{ old('company') }}"
                                        class="w-full rounded-2xl p-3.5 text-xs text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                    />
                                    @error('company')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Isi Testimoni -->
                            <div class="space-y-2">
                                <label for="content" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Isi Ulasan Testimoni <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    name="content"
                                    id="content"
                                    rows="6"
                                    placeholder="Tuliskan pengalaman positif klien terhadap layanan atau produk yang digunakan..."
                                    class="w-full rounded-2xl p-4 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none leading-relaxed"
                                    required
                                >{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-admin.card>
                </div>

                <!-- ==================================================== -->
                <!-- RIGHT COLUMN: AVATAR, RATING & SETTINGS              -->
                <!-- ==================================================== -->
                <div class="md:col-span-4 space-y-6">
                    <!-- Foto Avatar (Media Library) -->
                    <x-admin.card 
                        title="Foto Avatar Klien" 
                        subtitle="Pilih foto profil dari Media Library."
                        icon="user"
                    >
                        <div class="space-y-4">
                            <!-- Hidden Inputs -->
                            <input type="hidden" name="avatar_media_id" :value="avatarMediaId">
                            <input type="hidden" name="avatar_url" :value="avatarUrl">

                            <!-- Avatar Preview Box -->
                            <div class="rounded-2xl border-2 border-dashed border-[#99cab7]/60 bg-[#f2f8f5]/40 p-4 text-center">
                                <template x-if="avatarUrl">
                                    <div class="space-y-3">
                                        <div class="w-24 h-24 mx-auto rounded-full bg-white border-2 border-[#31725e] shadow-md overflow-hidden p-0.5">
                                            <img :src="avatarUrl" alt="Avatar Preview" class="w-full h-full object-cover rounded-full">
                                        </div>
                                        <div class="flex items-center justify-center gap-2">
                                            <button 
                                                type="button"
                                                @click="$dispatch('open-media-picker', { targetField: 'testimonial_avatar', multiple: false })"
                                                class="px-3 py-1.5 rounded-xl bg-[#1d3e35] text-white hover:bg-[#31725e] text-xs font-bold transition-colors cursor-pointer"
                                            >
                                                Ganti Foto
                                            </button>
                                            <button 
                                                type="button"
                                                @click="removeAvatar()"
                                                class="px-3 py-1.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 text-xs font-bold transition-colors cursor-pointer"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!avatarUrl">
                                    <div class="py-4 space-y-3">
                                        <div class="w-16 h-16 mx-auto rounded-full bg-white border border-[#99cab7]/40 flex items-center justify-center text-[#31725e] shadow-2xs">
                                            <i data-lucide="user" class="w-8 h-8"></i>
                                        </div>
                                        <div class="space-y-0.5">
                                            <p class="text-xs font-bold text-[#1d3e35]">Belum ada foto avatar</p>
                                            <p class="text-[11px] text-stone-400">Pilih foto wajah klien</p>
                                        </div>
                                        <button 
                                            type="button"
                                            @click="$dispatch('open-media-picker', { targetField: 'testimonial_avatar', multiple: false })"
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

                    <!-- Rating & Pengaturan -->
                    <x-admin.card 
                        title="Rating & Pengaturan" 
                        subtitle="Atur penilaian bintang, kategori, dan visibilitas."
                        icon="star"
                    >
                        <div class="space-y-5">
                            <!-- Rating Bintang Selector -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Rating Bintang <span class="text-red-500">*</span>
                                </label>
                                <input type="hidden" name="rating" :value="rating">
                                
                                <div class="flex items-center gap-2 p-3 rounded-2xl bg-white border border-[#99cab7]/50 shadow-2xs justify-center">
                                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                        <button 
                                            type="button" 
                                            @click="rating = star"
                                            class="p-1 text-2xl transition-transform hover:scale-125 focus:outline-none cursor-pointer"
                                            :class="star <= rating ? 'text-amber-400' : 'text-stone-300 hover:text-amber-300'"
                                        >
                                            ★
                                        </button>
                                    </template>
                                    <span class="text-xs font-bold text-stone-700 ml-2 font-mono" x-text="rating + ' / 5 Bintang'"></span>
                                </div>
                                @error('rating')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kategori Topik Ulasan -->
                            <div class="space-y-1.5">
                                <label for="category_select" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Kategori Layanan
                                </label>
                                <select 
                                    name="category" 
                                    id="category_select" 
                                    class="w-full"
                                    placeholder="Pilih atau ketik kategori..."
                                >
                                    <option value="">-- Pilih / Ketik Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                    <option value="Layanan Website" {{ old('category') == 'Layanan Website' ? 'selected' : '' }}>Layanan Website</option>
                                    <option value="Aplikasi Mobile" {{ old('category') == 'Aplikasi Mobile' ? 'selected' : '' }}>Aplikasi Mobile</option>
                                    <option value="Digital Marketing" {{ old('category') == 'Digital Marketing' ? 'selected' : '' }}>Digital Marketing</option>
                                    <option value="Konsultasi IT" {{ old('category') == 'Konsultasi IT' ? 'selected' : '' }}>Konsultasi IT</option>
                                </select>
                                <p class="text-[10px] text-stone-400">Pilih dari daftar atau ketik kategori baru lalu tekan Enter.</p>
                                @error('category')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

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
                                    value="{{ old('order', 0) }}"
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
                                    <p class="text-[11px] text-stone-400">Tampilkan testimoni di website</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        value="1" 
                                        {{ old('is_active', '1') == '1' ? 'checked' : '' }} 
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
