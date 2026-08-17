<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    /**
     * Display a listing of dynamic menus with Yajra DataTables support.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $menus = Menu::with(['parent', 'permissions'])->select('menus.*')->orderBy('order', 'asc');

            return DataTables::of($menus)
                ->addIndexColumn()
                ->addColumn('menu_title', function (Menu $menu) {
                    $iconHtml = $menu->icon 
                        ? '<div class="w-8 h-8 rounded-xl bg-[#e2f0ea] text-[#31725e] flex items-center justify-center shrink-0"><i data-lucide="' . e($menu->icon) . '" class="w-4 h-4"></i></div>'
                        : '<div class="w-8 h-8 rounded-xl bg-stone-100 text-stone-400 flex items-center justify-center shrink-0"><i data-lucide="minus" class="w-4 h-4"></i></div>';

                    $badgeHtml = $menu->badge
                        ? '<span class="ml-1.5 px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800">' . e($menu->badge) . '</span>'
                        : '';

                    return '
                        <div class="flex items-center gap-3">
                            ' . $iconHtml . '
                            <div>
                                <span class="font-bold text-xs text-[#1d3e35]">' . e($menu->title) . '</span>
                                ' . $badgeHtml . '
                            </div>
                        </div>
                    ';
                })
                ->addColumn('type_badge', function (Menu $menu) {
                    $styles = [
                        'header' => 'bg-stone-100 text-stone-700 border-stone-200',
                        'link' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'dropdown' => 'bg-amber-50 text-amber-800 border-amber-200',
                    ];
                    $typeClass = $styles[$menu->type] ?? 'bg-stone-100 text-stone-700 border-stone-200';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border uppercase tracking-wider ' . $typeClass . '">' . e(ucfirst($menu->type)) . '</span>';
                })
                ->addColumn('parent_title', function (Menu $menu) {
                    if ($menu->parent) {
                        return '<span class="inline-flex items-center gap-1 text-xs font-semibold text-[#295c4d] bg-[#f2f8f5] px-2.5 py-0.5 rounded-lg border border-[#99cab7]/40"><i data-lucide="corner-down-right" class="w-3 h-3 text-[#31725e]"></i> ' . e($menu->parent->title) . '</span>';
                    }
                    return '<span class="text-xs text-stone-400 font-medium">— Top Level</span>';
                })
                ->addColumn('destination', function (Menu $menu) {
                    if ($menu->route) {
                        return '<span class="font-mono text-xs font-semibold text-[#1d3e35] bg-stone-50 px-2 py-0.5 rounded-md border border-stone-200">' . e($menu->route) . '</span>';
                    }
                    if ($menu->url && $menu->url !== '#') {
                        return '<span class="font-mono text-xs text-stone-500">' . e($menu->url) . '</span>';
                    }
                    return '<span class="text-xs text-stone-400 italic">—</span>';
                })
                ->addColumn('order_num', function (Menu $menu) {
                    return '<span class="font-mono text-xs font-bold text-stone-600 bg-stone-100 px-2 py-0.5 rounded-md">' . $menu->order . '</span>';
                })
                ->addColumn('status_badge', function (Menu $menu) {
                    return $menu->is_active
                        ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif</span>'
                        : '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-500 border border-stone-200"><span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span> Non-Aktif</span>';
                })
                ->addColumn('permissions_count', function (Menu $menu) {
                    $count = $menu->permissions->count();
                    if ($count > 0) {
                        return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#e2f0ea] text-[#1d3e35] border border-[#99cab7]/50"><i data-lucide="key" class="w-3.5 h-3.5 text-[#31725e]"></i> ' . $count . ' View Permission</span>';
                    }
                    return '<span class="text-xs text-stone-400 italic">Semua Role</span>';
                })
                ->addColumn('action', function (Menu $menu) {
                    $showUrl = route('admin.menus.show', $menu->id);
                    $editUrl = route('admin.menus.edit', $menu->id);
                    $permUrl = route('admin.menus.permissions', $menu->id);
                    $deleteUrl = route('admin.menus.destroy', $menu->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $showUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors" title="Lihat Detail Menu">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $permUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#b17042] hover:bg-amber-50 transition-colors" title="Assign View Permissions">
                                <i data-lucide="key" class="w-4 h-4 text-[#b17042]"></i>
                            </a>
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Menu">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <button type="button" @click="$dispatch(\'open-delete-modal\', { url: \'' . $deleteUrl . '\', name: \'' . addslashes(e($menu->title)) . '\' })" class="p-1.5 rounded-xl text-stone-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Menu">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['menu_title', 'type_badge', 'parent_title', 'destination', 'order_num', 'status_badge', 'permissions_count', 'action'])
                ->toJson();
        }

        return view('admin.menus.index');
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create(): View
    {
        $parentOptions = Menu::whereIn('type', ['header', 'dropdown'])
            ->orderBy('order', 'asc')
            ->get()
            ->pluck('title', 'id')
            ->toArray();

        return view('admin.menus.create', compact('parentOptions'));
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:header,link,dropdown'],
            'parent_id' => ['nullable', 'string', 'exists:menus,id'],
            'route' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'badge' => ['nullable', 'string', 'max:50'],
            'badge_color' => ['nullable', 'string', 'max:50'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['badge_color'] = $validated['badge_color'] ?? 'emerald';
        $validated['target'] = $validated['target'] ?? '_self';

        $menu = Menu::create($validated);

        return redirect()->route('admin.menus.permissions', $menu->id)
            ->with('success', "Menu '{$menu->title}' berhasil dibuat! Silakan atur hak akses view permission untuk menu ini.");
    }

    /**
     * Display the specified menu.
     */
    public function show(Menu $menu): View
    {
        $menu->load(['parent', 'children', 'permissions']);
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu): View
    {
        $parentOptions = Menu::whereIn('type', ['header', 'dropdown'])
            ->where('id', '!=', $menu->id)
            ->orderBy('order', 'asc')
            ->get()
            ->pluck('title', 'id')
            ->toArray();

        return view('admin.menus.edit', compact('menu', 'parentOptions'));
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:header,link,dropdown'],
            'parent_id' => ['nullable', 'string', 'exists:menus,id', Rule::notIn([$menu->id])],
            'route' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:50'],
            'badge' => ['nullable', 'string', 'max:50'],
            'badge_color' => ['nullable', 'string', 'max:50'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'target' => ['nullable', 'string', 'in:_self,_blank'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['badge_color'] = $validated['badge_color'] ?? 'emerald';
        $validated['target'] = $validated['target'] ?? '_self';

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
            ->with('success', "Menu '{$menu->title}' berhasil diperbarui!");
    }

    /**
     * Remove the specified menu item from storage.
     */
    public function destroy(Menu $menu): RedirectResponse
    {
        $title = $menu->title;
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('success', "Menu '{$title}' berhasil dihapus dari sistem.");
    }

    /**
     * Show form for assigning view permissions to a menu.
     * Note: Filter ONLY view-* permissions per user instruction.
     */
    public function permissions(Menu $menu): View
    {
        // FILTER: Only permissions starting with 'view-'
        $viewPermissions = Permission::where('name', 'like', 'view-%')
            ->orderBy('name', 'asc')
            ->get();

        $menuPermissionNames = $menu->permissions->pluck('name')->toArray();

        // Group view permissions by module suffix
        $groupedPermissions = $viewPermissions->groupBy(function (Permission $permission) {
            $parts = explode('-', $permission->name, 2);
            if (count($parts) === 2) {
                return ucfirst($parts[1]); // e.g. "Users", "Roles", "Permissions", "Dashboard", "Menus"
            }
            return 'General';
        });

        return view('admin.menus.permissions', compact('menu', 'groupedPermissions', 'menuPermissionNames'));
    }

    /**
     * Update view permissions for a menu item.
     */
    public function updatePermissions(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'starts_with:view-', 'exists:permissions,name'],
        ]);

        $permissions = $validated['permissions'] ?? [];

        // Sync view permissions
        $menu->syncPermissions($permissions);

        $count = count($permissions);

        return redirect()->route('admin.menus.index')
            ->with('success', "Hak akses permission untuk menu '{$menu->title}' berhasil diperbarui ({$count} view permission aktif)!");
    }
}
