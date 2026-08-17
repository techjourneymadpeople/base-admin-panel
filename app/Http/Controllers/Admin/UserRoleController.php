<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    /**
     * Show the form for assigning roles to the specified user.
     */
    public function edit(User $user): View
    {
        $currentUser = auth()->user();
        if ($currentUser && !$currentUser->hasRole('Super Admin') && $user->hasRole('Super Admin')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola role Super Admin.');
        }

        $roles = Role::with('permissions')->orderBy('name')->get();
        $userRoleNames = $user->roles->pluck('name')->toArray();

        return view('admin.users.roles.edit', compact('user', 'roles', 'userRoleNames'));
    }

    /**
     * Update the assigned roles for the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $currentUser = auth()->user();
        if ($currentUser && !$currentUser->hasRole('Super Admin') && $user->hasRole('Super Admin')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengelola role Super Admin.');
        }
        $validated = $request->validate([
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        // Safety check: Prevent removing Super Admin role from the last Super Admin
        if ($user->hasRole('Super Admin') && !in_array('Super Admin', $validated['roles'])) {
            $superAdminCount = User::role('Super Admin')->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat mencabut hak akses Super Admin dari satu-satunya Super Admin di sistem.');
            }
        }

        // Sync roles via Spatie
        $user->syncRoles($validated['roles']);

        $roleList = implode(', ', $validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', "Hak akses role untuk pengguna '{$user->name}' berhasil diperbarui menjadi: {$roleList}!");
    }
}
