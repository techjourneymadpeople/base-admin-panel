<x-layouts.admin title="Detail Galeri: {{ $gallery->title }}">
    <x-admin.breadcrumb 
        title="Detail Kegiatan" 
        :items="[
            'Article SEO' => '',
            'Gallery Activity' => route('admin.gallery-activities.index'),
            $gallery->title => ''
        ]" 
    />

    <div class="space-y-6" x-data="{ selectedPhotoModal: null }">
        <!-- Top Action Bar -->
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
                    <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider">Detail Foto Kegiatan</span>
                    <h3 class="text-base font-extrabold text-[#1d3e35] line-clamp-1">{{ $gallery->title }}</h3>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a 
                    href="{{ route('admin.gallery-activities.edit', $gallery->id) }}" 
                    class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/20 transition-all"
                >
                    <i data-lucide="pencil" class="w-4 h-4 text-[#cca06e]"></i>
                    <span>Edit Kegiatan</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Main Detail & Photo Gallery Grid (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- 1. Header Banner & Info Kegiatan -->
                <div class="rounded-3xl bg-white border border-[#99cab7]/40 overflow-hidden shadow-2xs">
                    @if($gallery->getThumbnail())
                        <div class="relative w-full h-64 sm:h-80 bg-stone-900">
                            <img 
                                src="{{ $gallery->getThumbnail() }}" 
                                alt="{{ $gallery->title }}" 
                                class="w-full h-full object-cover"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                            
                            <div class="absolute bottom-6 left-6 right-6 text-white space-y-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/90 text-white">
                                        {{ ucfirst($gallery->status) }}
                                    </span>
                                    @if($gallery->activity_date)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md text-white flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            {{ $gallery->activity_date->translatedFormat('d F Y') }}
                                        </span>
                                    @endif
                                    @if($gallery->location)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md text-white flex items-center gap-1">
                                            <i data-lucide="map-pin" class="w-3 h-3 text-[#cca06e]"></i>
                                            {{ $gallery->location }}
                                        </span>
                                    @endif
                                </div>
                                <h1 class="text-xl sm:text-2xl font-black leading-tight">{{ $gallery->title }}</h1>
                            </div>
                        </div>
                    @else
                        <div class="p-6 sm:p-8 space-y-3 bg-[#f2f8f5]/40 border-b border-stone-100">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($gallery->status) }}
                                </span>
                                @if($gallery->activity_date)
                                    <span class="text-xs font-bold text-[#295c4d] flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#31725e]"></i>
                                        {{ $gallery->activity_date->translatedFormat('d F Y') }}
                                    </span>
                                @endif
                                @if($gallery->location)
                                    <span class="text-xs font-bold text-stone-500 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-[#cca06e]"></i>
                                        {{ $gallery->location }}
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-xl sm:text-2xl font-black text-[#1d3e35] leading-tight">{{ $gallery->title }}</h1>
                        </div>
                    @endif

                    @if($gallery->description)
                        <div class="p-6 sm:p-8 space-y-3">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[#295c4d]">Deskripsi Acara</h3>
                            <div class="text-sm text-stone-700 leading-relaxed whitespace-pre-line bg-[#f2f8f5]/30 p-4 rounded-2xl border border-[#99cab7]/30">
                                {{ $gallery->description }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 2. Galeri Foto Dokumentasi Kegiatan -->
                <x-admin.card 
                    title="Foto-Foto Dokumentasi Kegiatan" 
                    subtitle="Kumpulan foto yang diunggah untuk kegiatan ini."
                    icon="images"
                    badge="{{ $gallery->photos->count() }} Foto"
                >
                    @if($gallery->photos->isEmpty())
                        <div class="py-12 text-center text-stone-400">
                            <i data-lucide="image-off" class="w-10 h-10 mx-auto text-stone-300 mb-2"></i>
                            <p class="text-xs font-semibold">Belum ada foto yang ditambahkan ke galeri ini.</p>
                            <a href="{{ route('admin.gallery-activities.edit', $gallery->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#31725e] mt-2 hover:underline">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambahkan foto sekarang
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($gallery->photos as $index => $photo)
                                <div 
                                    @click="selectedPhotoModal = { url: '{{ $photo->getUrl() }}', caption: '{{ addslashes($photo->caption ?? '') }}', index: {{ $index + 1 }} }"
                                    class="group relative rounded-2xl bg-white border border-stone-200 overflow-hidden shadow-2xs hover:shadow-md transition-all cursor-pointer flex flex-col"
                                >
                                    <div class="relative w-full aspect-4/3 bg-stone-100 overflow-hidden">
                                        <img 
                                            src="{{ $photo->getUrl() }}" 
                                            alt="{{ $photo->caption ?? $gallery->title }}" 
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                            loading="lazy"
                                        />
                                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <div class="w-10 h-10 rounded-full bg-white/90 text-stone-800 flex items-center justify-center shadow-lg">
                                                <i data-lucide="zoom-in" class="w-5 h-5"></i>
                                            </div>
                                        </div>

                                        <div class="absolute top-2 left-2 px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-xs text-white font-mono text-[10px] font-bold">
                                            #{{ $index + 1 }}
                                        </div>
                                    </div>

                                    @if($photo->caption)
                                        <div class="p-3 bg-white border-t border-stone-100">
                                            <p class="text-xs text-stone-700 line-clamp-2 font-medium">{{ $photo->caption }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-admin.card>
            </div>

            <!-- Right Column: Metadata & SEO Status (4 cols) -->
            <div class="lg:col-span-4 space-y-4">
                <!-- 1. Metadata Publikasi -->
                <x-admin.card title="Status & Info" icon="info">
                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-stone-100">
                            <span class="text-stone-500">Status</span>
                            <span class="font-bold text-emerald-700 capitalize">{{ $gallery->status }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-stone-100">
                            <span class="text-stone-500">Penulis</span>
                            <span class="font-bold text-stone-800">{{ $gallery->author ? $gallery->author->name : 'Sistem' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-stone-100">
                            <span class="text-stone-500">Tanggal Terbit</span>
                            <span class="font-bold text-stone-800">{{ $gallery->published_at ? $gallery->published_at->translatedFormat('d M Y, H:i') : '—' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-stone-100">
                            <span class="text-stone-500">Dibuat Pada</span>
                            <span class="font-bold text-stone-800">{{ $gallery->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-stone-500">Total Views</span>
                            <span class="font-bold text-stone-800 flex items-center gap-1">
                                <i data-lucide="eye" class="w-3.5 h-3.5 text-stone-400"></i>
                                {{ number_format($gallery->views_count) }}
                            </span>
                        </div>
                    </div>
                </x-admin.card>

                <!-- 2. Google SERP Preview Card -->
                <x-admin.card title="Optimasi SEO Google" icon="globe" badge="SERP Preview">
                    <div class="space-y-4">
                        <div class="p-3.5 rounded-2xl bg-white border border-stone-200 shadow-2xs space-y-1">
                            <div class="text-[11px] text-stone-500 font-mono truncate">
                                {{ url('/galleries') }}/{{ $gallery->slug }}
                            </div>
                            <h4 class="text-sm font-semibold text-[#1a0dab] hover:underline cursor-pointer truncate">
                                {{ $gallery->meta_title ?: $gallery->title . ' - ' . config('app.name') }}
                            </h4>
                            <p class="text-[11px] text-stone-600 line-clamp-2 leading-relaxed">
                                {{ $gallery->meta_description ?: ($gallery->description ?: 'Dokumentasi foto kegiatan menarik seputar kegiatan dan acara...') }}
                            </p>
                        </div>

                        <div class="space-y-2 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="text-stone-500">Canonical URL</span>
                                <span class="font-mono text-[10px] text-stone-600 truncate max-w-[170px]">{{ $gallery->getCanonicalUrl() }}</span>
                            </div>
                            @if($gallery->meta_keywords)
                                <div class="space-y-1">
                                    <span class="text-stone-500 text-[11px]">Keywords</span>
                                    <p class="font-medium text-stone-800 bg-stone-50 p-2 rounded-xl border border-stone-100">{{ $gallery->meta_keywords }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-admin.card>
            </div>
        </div>

        <!-- Photo Lightbox Zoom Modal -->
        <div 
            x-show="selectedPhotoModal" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto bg-black/90 backdrop-blur-md flex items-center justify-center p-4"
            x-cloak
            @keydown.escape.window="selectedPhotoModal = null"
        >
            <button 
                type="button" 
                @click="selectedPhotoModal = null"
                class="absolute top-4 right-4 p-2.5 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors z-10"
            >
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

            <div class="relative max-w-4xl max-h-[90vh] flex flex-col items-center justify-center" @click.outside="selectedPhotoModal = null">
                <template x-if="selectedPhotoModal">
                    <div class="space-y-3 text-center">
                        <img 
                            :src="selectedPhotoModal.url" 
                            alt="Zoom Preview" 
                            class="max-h-[75vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl mx-auto"
                        />
                        <template x-if="selectedPhotoModal.caption">
                            <p class="text-white text-sm font-medium bg-black/60 px-4 py-2 rounded-xl backdrop-blur-md max-w-xl mx-auto" x-text="selectedPhotoModal.caption"></p>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-layouts.admin>
