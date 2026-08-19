<?php

namespace Tests\Feature;

use App\Models\WebConfiguration;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RobotsTxtTest extends TestCase
{
    use RefreshDatabase;

    public function test_robots_txt_disallows_admin_path_when_indexing_enabled(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $config = WebConfiguration::current();
        $config->robots_indexing = true;
        $config->save();

        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *');
        $response->assertSee('Disallow: /admin');
        $response->assertSee('Disallow: /admin/');
        $response->assertSee('Allow: /');
        $response->assertSee('Sitemap:');
    }

    public function test_robots_txt_disallows_all_when_indexing_disabled(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $config = WebConfiguration::current();
        $config->robots_indexing = false;
        $config->save();

        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertSee('User-agent: *');
        $response->assertSee("Disallow: /\n");
    }
}
