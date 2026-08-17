<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define all permissions
        $permissions = [
            // General / Dashboard
            'view-dashboard',

            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',

            // Role & Permission Management
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'assign-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            'assign-permissions',

            // Content & Media Management
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'publish-content',
            'upload-media',
            'delete-media',

            // Support & Ticket Management
            'view-support',
            'reply-support',
            'manage-support',

            // Settings, Logs & System
            'view-settings',
            'edit-settings',
            'view-activity-logs',
            'view-telescope',
            'view-backups',
            'create-backups',

            // Financial, Reports & Ownership
            'view-analytics',
            'export-reports',
            'manage-billing',
            'manage-ownership',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Define and assign permissions to Roles

        // Role 1: Super Admin (Has all permissions)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // Role 2: Owner (Executive ownership & business management)
        $ownerRole = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $ownerRole->syncPermissions([
            'view-dashboard',
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'view-roles',
            'create-roles',
            'edit-roles',
            'assign-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'assign-permissions',
            'view-content',
            'publish-content',
            'view-support',
            'view-settings',
            'edit-settings',
            'view-activity-logs',
            'view-backups',
            'create-backups',
            'view-analytics',
            'export-reports',
            'manage-billing',
            'manage-ownership',
        ]);

        // Role 3: Admin (Daily operational and system management)
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions([
            'view-dashboard',
            'view-users',
            'create-users',
            'edit-users',
            'view-roles',
            'create-roles',
            'edit-roles',
            'assign-roles',
            'view-permissions',
            'create-permissions',
            'edit-permissions',
            'assign-permissions',
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'publish-content',
            'upload-media',
            'delete-media',
            'view-support',
            'reply-support',
            'manage-support',
            'view-settings',
            'view-activity-logs',
            'view-analytics',
            'export-reports',
        ]);

        // Role 4: Support (Customer service & user assistance)
        $supportRole = Role::firstOrCreate(['name' => 'Support', 'guard_name' => 'web']);
        $supportRole->syncPermissions([
            'view-dashboard',
            'view-users',
            'view-support',
            'reply-support',
            'manage-support',
            'view-activity-logs',
        ]);

        // Role 5: Editor (Content production and publishing)
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions([
            'view-dashboard',
            'view-content',
            'create-content',
            'edit-content',
            'delete-content',
            'publish-content',
            'upload-media',
            'delete-media',
            'export-reports',
        ]);

        // Role 6: User (Standard registered user)
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view-dashboard',
            'view-content',
        ]);
    }
}
