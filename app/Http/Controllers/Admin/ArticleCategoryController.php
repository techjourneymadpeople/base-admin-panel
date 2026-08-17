<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of article categories.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $categories = ArticleCategory::withCount('articles')->select('article_categories.*')->orderBy('order', 'asc')->orderBy('name', 'asc');

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('name_info', function (ArticleCategory $category) {
                    return '
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center shrink-0">
                                <i data-lucide="folder" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="font-bold text-xs text-[#1d3e35]">' . e($category->name) . '</span>
                                <div class="text-[11px] text-stone-400 font-mono">/' . e($category->slug) . '</div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('description_text', function (ArticleCategory $category) {
                    if ($category->description) {
                        return '<span class="text-xs text-stone-600 line-clamp-1">' . e($category->description) . '</span>';
                    }
                    return '<span class="text-xs text-stone-400 italic">—</span>';
                })
                ->addColumn('articles_count_badge', function (ArticleCategory $category) {
                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-[#e2f0ea] text-[#1d3e35] border border-[#99cab7]/40"><i data-lucide="file-text" class="w-3.5 h-3.5 text-[#31725e]"></i> ' . $category->articles_count . ' Artikel</span>';
                })
                ->addColumn('status_badge', function (ArticleCategory $category) {
                    return $category->is_active
                        ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>'
                        : '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-500 border border-stone-200"><span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span> Non-Aktif</span>';
                })
                ->addColumn('action', function (ArticleCategory $category) {
                    $editUrl = route('admin.article-categories.edit', $category->id);
                    $deleteUrl = route('admin.article-categories.destroy', $category->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Kategori">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <button type="button" @click="$dispatch(\'open-delete-modal\', { url: \'' . $deleteUrl . '\', name: \'' . addslashes(e($category->name)) . '\' })" class="p-1.5 rounded-xl text-stone-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Kategori">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['name_info', 'description_text', 'articles_count_badge', 'status_badge', 'action'])
                ->toJson();
        }

        return view('admin.article-categories.index');
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.article-categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        ArticleCategory::create($validated);

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori artikel berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the category.
     */
    public function edit(ArticleCategory $articleCategory): View
    {
        return view('admin.article-categories.edit', compact('articleCategory'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, ArticleCategory $articleCategory): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? 0;

        $articleCategory->update($validated);

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(ArticleCategory $articleCategory): RedirectResponse
    {
        if ($articleCategory->articles()->count() > 0) {
            return redirect()->route('admin.article-categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki artikel terkait!');
        }

        $articleCategory->delete();

        return redirect()->route('admin.article-categories.index')
            ->with('success', 'Kategori artikel berhasil dihapus!');
    }
}
