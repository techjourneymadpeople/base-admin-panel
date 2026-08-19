<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryActivity;
use App\Models\GalleryActivityPhoto;
use App\Models\Media;
use App\Models\WebConfiguration;
use App\Services\SitemapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class GalleryActivityController extends Controller
{
    /**
     * Display a listing of gallery activities.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $galleries = GalleryActivity::with(['author', 'thumbnailMedia'])
                ->withCount('photos')
                ->select('gallery_activities.*')
                ->orderBy('created_at', 'desc');

            if ($request->filled('status')) {
                $galleries->where('status', $request->input('status'));
            }

            return DataTables::of($galleries)
                ->addIndexColumn()
                ->addColumn('gallery_info', function (GalleryActivity $gallery) {
                    $thumbUrl = $gallery->getThumbnail();
                    $thumbHtml = $thumbUrl 
                        ? '<img src="' . e($thumbUrl) . '" alt="Cover" class="w-14 h-12 rounded-xl object-cover border border-[#99cab7]/30 shrink-0 shadow-2xs">'
                        : '<div class="w-14 h-12 rounded-xl bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-400 shrink-0"><i data-lucide="image" class="w-5 h-5"></i></div>';

                    $dateInfo = $gallery->activity_date 
                        ? '<span class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#295c4d]"><i data-lucide="calendar" class="w-3 h-3 text-[#31725e]"></i> ' . $gallery->activity_date->translatedFormat('d M Y') . '</span>'
                        : '';

                    $locInfo = $gallery->location 
                        ? '<span class="inline-flex items-center gap-1 text-[11px] text-stone-500 truncate max-w-[160px]"><i data-lucide="map-pin" class="w-3 h-3 text-[#cca06e]"></i> ' . e($gallery->location) . '</span>'
                        : '';

                    return '
                        <div class="flex items-center gap-3">
                            ' . $thumbHtml . '
                            <div class="min-w-0">
                                <a href="' . route('admin.gallery-activities.show', $gallery->id) . '" class="font-bold text-xs text-[#1d3e35] hover:text-[#31725e] line-clamp-1 transition-colors">' . e($gallery->title) . '</a>
                                <div class="flex items-center gap-2.5 mt-1 flex-wrap">
                                    ' . $dateInfo . '
                                    ' . $locInfo . '
                                    <span class="text-[10px] text-stone-400 font-mono">/' . e($gallery->slug) . '</span>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('photos_badge', function (GalleryActivity $gallery) {
                    return '
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200/80 shadow-2xs">
                            <i data-lucide="images" class="w-3.5 h-3.5 text-sky-500"></i>
                            ' . number_format($gallery->photos_count) . ' Foto
                        </span>
                    ';
                })
                ->addColumn('author_info', function (GalleryActivity $gallery) {
                    $name = $gallery->author ? $gallery->author->name : 'Sistem';
                    return '
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-[#31725e] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                ' . strtoupper(substr($name, 0, 1)) . '
                            </div>
                            <span class="text-xs text-stone-700 font-medium truncate">' . e($name) . '</span>
                        </div>
                    ';
                })
                ->addColumn('status_badge', function (GalleryActivity $gallery) {
                    $statusConfig = [
                        'published' => ['label' => 'Terbit', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                        'draft' => ['label' => 'Draft', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
                        'archived' => ['label' => 'Arsip', 'class' => 'bg-stone-100 text-stone-600 border-stone-200', 'dot' => 'bg-stone-400'],
                    ];

                    $conf = $statusConfig[$gallery->status] ?? $statusConfig['draft'];

                    return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border ' . $conf['class'] . '"><span class="w-1.5 h-1.5 rounded-full ' . $conf['dot'] . '"></span> ' . $conf['label'] . '</span>';
                })
                ->addColumn('published_date', function (GalleryActivity $gallery) {
                    if ($gallery->published_at) {
                        return '<div class="text-xs text-stone-600 font-medium">' . $gallery->published_at->translatedFormat('d M Y') . '</div><div class="text-[10px] text-stone-400">' . $gallery->published_at->format('H:i') . ' WIB</div>';
                    }
                    return '<span class="text-xs text-stone-400 italic">—</span>';
                })
                ->addColumn('views_info', function (GalleryActivity $gallery) {
                    return '<span class="inline-flex items-center gap-1 text-xs font-semibold text-stone-600"><i data-lucide="eye" class="w-3.5 h-3.5 text-stone-400"></i> ' . number_format($gallery->views_count) . '</span>';
                })
                ->addColumn('action', function (GalleryActivity $gallery) {
                    $showUrl = route('admin.gallery-activities.show', $gallery->id);
                    $editUrl = route('admin.gallery-activities.edit', $gallery->id);
                    $deleteUrl = route('admin.gallery-activities.destroy', $gallery->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $showUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors" title="Lihat Galeri Foto">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Kegiatan">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <button type="button" onclick="confirmDeleteGallery(\'' . $deleteUrl . '\', \'' . addslashes($gallery->title) . '\')" class="p-1.5 rounded-xl text-stone-500 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer" title="Hapus Kegiatan">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['gallery_info', 'photos_badge', 'author_info', 'status_badge', 'published_date', 'views_info', 'action'])
                ->make(true);
        }

        // Summary metrics
        $stats = [
            'total' => GalleryActivity::count(),
            'published' => GalleryActivity::where('status', 'published')->count(),
            'draft' => GalleryActivity::where('status', 'draft')->count(),
            'total_photos' => GalleryActivityPhoto::count(),
        ];

        return view('admin.gallery-activities.index', compact('stats'));
    }

    /**
     * Show the form for creating a new gallery activity.
     */
    public function create(): View
    {
        return view('admin.gallery-activities.create');
    }

    /**
     * Store a newly created gallery activity in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $config = WebConfiguration::current();
        if ($config && $config->limit_gallery_activities_count > 0 && GalleryActivity::count() >= $config->limit_gallery_activities_count) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Jumlah galeri kegiatan telah mencapai batas kuota maksimal ({$config->limit_gallery_activities_count} galeri). Silakan perluas kuota di Web Konfigurasi.");
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'activity_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_media_id' => 'nullable|string|exists:media,id',
            'thumbnail_url' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'photos' => 'nullable|array',
            'photos.*.media_id' => 'required|string|exists:media,id',
            'photos.*.caption' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();

        // Default published_at if status is published and date is empty
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $gallery = GalleryActivity::create($validated);

        // Save Gallery Photos
        if (!empty($validated['photos']) && is_array($validated['photos'])) {
            foreach ($validated['photos'] as $index => $photoData) {
                if (!empty($photoData['media_id'])) {
                    $gallery->photos()->create([
                        'media_id' => $photoData['media_id'],
                        'caption' => $photoData['caption'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.gallery-activities.index')
            ->with('success', 'Galeri Kegiatan "' . $gallery->title . '" berhasil dibuat dan sitemap diperbarui!');
    }

    /**
     * Display the specified gallery activity.
     */
    public function show(GalleryActivity $galleryActivity): View
    {
        $galleryActivity->load(['author', 'thumbnailMedia', 'photos.media']);

        return view('admin.gallery-activities.show', [
            'gallery' => $galleryActivity,
        ]);
    }

    /**
     * Show the form for editing the specified gallery activity.
     */
    public function edit(GalleryActivity $galleryActivity): View
    {
        $galleryActivity->load(['thumbnailMedia', 'photos.media']);

        // Prepare existing photos for Alpine state
        $existingPhotos = $galleryActivity->photos->map(function ($p) {
            return [
                'media_id' => $p->media_id,
                'url' => $p->media ? $p->media->getUrl() : '',
                'filename' => $p->media ? $p->media->file_name : 'Foto',
                'caption' => $p->caption ?? '',
            ];
        })->values()->all();

        return view('admin.gallery-activities.edit', [
            'gallery' => $galleryActivity,
            'existingPhotos' => $existingPhotos,
        ]);
    }

    /**
     * Update the specified gallery activity in storage.
     */
    public function update(Request $request, GalleryActivity $galleryActivity): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'activity_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'thumbnail_media_id' => 'nullable|string|exists:media,id',
            'thumbnail_url' => 'nullable|string',
            'remove_thumbnail' => 'nullable|boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'photos' => 'nullable|array',
            'photos.*.media_id' => 'required|string|exists:media,id',
            'photos.*.caption' => 'nullable|string|max:255',
        ]);

        if ($request->boolean('remove_thumbnail')) {
            $validated['thumbnail_media_id'] = null;
            $validated['thumbnail_url'] = null;
        }

        if ($validated['status'] === 'published' && empty($validated['published_at']) && empty($galleryActivity->published_at)) {
            $validated['published_at'] = now();
        }

        $galleryActivity->update($validated);

        // Sync Gallery Photos
        $galleryActivity->photos()->delete();

        if (!empty($validated['photos']) && is_array($validated['photos'])) {
            foreach ($validated['photos'] as $index => $photoData) {
                if (!empty($photoData['media_id'])) {
                    $galleryActivity->photos()->create([
                        'media_id' => $photoData['media_id'],
                        'caption' => $photoData['caption'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.gallery-activities.index')
            ->with('success', 'Galeri Kegiatan "' . $galleryActivity->title . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified gallery activity from storage.
     */
    public function destroy(GalleryActivity $galleryActivity): RedirectResponse|JsonResponse
    {
        $title = $galleryActivity->title;
        $galleryActivity->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Galeri Kegiatan "' . $title . '" berhasil dihapus!',
            ]);
        }

        return redirect()
            ->route('admin.gallery-activities.index')
            ->with('success', 'Galeri Kegiatan "' . $title . '" berhasil dihapus!');
    }
}
