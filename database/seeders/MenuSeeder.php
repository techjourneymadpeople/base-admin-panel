<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean menus table safely
        Schema::disableForeignKeyConstraints();
        Menu::truncate();
        Schema::enableForeignKeyConstraints();

        // ==========================================
        // 1. SECTION: General
        // ==========================================
        $generalHeader = Menu::create([
            'title' => 'General',
            'type' => 'header',
            'order' => 1,
            'is_active' => true,
        ]);

        // 1.1 Dashboard
        $dashboardMenu = Menu::create([
            'title' => 'Dashboard',
            'type' => 'link',
            'route' => 'admin.dashboard',
            'icon' => 'layout-dashboard',
            'permission' => 'view-dashboard',
            'badge' => 'Utama',
            'badge_color' => 'emerald',
            'order' => 2,
            'is_active' => true,
        ]);
        $dashboardMenu->assignPermissions('view-dashboard');

        // 1.2 Media Library
        $mediaMenu = Menu::create([
            'title' => 'Media Library',
            'type' => 'link',
            'route' => 'admin.media.index',
            'icon' => 'image',
            'permission' => 'view-content',
            'badge' => 'Gudang',
            'badge_color' => 'emerald',
            'order' => 3,
            'is_active' => true,
        ]);
        $mediaMenu->assignPermissions('view-content');

        // ==========================================
        // 2. SECTION: Pengguna & Hak Akses
        // ==========================================
        $userHeader = Menu::create([
            'title' => 'Pengguna & Hak Akses',
            'type' => 'header',
            'order' => 4,
            'is_active' => true,
        ]);

        // 2.1 Daftar Pengguna
        $userMenu = Menu::create([
            'title' => 'Daftar Pengguna',
            'type' => 'link',
            'route' => 'admin.users.index',
            'icon' => 'users',
            'permission' => 'view-users',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 5,
            'is_active' => true,
        ]);
        $userMenu->assignPermissions('view-users');

        // 2.2 Kelola Role
        $roleMenu = Menu::create([
            'title' => 'Kelola Role',
            'type' => 'link',
            'route' => 'admin.roles.index',
            'icon' => 'shield',
            'permission' => 'view-roles',
            'badge' => '6 Level',
            'badge_color' => 'amber',
            'order' => 6,
            'is_active' => true,
        ]);
        $roleMenu->assignPermissions('view-roles');

        // 2.3 Kelola Permission
        $permissionMenu = Menu::create([
            'title' => 'Kelola Permission',
            'type' => 'link',
            'route' => 'admin.permissions.index',
            'icon' => 'key',
            'permission' => 'view-permissions',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 7,
            'is_active' => true,
        ]);
        $permissionMenu->assignPermissions('view-permissions');

        // 2.4 Kelola Menu
        $menuManagement = Menu::create([
            'title' => 'Kelola Menu',
            'type' => 'link',
            'route' => 'admin.menus.index',
            'icon' => 'menu',
            'permission' => 'view-menus',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 8,
            'is_active' => true,
        ]);
        $menuManagement->assignPermissions('view-menus');

        // ==========================================
        // 3. SECTION: Content
        // ==========================================
        $contentHeader = Menu::create([
            'title' => 'Content',
            'type' => 'header',
            'order' => 9,
            'is_active' => true,
        ]);

        // 3.1 Article SEO Dropdown Parent Menu
        $articleSeoMenu = Menu::create([
            'title' => 'Article SEO',
            'type' => 'dropdown',
            'icon' => 'newspaper',
            'badge' => 'SEO',
            'badge_color' => 'emerald',
            'order' => 10,
            'is_active' => true,
        ]);

        // 3.1.1 Child: Article Category
        $articleCategoryMenu = Menu::create([
            'parent_id' => $articleSeoMenu->id,
            'title' => 'Article Category',
            'type' => 'link',
            'route' => 'admin.article-categories.index',
            'icon' => 'folder',
            'permission' => 'view-article-categories',
            'order' => 1,
            'is_active' => true,
        ]);
        $articleCategoryMenu->assignPermissions('view-article-categories');

        // 3.1.2 Child: Article Tag
        $articleTagMenu = Menu::create([
            'parent_id' => $articleSeoMenu->id,
            'title' => 'Article Tag',
            'type' => 'link',
            'route' => 'admin.article-tags.index',
            'icon' => 'tag',
            'permission' => 'view-article-tags',
            'order' => 2,
            'is_active' => true,
        ]);
        $articleTagMenu->assignPermissions('view-article-tags');

        // 3.1.3 Child: Article
        $articleMenu = Menu::create([
            'parent_id' => $articleSeoMenu->id,
            'title' => 'Article',
            'type' => 'link',
            'route' => 'admin.articles.index',
            'icon' => 'file-text',
            'permission' => 'view-articles',
            'order' => 3,
            'is_active' => true,
        ]);
        $articleMenu->assignPermissions('view-articles');

        // 3.2 Gallery Activity
        $galleryMenu = Menu::create([
            'title' => 'Gallery Activity',
            'type' => 'link',
            'route' => 'admin.gallery-activities.index',
            'icon' => 'images',
            'permission' => 'view-gallery-activities',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 11,
            'is_active' => true,
        ]);
        $galleryMenu->assignPermissions('view-gallery-activities');

        // 3.3 FAQ
        $faqMenu = Menu::create([
            'title' => 'FAQ',
            'type' => 'link',
            'route' => 'admin.faqs.index',
            'icon' => 'help-circle',
            'permission' => 'view-faqs',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 12,
            'is_active' => true,
        ]);
        $faqMenu->assignPermissions('view-faqs');

        // 3.4 Brand / Partner
        $partnerMenu = Menu::create([
            'title' => 'Brand / Partner',
            'type' => 'link',
            'route' => 'admin.partners.index',
            'icon' => 'handshake',
            'permission' => 'view-partners',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 13,
            'is_active' => true,
        ]);
        $partnerMenu->assignPermissions('view-partners');

        // 3.5 Testimonial
        $testimonialMenu = Menu::create([
            'title' => 'Testimonial',
            'type' => 'link',
            'route' => 'admin.testimonials.index',
            'icon' => 'quote',
            'permission' => 'view-testimonials',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 14,
            'is_active' => true,
        ]);
        $testimonialMenu->assignPermissions('view-testimonials');

        // 3.6 Saran & Masukan
        $feedbackMenu = Menu::create([
            'title' => 'Saran & Masukan',
            'type' => 'link',
            'route' => 'admin.feedbacks.index',
            'icon' => 'message-square',
            'permission' => 'view-feedbacks',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 15,
            'is_active' => true,
        ]);
        $feedbackMenu->assignPermissions('view-feedbacks');

        // ==========================================
        // 4. SECTION: Pengaturan
        // ==========================================
        $settingsHeader = Menu::create([
            'title' => 'Pengaturan',
            'type' => 'header',
            'order' => 16,
            'is_active' => true,
        ]);

        // 4.1 Profile Business Identity
        $businessIdentityMenu = Menu::create([
            'title' => 'Profile Business Identity',
            'type' => 'link',
            'route' => 'admin.business-identity.edit',
            'icon' => 'building-2',
            'permission' => 'view-business-identity',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 17,
            'is_active' => true,
        ]);
        $businessIdentityMenu->assignPermissions('view-business-identity');

        // 4.2 Web Konfigurasi
        $webConfigMenu = Menu::create([
            'title' => 'Web Konfigurasi',
            'type' => 'link',
            'route' => 'admin.settings.edit',
            'icon' => 'settings',
            'permission' => 'view-settings',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 18,
            'is_active' => true,
        ]);
        $webConfigMenu->assignPermissions('view-settings');
    }
}
