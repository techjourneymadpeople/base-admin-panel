<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_index_renders_with_featured_stats(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $category = ArticleCategory::create([
            'name' => 'Bisnis & Pasar',
            'slug' => 'bisnis-pasar',
            'is_active' => true,
        ]);

        Article::create([
            'category_id' => $category->id,
            'user_id' => $admin->id,
            'title' => 'Strategi Penjualan Pasar Digital Modern',
            'slug' => 'strategi-penjualan-pasar-digital-modern',
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get(route('admin.articles.index'));
        $response->assertStatus(200);
        $response->assertSee('Featured Article');
        $response->assertSee('Daftar Artikel SEO');
    }

    public function test_article_featured_status_can_be_toggled(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $article = Article::create([
            'user_id' => $admin->id,
            'title' => 'Inovasi Teknologi Retail 2026',
            'slug' => 'inovasi-teknologi-retail-2026',
            'status' => 'published',
            'is_featured' => false,
            'published_at' => now(),
        ]);

        // Toggle to true
        $response = $this->actingAs($admin)->postJson(route('admin.articles.toggle-featured', $article->id));
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_featured' => true,
        ]);

        $this->assertTrue($article->fresh()->is_featured);

        // Toggle back to false
        $response2 = $this->actingAs($admin)->postJson(route('admin.articles.toggle-featured', $article->id));
        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'is_featured' => false,
        ]);

        $this->assertFalse($article->fresh()->is_featured);
    }

    public function test_article_index_ajax_returns_featured_data(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        Article::create([
            'user_id' => $admin->id,
            'title' => 'Panduan Transformasi Digital',
            'slug' => 'panduan-transformasi-digital',
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->getJson(route('admin.articles.index'), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'article_info',
                    'featured_badge',
                    'status_badge',
                ],
            ],
        ]);
    }
}
