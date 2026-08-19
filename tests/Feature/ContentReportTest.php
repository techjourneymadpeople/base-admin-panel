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

    public function test_content_report_hides_modules_when_toggled_off(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $owner = User::factory()->create(['name' => 'Owner Lentera']);
        $owner->assignRole('Owner');

        $editor = User::factory()->create(['name' => 'Editor Tim']);
        $editor->assignRole('Editor');

        $category = ArticleCategory::create([
            'name' => 'Bisnis',
            'slug' => 'bisnis',
            'is_active' => true,
        ]);

        Article::create([
            'category_id' => $category->id,
            'user_id' => $editor->id,
            'title' => 'Judul Artikel Tersembunyi',
            'slug' => 'judul-artikel-tersembunyi',
            'status' => 'published',
            'published_at' => now(),
        ]);

        GalleryActivity::create([
            'title' => 'Galeri Foto Tersembunyi',
            'slug' => 'galeri-foto-tersembunyi',
            'status' => 'published',
            'event_date' => now(),
        ]);

        Faq::create([
            'category' => 'Umum',
            'question' => 'Pertanyaan FAQ Tersembunyi',
            'answer' => 'Jawaban.',
            'is_active' => true,
        ]);

        $config = \App\Models\WebConfiguration::current();
        $config->update([
            'article_module_enabled' => false,
            'gallery_module_enabled' => false,
            'faq_module_enabled' => false,
            'partner_module_enabled' => false,
            'testimonial_module_enabled' => false,
        ]);

        $response = $this->actingAs($owner)->get(route('admin.content-report.index'));
        $response->assertStatus(200);

        // Should not see disabled module names / content items in report
        $response->assertDontSeeText('Judul Artikel Tersembunyi');
        $response->assertDontSeeText('Galeri Foto Tersembunyi');
        $response->assertDontSeeText('Pertanyaan FAQ Tersembunyi');
        $response->assertDontSeeText('Artikel SEO');
        $response->assertDontSeeText('Galeri Foto');
        $response->assertDontSeeText('FAQ Tanya Jawab');
        $response->assertDontSeeText('Brand Mitra');
        $response->assertDontSeeText('Testimoni');
        $response->assertDontSeeText('Kinerja & Aktivitas Tim Kontributor Artikel');
    }

    public function test_content_report_shows_modules_when_toggled_on(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $owner = User::factory()->create(['name' => 'Owner Lentera']);
        $owner->assignRole('Owner');

        $editor = User::factory()->create(['name' => 'Editor Tim']);
        $editor->assignRole('Editor');

        $category = ArticleCategory::create([
            'name' => 'Bisnis',
            'slug' => 'bisnis',
            'is_active' => true,
        ]);

        Article::create([
            'category_id' => $category->id,
            'user_id' => $editor->id,
            'title' => 'Judul Artikel Terbuka',
            'slug' => 'judul-artikel-terbuka',
            'status' => 'published',
            'published_at' => now(),
        ]);

        GalleryActivity::create([
            'title' => 'Galeri Foto Terbuka',
            'slug' => 'galeri-foto-terbuka',
            'status' => 'published',
            'event_date' => now(),
        ]);

        Faq::create([
            'category' => 'Umum',
            'question' => 'Pertanyaan FAQ Terbuka',
            'answer' => 'Jawaban.',
            'is_active' => true,
        ]);

        $config = \App\Models\WebConfiguration::current();
        $config->update([
            'article_module_enabled' => true,
            'gallery_module_enabled' => true,
            'faq_module_enabled' => true,
            'partner_module_enabled' => true,
            'testimonial_module_enabled' => true,
        ]);

        $response = $this->actingAs($owner)->get(route('admin.content-report.index'));
        $response->assertStatus(200);

        $response->assertSeeText('Judul Artikel Terbuka');
        $response->assertSeeText('Galeri Foto Terbuka');
        $response->assertSeeText('Pertanyaan FAQ Terbuka');
        $response->assertSeeText('Artikel SEO');
        $response->assertSeeText('Galeri Foto');
        $response->assertSeeText('FAQ Tanya Jawab');
        $response->assertSeeText('Brand Mitra');
        $response->assertSeeText('Testimoni');
        $response->assertSeeText('Kinerja & Aktivitas Tim Kontributor Artikel');
    }
}
