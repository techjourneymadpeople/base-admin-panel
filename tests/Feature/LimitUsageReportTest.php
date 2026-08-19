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
        $response->assertSee('Laporan Penggunaan Kuota & Limit Sistem');
        $response->assertSee('Kapasitas Media Storage');
        $response->assertSee('Akun Pengguna Terdaftar');
    }
}
