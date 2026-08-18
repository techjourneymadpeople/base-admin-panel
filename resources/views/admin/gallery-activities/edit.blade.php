<x-layouts.admin title="Edit Foto Kegiatan: {{ $gallery->title }}">
    <x-admin.breadcrumb 
        title="Edit Foto Kegiatan" 
        :items="[
            'Article SEO' => '',
            'Gallery Activity' => route('admin.gallery-activities.index'),
            'Edit Kegiatan' => ''
        ]" 
    />

    <div class="space-y-6" x-data="galleryEditFormHandler(@js($existingPhotos))">
        <form action="{{ route('admin.gallery-activities.update', $gallery->id) }}" method="POST" class="space-y-6" id="gallery-form">
            @csrf
            @method('PUT')

            <!-- Hidden Inputs for Thumbnail Cover from Media Library -->
            <input type="hidden" name="thumbnail_media_id" :value="thumbnailMediaId" />
            <input type="hidden" name="thumbnail_url" :value="thumbnailUrl" />
            <input type="hidden" name="remove_thumbnail" :value="removeThumbnailFlag ? 1 : 0" />

            <!-- Top Action Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs">
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('admin.gallery-activities.index') }}" 
                        class="p-2.5 rounded-2xl bg-stone-100 text-stone-600 hover:bg-stone-200 transition-colors"
                        title="Kembali ke Daftar Galeri"
                    >
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </a>
                    <div>
                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Perbarui Kegiatan</span>
                        <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">{{ $gallery->title }}</h3>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.gallery-activities.index') }}" 
                        class="px-4 py-2.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-600 font-bold text-xs transition-colors"
                    >
                        Batal
                    </a>

                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Simpan Perubahan & Update Sitemap</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full">
                <!-- ==================================================== -->
                <!-- LEFT COLUMN: MAIN GALLERY INFO & MULTI-PHOTO UPLOAD  -->
                <!-- ==================================================== -->
                <div class="md:col-span-8 space-y-6">
                    <!-- 1. Card Informasi Kegiatan -->
                    <x-admin.card 
                        title="Informasi Kegiatan" 
                        subtitle="Perbarui judul, tanggal pelaksanaan, lokasi, dan penjelasan kegiatan."
                    >
                        <div class="space-y-6">
                            <!-- Judul Kegiatan -->
                            <div class="space-y-2">
                                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Judul Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    x-model="title"
                                    placeholder="Masukkan judul kegiatan..."
                                    value="{{ old('title', $gallery->title) }}"
                                    class="w-full rounded-2xl p-4 text-lg font-extrabold text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none shadow-2xs"
                                    required
                                />
                                @error('title')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal & Lokasi Kegiatan Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="activity_date" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                        Tanggal Pelaksanaan
                                    </label>
                                    <input
                                        type="date"
                                        name="activity_date"
                                        id="activity_date"
                                        value="{{ old('activity_date', $gallery->activity_date ? $gallery->activity_date->format('Y-m-d') : '') }}"
                                        class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none font-semibold"
                                    />
                                    @error('activity_date')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="location" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                        Lokasi Acara / Kegiatan
                                    </label>
                                    <input
                                        type="text"
                                        name="location"
                                        id="location"
                                        placeholder="Contoh: Gedung Serbaguna..."
                                        value="{{ old('location', $gallery->location) }}"
                                        class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                    />
                                    @error('location')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deskripsi Lengkap Kegiatan -->
                            <div class="space-y-2">
                                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Deskripsi & Cerita Kegiatan
                                </label>
                                <textarea
                                    name="description"
                                    id="description"
                                    x-model="description"
                                    rows="4"
                                    placeholder="Ceritakan latar belakang dan dokumentasi acara..."
                                    class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                >{{ old('description', $gallery->description) }}</textarea>
                                @error('description')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-admin.card>

                    <!-- 2. Card Galeri Foto Kegiatan (Multi-Photo Picker from Media Library) -->
                    <x-admin.card 
                        title="Foto-Foto Kegiatan (Media Library)" 
                        subtitle="Kelola foto dokumentasi kegiatan dari Gudang Media Library."
                        icon="images"
                    >
                        <x-slot:actions>
                            <button 
                                type="button" 
                                @click="openPhotosPicker()"
                                class="px-4 py-2 rounded-2xl bg-[#31725e] hover:bg-[#295c4d] text-white font-bold text-xs inline-flex items-center gap-2 transition-all shadow-md shadow-[#31725e]/20 cursor-pointer"
                            >
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Tambah Foto Kegiatan</span>
                            </button>
                        </x-slot:actions>

                        <div class="space-y-4">
                            <!-- Empty Photos State -->
                            <div 
                                x-show="photos.length === 0" 
                                class="p-8 rounded-3xl border-2 border-dashed border-[#99cab7]/60 bg-[#f2f8f5]/40 text-center flex flex-col items-center justify-center gap-3 transition-all"
                            >
                                <div class="w-14 h-14 rounded-3xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center">
                                    <i data-lucide="camera" class="w-7 h-7"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-[#1d3e35]">Belum ada foto kegiatan</h4>
                                    <p class="text-xs text-stone-400 mt-1 max-w-sm mx-auto">
                                        Klik tombol di atas untuk memilih foto dokumentasi kegiatan dari Media Library.
                                    </p>
                                </div>
                                <button 
                                    type="button" 
                                    @click="openPhotosPicker()"
                                    class="mt-2 px-4 py-2 rounded-2xl bg-white border border-[#99cab7] hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center gap-2 transition-colors cursor-pointer shadow-2xs"
                                >
                                    <i data-lucide="folder-search" class="w-4 h-4 text-[#31725e]"></i>
                                    <span>Buka Media Library</span>
                                </button>
                            </div>

                            <!-- Photos Grid List -->
                            <div x-show="photos.length > 0" class="space-y-3">
                                <div class="flex items-center justify-between px-1">
                                    <span class="text-xs font-bold text-[#295c4d]">
                                        Total Foto: <span x-text="photos.length" class="text-[#31725e]"></span> Foto
                                    </span>
                                    <span class="text-[11px] text-stone-400 italic">Tambahkan caption per foto untuk penjelasan</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <template x-for="(photo, index) in photos" :key="photo.media_id + '_' + index">
                                        <div class="relative group rounded-2xl bg-white border border-[#99cab7]/50 overflow-hidden shadow-2xs hover:shadow-md transition-all flex flex-col">
                                            <!-- Hidden Inputs for Form Submission -->
                                            <input type="hidden" :name="'photos[' + index + '][media_id]'" :value="photo.media_id" />

                                            <!-- Thumbnail Container -->
                                            <div class="relative w-full aspect-video bg-stone-100 overflow-hidden">
                                                <img :src="photo.url" :alt="photo.filename" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />

                                                <!-- Index Badge -->
                                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-xs text-white font-mono text-[10px] font-bold">
                                                    #<span x-text="index + 1"></span>
                                                </div>

                                                <!-- Action Buttons Overlay -->
                                                <div class="absolute top-2 right-2 flex items-center gap-1">
                                                    <button 
                                                        type="button" 
                                                        @click="removePhoto(index)"
                                                        class="p-1.5 rounded-xl bg-white/90 text-red-600 hover:bg-red-50 transition-colors shadow-md"
                                                        title="Hapus Foto"
                                                    >
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Caption Input -->
                                            <div class="p-3 bg-stone-50/80 border-t border-stone-100 flex-1 flex flex-col justify-between gap-1.5">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span class="text-[10px] font-bold text-stone-400 truncate max-w-[150px]" x-text="photo.filename"></span>
                                                </div>
                                                <input 
                                                    type="text" 
                                                    :name="'photos[' + index + '][caption]'" 
                                                    x-model="photo.caption" 
                                                    placeholder="Tulis keterangan foto (opsional)..."
                                                    class="w-full px-2.5 py-1.5 rounded-xl text-xs bg-white border border-stone-200 focus:border-[#31725e] outline-none text-[#1d3e35] placeholder:text-stone-400"
                                                />
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </x-admin.card>
                </div>

                <!-- ======================================================== -->
                <!-- RIGHT COLUMN: SIDEBAR (Compact & Accordion Cards)         -->
                <!-- ======================================================== -->
                <div class="md:col-span-4 space-y-4">
                    <!-- 1. Card Status Publikasi -->
                    <x-admin.card 
                        title="Publikasi" 
                        subtitle="Atur status penerbitan galeri kegiatan."
                        icon="send"
                        collapsible
                        :open="true"
                    >
                        <div class="space-y-5">
                            <!-- Status (TomSelect) -->
                            <div class="space-y-1.5">
                                <label for="status_select" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Status Publikasi
                                </label>
                                <select 
                                    name="status" 
                                    id="status_select"
                                    class="w-full"
                                >
                                    <option value="published" {{ old('status', $gallery->status) === 'published' ? 'selected' : '' }}>Terbitkan Langsung (Published)</option>
                                    <option value="draft" {{ old('status', $gallery->status) === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                                    <option value="archived" {{ old('status', $gallery->status) === 'archived' ? 'selected' : '' }}>Arsipkan</option>
                                </select>
                            </div>

                            <!-- Tanggal Publikasi -->
                            <x-form.input
                                type="datetime-local"
                                name="published_at"
                                label="Jadwal / Waktu Terbit"
                                :value="old('published_at', $gallery->published_at ? $gallery->published_at->format('Y-m-d\TH:i') : '')"
                            />
                        </div>
                    </x-admin.card>

                    <!-- 2. Card Foto Cover Utama (Media Library) -->
                    <x-admin.card 
                        title="Foto Cover Utama" 
                        subtitle="Pilih foto sampul utama untuk galeri kegiatan."
                        icon="image"
                        collapsible
                        :open="true"
                    >
                        <div class="space-y-4">
                            <!-- Preview Box -->
                            <div class="relative w-full aspect-video rounded-2xl bg-[#f2f8f5]/80 border-2 border-dashed border-[#99cab7]/60 overflow-hidden flex flex-col items-center justify-center p-2 group shadow-2xs">
                                <template x-if="thumbnailUrl">
                                    <div class="relative w-full h-full">
                                        <img :src="thumbnailUrl" alt="Cover Preview" class="w-full h-full object-cover rounded-xl" />
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl">
                                            <button 
                                                type="button" 
                                                @click="openThumbnailPicker()"
                                                class="p-2 rounded-xl bg-white text-stone-800 hover:bg-[#e2f0ea] transition-colors shadow-md"
                                                title="Ganti Foto Cover"
                                            >
                                                <i data-lucide="refresh-cw" class="w-4 h-4 text-[#31725e]"></i>
                                            </button>
                                            <button 
                                                type="button" 
                                                @click="removeThumbnail()"
                                                class="p-2 rounded-xl bg-white text-red-600 hover:bg-red-50 transition-colors shadow-md"
                                                title="Hapus Cover"
                                            >
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!thumbnailUrl">
                                    <div class="text-center p-4">
                                        <div class="w-12 h-12 rounded-2xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center mx-auto mb-2">
                                            <i data-lucide="image" class="w-6 h-6"></i>
                                        </div>
                                        <p class="text-xs font-bold text-[#1d3e35]">Belum ada cover utama</p>
                                        <p class="text-[11px] text-stone-400 mt-0.5">Ambil dari Gudang Media Library</p>
                                    </div>
                                </template>
                            </div>

                            <!-- Button Trigger Media Library Modal -->
                            <button 
                                type="button" 
                                @click="openThumbnailPicker()"
                                class="w-full py-2.5 px-4 rounded-2xl bg-white border border-[#99cab7]/60 hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center justify-center gap-2 transition-colors cursor-pointer shadow-2xs"
                            >
                                <i data-lucide="folder-search" class="w-4 h-4 text-[#31725e]"></i>
                                <span x-text="thumbnailUrl ? 'Ganti Cover dari Media Library' : 'Pilih Cover dari Media Library'"></span>
                            </button>
                        </div>
                    </x-admin.card>

                    <!-- 3. Card Optimasi SEO & SERP Snippet Preview -->
                    <x-admin.card 
                        title="Optimasi SEO & Meta Tags" 
                        subtitle="Pengaturan meta tags hasil pencarian Google."
                        icon="globe"
                        badge="Google SERP"
                        collapsible
                        :open="$errors->hasAny(['meta_title', 'meta_description', 'meta_keywords']) || old('meta_title') || !empty($gallery->meta_title) ? true : false"
                    >
                        <div class="space-y-5">
                            <!-- Live Google SERP Snippet Preview -->
                            <div class="p-3.5 rounded-2xl bg-white border border-stone-200 shadow-2xs space-y-1.5">
                                <div class="flex items-center gap-1.5 text-stone-400 text-[10px] font-semibold">
                                    <i data-lucide="globe" class="w-3.5 h-3.5 text-blue-500"></i>
                                    <span>Pratinjau Pencarian Google</span>
                                </div>
                                <div class="space-y-1 pt-1">
                                    <div class="text-[11px] text-stone-500 font-mono truncate">
                                        {{ url('/galleries') }}/{{ $gallery->slug }}
                                    </div>
                                    <h4 class="text-sm font-semibold text-[#1a0dab] hover:underline cursor-pointer truncate" x-text="metaTitle || title || '{{ addslashes($gallery->title) }}'"></h4>
                                    <p class="text-[11px] text-stone-600 line-clamp-2 leading-relaxed" x-text="metaDescription || description || '{{ addslashes($gallery->description ?? '') }}' || 'Dokumentasi foto kegiatan menarik seputar kegiatan dan acara...'"></p>
                                </div>
                            </div>

                            <!-- Meta Title -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="meta_title" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                        Meta Title
                                    </label>
                                    <span class="text-[10px] text-stone-400 font-mono" x-text="(metaTitle ? metaTitle.length : 0) + ' / 60 Karakter'"></span>
                                </div>
                                <input
                                    type="text"
                                    name="meta_title"
                                    id="meta_title"
                                    x-model="metaTitle"
                                    placeholder="Gunakan default judul kegiatan..."
                                    value="{{ old('meta_title', $gallery->meta_title) }}"
                                    class="w-full rounded-2xl p-3 text-xs text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                />
                                @error('meta_title')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <label for="meta_description" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                        Meta Description
                                    </label>
                                    <span class="text-[10px] text-stone-400 font-mono" x-text="(metaDescription ? metaDescription.length : 0) + ' / 160 Karakter'"></span>
                                </div>
                                <textarea
                                    name="meta_description"
                                    id="meta_description"
                                    x-model="metaDescription"
                                    rows="3"
                                    placeholder="Deskripsi ringkas yang muncul pada snippet hasil pencarian Google..."
                                    class="w-full rounded-2xl p-3 text-xs text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                >{{ old('meta_description', $gallery->meta_description) }}</textarea>
                                @error('meta_description')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Meta Keywords -->
                            <div class="space-y-1.5">
                                <label for="meta_keywords" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Meta Keywords
                                </label>
                                <input
                                    type="text"
                                    name="meta_keywords"
                                    id="meta_keywords"
                                    placeholder="kegiatan, dokumentasi, foto acara..."
                                    value="{{ old('meta_keywords', $gallery->meta_keywords) }}"
                                    class="w-full rounded-2xl p-3 text-xs text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                />
                            </div>
                        </div>
                    </x-admin.card>
                </div>
            </div>
        </form>

        <!-- Media Picker Modal Component -->
        <x-admin.media-picker-modal />
    </div>

    <!-- Script Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.TomSelect) {
                new window.TomSelect('#status_select', {
                    create: false,
                    controlInput: null,
                    placeholder: 'Pilih Status Publikasi',
                });
            }
        });

        function galleryEditFormHandler(initialPhotos = []) {
            return {
                title: "{{ old('title', addslashes($gallery->title)) }}",
                description: "{{ old('description', addslashes($gallery->description ?? '')) }}",
                metaTitle: "{{ old('meta_title', addslashes($gallery->meta_title ?? '')) }}",
                metaDescription: "{{ old('meta_description', addslashes($gallery->meta_description ?? '')) }}",
                thumbnailMediaId: "{{ old('thumbnail_media_id', $gallery->thumbnail_media_id ?? '') }}",
                thumbnailUrl: "{{ old('thumbnail_url', $gallery->getThumbnail() ?? '') }}",
                removeThumbnailFlag: false,
                photos: initialPhotos || [],

                openThumbnailPicker() {
                    window.dispatchEvent(new CustomEvent('open-media-picker', {
                        detail: { targetField: 'gallery_thumbnail', multiple: false }
                    }));
                },

                openPhotosPicker() {
                    window.dispatchEvent(new CustomEvent('open-media-picker', {
                        detail: { targetField: 'gallery_photos', multiple: true }
                    }));
                },

                removeThumbnail() {
                    this.thumbnailMediaId = '';
                    this.thumbnailUrl = '';
                    this.removeThumbnailFlag = true;
                },

                removePhoto(index) {
                    this.photos.splice(index, 1);
                    setTimeout(() => { if (window.refreshIcons) window.refreshIcons(); }, 50);
                },

                init() {
                    window.addEventListener('media-selected', (event) => {
                        const detail = event.detail;
                        if (!detail) return;

                        if (detail.targetField === 'gallery_thumbnail' && detail.media) {
                            this.thumbnailMediaId = detail.media.id;
                            this.thumbnailUrl = detail.media.url;
                            this.removeThumbnailFlag = false;
                        } else if (detail.targetField === 'gallery_photos') {
                            const newItems = detail.multiple ? (detail.items || []) : (detail.media ? [detail.media] : []);
                            
                            newItems.forEach(item => {
                                const exists = this.photos.some(p => p.media_id === item.id);
                                if (!exists) {
                                    this.photos.push({
                                        media_id: item.id,
                                        url: item.url,
                                        filename: item.name || 'Foto Kegiatan',
                                        caption: '',
                                    });
                                }
                            });

                            setTimeout(() => { if (window.refreshIcons) window.refreshIcons(); }, 100);
                        }
                    });
                }
            };
        }
    </script>
</x-layouts.admin>
