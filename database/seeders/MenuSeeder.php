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

        // 1. Heading: General
        $generalHeader = Menu::create([
            'title' => 'General',
            'type' => 'header',
            'order' => 1,
            'is_active' => true,
        ]);

        // 2. Dashboard Menu Item with 'view-dashboard' permission
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

        // 3. Heading: Pengguna & Akses
        $userHeader = Menu::create([
            'title' => 'Pengguna & Akses',
            'type' => 'header',
            'order' => 3,
            'is_active' => true,
        ]);

        // 4. User List Menu Item with 'view-users' permission
        $userMenu = Menu::create([
            'title' => 'Daftar Pengguna',
            'type' => 'link',
            'route' => 'admin.users.index',
            'icon' => 'users',
            'permission' => 'view-users',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 4,
            'is_active' => true,
        ]);
        $userMenu->assignPermissions('view-users');

        // 5. Role Management Menu Item with 'view-roles' permission
        $roleMenu = Menu::create([
            'title' => 'Kelola Role',
            'type' => 'link',
            'route' => 'admin.roles.index',
            'icon' => 'shield',
            'permission' => 'view-roles',
            'badge' => '6 Level',
            'badge_color' => 'amber',
            'order' => 5,
            'is_active' => true,
        ]);
        $roleMenu->assignPermissions('view-roles');

        // 6. Permission Management Menu Item with 'view-permissions' permission
        $permissionMenu = Menu::create([
            'title' => 'Kelola Permission',
            'type' => 'link',
            'route' => 'admin.permissions.index',
            'icon' => 'key',
            'permission' => 'view-permissions',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 6,
            'is_active' => true,
        ]);
        $permissionMenu->assignPermissions('view-permissions');

        // 7. Dynamic Menu Management Menu Item with 'view-menus' permission
        $menuManagement = Menu::create([
            'title' => 'Kelola Menu',
            'type' => 'link',
            'route' => 'admin.menus.index',
            'icon' => 'menu',
            'permission' => 'view-menus',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 7,
            'is_active' => true,
        ]);
        $menuManagement->assignPermissions('view-menus');

        // 8. Media Library Menu Item with 'view-content' permission
        $mediaMenu = Menu::create([
            'title' => 'Media Library',
            'type' => 'link',
            'route' => 'admin.media.index',
            'icon' => 'image',
            'permission' => 'view-content',
            'badge' => 'Gudang',
            'badge_color' => 'emerald',
            'order' => 8,
            'is_active' => true,
        ]);
        $mediaMenu->assignPermissions('view-content');

        // 9. Article SEO Dropdown Parent Menu
        $articleSeoMenu = Menu::create([
            'title' => 'Article SEO',
            'type' => 'dropdown',
            'icon' => 'newspaper',
            'badge' => 'SEO',
            'badge_color' => 'emerald',
            'order' => 9,
            'is_active' => true,
        ]);

        // 9.1 Child: Article Category
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

        // 9.2 Child: Article Tag
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

        // 9.3 Child: Article
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

        // 9.4 Child: Gallery Activity
        $galleryMenu = Menu::create([
            'parent_id' => $articleSeoMenu->id,
            'title' => 'Gallery Activity',
            'type' => 'link',
            'route' => 'admin.gallery-activities.index',
            'icon' => 'images',
            'permission' => 'view-gallery-activities',
            'order' => 4,
            'is_active' => true,
        ]);
        $galleryMenu->assignPermissions('view-gallery-activities');

        // 10. FAQ Menu Item with 'view-faqs' permission
        $faqMenu = Menu::create([
            'title' => 'FAQ',
            'type' => 'link',
            'route' => 'admin.faqs.index',
            'icon' => 'help-circle',
            'permission' => 'view-faqs',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 10,
            'is_active' => true,
        ]);
        $faqMenu->assignPermissions('view-faqs');

        // 11. Brand / Partner Menu Item with 'view-partners' permission
        $partnerMenu = Menu::create([
            'title' => 'Brand / Partner',
            'type' => 'link',
            'route' => 'admin.partners.index',
            'icon' => 'handshake',
            'permission' => 'view-partners',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 11,
            'is_active' => true,
        ]);
        $partnerMenu->assignPermissions('view-partners');

        // 12. Testimonial Menu Item with 'view-testimonials' permission
        $testimonialMenu = Menu::create([
            'title' => 'Testimonial',
            'type' => 'link',
            'route' => 'admin.testimonials.index',
            'icon' => 'quote',
            'permission' => 'view-testimonials',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 12,
            'is_active' => true,
        ]);
        $testimonialMenu->assignPermissions('view-testimonials');

        // 13. Heading: Pengaturan
        $settingsHeader = Menu::create([
            'title' => 'Pengaturan',
            'type' => 'header',
            'order' => 13,
            'is_active' => true,
        ]);

        // 14. Web Configuration Menu Item with 'view-settings' permission
        $webConfigMenu = Menu::create([
            'title' => 'Web Konfigurasi',
            'type' => 'link',
            'route' => 'admin.settings.edit',
            'icon' => 'settings',
            'permission' => 'view-settings',
            'badge' => null,
            'badge_color' => 'emerald',
            'order' => 14,
            'is_active' => true,
        ]);
        $webConfigMenu->assignPermissions('view-settings');
    }
}
