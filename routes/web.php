<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 1. User Export & Import (Excel)
    Route::get('/users/export/excel', [UserController::class, 'export'])->name('users.export')->middleware('can:view-users');
    Route::post('/users/import/excel', [UserController::class, 'import'])->name('users.import')->middleware('can:create-users');
    Route::get('/users/import/template', [UserController::class, 'downloadTemplate'])->name('users.import.template')->middleware('can:create-users');

    // 2. Dedicated Assign Role Routes
    Route::get('/users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit')->middleware('can:assign-roles');
    Route::put('/users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update')->middleware('can:assign-roles');

    // 3. User CRUD Resource (No Delete)
    Route::resource('users', UserController::class, ['except' => ['destroy']]);

    // 4. Role Routes (with Dedicated Assign Permissions)
    Route::get('/roles/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions')->middleware('can:assign-permissions');
    Route::put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update')->middleware('can:assign-permissions');
    Route::resource('roles', RoleController::class);

    // 5. Permission Routes
    Route::resource('permissions', PermissionController::class);
});
