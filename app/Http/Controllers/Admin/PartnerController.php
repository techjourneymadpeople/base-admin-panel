<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\WebConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PartnerController extends Controller
{
    /**
     * Display a listing of Brands / Partners.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $partners = Partner::query()
                ->with('logoMedia')
                ->select('partners.*')
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc');

            if ($request->filled('category')) {
                $partners->where('category', $request->input('category'));
            }

            if ($request->has('is_active') && $request->input('is_active') !== '' && $request->input('is_active') !== null) {
                $partners->where('is_active', $request->input('is_active') == '1' ? 1 : 0);
            }

            return DataTables::of($partners)
                ->addIndexColumn()
                ->addColumn('partner_info', function (Partner $partner) {
                    $logo = $partner->getLogo();
                    $logoHtml = $logo
                        ? '<img src="' . e($logo) . '" alt="' . e($partner->name) . '" class="w-12 h-12 rounded-xl object-contain p-1 bg-white border border-[#99cab7]/30 shrink-0 shadow-2xs">'
                        : '<div class="w-12 h-12 rounded-xl bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-400 font-bold text-xs shrink-0">' . e(substr($partner->name, 0, 2)) . '</div>';

                    $siteLink = $partner->website_url
                        ? '<a href="' . e($partner->website_url) . '" target="_blank" class="text-[11px] text-[#31725e] hover:underline flex items-center gap-1 mt-0.5"><i data-lucide="external-link" class="w-3 h-3"></i> ' . e(parse_url($partner->website_url, PHP_URL_HOST) ?: $partner->website_url) . '</a>'
                        : '<span class="text-[11px] text-stone-400">Tidak ada tautan web</span>';

                    return '
                        <div class="flex items-center gap-3">
                            ' . $logoHtml . '
                            <div class="space-y-0.5">
                                <h4 class="font-extrabold text-xs text-[#1d3e35]">' . e($partner->name) . '</h4>
                                ' . $siteLink . '
                            </div>
                        </div>
                    ';
                })
                ->addColumn('category_badge', function (Partner $partner) {
                    $cat = $partner->category ?: 'Umum';
                    return '<span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#295c4d] bg-[#f2f8f5] px-2.5 py-1 rounded-xl border border-[#99cab7]/40 shadow-2xs">' . e($cat) . '</span>';
                })
                ->addColumn('order_badge', function (Partner $partner) {
                    return '<span class="font-mono text-xs font-bold text-stone-600 bg-stone-100 px-2 py-0.5 rounded-lg">#' . $partner->order . '</span>';
                })
                ->addColumn('status_toggle', function (Partner $partner) {
                    $checked = $partner->is_active ? 'checked' : '';
                    $toggleUrl = route('admin.partners.toggle-status', $partner->id);

                    return '
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" ' . $checked . ' onchange="togglePartnerStatus(\'' . $toggleUrl . '\', this)" class="sr-only peer">
                            <div class="w-9 h-5 bg-stone-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-stone-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#31725e]"></div>
                        </label>
                    ';
                })
                ->addColumn('action', function (Partner $partner) {
                    $editUrl = route('admin.partners.edit', $partner->id);
                    $deleteUrl = route('admin.partners.destroy', $partner->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Partner">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <button type="button" onclick="confirmDeletePartner(\'' . $deleteUrl . '\', \'' . addslashes($partner->name) . '\')" class="p-1.5 rounded-xl text-stone-500 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer" title="Hapus Partner">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['partner_info', 'category_badge', 'order_badge', 'status_toggle', 'action'])
                ->make(true);
        }

        // Summary metrics
        $stats = [
            'total' => Partner::count(),
            'active' => Partner::where('is_active', true)->count(),
            'inactive' => Partner::where('is_active', false)->count(),
            'categories' => Partner::whereNotNull('category')->where('category', '!=', '')->distinct('category')->count('category'),
        ];

        // Available distinct categories for filter
        $categories = Partner::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.partners.index', compact('stats', 'categories'));
    }

    /**
     * Show the form for creating a new Brand / Partner.
     */
    public function create(): View
    {
        $categories = Partner::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.partners.create', compact('categories'));
    }

    /**
     * Store a newly created Brand / Partner in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $config = WebConfiguration::current();
        if ($config && $config->limit_partners_count > 0 && Partner::count() >= $config->limit_partners_count) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Jumlah Brand / Partner telah mencapai batas kuota maksimal ({$config->limit_partners_count} partner). Silakan perluas kuota di Web Konfigurasi.");
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_media_id' => 'nullable|string|exists:media,id',
            'logo_url' => 'nullable|string|max:2048',
            'website_url' => 'nullable|url|max:255',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        Partner::create($validated);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Brand / Partner baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified Brand / Partner.
     */
    public function edit(Partner $partner): View
    {
        $partner->load('logoMedia');
        $categories = Partner::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category');

        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    /**
     * Update the specified Brand / Partner in storage.
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo_media_id' => 'nullable|string|exists:media,id',
            'logo_url' => 'nullable|string|max:2048',
            'website_url' => 'nullable|url|max:255',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $partner->update($validated);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Brand / Partner "' . $partner->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified Brand / Partner from storage.
     */
    public function destroy(Partner $partner): RedirectResponse|JsonResponse
    {
        $name = $partner->name;
        $partner->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Partner "' . $name . '" berhasil dihapus!',
            ]);
        }

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner "' . $name . '" berhasil dihapus!');
    }

    /**
     * Toggle the active status of a Brand / Partner via AJAX.
     */
    public function toggleStatus(Partner $partner): JsonResponse
    {
        $partner->is_active = !$partner->is_active;
        $partner->save();

        return response()->json([
            'success' => true,
            'is_active' => $partner->is_active,
            'message' => 'Status partner berhasil diubah menjadi ' . ($partner->is_active ? 'Aktif' : 'Nonaktif') . '.',
        ]);
    }
}
