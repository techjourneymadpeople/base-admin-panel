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
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of roles with Yajra DataTables support.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $roles = Role::withCount(['permissions', 'users'])->select('roles.*');

            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('role_name', function (Role $role) {
                    $colorMap = [
                        'Super Admin' => 'bg-[#1d3e35] text-white border-[#1d3e35]',
                        'Owner' => 'bg-[#784732] text-white border-[#784732]',
                        'Admin' => 'bg-[#295c4d] text-white border-[#295c4d]',
                        'Support' => 'bg-[#b17042] text-white border-[#b17042]',
                        'Editor' => 'bg-[#31725e] text-white border-[#31725e]',
                        'User' => 'bg-[#e2f0ea] text-[#1d3e35] border-[#99cab7]/40',
                    ];

                    $badgeClass = $colorMap[$role->name] ?? 'bg-stone-100 text-stone-800 border-stone-200';

                    return '
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border uppercase tracking-wider shadow-2xs ' . $badgeClass . '">
                                ' . e($role->name) . '
                            </span>
                        </div>
                    ';
                })
                ->addColumn('permissions_count', function (Role $role) {
                    if ($role->name === 'Super Admin') {
                        return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                            ⚡ Akses Penuh (Bypass)
                        </span>';
                    }

                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#f2f8f5] text-[#295c4d] border border-[#99cab7]/40">
                        <i data-lucide="key" class="w-3.5 h-3.5 text-[#31725e]"></i>
                        ' . $role->permissions_count . ' Permission
                    </span>';
                })
                ->addColumn('users_count', function (Role $role) {
                    return '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-stone-100 text-stone-700 border border-stone-200">
                        <i data-lucide="users" class="w-3.5 h-3.5 text-stone-500"></i>
                        ' . $role->users_count . ' Pengguna
                    </span>';
                })
                ->addColumn('guard_name', function (Role $role) {
                    return '<span class="text-xs font-mono text-stone-500">' . e($role->guard_name) . '</span>';
                })
                ->addColumn('action', function (Role $role) {
                    $showUrl = route('admin.roles.show', $role->id);
                    $editUrl = route('admin.roles.edit', $role->id);
                    $permUrl = route('admin.roles.permissions', $role->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $showUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors" title="Lihat Detail Role">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $permUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#b17042] hover:bg-amber-50 transition-colors" title="Assign Permissions">
                                <i data-lucide="shield-check" class="w-4 h-4 text-[#b17042]"></i>
                            </a>
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Nama Role">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                        </div>
                    ';
                })
                ->rawColumns(['role_name', 'permissions_count', 'users_count', 'guard_name', 'action'])
                ->toJson();
        }

        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new role.
     */
    public function create(): View
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'guard_name' => ['sometimes', 'string', 'max:255'],
        ]);

        $role = Role::create([
            'name' => trim($validated['name']),
            'guard_name' => $validated['guard_name'] ?? 'web',
        ]);

        return redirect()->route('admin.roles.permissions', $role->id)
            ->with('success', "Role '{$role->name}' berhasil dibuat! Silakan atur hak akses / permission untuk role ini.");
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role): View
    {
        $role->load(['permissions', 'users']);
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        // Protect Super Admin role renaming
        if ($role->name === 'Super Admin' && $request->name !== 'Super Admin') {
            return back()->with('error', 'Nama role Super Admin tidak dapat diubah.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
        ]);

        $role->name = trim($validated['name']);
        $role->save();

        return redirect()->route('admin.roles.index')
            ->with('success', "Nama role berhasil diperbarui menjadi '{$role->name}'!");
    }

    /**
     * Show form for assigning permissions to a role.
     */
    public function permissions(Role $role): View
    {
        $allPermissions = Permission::orderBy('name')->get();
        $rolePermissionNames = $role->permissions->pluck('name')->toArray();

        // Group permissions logically by module / prefix
        $groupedPermissions = $allPermissions->groupBy(function (Permission $permission) {
            $parts = explode('-', $permission->name, 2);
            if (count($parts) === 2) {
                return ucfirst($parts[1]); // e.g. "Users", "Roles", "Permissions", "Dashboard", "Settings"
            }
            return 'General';
        });

        return view('admin.roles.permissions', compact('role', 'groupedPermissions', 'rolePermissionNames'));
    }

    /**
     * Update permissions for a role.
     */
    public function updatePermissions(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $permissions = $validated['permissions'] ?? [];

        // Sync permissions
        $role->syncPermissions($permissions);

        $count = count($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', "Hak akses permission untuk role '{$role->name}' berhasil diperbarui ({$count} permission aktif)!");
    }
}
