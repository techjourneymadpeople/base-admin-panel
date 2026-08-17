<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions with Yajra DataTables support.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $permissions = Permission::withCount('roles')->select('permissions.*');

            return DataTables::of($permissions)
                ->addIndexColumn()
                ->addColumn('perm_name', function (Permission $perm) {
                    return '
                        <div class="flex items-center gap-2.5 font-mono text-xs font-bold text-[#1d3e35]">
                            <div class="w-7 h-7 rounded-lg bg-[#e2f0ea] flex items-center justify-center text-[#31725e] shrink-0">
                                <i data-lucide="key" class="w-3.5 h-3.5"></i>
                            </div>
                            <span>' . e($perm->name) . '</span>
                        </div>
                    ';
                })
                ->addColumn('module_group', function (Permission $perm) {
                    $parts = explode('-', $perm->name, 2);
                    $module = count($parts) === 2 ? ucfirst($parts[1]) : 'General';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#f2f8f5] text-[#295c4d] border border-[#99cab7]/40">' . e($module) . '</span>';
                })
                ->addColumn('roles_count', function (Permission $perm) {
                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700 border border-stone-200">
                        <i data-lucide="shield" class="w-3.5 h-3.5 text-stone-500"></i>
                        ' . $perm->roles_count . ' Role
                    </span>';
                })
                ->addColumn('guard_name', function (Permission $perm) {
                    return '<span class="text-xs font-mono text-stone-500">' . e($perm->guard_name) . '</span>';
                })
                ->addColumn('action', function (Permission $perm) {
                    $showUrl = route('admin.permissions.show', $perm->id);
                    $editUrl = route('admin.permissions.edit', $perm->id);
                    $deleteUrl = route('admin.permissions.destroy', $perm->id);

                    // Core system permissions
                    $corePermissions = [
                        'view-dashboard', 'view-users', 'create-users', 'edit-users', 'delete-users',
                        'assign-roles', 'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
                        'assign-permissions', 'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions'
                    ];
                    $isCore = in_array($perm->name, $corePermissions);

                    $deleteButton = $isCore
                        ? '<button type="button" disabled class="p-1.5 rounded-xl text-stone-300 cursor-not-allowed" title="Permission sistem tidak dapat dihapus"><i data-lucide="trash-2" class="w-4 h-4"></i></button>'
                        : '<button type="button" @click="$dispatch(\'open-delete-modal\', { url: \'' . $deleteUrl . '\', name: \'' . addslashes(e($perm->name)) . '\' })" class="p-1.5 rounded-xl text-stone-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus Permission"><i data-lucide="trash-2" class="w-4 h-4"></i></button>';

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $showUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors" title="Lihat Detail">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Permission">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            ' . $deleteButton . '
                        </div>
                    ';
                })
                ->rawColumns(['perm_name', 'module_group', 'roles_count', 'guard_name', 'action'])
                ->toJson();
        }

        return view('admin.permissions.index');
    }

    /**
     * Show the form for creating new permission(s).
     */
    public function create(): View
    {
        return view('admin.permissions.create');
    }

    /**
     * Store newly created permission(s).
     */
    public function store(Request $request): RedirectResponse
    {
        $creationType = $request->input('creation_type', 'single');

        if ($creationType === 'bulk') {
            $validated = $request->validate([
                'module' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9_\-]+$/'],
                'actions' => ['required', 'array', 'min:1'],
                'actions.*' => ['string', 'in:view,create,edit,delete,export,import,manage'],
            ]);

            $moduleSlug = Str::slug($validated['module']);
            $createdCount = 0;

            foreach ($validated['actions'] as $action) {
                $permissionName = "{$action}-{$moduleSlug}";
                if (!Permission::where('name', $permissionName)->where('guard_name', 'web')->exists()) {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ]);
                    $createdCount++;
                }
            }

            return redirect()->route('admin.permissions.index')
                ->with('success', "Berhasil membuat {$createdCount} permission baru untuk modul '{$moduleSlug}'!");
        }

        // Single creation
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name', 'regex:/^[a-zA-Z0-9_\-]+$/'],
        ]);

        $perm = Permission::create([
            'name' => strtolower(trim($validated['name'])),
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$perm->name}' berhasil dibuat!");
    }

    /**
     * Display the specified permission.
     */
    public function show(Permission $permission): View
    {
        $permission->load('roles.users');
        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission): View
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id), 'regex:/^[a-zA-Z0-9_\-]+$/'],
        ]);

        $permission->name = strtolower(trim($validated['name']));
        $permission->save();

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission berhasil diperbarui menjadi '{$permission->name}'!");
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission): RedirectResponse
    {
        // Core system permissions check
        $corePermissions = [
            'view-dashboard', 'view-users', 'create-users', 'edit-users', 'delete-users',
            'assign-roles', 'view-roles', 'create-roles', 'edit-roles', 'delete-roles',
            'assign-permissions', 'view-permissions', 'create-permissions', 'edit-permissions', 'delete-permissions'
        ];

        if (in_array($permission->name, $corePermissions)) {
            return back()->with('error', "Permission inti sistem '{$permission->name}' tidak dapat dihapus.");
        }

        $name = $permission->name;
        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', "Permission '{$name}' berhasil dihapus dari sistem.");
    }
}
