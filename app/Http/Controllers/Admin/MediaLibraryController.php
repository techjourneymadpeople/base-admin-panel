<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\MediaWarehouse;
use App\Models\WebConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MediaLibraryController extends Controller
{
    /**
     * Display the Media Library gallery.
     */
    public function index(Request $request): View|JsonResponse
    {
        $warehouse = MediaWarehouse::getInstance();

        $query = Media::where('model_type', MediaWarehouse::class)
            ->where('model_id', $warehouse->id);

        // Search by name or filename
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        // Filter by format / extension
        if ($request->filled('format') && $request->input('format') !== 'all') {
            $format = strtolower($request->input('format'));
            if ($format === 'jpg' || $format === 'jpeg') {
                $query->whereIn('mime_type', ['image/jpeg', 'image/jpg']);
            } elseif ($format === 'png') {
                $query->where('mime_type', 'image/png');
            } elseif ($format === 'webp') {
                $query->where('mime_type', 'image/webp');
            } elseif ($format === 'svg') {
                $query->whereIn('mime_type', ['image/svg+xml', 'image/svg']);
            }
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'size_desc') {
            $query->orderBy('size', 'desc');
        } elseif ($sort === 'size_asc') {
            $query->orderBy('size', 'asc');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $mediaItems = $query->paginate(24)->withQueryString();

        // Overall stats
        $allMedia = Media::where('model_type', MediaWarehouse::class)
            ->where('model_id', $warehouse->id)
            ->get();

        $totalBytes = $allMedia->sum('size');
        $totalSizeFormatted = $this->formatBytes($totalBytes);

        $formatCounts = [
            'all' => $allMedia->count(),
            'webp' => $allMedia->where('mime_type', 'image/webp')->count(),
            'png' => $allMedia->where('mime_type', 'image/png')->count(),
            'jpg' => $allMedia->filter(fn($m) => in_array($m->mime_type, ['image/jpeg', 'image/jpg']))->count(),
            'svg' => $allMedia->filter(fn($m) => in_array($m->mime_type, ['image/svg+xml', 'image/svg']))->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.media.partials.grid', compact('mediaItems'))->render(),
                'pagination' => $mediaItems->links()->toHtml(),
                'total' => $mediaItems->total(),
            ]);
        }

        return view('admin.media.index', compact('mediaItems', 'totalSizeFormatted', 'formatCounts'));
    }

    /**
     * Store newly uploaded image(s) to the Media Warehouse.
     * Allowed: jpg, jpeg, png, webp, svg. Max: 10MB (10240 KB).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:10240'],
        ], [
            'files.required' => 'Silakan pilih minimal 1 file gambar untuk diunggah.',
            'files.*.mimes' => 'Format file harus berupa JPG, JPEG, PNG, WEBP, atau SVG.',
            'files.*.max' => 'Ukuran file gambar maksimal adalah 10 MB.',
        ]);

        $warehouse = MediaWarehouse::getInstance();

        $config = WebConfiguration::current();
        if ($config && $config->limit_media_storage_mb > 0) {
            $totalBytes = Media::where('model_type', MediaWarehouse::class)
                ->where('model_id', $warehouse->id)
                ->sum('size');

            $incomingBytes = 0;
            foreach ($request->file('files') as $file) {
                $incomingBytes += $file->getSize();
            }

            $maxAllowedBytes = $config->limit_media_storage_mb * 1024 * 1024;
            if (($totalBytes + $incomingBytes) > $maxAllowedBytes) {
                $errorMsg = "Kapasitas penyimpanan Media Library telah melebihi batas kuota maksimal ({$config->limit_media_storage_mb} MB). Silakan tingkatkan kuota di Web Konfigurasi atau hapus gambar yang tidak terpakai.";
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMsg,
                    ], 422);
                }
                return redirect()->back()->with('error', $errorMsg);
            }
        }

        $uploadedCount = 0;

        foreach ($request->file('files') as $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $cleanName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = Str::slug($cleanName, '-');

            $customProps = [
                'original_name' => $file->getClientOriginalName(),
                'is_in_use' => false,
                'usages_count' => 0,
            ];

            // Get dimensions for raster images
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                $imageSize = @getimagesize($file->getRealPath());
                if ($imageSize) {
                    $customProps['width'] = $imageSize[0];
                    $customProps['height'] = $imageSize[1];
                }
            }

            $warehouse->addMedia($file)
                ->usingName($cleanName)
                ->usingFileName($cleanName . '_' . uniqid() . '.' . $extension)
                ->withCustomProperties($customProps)
                ->toMediaCollection('default');

            $uploadedCount++;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "{$uploadedCount} gambar berhasil diunggah ke Media Library!",
            ]);
        }

        return redirect()->route('admin.media.index')
            ->with('success', "{$uploadedCount} gambar berhasil diunggah ke Media Library!");
    }

    /**
     * Crop, resize, convert to WebP, and compress strictly under 200KB.
     */
    public function crop(Request $request, Media $media): JsonResponse|RedirectResponse
    {
        $request->validate([
            'image_data' => ['required', 'string'], // base64 data URL
            'save_as_new' => ['sometimes', 'boolean'],
            'target_width' => ['nullable', 'integer', 'min:1'],
            'target_height' => ['nullable', 'integer', 'min:1'],
        ]);

        $base64Data = $request->input('image_data');

        // Extract base64 image data
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $matches)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
        }

        $decodedImage = base64_decode($base64Data);
        if (!$decodedImage) {
            return response()->json([
                'success' => false,
                'message' => 'Format data gambar hasil crop tidak valid.',
            ], 422);
        }

        $gdImage = @imagecreatefromstring($decodedImage);
        if (!$gdImage) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses gambar pada server.',
            ], 422);
        }

        // Ensure proper alpha transparency handling
        imagealphablending($gdImage, true);
        imagesavealpha($gdImage, true);

        $tempPath = storage_path('app/temp_' . uniqid() . '.webp');

        // Compress strictly under 200KB as WebP
        $this->saveAsWebpUnder200KB($gdImage, $tempPath, 85);
        imagedestroy($gdImage);

        $finalFileSize = filesize($tempPath);
        $finalDimensions = @getimagesize($tempPath);
        $width = $finalDimensions ? $finalDimensions[0] : null;
        $height = $finalDimensions ? $finalDimensions[1] : null;

        $saveAsNew = $request->boolean('save_as_new', false);
        $warehouse = MediaWarehouse::getInstance();

        if ($saveAsNew) {
            $baseName = Str::slug($media->name, '-') . '-cropped';
            $warehouse->addMedia($tempPath)
                ->usingName($baseName)
                ->usingFileName($baseName . '_' . uniqid() . '.webp')
                ->withCustomProperties([
                    'original_name' => $baseName . '.webp',
                    'width' => $width,
                    'height' => $height,
                    'is_in_use' => false,
                    'usages_count' => 0,
                    'is_cropped' => true,
                ])
                ->toMediaCollection('default');
        } else {
            // Check if media is currently in use before replacing
            if ($this->isMediaInUse($media)) {
                @unlink($tempPath);
                return response()->json([
                    'success' => false,
                    'message' => 'Gambar ini sedang digunakan pada sistem dan tidak dapat ditimpa secara langsung. Silakan pilih opsi "Simpan Sebagai Gambar Baru".',
                ], 422);
            }

            // Replace current media file with cropped WebP
            $baseName = Str::slug($media->name, '-');
            $newFileName = $baseName . '_' . uniqid() . '.webp';

            $mediaPath = $media->getPath();
            if (File::exists($mediaPath)) {
                File::delete($mediaPath);
            }

            // Move temp file to media directory
            File::move($tempPath, dirname($mediaPath) . '/' . $newFileName);

            $media->file_name = $newFileName;
            $media->mime_type = 'image/webp';
            $media->size = $finalFileSize;
            $media->setCustomProperty('width', $width);
            $media->setCustomProperty('height', $height);
            $media->setCustomProperty('is_cropped', true);
            $media->save();
        }

        // Clean up temp file if still exists
        if (File::exists($tempPath)) {
            File::delete($tempPath);
        }

        $sizeKb = round($finalFileSize / 1024, 1);
        $message = "Gambar berhasil di-crop dan dikonversi ke format WebP ({$sizeKb} KB, {$width}x{$height} px)!";

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'size_kb' => $sizeKb,
                'width' => $width,
                'height' => $height,
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', $message);
    }

    /**
     * Delete media item with strict usage protection.
     */
    public function destroy(Request $request, Media $media): RedirectResponse|JsonResponse
    {
        // 1. Guard against deleting used images
        if ($this->isMediaInUse($media)) {
            $errorMessage = 'Gambar ini sedang digunakan pada sistem dan tidak dapat dihapus.';
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                ], 422);
            }
            return redirect()->route('admin.media.index')->with('error', $errorMessage);
        }

        $fileName = $media->name;
        $media->delete();

        $successMessage = "Gambar '{$fileName}' berhasil dihapus dari Media Library.";

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return redirect()->route('admin.media.index')->with('success', $successMessage);
    }

    /**
     * Check if a media item is in active use across the system.
     */
    private function isMediaInUse(Media $media): bool
    {
        // Check custom property usage flag
        if ($media->getCustomProperty('is_in_use', false) === true) {
            return true;
        }

        if ((int) $media->getCustomProperty('usages_count', 0) > 0) {
            return true;
        }

        // Check Web Configuration references
        $config = WebConfiguration::first();
        if ($config) {
            if ($config->logo_path && (str_contains($config->logo_path, $media->file_name) || str_contains($config->logo_path, $media->id))) {
                return true;
            }
            if ($config->favicon_path && (str_contains($config->favicon_path, $media->file_name) || str_contains($config->favicon_path, $media->id))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Save GD Image as WebP ensuring file size is strictly under 200KB (204,800 bytes).
     */
    private function saveAsWebpUnder200KB(\GdImage $gdImage, string $destinationPath, int $initialQuality = 85): bool
    {
        $maxBytes = 200 * 1024; // 204,800 bytes
        $quality = $initialQuality;

        $width = imagesx($gdImage);
        $height = imagesy($gdImage);
        $currentImage = $gdImage;

        // First compression test
        ob_start();
        imagewebp($currentImage, null, $quality);
        $data = ob_get_clean();

        // 1. Reduce quality step-by-step
        while (strlen($data) > $maxBytes && $quality > 25) {
            $quality -= 10;
            ob_start();
            imagewebp($currentImage, null, $quality);
            $data = ob_get_clean();
        }

        // 2. If still > 200KB, scale down dimensions progressively
        while (strlen($data) > $maxBytes && ($width > 300 || $height > 300)) {
            $width = (int) round($width * 0.85);
            $height = (int) round($height * 0.85);

            $resized = imagecreatetruecolor($width, $height);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $currentImage, 0, 0, 0, 0, $width, $height, imagesx($currentImage), imagesy($currentImage));

            if ($currentImage !== $gdImage) {
                imagedestroy($currentImage);
            }
            $currentImage = $resized;
            $quality = 75;

            ob_start();
            imagewebp($currentImage, null, $quality);
            $data = ob_get_clean();
        }

        $result = file_put_contents($destinationPath, $data);

        if ($currentImage !== $gdImage) {
            imagedestroy($currentImage);
        }

        return $result !== false;
    }

    /**
     * Format bytes into readable KB/MB.
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Get JSON list of media items for Media Picker modal.
     */
    public function apiList(Request $request): JsonResponse
    {
        $warehouse = MediaWarehouse::getInstance();

        $query = Media::where('model_type', MediaWarehouse::class)
            ->where('model_id', $warehouse->id);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('format') && $request->input('format') !== 'all') {
            $format = strtolower($request->input('format'));
            if ($format === 'jpg' || $format === 'jpeg') {
                $query->whereIn('mime_type', ['image/jpeg', 'image/jpg']);
            } elseif ($format === 'png') {
                $query->where('mime_type', 'image/png');
            } elseif ($format === 'webp') {
                $query->where('mime_type', 'image/webp');
            } elseif ($format === 'svg') {
                $query->whereIn('mime_type', ['image/svg+xml', 'image/svg']);
            }
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(18);

        $formatted = $items->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'file_name' => $item->file_name,
                'url' => $item->getUrl(),
                'size_formatted' => $this->formatBytes($item->size),
                'mime_type' => $item->mime_type,
                'created_at_human' => $item->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'data' => $formatted,
            'current_page' => $items->currentPage(),
            'last_page' => $items->lastPage(),
            'total' => $items->total(),
            'has_more' => $items->hasMorePages(),
        ]);
    }
}

