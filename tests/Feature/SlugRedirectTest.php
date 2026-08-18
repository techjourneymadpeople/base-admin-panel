<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\SlugRedirect;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SlugRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_slug_change_creates_301_redirect(): void
    {
        $user = User::factory()->create();

        // Create article
        $article = Article::create([
            'user_id' => $user->id,
            'title' => 'Judul Awal Artikel',
            'slug' => 'judul-awal-artikel',
            'content' => 'Konten artikel...',
            'status' => 'published',
        ]);

        // Update article slug
        $article->update([
            'slug' => 'judul-baru-artikel',
        ]);

        // Verify redirect was recorded in DB
        $this->assertDatabaseHas('slug_redirects', [
            'source_path' => '/articles/judul-awal-artikel',
            'target_path' => '/articles/judul-baru-artikel',
            'status_code' => 301,
        ]);

        // Test middleware redirect
        $response = $this->get('/articles/judul-awal-artikel');
        $response->assertStatus(301);
        $response->assertRedirect('/articles/judul-baru-artikel');
    }

    public function test_category_slug_change_creates_301_redirect(): void
    {
        $category = ArticleCategory::create([
            'name' => 'Kategori Lama',
            'slug' => 'kategori-lama',
            'is_active' => true,
        ]);

        $category->update([
            'slug' => 'kategori-baru',
        ]);

        $this->assertDatabaseHas('slug_redirects', [
            'source_path' => '/articles/category/kategori-lama',
            'target_path' => '/articles/category/kategori-baru',
            'status_code' => 301,
        ]);

        $response = $this->get('/articles/category/kategori-lama?page=2');
        $response->assertStatus(301);
        $response->assertRedirect('/articles/category/kategori-baru?page=2');
    }

    public function test_redirect_chain_resolution(): void
    {
        $user = User::factory()->create();

        $article = Article::create([
            'user_id' => $user->id,
            'title' => 'Slug A',
            'slug' => 'slug-a',
            'content' => 'Konten...',
            'status' => 'published',
        ]);

        // A -> B
        $article->update(['slug' => 'slug-b']);

        // B -> C
        $article->update(['slug' => 'slug-c']);

        // Directly visiting A should 301 redirect directly to C (no double redirect!)
        $responseA = $this->get('/articles/slug-a');
        $responseA->assertStatus(301);
        $responseA->assertRedirect('/articles/slug-c');

        // Visiting B should also redirect to C
        $responseB = $this->get('/articles/slug-b');
        $responseB->assertStatus(301);
        $responseB->assertRedirect('/articles/slug-c');
    }
}
