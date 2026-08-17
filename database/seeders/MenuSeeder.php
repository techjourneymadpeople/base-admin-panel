<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate or clean menus table first
        Menu::truncate();

        // 1. Heading: General
        Menu::create([
            'title' => 'General',
            'type' => 'header',
            'order' => 1,
            'is_active' => true,
        ]);

        // 2. Dashboard Menu Item
        Menu::create([
            'title' => 'Dashboard',
            'type' => 'link',
            'route' => 'admin.dashboard',
            'icon' => 'layout-dashboard',
            'badge' => 'Utama',
            'badge_color' => 'emerald',
            'order' => 2,
            'is_active' => true,
        ]);
    }
}
