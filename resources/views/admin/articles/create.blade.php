<x-layouts.admin title="Tulis Artikel Baru">
    <x-admin.breadcrumb 
        title="Tulis Artikel Baru" 
        :items="[
            'Article SEO' => '',
            'Article' => route('admin.articles.index'),
            'Tulis Artikel' => ''
        ]" 
    />

    <div class="space-y-6" x-data="articleFormHandler()">
        <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Hidden Inputs for Thumbnail from Media Library -->
            <input type="hidden" name="thumbnail_media_id" :value="thumbnailMediaId" />
            <input type="hidden" name="thumbnail_url" :value="thumbnailUrl" />

            <!-- Top Action Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs">
                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('admin.articles.index') }}" 
                        class="p-2.5 rounded-2xl bg-stone-100 text-stone-600 hover:bg-stone-200 transition-colors"
                        title="Kembali ke Daftar Artikel"
                    >
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </a>
                    <div>
                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Tulis Artikel Baru</span>
                        <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">Form Pembuatan Konten SEO</h3>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a 
                        href="{{ route('admin.articles.index') }}" 
                        class="px-4 py-2.5 rounded-2xl border border-stone-200 bg-white hover:bg-stone-50 text-stone-600 font-bold text-xs transition-colors"
                    >
                        Batal
                    </a>

                    <button 
                        type="submit" 
                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all cursor-pointer"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Simpan & Buat Sitemap.xml</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full">
                <!-- ==================================================== -->
                <!-- LEFT COLUMN: PURE ARTICLE CONTENT (Dominant / Wide)  -->
                <!-- ==================================================== -->
                <div class="md:col-span-8 space-y-6">
                    <x-admin.card 
                        title="Tulis Artikel" 
                        subtitle="Fokus menulis judul, ringkasan, dan isi lengkap artikel. Slug URL akan dibuat secara otomatis di sistem."
                    >
                        <div class="space-y-6">
                            <!-- Judul Artikel -->
                            <div class="space-y-2">
                                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Judul Artikel <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    x-model="title"
                                    placeholder="Masukkan judul artikel yang menarik dan informatif..."
                                    value="{{ old('title') }}"
                                    class="w-full rounded-2xl p-4 text-lg font-extrabold text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none shadow-2xs"
                                    required
                                    autofocus
                                />
                                @error('title')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Excerpt / Ringkasan Singkat -->
                            <div class="space-y-2">
                                <label for="excerpt" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Ringkasan / Excerpt (Opsional)
                                </label>
                                <textarea
                                    name="excerpt"
                                    id="excerpt"
                                    x-model="excerpt"
                                    rows="3"
                                    placeholder="Ringkasan singkat isi artikel untuk pengantar & lead paragraph..."
                                    class="w-full rounded-2xl p-3.5 text-sm text-[#1d3e35] placeholder:text-[#99cab7]/70 transition-all duration-200 border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-4 focus:ring-[#428e75]/20 bg-white/80 hover:bg-white outline-none"
                                >{{ old('excerpt') }}</textarea>
                                @error('excerpt')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Isi Konten Artikel Lengkap dengan TipTap -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label for="content" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                        Isi Artikel Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-stone-400 font-medium">Editor Visual (TipTap)</span>
                                </div>
                                <x-form.tiptap-editor
                                    name="content"
                                    id="content"
                                    :value="old('content')"
                                    placeholder="Mulai menulis isi konten artikel lengkap di sini..."
                                />
                                @error('content')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
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
                        subtitle="Atur status penerbitan artikel."
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
                                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Terbitkan Langsung (Published)</option>
                                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Arsipkan</option>
                                </select>
                            </div>

                            <!-- Tanggal Publikasi -->
                            <x-form.input
                                type="datetime-local"
                                name="published_at"
                                label="Jadwal / Waktu Terbit"
                                :value="old('published_at', now()->format('Y-m-d\TH:i'))"
                            />
                        </div>
                    </x-admin.card>

                    <!-- 2. Card Gambar Thumbnail (Media Library) -->
                    <x-admin.card 
                        title="Gambar Thumbnail" 
                        subtitle="Pilih thumbnail artikel dari Media Library."
                        icon="image"
                        collapsible
                        :open="true"
                    >
                        <div class="space-y-4">
                            <!-- Preview Box -->
                            <div class="relative w-full aspect-video rounded-2xl bg-[#f2f8f5]/80 border-2 border-dashed border-[#99cab7]/60 overflow-hidden flex flex-col items-center justify-center p-2 group shadow-2xs">
                                <template x-if="thumbnailUrl">
                                    <div class="relative w-full h-full">
                                        <img :src="thumbnailUrl" alt="Thumbnail Preview" class="w-full h-full object-cover rounded-xl" />
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl">
                                            <button 
                                                type="button" 
                                                @click="openMediaPicker()"
                                                class="p-2 rounded-xl bg-white text-stone-800 hover:bg-[#e2f0ea] transition-colors shadow-md"
                                                title="Ganti Gambar"
                                            >
                                                <i data-lucide="refresh-cw" class="w-4 h-4 text-[#31725e]"></i>
                                            </button>
                                            <button 
                                                type="button" 
                                                @click="removeThumbnail()"
                                                class="p-2 rounded-xl bg-white text-red-600 hover:bg-red-50 transition-colors shadow-md"
                                                title="Hapus Thumbnail"
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
                                        <p class="text-xs font-bold text-[#1d3e35]">Belum ada thumbnail dipilih</p>
                                        <p class="text-[11px] text-stone-400 mt-0.5">Ambil dari Gudang Media Library</p>
                                    </div>
                                </template>
                            </div>

                            <!-- Button Trigger Media Library Modal -->
                            <button 
                                type="button" 
                                @click="openMediaPicker()"
                                class="w-full py-2.5 px-4 rounded-2xl bg-white border border-[#99cab7]/60 hover:bg-[#e2f0ea] text-[#1d3e35] font-bold text-xs inline-flex items-center justify-center gap-2 transition-colors cursor-pointer shadow-2xs"
                            >
                                <i data-lucide="folder-search" class="w-4 h-4 text-[#31725e]"></i>
                                <span x-text="thumbnailUrl ? 'Ganti dari Media Library' : 'Pilih dari Media Library'"></span>
                            </button>
                        </div>
                    </x-admin.card>

                    <!-- 3. Card Kategori & Tag -->
                    <x-admin.card 
                        title="Kategori & Tag" 
                        subtitle="Klasifikasi artikel untuk navigasi & struktur sitemap."
                        icon="tags"
                        collapsible
                        :open="true"
                        class="relative z-10"
                    >
                        <div class="space-y-5">
                            <!-- Kategori Selector (TomSelect) -->
                            <div class="space-y-1.5">
                                <label for="category_select" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Kategori Artikel
                                </label>
                                <select 
                                    name="category_id" 
                                    id="category_select"
                                    class="w-full"
                                >
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                             {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tag Multi-select (TomSelect) -->
                            <div class="space-y-1.5">
                                <label for="tags_select" class="block text-xs font-bold uppercase tracking-wider text-[#295c4d]">
                                    Tag Artikel
                                </label>
                                <select 
                                    name="tags[]" 
                                    id="tags_select" 
                                    multiple 
                                    placeholder="Pilih atau ketik tag baru..."
                                    class="w-full"
                                >
                                    @foreach($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-stone-400">Tekan Enter untuk membuat label tag baru.</p>
                            </div>
                        </div>
                    </x-admin.card>

                    <!-- 4. Card Optimasi SEO & SERP Snippet Preview -->
                    <x-admin.card 
                        title="Optimasi SEO & Meta Tags" 
                        subtitle="Pengaturan meta tags untuk Search Engine Google."
                        icon="globe"
                        badge="Google SERP"
                        collapsible
                        :open="$errors->hasAny(['meta_title', 'meta_description', 'meta_keywords']) || old('meta_title') || old('meta_description') ? true : false"
                    >
                        <div class="space-y-5">
                            <!-- Live Google SERP Snippet Preview -->
                            <div class="p-3.5 rounded-2xl bg-white border border-stone-200 shadow-2xs space-y-1.5">
                                <div class="flex items-center gap-1.5 text-stone-400 text-[10px] font-semibold">
                                    <i data-lucide="globe" class="w-3.5 h-3.5 text-blue-500"></i>
                                    <span>Pratinjau Hasil Pencarian Google</span>
                                </div>
                                <div class="space-y-1 pt-1">
                                    <div class="text-[11px] text-stone-500 font-mono truncate">
                                        {{ url('/articles') }}/<span x-text="computedSlug || 'judul-artikel-anda'"></span>
                                    </div>
                                    <h4 class="text-sm font-semibold text-[#1a0dab] hover:underline cursor-pointer truncate" x-text="metaTitle || title || 'Judul Artikel Anda - ' + '{{ config('app.name') }}'"></h4>
                                    <p class="text-[11px] text-stone-600 line-clamp-2 leading-relaxed" x-text="metaDescription || excerpt || 'Deskripsi meta artikel akan muncul di sini sebagai cuplikan ringkas pencarian search engine Google...'"></p>
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
                                    placeholder="Gunakan default judul utama..."
                                    value="{{ old('meta_title') }}"
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
                                >{{ old('meta_description') }}</textarea>
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
                                    placeholder="keyword 1, keyword 2, topik..."
                                    value="{{ old('meta_keywords') }}"
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

    <!-- TomSelect Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.TomSelect) {
                new window.TomSelect('#category_select', {
                    create: false,
                    placeholder: '-- Pilih Kategori --',
                    allowEmptyOption: true,
                });

                new window.TomSelect('#status_select', {
                    create: false,
                    controlInput: null,
                    placeholder: 'Pilih Status Publikasi',
                });

                new window.TomSelect('#tags_select', {
                    create: true,
                    persist: false,
                    plugins: ['remove_button'],
                    placeholder: 'Pilih atau ketik tag baru...',
                });
            }
        });

        function articleFormHandler() {
            return {
                title: "{{ old('title', '') }}",
                excerpt: "{{ old('excerpt', '') }}",
                metaTitle: "{{ old('meta_title', '') }}",
                metaDescription: "{{ old('meta_description', '') }}",
                thumbnailMediaId: "{{ old('thumbnail_media_id', '') }}",
                thumbnailUrl: "{{ old('thumbnail_url', '') }}",

                get computedSlug() {
                    if (!this.title) return '';
                    return this.title
                        .toLowerCase()
                        .trim()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                },

                openMediaPicker() {
                    window.dispatchEvent(new CustomEvent('open-media-picker', {
                        detail: { targetField: 'article_thumbnail' }
                    }));
                },

                removeThumbnail() {
                    this.thumbnailMediaId = '';
                    this.thumbnailUrl = '';
                },

                init() {
                    window.addEventListener('media-selected', (event) => {
                        if (event.detail && event.detail.targetField === 'article_thumbnail' && event.detail.media) {
                            this.thumbnailMediaId = event.detail.media.id;
                            this.thumbnailUrl = event.detail.media.url;
                        }
                    });
                }
            };
        }
    </script>
</x-layouts.admin>
