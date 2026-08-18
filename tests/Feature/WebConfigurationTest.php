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
            'robots_indexing' => 1,
        ]);

        $updateResponse->assertRedirect(route('admin.settings.edit'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('web_configurations', [
            'site_name' => 'Lentera Pasar Baru',
            'social_tiktok' => 'https://tiktok.com/@lenterapasar',
            'google_analytics_id' => 'G-ABC1234567',
            'maintenance_mode' => 1,
        ]);
    }
}
