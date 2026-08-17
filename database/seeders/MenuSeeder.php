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

        // Assign Spatie permission to menu
        $dashboardMenu->assignPermissions('view-dashboard');
    }
}
