<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\WebConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    /**
     * Display a listing of Testimonials.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $testimonials = Testimonial::query()
                ->with('avatarMedia')
                ->select('testimonials.*')
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc');

            if ($request->filled('category')) {
                $testimonials->where('category', $request->input('category'));
            }

            if ($request->filled('rating')) {
                $testimonials->where('rating', (int) $request->input('rating'));
            }

            if ($request->has('is_active') && $request->input('is_active') !== '' && $request->input('is_active') !== null) {
                $testimonials->where('is_active', $request->input('is_active') == '1' ? 1 : 0);
            }

            return DataTables::of($testimonials)
                ->addIndexColumn()
                ->addColumn('client_info', function (Testimonial $testimonial) {
                    $avatar = $testimonial->getAvatar();
                    $avatarHtml = $avatar
                        ? '<img src="' . e($avatar) . '" alt="' . e($testimonial->name) . '" class="w-10 h-10 rounded-full object-cover border border-[#99cab7]/50 shrink-0 shadow-2xs">'
                        : '<div class="w-10 h-10 rounded-full bg-[#31725e]/10 text-[#31725e] font-extrabold text-xs flex items-center justify-center border border-[#99cab7]/30 shrink-0 uppercase">' . substr($testimonial->name, 0, 2) . '</div>';

                    $subInfo = array_filter([$testimonial->role_or_title, $testimonial->company]);
                    $subHtml = !empty($subInfo)
                        ? '<p class="text-[11px] text-stone-500 font-medium line-clamp-1">' . e(implode(' • ', $subInfo)) . '</p>'
                        : '<p class="text-[11px] text-stone-400 italic">Pelanggan</p>';

                    return '
                        <div class="flex items-center gap-3">
                            ' . $avatarHtml . '
                            <div class="space-y-0.5">
                                <h4 class="font-extrabold text-xs text-[#1d3e35]">' . e($testimonial->name) . '</h4>
                                ' . $subHtml . '
                            </div>
                        </div>
                    ';
                })
                ->addColumn('rating_stars', function (Testimonial $testimonial) {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $testimonial->rating) {
                            $stars .= '<span class="text-amber-400 text-sm">★</span>';
                        } else {
                            $stars .= '<span class="text-stone-300 text-sm">★</span>';
                        }
                    }
                    return '<div class="flex items-center gap-0.5 font-bold" title="' . $testimonial->rating . ' dari 5 Bintang">' . $stars . ' <span class="text-[10px] text-stone-500 ml-1 font-mono">(' . $testimonial->rating . ')</span></div>';
                })
                ->addColumn('content_snippet', function (Testimonial $testimonial) {
                    $plain = strip_tags($testimonial->content);
                    $snippet = mb_strimwidth($plain, 0, 90, '...');
                    return '<p class="text-[11px] text-stone-600 italic line-clamp-2 max-w-xs leading-relaxed">"' . e($snippet) . '"</p>';
                })
                ->addColumn('category_badge', function (Testimonial $testimonial) {
                    $cat = $testimonial->category ?: 'Umum';
                    return '<span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#295c4d] bg-[#f2f8f5] px-2.5 py-1 rounded-xl border border-[#99cab7]/40 shadow-2xs">' . e($cat) . '</span>';
                })
                ->addColumn('order_badge', function (Testimonial $testimonial) {
                    return '<span class="font-mono text-xs font-bold text-stone-600 bg-stone-100 px-2 py-0.5 rounded-lg">#' . $testimonial->order . '</span>';
                })
                ->addColumn('status_toggle', function (Testimonial $testimonial) {
                    $checked = $testimonial->is_active ? 'checked' : '';
                    $toggleUrl = route('admin.testimonials.toggle-status', $testimonial->id);

                    return '
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" ' . $checked . ' onchange="toggleTestimonialStatus(\'' . $toggleUrl . '\', this)" class="sr-only peer">
                            <div class="w-9 h-5 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    ';
                })
                ->addColumn('action', function (Testimonial $testimonial) {
                    $editUrl = route('admin.testimonials.edit', $testimonial->id);
                    $deleteUrl = route('admin.testimonials.destroy', $testimonial->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Testimonial">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <button type="button" onclick="confirmDeleteTestimonial(\'' . $deleteUrl . '\', \'' . addslashes($testimonial->name) . '\')" class="p-1.5 rounded-xl text-stone-500 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer" title="Hapus Testimonial">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['client_info', 'rating_stars', 'content_snippet', 'category_badge', 'order_badge', 'status_toggle', 'action'])
                ->make(true);
        }

        // Summary metrics
        $avgRating = Testimonial::avg('rating') ?: 5.0;
        $stats = [
            'total' => Testimonial::count(),
            'active' => Testimonial::where('is_active', true)->count(),
            'avg_rating' => number_format($avgRating, 1),
            'categories' => Testimonial::whereNotNull('category')->where('category', '!=', '')->distinct('category')->count('category'),
        ];

        // Available distinct categories for filter
        $categories = Testimonial::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.testimonials.index', compact('stats', 'categories'));
    }

    /**
     * Show the form for creating a new Testimonial.
     */
    public function create(): View
    {
        $categories = Testimonial::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.testimonials.create', compact('categories'));
    }

    /**
     * Store a newly created Testimonial in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $config = WebConfiguration::current();
        if ($config && $config->limit_testimonials_count > 0 && Testimonial::count() >= $config->limit_testimonials_count) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Jumlah Testimonial telah mencapai batas kuota maksimal ({$config->limit_testimonials_count} testimoni). Silakan perluas kuota di Web Konfigurasi.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_or_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'avatar_media_id' => 'nullable|string|exists:media,id',
            'avatar_url' => 'nullable|string|max:2048',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        Testimonial::create($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimoni baru dari "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified Testimonial.
     */
    public function edit(Testimonial $testimonial): View
    {
        $testimonial->load('avatarMedia');
        $categories = Testimonial::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.testimonials.edit', compact('testimonial', 'categories'));
    }

    /**
     * Update the specified Testimonial in storage.
     */
    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role_or_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'avatar_media_id' => 'nullable|string|exists:media,id',
            'avatar_url' => 'nullable|string|max:2048',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $testimonial->update($validated);

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimoni dari "' . $testimonial->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified Testimonial from storage.
     */
    public function destroy(Testimonial $testimonial): RedirectResponse|JsonResponse
    {
        $name = $testimonial->name;
        $testimonial->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Testimoni dari "' . $name . '" berhasil dihapus!',
            ]);
        }

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'Testimoni dari "' . $name . '" berhasil dihapus!');
    }

    /**
     * Toggle the active status of a Testimonial via AJAX.
     */
    public function toggleStatus(Testimonial $testimonial): JsonResponse
    {
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();

        return response()->json([
            'success' => true,
            'is_active' => $testimonial->is_active,
            'message' => 'Status testimoni berhasil diubah menjadi ' . ($testimonial->is_active ? 'Aktif' : 'Nonaktif') . '.',
        ]);
    }
}
