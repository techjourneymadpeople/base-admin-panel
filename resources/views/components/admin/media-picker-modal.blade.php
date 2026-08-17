<!-- Media Library Picker Modal Component -->
<div 
    x-data="mediaPickerModal()"
    @open-media-picker.window="openPicker($event.detail)"
    x-show="isOpen"
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true"
    x-cloak
>
    <!-- Background Backdrop -->
    <div 
        x-show="isOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-stone-900/60 backdrop-blur-xs transition-opacity"
        @click="closePicker()"
    ></div>

    <div class="flex min-h-full items-center justify-center p-3 sm:p-4 text-center">
        <!-- Modal Panel -->
        <div 
            x-show="isOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-[#99cab7]/40 flex flex-col max-h-[90vh]"
        >
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-stone-100 bg-[#f2f8f5]/60 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[#31725e] text-white flex items-center justify-center shadow-md shadow-[#31725e]/20">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-[#1d3e35]" id="modal-title">
                            Pilih Gambar dari Media Library
                        </h3>
                        <p class="text-xs text-stone-500">
                            Pilih gambar yang sudah ada atau unggah langsung ke Gudang Media
                        </p>
                    </div>
                </div>

                <button 
                    type="button" 
                    @click="closePicker()"
                    class="p-2 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 transition-colors"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Tab Switcher (Galeri / Unggah Baru) -->
            <div class="px-6 pt-3 pb-2 border-b border-stone-100 flex items-center justify-between gap-4 flex-wrap bg-white shrink-0">
                <div class="flex items-center gap-2">
                    <button 
                        type="button"
                        @click="activeTab = 'gallery'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
                        :class="activeTab === 'gallery' ? 'bg-[#31725e] text-white shadow-sm' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                    >
                        <i data-lucide="grid" class="w-4 h-4"></i>
                        <span>Gudang Media (<span x-text="totalItems"></span>)</span>
                    </button>

                    <button 
                        type="button"
                        @click="activeTab = 'upload'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
                        :class="activeTab === 'upload' ? 'bg-[#31725e] text-white shadow-sm' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
                    >
                        <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                        <span>Unggah Baru</span>
                    </button>
                </div>

                <!-- Search & Filters (Only on gallery tab) -->
                <div x-show="activeTab === 'gallery'" class="flex items-center gap-2 flex-1 max-w-sm ml-auto">
                    <div class="relative w-full">
                        <i data-lucide="search" class="w-4 h-4 text-stone-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input 
                            type="text" 
                            x-model.debounce.300ms="searchQuery" 
                            @input="fetchMedia(1)"
                            placeholder="Cari file gambar..."
                            class="w-full pl-9 pr-3 py-1.5 rounded-xl text-xs border border-stone-200 focus:border-[#31725e] focus:ring-2 focus:ring-[#31725e]/20 outline-none"
                        />
                    </div>
                </div>
            </div>

            <!-- Modal Content Body -->
            <div class="p-6 overflow-y-auto flex-1 scrollbar-thin">
                <!-- Tab 1: Media Gallery Grid -->
                <div x-show="activeTab === 'gallery'">
                    <!-- Loading State -->
                    <div x-show="isLoading" class="py-16 text-center text-stone-400 flex flex-col items-center justify-center gap-3">
                        <div class="w-8 h-8 border-3 border-[#31725e] border-t-transparent rounded-full animate-spin"></div>
                        <span class="text-xs font-semibold">Memuat gambar dari media library...</span>
                    </div>

                    <!-- Empty State -->
                    <div x-show="!isLoading && mediaList.length === 0" class="py-16 text-center text-stone-400 flex flex-col items-center justify-center gap-3">
                        <div class="w-14 h-14 rounded-2xl bg-stone-100 flex items-center justify-center text-stone-300">
                            <i data-lucide="image-off" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-stone-600">Belum ada gambar yang ditemukan</p>
                            <p class="text-xs text-stone-400 mt-0.5">Unggah gambar baru atau sesuaikan kata kunci pencarian</p>
                        </div>
                    </div>

                    <!-- Grid of Images -->
                    <div x-show="!isLoading && mediaList.length > 0" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        <template x-for="item in mediaList" :key="item.id">
                            <div 
                                @click="selectMedia(item)"
                                class="group relative rounded-2xl overflow-hidden border-2 cursor-pointer transition-all duration-200 aspect-square flex flex-col bg-stone-50"
                                :class="selectedItem && selectedItem.id === item.id ? 'border-[#31725e] ring-4 ring-[#31725e]/20 shadow-md' : 'border-stone-200 hover:border-[#99cab7] hover:shadow-sm'"
                            >
                                <img 
                                    :src="item.url" 
                                    :alt="item.name" 
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    loading="lazy"
                                />

                                <!-- Selected Checkmark Overlay -->
                                <div 
                                    x-show="selectedItem && selectedItem.id === item.id" 
                                    class="absolute top-2 right-2 w-6 h-6 rounded-full bg-[#31725e] text-white flex items-center justify-center shadow-md"
                                >
                                    <i data-lucide="check" class="w-3.5 h-3.5 stroke-[3]"></i>
                                </div>

                                <!-- Info Hover Overlay -->
                                <div class="absolute inset-x-0 bottom-0 p-2 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end text-white">
                                    <span class="text-[10px] font-bold truncate leading-tight" x-text="item.name"></span>
                                    <span class="text-[9px] text-[#c5e1d5] font-mono mt-0.5" x-text="item.size_formatted"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Pagination -->
                    <div x-show="!isLoading && totalPages > 1" class="flex items-center justify-between pt-6 border-t border-stone-100 mt-6">
                        <span class="text-xs text-stone-500 font-medium">
                            Halaman <span x-text="currentPage" class="font-bold text-[#1d3e35]"></span> dari <span x-text="totalPages" class="font-bold text-[#1d3e35]"></span>
                        </span>

                        <div class="flex items-center gap-2">
                            <button 
                                type="button" 
                                @click="fetchMedia(currentPage - 1)" 
                                :disabled="currentPage <= 1"
                                class="px-3 py-1.5 rounded-xl border border-stone-200 text-xs font-semibold text-stone-600 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed"
                            >
                                Sebelumnya
                            </button>
                            <button 
                                type="button" 
                                @click="fetchMedia(currentPage + 1)" 
                                :disabled="currentPage >= totalPages"
                                class="px-3 py-1.5 rounded-xl border border-stone-200 text-xs font-semibold text-stone-600 hover:bg-stone-50 disabled:opacity-40 disabled:cursor-not-allowed"
                            >
                                Selanjutnya
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Direct Upload -->
                <div x-show="activeTab === 'upload'" class="py-4">
                    <div 
                        class="border-2 border-dashed border-[#99cab7] hover:border-[#31725e] rounded-3xl p-8 text-center bg-[#f2f8f5]/40 transition-colors"
                        @dragover.prevent=""
                        @drop.prevent="handleDrop($event)"
                    >
                        <div class="w-16 h-16 rounded-3xl bg-[#31725e]/10 text-[#31725e] flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="upload-cloud" class="w-8 h-8"></i>
                        </div>
                        <h4 class="text-sm font-bold text-[#1d3e35]">Tarik & Letakkan file gambar ke sini</h4>
                        <p class="text-xs text-stone-400 mt-1 max-w-sm mx-auto">Mendukung format JPG, PNG, WEBP, dan SVG (Maks. 10 MB per file)</p>

                        <div class="mt-5">
                            <input 
                                type="file" 
                                id="picker_file_input" 
                                multiple 
                                accept="image/jpeg,image/png,image/webp,image/svg+xml"
                                class="hidden" 
                                @change="uploadFiles($event.target.files)"
                            />
                            <label 
                                for="picker_file_input" 
                                class="px-5 py-2.5 rounded-2xl bg-[#31725e] hover:bg-[#295c4d] text-white font-bold text-xs inline-flex items-center gap-2 cursor-pointer shadow-md shadow-[#31725e]/20 transition-all hover:scale-[1.02]"
                            >
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Pilih File Gambar</span>
                            </label>
                        </div>

                        <!-- Uploading Indicator -->
                        <div x-show="isUploading" class="mt-6 flex items-center justify-center gap-3 text-xs font-bold text-[#31725e]">
                            <div class="w-4 h-4 border-2 border-[#31725e] border-t-transparent rounded-full animate-spin"></div>
                            <span>Sedang mengunggah gambar ke Media Library...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-stone-100 bg-stone-50 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <template x-if="selectedItem">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <img :src="selectedItem.url" class="w-8 h-8 rounded-lg object-cover border border-stone-200 shrink-0" />
                            <div class="truncate text-left">
                                <p class="text-xs font-bold text-[#1d3e35] truncate" x-text="selectedItem.name"></p>
                                <p class="text-[10px] text-stone-400 font-mono" x-text="selectedItem.size_formatted"></p>
                            </div>
                        </div>
                    </template>
                    <template x-if="!selectedItem">
                        <span class="text-xs text-stone-400 italic">Pilih satu gambar untuk melanjutkan</span>
                    </template>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="closePicker()"
                        class="px-4 py-2 rounded-xl border border-stone-200 bg-white hover:bg-stone-100 text-stone-600 font-bold text-xs transition-colors"
                    >
                        Batal
                    </button>
                    <button 
                        type="button" 
                        @click="confirmSelection()"
                        :disabled="!selectedItem"
                        class="px-5 py-2 rounded-xl bg-[#31725e] hover:bg-[#295c4d] text-white font-bold text-xs transition-all shadow-md shadow-[#31725e]/20 disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5"
                    >
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Gunakan Gambar Ini</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function mediaPickerModal() {
    return {
        isOpen: false,
        activeTab: 'gallery',
        isLoading: false,
        isUploading: false,
        mediaList: [],
        selectedItem: null,
        searchQuery: '',
        currentPage: 1,
        totalPages: 1,
        totalItems: 0,
        targetField: null,

        init() {
            // Re-render lucide icons when window opens
            this.$watch('isOpen', (value) => {
                if (value) {
                    this.fetchMedia(1);
                    setTimeout(() => { if (window.refreshIcons) window.refreshIcons(); }, 100);
                }
            });
            this.$watch('mediaList', () => {
                setTimeout(() => { if (window.refreshIcons) window.refreshIcons(); }, 50);
            });
            this.$watch('activeTab', () => {
                setTimeout(() => { if (window.refreshIcons) window.refreshIcons(); }, 50);
            });
        },

        openPicker(detail = {}) {
            this.targetField = detail.targetField || 'thumbnail';
            this.selectedItem = null;
            this.activeTab = 'gallery';
            this.isOpen = true;
        },

        closePicker() {
            this.isOpen = false;
        },

        async fetchMedia(page = 1) {
            this.isLoading = true;
            this.currentPage = page;

            try {
                const params = new URLSearchParams({
                    page: page,
                    search: this.searchQuery,
                });

                const response = await fetch(`{{ route('admin.media.api.list') }}?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                const result = await response.json();
                this.mediaList = result.data || [];
                this.totalPages = result.last_page || 1;
                this.totalItems = result.total || 0;
            } catch (err) {
                console.error('Error loading media:', err);
            } finally {
                this.isLoading = false;
            }
        },

        selectMedia(item) {
            this.selectedItem = item;
        },

        confirmSelection() {
            if (!this.selectedItem) return;

            window.dispatchEvent(new CustomEvent('media-selected', {
                detail: {
                    targetField: this.targetField,
                    media: this.selectedItem,
                }
            }));

            this.closePicker();
        },

        handleDrop(event) {
            const files = event.dataTransfer.files;
            if (files && files.length > 0) {
                this.uploadFiles(files);
            }
        },

        async uploadFiles(files) {
            if (!files || files.length === 0) return;

            this.isUploading = true;
            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch("{{ route('admin.media.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    this.activeTab = 'gallery';
                    await this.fetchMedia(1);
                    if (this.mediaList.length > 0) {
                        this.selectedItem = this.mediaList[0];
                    }
                }
            } catch (err) {
                console.error('Upload failed:', err);
                alert('Gagal mengunggah gambar. Pastikan format valid dan ukuran di bawah 10MB.');
            } finally {
                this.isUploading = false;
            }
        }
    };
}
</script>
