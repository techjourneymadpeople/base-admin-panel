<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\Media;
use App\Services\SitemapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $articles = Article::with(['category', 'author', 'thumbnailMedia', 'tags'])
                ->select('articles.*')
                ->orderBy('created_at', 'desc');

            if ($request->filled('category_id')) {
                $articles->where('category_id', $request->input('category_id'));
            }

            if ($request->filled('status')) {
                $articles->where('status', $request->input('status'));
            }

            return DataTables::of($articles)
                ->addIndexColumn()
                ->addColumn('article_info', function (Article $article) {
                    $thumbUrl = $article->getThumbnail();
                    $thumbHtml = $thumbUrl 
                        ? '<img src="' . e($thumbUrl) . '" alt="Thumb" class="w-14 h-11 rounded-xl object-cover border border-[#99cab7]/30 shrink-0 shadow-2xs">'
                        : '<div class="w-14 h-11 rounded-xl bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-400 shrink-0"><i data-lucide="image" class="w-5 h-5"></i></div>';

                    $categoryBadge = $article->category
                        ? '<span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#295c4d] bg-[#f2f8f5] px-2 py-0.5 rounded-md border border-[#99cab7]/40">' . e($article->category->name) . '</span>'
                        : '<span class="text-[11px] text-stone-400 italic">Tanpa Kategori</span>';

                    return '
                        <div class="flex items-center gap-3">
                            ' . $thumbHtml . '
                            <div class="min-w-0">
                                <a href="' . route('admin.articles.show', $article->id) . '" class="font-bold text-xs text-[#1d3e35] hover:text-[#31725e] line-clamp-1 transition-colors">' . e($article->title) . '</a>
                                <div class="flex items-center gap-2 mt-1">
                                    ' . $categoryBadge . '
                                    <span class="text-[10px] text-stone-400 font-mono">/' . e($article->slug) . '</span>
                                </div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('author_info', function (Article $article) {
                    $name = $article->author ? $article->author->name : 'Sistem';
                    return '
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-[#31725e] text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                ' . strtoupper(substr($name, 0, 1)) . '
                            </div>
                            <span class="text-xs text-stone-700 font-medium truncate">' . e($name) . '</span>
                        </div>
                    ';
                })
                ->addColumn('status_badge', function (Article $article) {
                    $statusConfig = [
                        'published' => ['label' => 'Terbit', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                        'draft' => ['label' => 'Draft', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
                        'archived' => ['label' => 'Arsip', 'class' => 'bg-stone-100 text-stone-600 border-stone-200', 'dot' => 'bg-stone-400'],
                    ];

                    $conf = $statusConfig[$article->status] ?? $statusConfig['draft'];

                    return '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border ' . $conf['class'] . '"><span class="w-1.5 h-1.5 rounded-full ' . $conf['dot'] . '"></span> ' . $conf['label'] . '</span>';
                })
                ->addColumn('published_date', function (Article $article) {
                    if ($article->published_at) {
                        return '<div class="text-xs text-stone-600 font-medium">' . $article->published_at->translatedFormat('d M Y') . '</div><div class="text-[10px] text-stone-400">' . $article->published_at->format('H:i') . ' WIB</div>';
                    }
                    return '<span class="text-xs text-stone-400 italic">—</span>';
                })
                ->addColumn('views_info', function (Article $article) {
                    return '<span class="inline-flex items-center gap-1 text-xs font-semibold text-stone-600"><i data-lucide="eye" class="w-3.5 h-3.5 text-stone-400"></i> ' . number_format($article->views_count) . '</span>';
                })
                ->addColumn('action', function (Article $article) {
                    $showUrl = route('admin.articles.show', $article->id);
                    $editUrl = route('admin.articles.edit', $article->id);
                    $deleteUrl = route('admin.articles.destroy', $article->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $showUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors" title="Lihat Detail & Analisis SEO">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Artikel">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <button type="button" @click="$dispatch(\'open-delete-modal\', { url: \'' . $deleteUrl . '\', name: \'' . addslashes(e($article->title)) . '\' })" class="p-1.5 rounded-xl text-stone-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Artikel">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['article_info', 'author_info', 'status_badge', 'published_date', 'views_info', 'action'])
                ->toJson();
        }

        $categories = ArticleCategory::where('is_active', true)->orderBy('name')->get();
        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $draftArticles = Article::where('status', 'draft')->count();

        return view('admin.articles.index', compact('categories', 'totalArticles', 'publishedArticles', 'draftArticles'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        $categories = ArticleCategory::where('is_active', true)->orderBy('name')->get();
        $tags = ArticleTag::orderBy('name')->get();

        return view('admin.articles.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:article_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'thumbnail_media_id' => ['nullable', 'exists:media,id'],
            'thumbnail_url' => ['nullable', 'string', 'max:1000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
        ]);

        $validated['user_id'] = auth()->id();

        // If status is published and published_at is empty, default to now
        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // If thumbnail_media_id is present, get its URL as fallback
        if (!empty($validated['thumbnail_media_id'])) {
            $media = Media::find($validated['thumbnail_media_id']);
            if ($media) {
                $validated['thumbnail_url'] = $media->getUrl();
            }
        }

        // Create article (Slug will be auto-generated by spatie/laravel-sluggable)
        $article = Article::create($validated);

        // Process Tags (Handle both existing and newly typed tags)
        if ($request->has('tags') && is_array($request->input('tags'))) {
            $tagIds = [];
            foreach ($request->input('tags') as $tagInput) {
                if (empty(trim($tagInput))) continue;

                // Check if it's already an existing ULID or ID
                $existingTag = ArticleTag::where('id', $tagInput)->orWhere('name', $tagInput)->first();
                if ($existingTag) {
                    $tagIds[] = $existingTag->id;
                } else {
                    $newTag = ArticleTag::create(['name' => trim($tagInput)]);
                    $tagIds[] = $newTag->id;
                }
            }
            $article->tags()->sync($tagIds);
        }

        // Ensure sitemap is updated
        SitemapService::generate();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dibuat dan sitemap.xml telah diperbarui secara otomatis!');
    }

    /**
     * Display the specified article with SEO preview analysis.
     */
    public function show(Article $article): View
    {
        $article->load(['category', 'author', 'thumbnailMedia', 'tags']);

        return view('admin.articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        $article->load(['category', 'thumbnailMedia', 'tags']);
        $categories = ArticleCategory::where('is_active', true)->orderBy('name')->get();
        $tags = ArticleTag::orderBy('name')->get();
        $selectedTagIds = $article->tags->pluck('id')->toArray();

        return view('admin.articles.edit', compact('article', 'categories', 'tags', 'selectedTagIds'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:article_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['nullable', 'string'],
            'thumbnail_media_id' => ['nullable', 'exists:media,id'],
            'thumbnail_url' => ['nullable', 'string', 'max:1000'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
        ]);

        // If status is published and published_at is empty, default to now
        if ($validated['status'] === 'published' && empty($validated['published_at']) && empty($article->published_at)) {
            $validated['published_at'] = now();
        }

        // If thumbnail_media_id is present, get its URL
        if (!empty($validated['thumbnail_media_id'])) {
            $media = Media::find($validated['thumbnail_media_id']);
            if ($media) {
                $validated['thumbnail_url'] = $media->getUrl();
            }
        } elseif ($request->has('remove_thumbnail') && $request->boolean('remove_thumbnail')) {
            $validated['thumbnail_media_id'] = null;
            $validated['thumbnail_url'] = null;
        }

        $article->update($validated);

        // Sync tags
        if ($request->has('tags')) {
            $tagIds = [];
            foreach ($request->input('tags', []) as $tagInput) {
                if (empty(trim($tagInput))) continue;

                $existingTag = ArticleTag::where('id', $tagInput)->orWhere('name', $tagInput)->first();
                if ($existingTag) {
                    $tagIds[] = $existingTag->id;
                } else {
                    $newTag = ArticleTag::create(['name' => trim($tagInput)]);
                    $tagIds[] = $newTag->id;
                }
            }
            $article->tags()->sync($tagIds);
        } else {
            $article->tags()->detach();
        }

        // Update sitemap
        SitemapService::generate();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui dan sitemap.xml telah diperbarui!');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->tags()->detach();
        $article->delete();

        // Update sitemap
        SitemapService::generate();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus dan sitemap.xml telah diperbarui!');
    }

    /**
     * Manual action to regenerate sitemap.xml.
     */
    public function regenerateSitemap(): RedirectResponse
    {
        $success = SitemapService::generate();

        if ($success) {
            return back()->with('success', 'File sitemap.xml berhasil diperbarui dengan seluruh artikel aktif!');
        }

        return back()->with('error', 'Gagal memperbarui sitemap.xml. Silakan periksa log aplikasi.');
    }
}
