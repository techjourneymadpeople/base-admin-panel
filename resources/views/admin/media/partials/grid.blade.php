@if($mediaItems->isEmpty())
    <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-[#99cab7]/30 p-8 space-y-3 shadow-2xs">
        <div class="w-16 h-16 rounded-2xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center mx-auto text-2xl">
            <i data-lucide="image-off" class="w-8 h-8"></i>
        </div>
        <h3 class="text-base font-extrabold text-[#1d3e35]">Tidak Ada Gambar Ditemukan</h3>
        <p class="text-xs text-stone-500 max-w-md mx-auto">
            Belum ada file media yang tersimpan di gudang media atau sesuai filter format Anda.
        </p>
        <button 
            type="button" 
            @click="openUploadStudio()"
            class="px-5 py-2.5 rounded-2xl bg-[#1d3e35] text-white font-bold text-xs inline-flex items-center gap-2 shadow-md hover:bg-[#31725e] transition-all cursor-pointer"
        >
            <i data-lucide="upload-cloud" class="w-4 h-4 text-[#cca06e]"></i>
            <span>Upload Gambar Sekarang</span>
        </button>
    </div>
@else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($mediaItems as $media)
            @php
                $ext = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
                $formatStyles = [
                    'webp' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    'png' => 'bg-blue-50 text-blue-800 border-blue-200',
                    'jpg' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'jpeg' => 'bg-amber-50 text-amber-800 border-amber-200',
                    'svg' => 'bg-purple-50 text-purple-800 border-purple-200',
                ];
                $formatBadge = $formatStyles[$ext] ?? 'bg-stone-100 text-stone-700 border-stone-200';
                $sizeKb = round($media->size / 1024, 1);
                $width = $media->getCustomProperty('width');
                $height = $media->getCustomProperty('height');
                $dimensions = ($width && $height) ? "{$width}x{$height} px" : ($ext === 'svg' ? 'Vector SVG' : '—');
                $url = $media->getUrl();
                $isInUse = $media->getCustomProperty('is_in_use', false) || ($media->getCustomProperty('usages_count', 0) > 0);
            @endphp

            <div class="group relative rounded-2xl bg-white border border-[#99cab7]/30 overflow-hidden shadow-2xs hover:shadow-lg transition-all duration-200 flex flex-col justify-between">
                <!-- Thumbnail Preview Container -->
                <div class="relative aspect-square w-full bg-stone-100/70 overflow-hidden flex items-center justify-center p-2 cursor-pointer"
                    @click="openDetail({
                        id: '{{ $media->id }}',
                        name: '{{ addslashes($media->name) }}',
                        file_name: '{{ addslashes($media->file_name) }}',
                        url: '{{ $url }}',
                        cropUrl: '{{ route('admin.media.crop', $media->id) }}',
                        originalWidth: {{ $width ?? 800 }},
                        originalHeight: {{ $height ?? 600 }},
                        mime_type: '{{ $media->mime_type }}',
                        size_kb: '{{ $sizeKb }} KB',
                        dimensions: '{{ $dimensions }}',
                        created_at: '{{ $media->created_at ? $media->created_at->translatedFormat('d M Y, H:i') : '-' }}',
                        is_in_use: {{ $isInUse ? 'true' : 'false' }},
                        is_svg: {{ $ext === 'svg' ? 'true' : 'false' }}
                    })"
                >
                    <img 
                        src="{{ $url }}" 
                        alt="{{ $media->name }}" 
                        loading="lazy"
                        class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                    />

                    <!-- Top Format Badge -->
                    <div class="absolute top-2 left-2 flex items-center gap-1">
                        <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider border shadow-2xs {{ $formatBadge }}">
                            {{ $ext }}
                        </span>
                        @if($isInUse)
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-2xs flex items-center gap-0.5" title="Gambar sedang digunakan">
                                <i data-lucide="link-2" class="w-3 h-3 text-amber-700"></i>
                            </span>
                        @endif
                    </div>

                    <!-- Size Badge -->
                    <div class="absolute bottom-2 right-2 px-1.5 py-0.5 rounded-md text-[10px] font-mono font-bold bg-black/60 text-white backdrop-blur-xs">
                        {{ $sizeKb }} KB
                    </div>
                </div>

                <!-- Card Body & Action Buttons -->
                <div class="p-2.5 bg-white border-t border-stone-100 space-y-2">
                    <div>
                        <p class="text-xs font-bold text-[#1d3e35] truncate" title="{{ $media->name }}">
                            {{ $media->name }}
                        </p>
                        <p class="text-[10px] font-mono text-stone-400 truncate">
                            {{ $dimensions }}
                        </p>
                    </div>

                    <!-- Prominent Crop & Resize Action Button -->
                    @if($ext !== 'svg')
                        <button 
                            type="button" 
                            @click.stop="openEditStudio({
                                id: '{{ $media->id }}',
                                name: '{{ addslashes($media->name) }}',
                                url: '{{ $url }}',
                                cropUrl: '{{ route('admin.media.crop', $media->id) }}',
                                originalWidth: {{ $width ?? 800 }},
                                originalHeight: {{ $height ?? 600 }},
                                isInUse: {{ $isInUse ? 'true' : 'false' }}
                            })"
                            class="w-full py-1.5 px-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-[11px] inline-flex items-center justify-center gap-1.5 border border-amber-200/80 transition-colors cursor-pointer"
                            title="Buka Kanvas Crop, Resize, dan Konversi ke WebP"
                        >
                            <i data-lucide="crop" class="w-3.5 h-3.5 text-[#b17042]"></i>
                            <span>Crop & Resize WebP</span>
                        </button>
                    @endif

                    <!-- Secondary Quick Actions -->
                    <div class="flex items-center justify-between gap-1 pt-1 border-t border-stone-100">
                        <button 
                            type="button" 
                            @click.stop="copyToClipboard('{{ $url }}')"
                            class="p-1.5 rounded-lg text-stone-400 hover:text-[#31725e] hover:bg-[#e2f0ea] transition-colors cursor-pointer"
                            title="Salin URL Publik Gambar"
                        >
                            <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                        </button>

                        <button 
                            type="button" 
                            @click.stop="openDetail({
                                id: '{{ $media->id }}',
                                name: '{{ addslashes($media->name) }}',
                                file_name: '{{ addslashes($media->file_name) }}',
                                url: '{{ $url }}',
                                cropUrl: '{{ route('admin.media.crop', $media->id) }}',
                                originalWidth: {{ $width ?? 800 }},
                                originalHeight: {{ $height ?? 600 }},
                                mime_type: '{{ $media->mime_type }}',
                                size_kb: '{{ $sizeKb }} KB',
                                dimensions: '{{ $dimensions }}',
                                created_at: '{{ $media->created_at ? $media->created_at->translatedFormat('d M Y, H:i') : '-' }}',
                                is_in_use: {{ $isInUse ? 'true' : 'false' }},
                                is_svg: {{ $ext === 'svg' ? 'true' : 'false' }}
                            })"
                            class="p-1.5 rounded-lg text-stone-400 hover:text-[#1d3e35] hover:bg-stone-100 transition-colors cursor-pointer"
                            title="Lihat Detail & Info"
                        >
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        </button>

                        <button 
                            type="button" 
                            @click.stop="openDeleteModal({
                                id: '{{ $media->id }}',
                                name: '{{ addslashes($media->name) }}',
                                url: '{{ route('admin.media.destroy', $media->id) }}',
                                isInUse: {{ $isInUse ? 'true' : 'false' }}
                            })"
                            class="p-1.5 rounded-lg text-stone-400 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer"
                            title="Hapus Gambar"
                        >
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $mediaItems->links() }}
    </div>
@endif
