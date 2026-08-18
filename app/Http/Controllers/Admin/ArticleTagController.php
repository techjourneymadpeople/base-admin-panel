<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ArticleTagController extends Controller
{
    /**
     * Display a listing of article tags.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $tags = ArticleTag::withCount('articles')->select('article_tags.*')->orderBy('name', 'asc');

            return DataTables::of($tags)
                ->addIndexColumn()
                ->addColumn('name_info', function (ArticleTag $tag) {
                    return '
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#fdf5ed] text-[#b17042] flex items-center justify-center shrink-0">
                                <i data-lucide="tag" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="font-bold text-xs text-[#1d3e35]">' . e($tag->name) . '</span>
                                <div class="text-[11px] text-stone-400 font-mono">#' . e($tag->slug) . '</div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('articles_count_badge', function (ArticleTag $tag) {
                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#e2f0ea] text-[#1d3e35] border border-[#99cab7]/40"><i data-lucide="file-text" class="w-3.5 h-3.5 text-[#31725e]"></i> ' . $tag->articles_count . ' Artikel</span>';
                })
                ->addColumn('created_formatted', function (ArticleTag $tag) {
                    return '<span class="text-xs text-stone-500 font-medium">' . $tag->created_at->translatedFormat('d M Y') . '</span>';
                })
                ->addColumn('action', function (ArticleTag $tag) {
                    $deleteUrl = route('admin.article-tags.destroy', $tag->id);
                    $tagJson = e(json_encode(['id' => $tag->id, 'name' => $tag->name]));

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <button type="button" @click="$dispatch(\'open-edit-modal\', ' . $tagJson . ')" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Tag">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button type="button" @click="$dispatch(\'open-delete-modal\', { url: \'' . $deleteUrl . '\', name: \'' . addslashes(e($tag->name)) . '\' })" class="p-1.5 rounded-xl text-stone-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Tag">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['name_info', 'articles_count_badge', 'created_formatted', 'action'])
                ->toJson();
        }

        return view('admin.article-tags.index');
    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $tag = ArticleTag::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag berhasil dibuat!',
                'tag' => $tag,
            ]);
        }

        return redirect()->route('admin.article-tags.index')
            ->with('success', 'Tag artikel berhasil ditambahkan!');
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, ArticleTag $articleTag): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $articleTag->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag berhasil diperbarui!',
                'tag' => $articleTag,
            ]);
        }

        return redirect()->route('admin.article-tags.index')
            ->with('success', 'Tag artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(ArticleTag $articleTag): RedirectResponse
    {
        $articleTag->articles()->detach();
        $articleTag->delete();

        return redirect()->route('admin.article-tags.index')
            ->with('success', 'Tag artikel berhasil dihapus!');
    }
}
