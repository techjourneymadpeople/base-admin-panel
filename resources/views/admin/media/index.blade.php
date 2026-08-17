<x-layouts.admin title="Media Library & Gudang Gambar">
    <x-admin.breadcrumb 
        title="Media Library" 
        :items="[
            'Konten & Media' => '',
            'Media Library' => ''
        ]" 
    />

    <div 
        class="space-y-6" 
        x-data="{
            search: '{{ request('search', '') }}',
            selectedFormat: '{{ request('format', 'all') }}',
            selectedSort: '{{ request('sort', 'newest') }}',
            
            // Single Unified Media Studio Modal
            studioModalOpen: false,
            studioMode: 'upload', // 'upload' | 'crop'
            
            // Upload & Current File State
            rawFile: null,
            rawFileName: '',
            rawFileSizeKb: 0,
            imageSrc: '',
            isSvg: false,
            isSavedMedia: false,
            savedMediaId: null,
            savedCropUrl: '',
            isInUse: false,
            isProcessing: false,
            isDraggingOver: false,

            // Cropper & Canvas State
            targetWidth: 800,
            targetHeight: 600,
            aspectRatio: 'free', // 'free', '1:1', '16:9', '4:3', '3:2', '9:16'
            saveAsNew: true,
            cropBox: { x: 40, y: 40, w: 320, h: 220 },
            isDragging: false,
            isResizing: false,
            resizeHandle: null,
            dragStart: { x: 0, y: 0 },
            scaleRatio: 1,
            loadedImg: null,

            // Open Studio for Fresh Upload
            openUploadStudio() {
                this.resetStudio();
                this.studioMode = 'upload';
                this.studioModalOpen = true;
                this.$nextTick(() => window.refreshIcons());
            },

            // Open Studio for Editing Existing Media
            openEditStudio(data) {
                this.resetStudio();
                this.isSavedMedia = true;
                this.savedMediaId = data.id;
                this.savedCropUrl = data.cropUrl;
                this.rawFileName = data.name;
                this.imageSrc = data.url;
                this.targetWidth = data.originalWidth > 1200 ? 1200 : (data.originalWidth || 800);
                this.targetHeight = data.originalHeight > 800 ? 800 : (data.originalHeight || 600);
                this.isInUse = data.isInUse || false;
                this.saveAsNew = true;
                this.studioMode = 'crop';
                this.studioModalOpen = true;
                this.$nextTick(() => {
                    this.initCanvasCropper();
                    window.refreshIcons();
                });
            },

            resetStudio() {
                this.rawFile = null;
                this.rawFileName = '';
                this.rawFileSizeKb = 0;
                this.imageSrc = '';
                this.isSvg = false;
                this.isSavedMedia = false;
                this.savedMediaId = null;
                this.savedCropUrl = '';
                this.isInUse = false;
                this.isProcessing = false;
                this.loadedImg = null;
                this.aspectRatio = 'free';
                const fileInput = document.getElementById('studioFileInput');
                if (fileInput) fileInput.value = '';
            },

            handleFileSelect(e) {
                if (e.target.files && e.target.files.length > 0) {
                    this.processSelectedFile(e.target.files[0]);
                }
            },

            handleFileDrop(e) {
                this.isDraggingOver = false;
                if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    this.processSelectedFile(e.dataTransfer.files[0]);
                }
            },

            processSelectedFile(file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/svg+xml'];
                const ext = file.name.split('.').pop().toLowerCase();
                const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

                if (!allowedTypes.includes(file.type) && !allowedExts.includes(ext)) {
                    alert('Format file tidak didukung. Harap pilih gambar bertipe JPG, PNG, WEBP, atau SVG.');
                    return;
                }

                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file melebihi batas maksimal 10 MB.');
                    return;
                }

                this.rawFile = file;
                this.rawFileName = file.name;
                this.rawFileSizeKb = Math.round(file.size / 1024);
                this.isSvg = ext === 'svg' || file.type === 'image/svg+xml';
                this.imageSrc = URL.createObjectURL(file);
                this.isSavedMedia = false;

                if (this.isSvg) {
                    // SVG does not need raster cropper, ready to upload directly
                    this.studioMode = 'upload_preview';
                } else {
                    // Instantly switch to Crop & Resize Editor
                    this.studioMode = 'crop';
                    this.$nextTick(() => {
                        this.initCanvasCropper();
                        window.refreshIcons();
                    });
                }
            },

            initCanvasCropper() {
                const img = new Image();
                if (this.imageSrc && (this.imageSrc.startsWith('http://') || this.imageSrc.startsWith('https://'))) {
                    try {
                        const parsed = new URL(this.imageSrc);
                        if (parsed.host !== window.location.host) {
                            img.crossOrigin = 'anonymous';
                        }
                    } catch (e) {}
                }
                img.onload = () => {
                    this.loadedImg = img;
                    this.targetWidth = img.width > 1200 ? 1200 : img.width;
                    this.targetHeight = img.height > 800 ? 800 : img.height;
                    this.drawCropCanvas();
                };
                img.src = this.imageSrc;
            },

            drawCropCanvas() {
                const canvas = document.getElementById('studioCropperCanvas');
                if (!canvas || !this.loadedImg) return;
                const ctx = canvas.getContext('2d');
                
                const container = canvas.parentElement;
                const maxW = container.clientWidth || 550;
                const maxH = 380;

                let drawW = this.loadedImg.width;
                let drawH = this.loadedImg.height;
                const ratio = Math.min(maxW / drawW, maxH / drawH, 1);
                
                canvas.width = drawW * ratio;
                canvas.height = drawH * ratio;
                this.scaleRatio = ratio;

                if (this.cropBox.w > canvas.width || this.cropBox.h > canvas.height) {
                    this.cropBox = {
                        x: Math.round(canvas.width * 0.08),
                        y: Math.round(canvas.height * 0.08),
                        w: Math.round(canvas.width * 0.84),
                        h: Math.round(canvas.height * 0.84)
                    };
                }

                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(this.loadedImg, 0, 0, canvas.width, canvas.height);

                // Dark overlay outside crop box
                ctx.fillStyle = 'rgba(0, 0, 0, 0.6)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);

                // Highlight crop area
                ctx.clearRect(this.cropBox.x, this.cropBox.y, this.cropBox.w, this.cropBox.h);
                ctx.drawImage(
                    this.loadedImg,
                    this.cropBox.x / this.scaleRatio,
                    this.cropBox.y / this.scaleRatio,
                    this.cropBox.w / this.scaleRatio,
                    this.cropBox.h / this.scaleRatio,
                    this.cropBox.x,
                    this.cropBox.y,
                    this.cropBox.w,
                    this.cropBox.h
                );

                // Crop Boundary Border
                ctx.strokeStyle = '#31725e';
                ctx.lineWidth = 2.5;
                ctx.strokeRect(this.cropBox.x, this.cropBox.y, this.cropBox.w, this.cropBox.h);

                // Grid Rule-of-Thirds Lines
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.45)';
                ctx.lineWidth = 1;
                ctx.beginPath();
                ctx.moveTo(this.cropBox.x + this.cropBox.w / 3, this.cropBox.y);
                ctx.lineTo(this.cropBox.x + this.cropBox.w / 3, this.cropBox.y + this.cropBox.h);
                ctx.moveTo(this.cropBox.x + (this.cropBox.w * 2) / 3, this.cropBox.y);
                ctx.lineTo(this.cropBox.x + (this.cropBox.w * 2) / 3, this.cropBox.y + this.cropBox.h);
                ctx.moveTo(this.cropBox.x, this.cropBox.y + this.cropBox.h / 3);
                ctx.lineTo(this.cropBox.x + this.cropBox.w, this.cropBox.y + this.cropBox.h / 3);
                ctx.moveTo(this.cropBox.x, this.cropBox.y + (this.cropBox.h * 2) / 3);
                ctx.lineTo(this.cropBox.x + this.cropBox.w, this.cropBox.y + (this.cropBox.h * 2) / 3);
                ctx.stroke();

                // Corner Handles
                const handleSize = 10;
                ctx.fillStyle = '#cca06e';
                const corners = [
                    [this.cropBox.x, this.cropBox.y],
                    [this.cropBox.x + this.cropBox.w, this.cropBox.y],
                    [this.cropBox.x, this.cropBox.y + this.cropBox.h],
                    [this.cropBox.x + this.cropBox.w, this.cropBox.y + this.cropBox.h]
                ];
                corners.forEach(([cx, cy]) => {
                    ctx.fillRect(cx - handleSize / 2, cy - handleSize / 2, handleSize, handleSize);
                });

                // Update real output pixel dimension inputs
                this.targetWidth = Math.round(this.cropBox.w / this.scaleRatio);
                this.targetHeight = Math.round(this.cropBox.h / this.scaleRatio);
            },

            handleCanvasMouseDown(e) {
                const canvas = document.getElementById('studioCropperCanvas');
                const rect = canvas.getBoundingClientRect();
                const mouseX = e.clientX - rect.left;
                const mouseY = e.clientY - rect.top;

                const threshold = 14;
                if (Math.hypot(mouseX - (this.cropBox.x + this.cropBox.w), mouseY - (this.cropBox.y + this.cropBox.h)) < threshold) {
                    this.isResizing = true;
                    this.resizeHandle = 'se';
                } else if (
                    mouseX >= this.cropBox.x &&
                    mouseX <= this.cropBox.x + this.cropBox.w &&
                    mouseY >= this.cropBox.y &&
                    mouseY <= this.cropBox.y + this.cropBox.h
                ) {
                    this.isDragging = true;
                    this.dragStart = { x: mouseX - this.cropBox.x, y: mouseY - this.cropBox.y };
                }
            },

            handleCanvasMouseMove(e) {
                if (!this.isDragging && !this.isResizing) return;
                const canvas = document.getElementById('studioCropperCanvas');
                const rect = canvas.getBoundingClientRect();
                const mouseX = Math.max(0, Math.min(canvas.width, e.clientX - rect.left));
                const mouseY = Math.max(0, Math.min(canvas.height, e.clientY - rect.top));

                if (this.isDragging) {
                    let newX = mouseX - this.dragStart.x;
                    let newY = mouseY - this.dragStart.y;
                    newX = Math.max(0, Math.min(canvas.width - this.cropBox.w, newX));
                    newY = Math.max(0, Math.min(canvas.height - this.cropBox.h, newY));
                    this.cropBox.x = newX;
                    this.cropBox.y = newY;
                } else if (this.isResizing) {
                    let newW = Math.max(40, mouseX - this.cropBox.x);
                    let newH = Math.max(40, mouseY - this.cropBox.y);
                    if (this.aspectRatio === '1:1') {
                        const s = Math.min(newW, newH);
                        newW = s; newH = s;
                    } else if (this.aspectRatio === '16:9') {
                        newH = Math.round((newW * 9) / 16);
                    } else if (this.aspectRatio === '4:3') {
                        newH = Math.round((newW * 3) / 4);
                    }
                    if (this.cropBox.x + newW <= canvas.width && this.cropBox.y + newH <= canvas.height) {
                        this.cropBox.w = newW;
                        this.cropBox.h = newH;
                    }
                }
                this.drawCropCanvas();
            },

            handleCanvasMouseUp() {
                this.isDragging = false;
                this.isResizing = false;
                this.resizeHandle = null;
            },

            setAspectRatio(ratio) {
                this.aspectRatio = ratio;
                const canvas = document.getElementById('studioCropperCanvas');
                if (!canvas) return;
                if (ratio === '1:1') {
                    const s = Math.min(this.cropBox.w, this.cropBox.h, canvas.width - this.cropBox.x, canvas.height - this.cropBox.y);
                    this.cropBox.w = s; this.cropBox.h = s;
                } else if (ratio === '16:9') {
                    this.cropBox.h = Math.min(Math.round((this.cropBox.w * 9) / 16), canvas.height - this.cropBox.y);
                } else if (ratio === '4:3') {
                    this.cropBox.h = Math.min(Math.round((this.cropBox.w * 3) / 4), canvas.height - this.cropBox.y);
                } else if (ratio === '9:16') {
                    this.cropBox.w = Math.min(Math.round((this.cropBox.h * 9) / 16), canvas.width - this.cropBox.x);
                }
                this.drawCropCanvas();
            },

            applyExactDimensions() {
                if (!this.loadedImg || !this.scaleRatio) return;
                const canvas = document.getElementById('studioCropperCanvas');
                const reqW = Math.min(this.targetWidth * this.scaleRatio, canvas.width);
                const reqH = Math.min(this.targetHeight * this.scaleRatio, canvas.height);
                this.cropBox.w = Math.max(30, reqW);
                this.cropBox.h = Math.max(30, reqH);
                if (this.cropBox.x + this.cropBox.w > canvas.width) this.cropBox.x = canvas.width - this.cropBox.w;
                if (this.cropBox.y + this.cropBox.h > canvas.height) this.cropBox.y = canvas.height - this.cropBox.h;
                this.drawCropCanvas();
            },

            // Save & Submit (Crop to WebP or Direct Upload)
            submitStudioCrop() {
                if (!this.loadedImg || !this.scaleRatio) return;
                this.isProcessing = true;

                const offCanvas = document.createElement('canvas');
                const outW = parseInt(this.targetWidth) || Math.round(this.cropBox.w / this.scaleRatio);
                const outH = parseInt(this.targetHeight) || Math.round(this.cropBox.h / this.scaleRatio);

                offCanvas.width = outW;
                offCanvas.height = outH;
                const offCtx = offCanvas.getContext('2d');

                const sourceX = this.cropBox.x / this.scaleRatio;
                const sourceY = this.cropBox.y / this.scaleRatio;
                const sourceW = this.cropBox.w / this.scaleRatio;
                const sourceH = this.cropBox.h / this.scaleRatio;

                offCtx.drawImage(this.loadedImg, sourceX, sourceY, sourceW, sourceH, 0, 0, outW, outH);

                if (this.isSavedMedia) {
                    // Editing existing media -> POST to crop URL
                    const base64WebP = offCanvas.toDataURL('image/webp', 0.85);

                    fetch(this.savedCropUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            image_data: base64WebP,
                            save_as_new: this.saveAsNew,
                            target_width: outW,
                            target_height: outH
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isProcessing = false;
                        if (data.success) {
                            this.studioModalOpen = false;
                            window.location.reload();
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat memproses crop.');
                        }
                    })
                    .catch(err => {
                        this.isProcessing = false;
                        alert('Gagal menghubungi server untuk memproses crop gambar.');
                    });
                } else {
                    // New Upload from local file -> convert canvas to WebP Blob and upload via FormData
                    offCanvas.toBlob((blob) => {
                        const cleanName = (this.rawFileName || 'image').replace(/\.[^/.]+$/, '') + '-processed.webp';
                        const croppedFile = new File([blob], cleanName, { type: 'image/webp' });

                        const formData = new FormData();
                        formData.append('files[]', croppedFile);
                        formData.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('admin.media.store') }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(res => {
                            if (res.ok) {
                                window.location.reload();
                            } else {
                                return res.json().then(d => { throw new Error(d.message || 'Gagal mengunggah gambar'); });
                            }
                        })
                        .catch(err => {
                            this.isProcessing = false;
                            alert(err.message || 'Gagal mengunggah gambar ke server.');
                        });
                    }, 'image/webp', 0.85);
                }
            },

            // Upload Raw Original File without cropping
            uploadRawOriginal() {
                if (!this.rawFile) return;
                this.isProcessing = true;

                const formData = new FormData();
                formData.append('files[]', this.rawFile);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('admin.media.store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => {
                    if (res.ok) {
                        window.location.reload();
                    } else {
                        return res.json().then(d => { throw new Error(d.message || 'Gagal mengunggah file.'); });
                    }
                })
                .catch(err => {
                    this.isProcessing = false;
                    alert(err.message || 'Gagal mengunggah file ke server.');
                });
            },

            // Detail Modal State
            detailModalOpen: false,
            activeDetail: null,
            openDetail(data) {
                this.activeDetail = data;
                this.detailModalOpen = true;
                this.$nextTick(() => window.refreshIcons());
            },

            // Delete Modal State
            deleteModalOpen: false,
            deleteData: null,
            openDeleteModal(data) {
                this.deleteData = data;
                this.deleteModalOpen = true;
                this.$nextTick(() => window.refreshIcons());
            },

            // Clipboard toast
            showToast: false,
            toastMessage: '',
            copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(() => {
                    this.toastMessage = 'URL gambar berhasil disalin ke clipboard!';
                    this.showToast = true;
                    setTimeout(() => this.showToast = false, 3000);
                });
            }
        }"
    >
        <!-- 1. Header Hero Card -->
        <div class="rounded-3xl p-6 sm:p-8 bg-white border border-[#99cab7]/30 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1d3e35] via-[#295c4d] to-[#cca06e] p-1 shadow-md shrink-0">
                    <div class="w-full h-full bg-[#1d3e35] rounded-[14px] flex items-center justify-center text-white text-xl">
                        <i data-lucide="images" class="w-7 h-7 text-[#cca06e]"></i>
                    </div>
                </div>

                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-[#1d3e35]">Media Library</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $formatCounts['all'] }} File Media
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-[#f2f8f5] text-[#295c4d] border border-[#99cab7]/40">
                            Penyimpanan: {{ $totalSizeFormatted }}
                        </span>
                    </div>
                    <p class="text-xs text-stone-500 mt-1">
                        Gudang penyimpanan aset gambar (JPG, PNG, WEBP, SVG maks 10MB) dengan fitur <strong>Crop, Resize, & Konversi WebP Otomatis (&lt; 200KB)</strong>.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                @can('upload-media')
                    <button 
                        type="button" 
                        @click="openUploadStudio()"
                        class="px-5 py-2.5 rounded-2xl bg-gradient-to-r from-[#1d3e35] to-[#31725e] text-white hover:opacity-95 font-bold text-xs inline-flex items-center gap-2 shadow-md shadow-[#1d3e35]/15 transition-all cursor-pointer"
                    >
                        <i data-lucide="upload-cloud" class="w-4 h-4 text-[#cca06e]"></i>
                        <span>Upload & Crop Gambar Baru</span>
                    </button>
                @endcan
            </div>
        </div>

        <!-- 2. Filter & Search Controls Bar -->
        <div class="p-4 rounded-3xl bg-white border border-[#99cab7]/30 shadow-2xs flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <!-- Format Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-none">
                <a 
                    href="{{ route('admin.media.index', array_merge(request()->query(), ['format' => 'all'])) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-colors shrink-0 {{ request('format', 'all') === 'all' ? 'bg-[#1d3e35] text-white border-[#1d3e35]' : 'bg-stone-50 text-stone-600 border-stone-200 hover:bg-stone-100' }}"
                >
                    Semua ({{ $formatCounts['all'] }})
                </a>

                <a 
                    href="{{ route('admin.media.index', array_merge(request()->query(), ['format' => 'webp'])) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-colors shrink-0 {{ request('format') === 'webp' ? 'bg-emerald-700 text-white border-emerald-700' : 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' }}"
                >
                    WebP ({{ $formatCounts['webp'] }})
                </a>

                <a 
                    href="{{ route('admin.media.index', array_merge(request()->query(), ['format' => 'png'])) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-colors shrink-0 {{ request('format') === 'png' ? 'bg-blue-700 text-white border-blue-700' : 'bg-blue-50 text-blue-800 border-blue-200 hover:bg-blue-100' }}"
                >
                    PNG ({{ $formatCounts['png'] }})
                </a>

                <a 
                    href="{{ route('admin.media.index', array_merge(request()->query(), ['format' => 'jpg'])) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-colors shrink-0 {{ request('format') === 'jpg' ? 'bg-amber-700 text-white border-amber-700' : 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100' }}"
                >
                    JPG ({{ $formatCounts['jpg'] }})
                </a>

                <a 
                    href="{{ route('admin.media.index', array_merge(request()->query(), ['format' => 'svg'])) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold border transition-colors shrink-0 {{ request('format') === 'svg' ? 'bg-purple-700 text-white border-purple-700' : 'bg-purple-50 text-purple-800 border-purple-200 hover:bg-purple-100' }}"
                >
                    SVG ({{ $formatCounts['svg'] }})
                </a>
            </div>

            <!-- Search and Sort Form -->
            <form method="GET" action="{{ route('admin.media.index') }}" class="flex items-center gap-3 shrink-0">
                @if(request('format'))
                    <input type="hidden" name="format" value="{{ request('format') }}">
                @endif

                <div class="relative w-48 sm:w-64">
                    <i data-lucide="search" class="w-4 h-4 text-[#31725e] absolute left-3 top-2.5 pointer-events-none"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Cari nama gambar..."
                        class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-[#99cab7]/50 focus:border-[#31725e] focus:ring-2 focus:ring-[#428e75]/20 bg-[#f2f8f5]/40 outline-none text-[#1d3e35]"
                    />
                </div>

                <select 
                    name="sort" 
                    onchange="this.form.submit()"
                    class="px-3 py-2 text-xs rounded-xl border border-[#99cab7]/50 bg-white font-medium text-stone-700 outline-none cursor-pointer"
                >
                    <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="size_desc" {{ request('sort') === 'size_desc' ? 'selected' : '' }}>Ukuran Terbesar</option>
                    <option value="size_asc" {{ request('sort') === 'size_asc' ? 'selected' : '' }}>Ukuran Terkecil</option>
                    <option value="name_asc" {{ request('sort', 'newest') === 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                </select>
            </form>
        </div>

        <!-- 3. Gallery Grid Container -->
        @include('admin.media.partials.grid')

        <!-- 4. ALL-IN-ONE UNIFIED MEDIA STUDIO MODAL (Upload, Crop & Resize, Convert to WebP) -->
        <template x-teleport="body">
            <div 
                x-show="studioModalOpen" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            >
                <div 
                    @click.outside="if(!isProcessing) studioModalOpen = false"
                    class="bg-white rounded-3xl max-w-4xl w-full p-6 space-y-5 shadow-2xl border border-[#99cab7]/40 max-h-[92vh] flex flex-col"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-[#1d3e35] to-[#31725e] text-white flex items-center justify-center shrink-0 shadow-xs">
                                <template x-if="studioMode === 'upload'">
                                    <i data-lucide="upload-cloud" class="w-5 h-5 text-[#cca06e]"></i>
                                </template>
                                <template x-if="studioMode !== 'upload'">
                                    <i data-lucide="crop" class="w-5 h-5 text-[#cca06e]"></i>
                                </template>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-[#1d3e35]">
                                    <span x-show="studioMode === 'upload'">Upload & Crop Gambar Baru</span>
                                    <span x-show="studioMode === 'crop'">Crop & Resize Gambar (WebP &lt;200KB)</span>
                                    <span x-show="studioMode === 'upload_preview'">Pratinjau File SVG</span>
                                </h3>
                                <p class="text-xs text-stone-500">
                                    <span x-show="studioMode === 'upload'">Pilih atau tarik file gambar untuk langsung disesuaikan dan dikonversi ke WebP.</span>
                                    <span x-show="studioMode === 'crop'">Atur kanvas, tentukan resolusi, dan simpan dalam format WebP terkompresi.</span>
                                    <span x-show="studioMode === 'upload_preview'">File SVG siap diunggah langsung ke gudang media.</span>
                                </p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="studioModalOpen = false"
                            class="p-2 rounded-xl text-stone-400 hover:text-stone-700 hover:bg-stone-100 cursor-pointer"
                        >
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Hidden Native File Input -->
                    <input 
                        type="file" 
                        id="studioFileInput"
                        accept="image/jpeg,image/jpg,image/png,image/webp,image/svg+xml"
                        @change="handleFileSelect($event)"
                        class="hidden"
                    />

                    <!-- MODAL BODY STEP 1: DROPZONE PICKER (When in upload mode) -->
                    <template x-if="studioMode === 'upload'">
                        <div class="flex-1 flex flex-col justify-center py-6">
                            <div 
                                @dragover.prevent="isDraggingOver = true"
                                @dragleave.prevent="isDraggingOver = false"
                                @drop.prevent="handleFileDrop($event)"
                                @click="document.getElementById('studioFileInput').click()"
                                class="border-2 border-dashed rounded-3xl p-10 text-center transition-all cursor-pointer flex flex-col items-center justify-center gap-3"
                                :class="isDraggingOver 
                                    ? 'border-[#31725e] bg-[#e2f0ea]/70 scale-[0.99]' 
                                    : 'border-[#99cab7]/60 hover:border-[#31725e] bg-[#f2f8f5]/50 hover:bg-[#e2f0ea]/30'"
                            >
                                <div class="w-16 h-16 rounded-2xl bg-white border border-[#99cab7]/40 text-[#31725e] flex items-center justify-center shadow-md">
                                    <i data-lucide="image-plus" class="w-8 h-8"></i>
                                </div>
                                <div class="space-y-1.5 max-w-sm">
                                    <p class="text-sm font-bold text-[#1d3e35]">Tarik & Letakkan gambar di sini, atau <span class="text-[#31725e] underline underline-offset-2">Klik untuk Memilih File</span></p>
                                    <p class="text-xs text-stone-500">Gambar akan langsung terbuka di editor crop interaktif.</p>
                                    <div class="flex items-center justify-center gap-1.5 pt-2 text-[10px] font-bold text-stone-500">
                                        <span class="px-2 py-0.5 rounded-md bg-white border border-stone-200">JPG</span>
                                        <span class="px-2 py-0.5 rounded-md bg-white border border-stone-200">PNG</span>
                                        <span class="px-2 py-0.5 rounded-md bg-white border border-stone-200">WEBP</span>
                                        <span class="px-2 py-0.5 rounded-md bg-white border border-stone-200">SVG</span>
                                        <span>&bull; Maks 10 MB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- MODAL BODY STEP 2: LIVE CROP & RESIZE CANVAS (When an image is loaded) -->
                    <template x-if="studioMode === 'crop'">
                        <div class="flex-1 overflow-hidden flex flex-col md:flex-row gap-6">
                            <!-- Left: Interactive Canvas Viewport -->
                            <div class="flex-1 bg-stone-900 rounded-2xl flex items-center justify-center p-4 relative overflow-hidden select-none min-h-[300px]">
                                <canvas 
                                    id="studioCropperCanvas" 
                                    class="max-w-full max-h-[360px] cursor-crosshair shadow-lg rounded-lg"
                                    @mousedown="handleCanvasMouseDown"
                                    @mousemove="handleCanvasMouseMove"
                                    @mouseup="handleCanvasMouseUp"
                                    @mouseleave="handleCanvasMouseUp"
                                ></canvas>
                            </div>

                            <!-- Right: Controls Sidebar -->
                            <div class="w-full md:w-80 space-y-4 shrink-0 overflow-y-auto pr-1">
                                <!-- File Info Banner -->
                                <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/80 flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-[#1d3e35] truncate" x-text="rawFileName"></p>
                                        <template x-if="!isSavedMedia">
                                            <p class="text-[10px] text-stone-400 font-mono" x-text="'File Asli: ' + rawFileSizeKb + ' KB'"></p>
                                        </template>
                                    </div>
                                    <template x-if="!isSavedMedia">
                                        <button 
                                            type="button" 
                                            @click="document.getElementById('studioFileInput').click()"
                                            class="text-[11px] font-bold text-[#31725e] hover:underline cursor-pointer shrink-0"
                                        >
                                            Ganti
                                        </button>
                                    </template>
                                </div>

                                <!-- Aspect Ratio Presets -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold text-[#295c4d] uppercase tracking-wider">Rasio Aspek</label>
                                    <div class="grid grid-cols-3 gap-1.5 text-xs">
                                        <button type="button" @click="setAspectRatio('free')" class="px-2.5 py-1.5 rounded-lg border font-bold text-center cursor-pointer transition-colors" :class="aspectRatio === 'free' ? 'bg-[#1d3e35] text-white border-[#1d3e35]' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'">Bebas</button>
                                        <button type="button" @click="setAspectRatio('1:1')" class="px-2.5 py-1.5 rounded-lg border font-bold text-center cursor-pointer transition-colors" :class="aspectRatio === '1:1' ? 'bg-[#1d3e35] text-white border-[#1d3e35]' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'">1:1 (Persegi)</button>
                                        <button type="button" @click="setAspectRatio('16:9')" class="px-2.5 py-1.5 rounded-lg border font-bold text-center cursor-pointer transition-colors" :class="aspectRatio === '16:9' ? 'bg-[#1d3e35] text-white border-[#1d3e35]' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'">16:9 (Banner)</button>
                                        <button type="button" @click="setAspectRatio('4:3')" class="px-2.5 py-1.5 rounded-lg border font-bold text-center cursor-pointer transition-colors" :class="aspectRatio === '4:3' ? 'bg-[#1d3e35] text-white border-[#1d3e35]' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'">4:3</button>
                                        <button type="button" @click="setAspectRatio('3:2')" class="px-2.5 py-1.5 rounded-lg border font-bold text-center cursor-pointer transition-colors" :class="aspectRatio === '3:2' ? 'bg-[#1d3e35] text-white border-[#1d3e35]' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'">3:2</button>
                                        <button type="button" @click="setAspectRatio('9:16')" class="px-2.5 py-1.5 rounded-lg border font-bold text-center cursor-pointer transition-colors" :class="aspectRatio === '9:16' ? 'bg-[#1d3e35] text-white border-[#1d3e35]' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'">9:16 (Story)</button>
                                    </div>
                                </div>

                                <!-- Target Dimensions (Width x Height) -->
                                <div class="space-y-1.5 pt-2 border-t border-stone-100">
                                    <label class="block text-xs font-bold text-[#295c4d] uppercase tracking-wider">Ukuran Target Output (Piksel)</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <span class="text-[10px] text-stone-400 font-medium">Lebar (px)</span>
                                            <input 
                                                type="number" 
                                                x-model="targetWidth" 
                                                @change="applyExactDimensions()"
                                                class="w-full px-3 py-1.5 text-xs rounded-xl border border-stone-300 font-mono font-bold text-[#1d3e35] outline-none"
                                            />
                                        </div>
                                        <div>
                                            <span class="text-[10px] text-stone-400 font-medium">Tinggi (px)</span>
                                            <input 
                                                type="number" 
                                                x-model="targetHeight" 
                                                @change="applyExactDimensions()"
                                                class="w-full px-3 py-1.5 text-xs rounded-xl border border-stone-300 font-mono font-bold text-[#1d3e35] outline-none"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Target Format Notice -->
                                <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-1">
                                    <div class="font-extrabold flex items-center gap-1.5">
                                        <i data-lucide="zap" class="w-4 h-4 text-emerald-600"></i>
                                        <span>Target Format: WebP</span>
                                    </div>
                                    <p class="text-[11px] text-emerald-800 leading-relaxed">
                                        Otomatis dikompresi agar ukuran file <strong>di bawah 200 KB</strong> untuk performa loading maksimal.
                                    </p>
                                </div>

                                <!-- Save Mode for Existing Media -->
                                <template x-if="isSavedMedia">
                                    <div class="space-y-2 pt-2 border-t border-stone-100 text-xs">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="save_mode" :value="true" x-model="saveAsNew" class="text-[#1d3e35] focus:ring-[#31725e]">
                                            <span class="font-bold text-stone-800">Simpan Sebagai Gambar Baru</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer" :class="isInUse ? 'opacity-50 cursor-not-allowed' : ''">
                                            <input type="radio" name="save_mode" :value="false" x-model="saveAsNew" :disabled="isInUse" class="text-[#1d3e35] focus:ring-[#31725e]">
                                            <span class="font-bold text-stone-800">Timpa Gambar Saat Ini</span>
                                        </label>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <!-- MODAL BODY STEP 3: SVG PREVIEW (For vector SVG) -->
                    <template x-if="studioMode === 'upload_preview'">
                        <div class="flex-1 flex flex-col items-center justify-center p-6 space-y-4">
                            <div class="w-48 h-48 bg-stone-100 rounded-2xl p-4 flex items-center justify-center border border-stone-200">
                                <img :src="imageSrc" alt="SVG Preview" class="max-h-full max-w-full">
                            </div>
                            <div class="text-center space-y-1">
                                <p class="text-sm font-bold text-[#1d3e35]" x-text="rawFileName"></p>
                                <p class="text-xs text-stone-400 font-mono" x-text="rawFileSizeKb + ' KB (Vector SVG)'"></p>
                                <p class="text-xs text-emerald-700 font-medium">File SVG merupakan vektor murni dan siap diunggah langsung.</p>
                            </div>
                        </div>
                    </template>

                    <!-- Modal Actions Footer -->
                    <div class="flex items-center justify-between gap-3 pt-3 border-t border-stone-100 shrink-0">
                        <div>
                            <template x-if="studioMode === 'crop' && !isSavedMedia">
                                <button 
                                    type="button" 
                                    @click="uploadRawOriginal()"
                                    class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 hover:bg-stone-50 font-bold text-xs cursor-pointer"
                                    :disabled="isProcessing"
                                >
                                    Upload File Asli (Tanpa Crop)
                                </button>
                            </template>
                        </div>

                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="studioModalOpen = false"
                                class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 font-bold text-xs hover:bg-stone-50 cursor-pointer"
                                :disabled="isProcessing"
                            >
                                Batal
                            </button>

                            <template x-if="studioMode === 'crop'">
                                <button 
                                    type="button" 
                                    @click="submitStudioCrop()"
                                    class="px-5 py-2.5 rounded-2xl bg-[#1d3e35] hover:bg-[#31725e] text-white font-bold text-xs inline-flex items-center gap-2 shadow-md cursor-pointer"
                                    :disabled="isProcessing"
                                >
                                    <template x-if="isProcessing">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                            <span>Memproses Crop & WebP...</span>
                                        </span>
                                    </template>
                                    <template x-if="!isProcessing">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="check" class="w-4 h-4 text-[#cca06e]"></i>
                                            <span x-text="isSavedMedia ? 'Terapkan & Simpan WebP' : 'Simpan & Upload sebagai WebP'"></span>
                                        </span>
                                    </template>
                                </button>
                            </template>

                            <template x-if="studioMode === 'upload_preview'">
                                <button 
                                    type="button" 
                                    @click="uploadRawOriginal()"
                                    class="px-5 py-2.5 rounded-2xl bg-[#1d3e35] hover:bg-[#31725e] text-white font-bold text-xs inline-flex items-center gap-2 shadow-md cursor-pointer"
                                    :disabled="isProcessing"
                                >
                                    <template x-if="isProcessing">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                            <span>Mengunggah SVG...</span>
                                        </span>
                                    </template>
                                    <template x-if="!isProcessing">
                                        <span class="inline-flex items-center gap-1.5">
                                            <i data-lucide="upload-cloud" class="w-4 h-4 text-[#cca06e]"></i>
                                            <span>Upload SVG Sekarang</span>
                                        </span>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- 5. Detail Modal -->
        <template x-teleport="body">
            <div 
                x-show="detailModalOpen" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            >
                <div 
                    @click.outside="detailModalOpen = false"
                    class="bg-white rounded-3xl max-w-2xl w-full p-6 space-y-5 shadow-2xl border border-[#99cab7]/40"
                    x-show="activeDetail"
                >
                    <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center">
                                <i data-lucide="eye" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-[#1d3e35]" x-text="activeDetail ? activeDetail.name : ''"></h3>
                                <p class="text-xs text-stone-400 font-mono" x-text="activeDetail ? activeDetail.file_name : ''"></p>
                            </div>
                        </div>

                        <button 
                            type="button" 
                            @click="detailModalOpen = false"
                            class="p-2 rounded-xl text-stone-400 hover:text-stone-700"
                        >
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Image Preview -->
                    <div class="w-full h-64 bg-stone-100 rounded-2xl flex items-center justify-center p-4 overflow-hidden">
                        <img :src="activeDetail ? activeDetail.url : ''" alt="Preview" class="max-h-full max-w-full object-contain">
                    </div>

                    <!-- Metadata Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                        <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/70 space-y-0.5">
                            <span class="text-[10px] text-stone-400 uppercase font-bold">Ukuran File</span>
                            <p class="font-mono font-bold text-stone-800" x-text="activeDetail ? activeDetail.size_kb : ''"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/70 space-y-0.5">
                            <span class="text-[10px] text-stone-400 uppercase font-bold">Dimensi</span>
                            <p class="font-mono font-bold text-stone-800" x-text="activeDetail ? activeDetail.dimensions : ''"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/70 space-y-0.5">
                            <span class="text-[10px] text-stone-400 uppercase font-bold">Tipe MIME</span>
                            <p class="font-mono font-bold text-stone-800 truncate" x-text="activeDetail ? activeDetail.mime_type : ''"></p>
                        </div>
                        <div class="p-3 rounded-2xl bg-stone-50 border border-stone-200/70 space-y-0.5">
                            <span class="text-[10px] text-stone-400 uppercase font-bold">Status Pakai</span>
                            <template x-if="activeDetail && activeDetail.is_in_use">
                                <span class="inline-flex items-center gap-1 font-bold text-amber-700">
                                    <i data-lucide="link-2" class="w-3 h-3"></i> Sedang Digunakan
                                </span>
                            </template>
                            <template x-if="activeDetail && !activeDetail.is_in_use">
                                <span class="text-stone-500">Tersedia</span>
                            </template>
                        </div>
                    </div>

                    <!-- URL Box & Crop Button -->
                    <div class="space-y-3 pt-2">
                        <div class="space-y-1">
                            <label class="block text-[11px] font-bold text-stone-500">URL Akses Publik</label>
                            <div class="flex items-center gap-2">
                                <input 
                                    type="text" 
                                    readonly 
                                    :value="activeDetail ? activeDetail.url : ''"
                                    class="flex-1 px-3 py-2 rounded-xl bg-stone-50 border border-stone-200 font-mono text-xs text-stone-700 outline-none select-all"
                                />
                                <button 
                                    type="button" 
                                    @click="copyToClipboard(activeDetail.url)"
                                    class="px-3.5 py-2 rounded-xl bg-[#1d3e35] text-white font-bold text-xs hover:bg-[#31725e] transition-colors cursor-pointer shrink-0"
                                >
                                    Salin URL
                                </button>
                            </div>
                        </div>

                        <!-- Crop Action in Detail Modal -->
                        <template x-if="activeDetail && !activeDetail.is_svg">
                            <div class="pt-2 border-t border-stone-100 flex justify-end">
                                <button 
                                    type="button" 
                                    @click="detailModalOpen = false; openEditStudio(activeDetail)"
                                    class="px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-[#784732] font-bold text-xs inline-flex items-center gap-1.5 border border-amber-200 transition-colors cursor-pointer"
                                >
                                    <i data-lucide="crop" class="w-4 h-4 text-[#b17042]"></i>
                                    <span>Buka Crop & Resize WebP</span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <!-- 6. Delete Modal (With Usage Guard) -->
        <template x-teleport="body">
            <div 
                x-show="deleteModalOpen" 
                x-cloak
                class="fixed inset-0 z-50 overflow-y-auto bg-stone-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            >
                <div 
                    @click.outside="deleteModalOpen = false"
                    class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl border border-[#99cab7]/40"
                    x-show="deleteData"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-stone-900">Hapus Gambar</h3>
                            <p class="text-xs text-stone-500">Penghapusan file media dari server.</p>
                        </div>
                    </div>

                    <!-- If image is currently in use -->
                    <template x-if="deleteData && deleteData.isInUse">
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                            <div class="font-extrabold flex items-center gap-1.5">
                                <i data-lucide="lock" class="w-4 h-4 text-amber-700"></i>
                                <span>Gambar Tidak Dapat Dihapus</span>
                            </div>
                            <p class="text-[11px] text-amber-800 leading-relaxed">
                                Gambar ini sedang digunakan pada sistem (misalnya sebagai logo, favicon, atau konten aktif) sehingga dilindungi dari penghapusan.
                            </p>
                        </div>
                    </template>

                    <!-- If image is safe to delete -->
                    <template x-if="deleteData && !deleteData.isInUse">
                        <p class="text-xs text-stone-600 leading-relaxed">
                            Apakah Anda yakin ingin menghapus gambar <strong class="text-red-600" x-text="deleteData ? deleteData.name : ''"></strong>? File fisik pada server akan dihapus secara permanen.
                        </p>
                    </template>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-stone-100">
                        <button 
                            type="button" 
                            @click="deleteModalOpen = false"
                            class="px-4 py-2.5 rounded-2xl border border-stone-200 text-stone-600 font-bold text-xs cursor-pointer"
                        >
                            Tutup
                        </button>

                        <template x-if="deleteData && !deleteData.isInUse">
                            <form :action="deleteData.url" method="POST">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="submit" 
                                    class="px-4 py-2.5 rounded-2xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs inline-flex items-center gap-1.5 shadow-md cursor-pointer"
                                >
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    <span>Hapus Sekarang</span>
                                </button>
                            </form>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <!-- 7. Floating Toast Notification -->
        <div 
            x-show="showToast" 
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-6 right-6 z-50 px-4 py-3 rounded-2xl bg-[#1d3e35] text-white font-bold text-xs shadow-2xl flex items-center gap-2 border border-[#428e75]/40"
        >
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
            <span x-text="toastMessage"></span>
        </div>
    </div>
</x-layouts.admin>
