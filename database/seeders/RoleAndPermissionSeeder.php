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

        // 1. Define only permissions that have active features and menus
        $permissions = [
            // General / Dashboard
            'view-dashboard',

            // User Management
            'view-users',
            'create-users',
            'edit-users',

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

            // Dynamic Menu Management
            'view-menus',
            'create-menus',
            'edit-menus',
            'delete-menus',
            'assign-menu-permissions',

            // Media Library
            'view-content',
            'upload-media',
            'delete-media',

            // Article SEO Management
            'view-article-categories',
            'create-article-categories',
            'edit-article-categories',
            'delete-article-categories',
            'view-article-tags',
            'create-article-tags',
            'edit-article-tags',
            'delete-article-tags',
            'view-articles',
            'create-articles',
            'edit-articles',
            'delete-articles',

            // Gallery Activity Management
            'view-gallery-activities',
            'create-gallery-activities',
            'edit-gallery-activities',
            'delete-gallery-activities',

            // FAQ Management
            'view-faqs',
            'create-faqs',
            'edit-faqs',
            'delete-faqs',

            // Brand & Partner Management
            'view-partners',
            'create-partners',
            'edit-partners',
            'delete-partners',

            // Testimonial Management
            'view-testimonials',
            'create-testimonials',
            'edit-testimonials',
            'delete-testimonials',

            // Profile Business Identity
            'view-business-identity',
            'edit-business-identity',

            // Web Configuration & Settings
            'view-settings',
            'edit-settings',
        ];

        // Clean up any stale permissions not present in active feature list
        Permission::whereNotIn('name', $permissions)->delete();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 2. Define and assign permissions to Roles

        // Role 1: Super Admin (Has all active permissions)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // Role 2: Owner (Executive ownership & management)
        $ownerRole = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'web']);
        $ownerRole->syncPermissions([
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
            'view-menus',
            'create-menus',
            'edit-menus',
            'delete-menus',
            'assign-menu-permissions',
            'view-content',
            'upload-media',
            'delete-media',
            'view-article-categories',
            'create-article-categories',
            'edit-article-categories',
            'delete-article-categories',
            'view-article-tags',
            'create-article-tags',
            'edit-article-tags',
            'delete-article-tags',
            'view-articles',
            'create-articles',
            'edit-articles',
            'delete-articles',
            'view-gallery-activities',
            'create-gallery-activities',
            'edit-gallery-activities',
            'delete-gallery-activities',
            'view-faqs',
            'create-faqs',
            'edit-faqs',
            'delete-faqs',
            'view-partners',
            'create-partners',
            'edit-partners',
            'delete-partners',
            'view-testimonials',
            'create-testimonials',
            'edit-testimonials',
            'delete-testimonials',
            'view-business-identity',
            'edit-business-identity',
            'view-settings',
            'edit-settings',
        ]);

        // Role 3: Admin (Daily operational & content management)
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
            'view-menus',
            'create-menus',
            'edit-menus',
            'delete-menus',
            'assign-menu-permissions',
            'view-content',
            'upload-media',
            'delete-media',
            'view-article-categories',
            'create-article-categories',
            'edit-article-categories',
            'delete-article-categories',
            'view-article-tags',
            'create-article-tags',
            'edit-article-tags',
            'delete-article-tags',
            'view-articles',
            'create-articles',
            'edit-articles',
            'delete-articles',
            'view-gallery-activities',
            'create-gallery-activities',
            'edit-gallery-activities',
            'delete-gallery-activities',
            'view-faqs',
            'create-faqs',
            'edit-faqs',
            'delete-faqs',
            'view-partners',
            'create-partners',
            'edit-partners',
            'delete-partners',
            'view-testimonials',
            'create-testimonials',
            'edit-testimonials',
            'delete-testimonials',
            'view-business-identity',
            'edit-business-identity',
            'view-settings',
        ]);

        // Role 4: Support (Customer service & user assistance)
        $supportRole = Role::firstOrCreate(['name' => 'Support', 'guard_name' => 'web']);
        $supportRole->syncPermissions([
            'view-dashboard',
            'view-users',
            'view-faqs',
            'view-partners',
            'view-testimonials',
            'view-business-identity',
        ]);

        // Role 5: Editor (Content production and publishing)
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorRole->syncPermissions([
            'view-dashboard',
            'view-content',
            'upload-media',
            'delete-media',
            'view-article-categories',
            'create-article-categories',
            'edit-article-categories',
            'delete-article-categories',
            'view-article-tags',
            'create-article-tags',
            'edit-article-tags',
            'delete-article-tags',
            'view-articles',
            'create-articles',
            'edit-articles',
            'delete-articles',
            'view-gallery-activities',
            'create-gallery-activities',
            'edit-gallery-activities',
            'delete-gallery-activities',
            'view-faqs',
            'create-faqs',
            'edit-faqs',
            'delete-faqs',
            'view-partners',
            'create-partners',
            'edit-partners',
            'delete-partners',
            'view-testimonials',
            'create-testimonials',
            'edit-testimonials',
            'delete-testimonials',
            'view-business-identity',
            'edit-business-identity',
        ]);

        // Role 6: User (Standard registered user)
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $userRole->syncPermissions([
            'view-dashboard',
            'view-content',
        ]);
    }
}
