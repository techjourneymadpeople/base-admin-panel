<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $faqs = Faq::query()->select('faqs.*')->orderBy('order', 'asc')->orderBy('created_at', 'desc');

            if ($request->filled('category')) {
                $faqs->where('category', $request->input('category'));
            }

            if ($request->has('is_active') && $request->input('is_active') !== '' && $request->input('is_active') !== null) {
                $faqs->where('is_active', $request->input('is_active') == '1' ? 1 : 0);
            }

            return DataTables::of($faqs)
                ->addIndexColumn()
                ->addColumn('faq_info', function (Faq $faq) {
                    $plainAnswer = strip_tags($faq->answer);
                    $truncated = mb_strimwidth($plainAnswer, 0, 120, '...');

                    return '
                        <div class="space-y-1 max-w-md">
                            <h4 class="font-extrabold text-xs text-[#1d3e35] line-clamp-1">' . e($faq->question) . '</h4>
                            <p class="text-[11px] text-stone-500 line-clamp-2 leading-relaxed">' . e($truncated) . '</p>
                        </div>
                    ';
                })
                ->addColumn('category_badge', function (Faq $faq) {
                    $cat = $faq->category ?: 'Umum';
                    return '<span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#295c4d] bg-[#f2f8f5] px-2.5 py-1 rounded-xl border border-[#99cab7]/40 shadow-2xs">' . e($cat) . '</span>';
                })
                ->addColumn('order_badge', function (Faq $faq) {
                    return '<span class="font-mono text-xs font-bold text-stone-600 bg-stone-100 px-2 py-0.5 rounded-lg">#' . $faq->order . '</span>';
                })
                ->addColumn('status_toggle', function (Faq $faq) {
                    $checked = $faq->is_active ? 'checked' : '';
                    $toggleUrl = route('admin.faqs.toggle-status', $faq->id);

                    return '
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" ' . $checked . ' onchange="toggleFaqStatus(\'' . $toggleUrl . '\', this)" class="sr-only peer">
                            <div class="w-9 h-5 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    ';
                })
                ->addColumn('action', function (Faq $faq) {
                    $editUrl = route('admin.faqs.edit', $faq->id);
                    $deleteUrl = route('admin.faqs.destroy', $faq->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit FAQ">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <button type="button" onclick="confirmDeleteFaq(\'' . $deleteUrl . '\', \'' . addslashes($faq->question) . '\')" class="p-1.5 rounded-xl text-stone-500 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer" title="Hapus FAQ">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['faq_info', 'category_badge', 'order_badge', 'status_toggle', 'action'])
                ->make(true);
        }

        // Summary metrics
        $stats = [
            'total' => Faq::count(),
            'active' => Faq::where('is_active', true)->count(),
            'inactive' => Faq::where('is_active', false)->count(),
            'categories' => Faq::whereNotNull('category')->where('category', '!=', '')->distinct('category')->count('category'),
        ];

        // Available distinct categories for filter
        $categories = Faq::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.faqs.index', compact('stats', 'categories'));
    }

    /**
     * Show the form for creating a new FAQ.
     */
    public function create(): View
    {
        $categories = Faq::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.faqs.create', compact('categories'));
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        Faq::create($validated);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified FAQ.
     */
    public function edit(Faq $faq): View
    {
        $categories = Faq::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    /**
     * Update the specified FAQ in storage.
     */
    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $faq->update($validated);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ berhasil diperbarui!');
    }

    /**
     * Remove the specified FAQ from storage.
     */
    public function destroy(Faq $faq): RedirectResponse|JsonResponse
    {
        $question = $faq->question;
        $faq->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ "' . $question . '" berhasil dihapus!',
            ]);
        }

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ "' . $question . '" berhasil dihapus!');
    }

    /**
     * Toggle the active status of a FAQ via AJAX.
     */
    public function toggleStatus(Faq $faq): JsonResponse
    {
        $faq->is_active = !$faq->is_active;
        $faq->save();

        return response()->json([
            'success' => true,
            'is_active' => $faq->is_active,
            'message' => 'Status FAQ berhasil diubah menjadi ' . ($faq->is_active ? 'Aktif' : 'Nonaktif') . '.',
        ]);
    }
}
