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
}
