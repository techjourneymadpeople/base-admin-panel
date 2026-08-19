<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Faq;
use App\Models\GalleryActivity;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_and_support_and_owner_can_view_content_report(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        // 1. Super Admin
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('Super Admin');
        $this->actingAs($superAdmin)->get(route('admin.content-report.index'))->assertStatus(200);

        // 2. Support
        $support = User::factory()->create();
        $support->assignRole('Support');
        $this->actingAs($support)->get(route('admin.content-report.index'))->assertStatus(200);

        // 3. Owner
        $owner = User::factory()->create();
        $owner->assignRole('Owner');
        $response = $this->actingAs($owner)->get(route('admin.content-report.index'));
        $response->assertStatus(200);
        $response->assertSee('Laporan Content', false);
        $response->assertSee('Laporan Produksi Konten', false);
        $response->assertSee('Total Konten Tersimpan', false);
    }

    public function test_admin_editor_and_user_cannot_view_content_report(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        // 1. Admin
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin)->get(route('admin.content-report.index'))->assertStatus(403);

        // 2. Editor
        $editor = User::factory()->create();
        $editor->assignRole('Editor');
        $this->actingAs($editor)->get(route('admin.content-report.index'))->assertStatus(403);

        // 3. User
        $user = User::factory()->create();
        $user->assignRole('User');
        $this->actingAs($user)->get(route('admin.content-report.index'))->assertStatus(403);
    }

    public function test_content_report_renders_content_statistics(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $owner = User::factory()->create(['name' => 'Pak Bos Owner']);
        $owner->assignRole('Owner');

        $editor = User::factory()->create(['name' => 'Siti Editor']);
        $editor->assignRole('Editor');

        $category = ArticleCategory::create([
            'name' => 'Bisnis',
            'slug' => 'bisnis',
            'is_active' => true,
        ]);

        Article::create([
            'category_id' => $category->id,
            'user_id' => $editor->id,
            'title' => 'Panduan Manajemen Konten Terkini',
            'slug' => 'panduan-manajemen-konten-terkini',
            'status' => 'published',
            'is_featured' => true,
            'views_count' => 150,
            'published_at' => now(),
        ]);

        GalleryActivity::create([
            'title' => 'Dokumentasi Bazar Pasar 2026',
            'slug' => 'dokumentasi-bazar-pasar-2026',
            'status' => 'published',
            'event_date' => now(),
        ]);

        Faq::create([
            'category' => 'Umum',
            'question' => 'Bagaimana cara bergabung?',
            'answer' => 'Silakan hubungi admin.',
            'is_active' => true,
        ]);

        $response = $this->actingAs($owner)->get(route('admin.content-report.index'));
        $response->assertStatus(200);
        $response->assertSee('Siti Editor');
        $response->assertSee('Panduan Manajemen Konten Terkini');
        $response->assertSee('Dokumentasi Bazar Pasar 2026');
        $response->assertSee('Bagaimana cara bergabung?');
    }
}
