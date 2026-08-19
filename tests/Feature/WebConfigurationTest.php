<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WebConfiguration;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebConfigurationTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_configuration_singleton_and_update(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo(['view-settings', 'edit-settings']);

        $config = WebConfiguration::current();
        $this->assertNotNull($config->id);

        $response = $this->actingAs($user)->get(route('admin.settings.edit'));
        $response->assertStatus(200);

        $updateResponse = $this->actingAs($user)->put(route('admin.settings.update'), [
            'site_name' => 'Lentera Pasar Baru',
            'site_tagline' => 'Platform Digital Terintegrasi',
            'social_instagram' => 'https://instagram.com/lenterapasar',
            'social_tiktok' => 'https://tiktok.com/@lenterapasar',
            'google_analytics_id' => 'G-ABC1234567',
            'maintenance_mode' => 1,
            'registration_enabled' => 0,
            'robots_indexing' => 1,
            'limit_media_storage_mb' => 2048,
            'limit_users_count' => 100,
            'limit_articles_count' => 250,
            'limit_gallery_activities_count' => 80,
            'limit_faqs_count' => 75,
            'limit_partners_count' => 60,
            'limit_testimonials_count' => 45,
        ]);

        $updateResponse->assertRedirect(route('admin.settings.edit'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('web_configurations', [
            'site_name' => 'Lentera Pasar Baru',
            'social_tiktok' => 'https://tiktok.com/@lenterapasar',
            'google_analytics_id' => 'G-ABC1234567',
            'maintenance_mode' => 1,
            'registration_enabled' => 0,
            'limit_media_storage_mb' => 2048,
            'limit_users_count' => 100,
            'limit_articles_count' => 250,
            'limit_gallery_activities_count' => 80,
            'limit_faqs_count' => 75,
            'limit_partners_count' => 60,
            'limit_testimonials_count' => 45,
        ]);
    }

    public function test_registration_disabled_redirects_to_login(): void
    {
        $config = WebConfiguration::current();
        $config->update(['registration_enabled' => false]);

        $response = $this->get('/register');
        $response->assertRedirect('/login');
        $response->assertSessionHas('status');
    }

    public function test_article_module_disabled_blocks_access_to_article_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['article_module_enabled' => false]);

        // 1. Articles route should be blocked and redirected
        $responseArticles = $this->actingAs($admin)->get(route('admin.articles.index'));
        $responseArticles->assertRedirect(route('admin.dashboard'));
        $responseArticles->assertSessionHas('error');

        // 2. Article Categories route should be blocked
        $responseCategories = $this->actingAs($admin)->get(route('admin.article-categories.index'));
        $responseCategories->assertRedirect(route('admin.dashboard'));
        $responseCategories->assertSessionHas('error');

        // 3. Article Tags route should be blocked
        $responseTags = $this->actingAs($admin)->get(route('admin.article-tags.index'));
        $responseTags->assertRedirect(route('admin.dashboard'));
        $responseTags->assertSessionHas('error');

        // 4. AJAX request should return 403 JSON
        $ajaxResponse = $this->actingAs($admin)->getJson(route('admin.articles.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(403);
    }

    public function test_article_module_enabled_allows_access_to_article_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['article_module_enabled' => true]);

        $response = $this->actingAs($admin)->get(route('admin.articles.index'));
        $response->assertStatus(200);

        $responseCategories = $this->actingAs($admin)->get(route('admin.article-categories.index'));
        $responseCategories->assertStatus(200);

        $responseTags = $this->actingAs($admin)->get(route('admin.article-tags.index'));
        $responseTags->assertStatus(200);
    }

    public function test_testimonial_module_disabled_blocks_access_to_testimonial_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['testimonial_module_enabled' => false]);

        // 1. Web request redirects to dashboard
        $response = $this->actingAs($admin)->get(route('admin.testimonials.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // 2. AJAX request returns 403 JSON
        $ajaxResponse = $this->actingAs($admin)->getJson(route('admin.testimonials.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(403);
    }

    public function test_testimonial_module_enabled_allows_access_to_testimonial_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['testimonial_module_enabled' => true]);

        $response = $this->actingAs($admin)->get(route('admin.testimonials.index'));
        $response->assertStatus(200);
    }

    public function test_partner_module_disabled_blocks_access_to_partner_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['partner_module_enabled' => false]);

        // 1. Web request redirects to dashboard
        $response = $this->actingAs($admin)->get(route('admin.partners.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // 2. AJAX request returns 403 JSON
        $ajaxResponse = $this->actingAs($admin)->getJson(route('admin.partners.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(403);
    }

    public function test_partner_module_enabled_allows_access_to_partner_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['partner_module_enabled' => true]);

        $response = $this->actingAs($admin)->get(route('admin.partners.index'));
        $response->assertStatus(200);
    }

    public function test_faq_module_disabled_blocks_access_to_faq_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['faq_module_enabled' => false]);

        // 1. Web request redirects to dashboard
        $response = $this->actingAs($admin)->get(route('admin.faqs.index'));
        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('error');

        // 2. AJAX request returns 403 JSON
        $ajaxResponse = $this->actingAs($admin)->getJson(route('admin.faqs.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(403);
    }

    public function test_faq_module_enabled_allows_access_to_faq_routes(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $config = WebConfiguration::current();
        $config->update(['faq_module_enabled' => true]);

        $response = $this->actingAs($admin)->get(route('admin.faqs.index'));
        $response->assertStatus(200);
    }
}
