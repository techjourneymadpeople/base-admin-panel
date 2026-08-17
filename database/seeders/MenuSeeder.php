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
            'badge' => '6 Role',
            'badge_color' => 'amber',
            'order' => 4,
            'is_active' => true,
        ]);
        $userMenu->assignPermissions('view-users');
    }
}
