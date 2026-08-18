<?php

namespace Tests\Feature;

use App\Models\BusinessIdentity;
use App\Models\Media;
use App\Models\MediaWarehouse;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessIdentityTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_identity_singleton_can_be_retrieved_and_updated(): void
    {
        $identity = BusinessIdentity::current();

        $this->assertNotNull($identity->id);
        $this->assertEquals(1, BusinessIdentity::count());

        $identity->update([
            'company_name' => 'PT Lentera Digital Berjaya',
            'brand_name' => 'Lentera Pasar Official',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'PT Lentera Digital Berjaya',
            'bank_branch' => 'Jakarta Sudirman',
            'email' => 'contact@lenteradigital.id',
            'whatsapp' => '08123456789',
        ]);

        $this->assertDatabaseHas('business_identities', [
            'id' => $identity->id,
            'company_name' => 'PT Lentera Digital Berjaya',
            'bank_name' => 'BCA',
            'bank_account_number' => '1234567890',
            'bank_account_holder' => 'PT Lentera Digital Berjaya',
        ]);
    }

    public function test_business_identity_can_be_updated_via_controller(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo(['view-business-identity', 'edit-business-identity']);

        $response = $this->actingAs($user)->get(route('admin.business-identity.edit'));
        $response->assertStatus(200);

        $updateResponse = $this->actingAs($user)->put(route('admin.business-identity.update'), [
            'company_name' => 'PT Lentera Nusantara Baru',
            'brand_name' => 'Lentera Brand',
            'bank_name' => 'Bank Mandiri',
            'bank_account_number' => '9876543210',
            'bank_account_holder' => 'PT LENTERA NUSANTARA BARU',
            'bank_branch' => 'KCU Thamrin',
            'email' => 'admin@lenteranusantara.co.id',
            'social_instagram' => 'https://instagram.com/lenterapasar',
            'social_tiktok' => 'https://tiktok.com/@lenterapasar',
        ]);

        $updateResponse->assertRedirect(route('admin.business-identity.edit'));
        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('business_identities', [
            'company_name' => 'PT Lentera Nusantara Baru',
            'bank_name' => 'Bank Mandiri',
            'bank_account_number' => '9876543210',
            'social_instagram' => 'https://instagram.com/lenterapasar',
            'social_tiktok' => 'https://tiktok.com/@lenterapasar',
        ]);
    }
}
