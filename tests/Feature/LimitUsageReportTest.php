<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LimitUsageReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_limit_usage_report_page_can_be_accessed_by_user_with_permission(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('User'); // All roles now have view-limit-usage

        $response = $this->actingAs($user)->get(route('admin.limit-usage.index'));
        $response->assertStatus(200);
        $response->assertSee('Penggunaan Kuota', false);
        $response->assertSee('Kapasitas Media Storage', false);
        $response->assertSee('Akun Pengguna Terdaftar', false);
    }

    public function test_limit_usage_report_hides_article_and_testimonial_when_toggled_off(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $config = \App\Models\WebConfiguration::current();
        $config->update([
            'article_module_enabled' => false,
            'testimonial_module_enabled' => false,
        ]);

        $response = $this->actingAs($user)->get(route('admin.limit-usage.index'));
        $response->assertStatus(200);

        $response->assertDontSeeText('Artikel & Berita (SEO)');
        $response->assertDontSeeText('Testimoni Klien');

        $response->assertSeeText('Kapasitas Media Storage');
        $response->assertSeeText('Galeri Kegiatan (Album)');
        $response->assertSeeText('Tanya Jawab (FAQ)');
    }

    public function test_limit_usage_report_shows_article_and_testimonial_when_toggled_on(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $config = \App\Models\WebConfiguration::current();
        $config->update([
            'article_module_enabled' => true,
            'testimonial_module_enabled' => true,
        ]);

        $response = $this->actingAs($user)->get(route('admin.limit-usage.index'));
        $response->assertStatus(200);

        $response->assertSeeText('Artikel & Berita (SEO)');
        $response->assertSeeText('Testimoni Klien');
    }
}
