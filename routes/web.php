<?php

use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\WebConfigurationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 1. User Profile Management (Profil Saya)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // 2. Web Configuration (Direct Edit Form)
    Route::get('/settings', [WebConfigurationController::class, 'edit'])->name('settings.edit')->middleware('can:view-settings');
    Route::put('/settings', [WebConfigurationController::class, 'update'])->name('settings.update')->middleware('can:edit-settings');

    // 3. User Export & Import (Excel)
    Route::get('/users/export/excel', [UserController::class, 'export'])->name('users.export')->middleware('can:view-users');
    Route::post('/users/import/excel', [UserController::class, 'import'])->name('users.import')->middleware('can:create-users');
    Route::get('/users/import/template', [UserController::class, 'downloadTemplate'])->name('users.import.template')->middleware('can:create-users');

    // 4. Dedicated Assign Role Routes
    Route::get('/users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit')->middleware('can:assign-roles');
    Route::put('/users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update')->middleware('can:assign-roles');

    // 5. User CRUD Resource (No Delete)
    Route::resource('users', UserController::class, ['except' => ['destroy']]);

    // 6. Role Routes (with Dedicated Assign Permissions)
    Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions')->middleware('can:assign-permissions');
    Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update')->middleware('can:assign-permissions');
    Route::resource('roles', RoleController::class);

    // 7. Permission Routes
    Route::resource('permissions', PermissionController::class);

    // 8. Dynamic Menu Routes (with View-only Assign Permissions)
    Route::get('/menus/{menu}/permissions', [MenuController::class, 'permissions'])->name('menus.permissions')->middleware('can:assign-menu-permissions');
    Route::put('/menus/{menu}/permissions', [MenuController::class, 'updatePermissions'])->name('menus.permissions.update')->middleware('can:assign-menu-permissions');
    Route::resource('menus', MenuController::class);
});
