<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\MediaWarehouse;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_can_be_created_with_media_logo(): void
    {
        $warehouse = MediaWarehouse::firstOrCreate(['name' => 'General Warehouse']);

        $media = Media::create([
            'model_type' => MediaWarehouse::class,
            'model_id' => $warehouse->id,
            'collection_name' => 'default',
            'name' => 'logo_google',
            'file_name' => 'google.png',
            'disk' => 'public',
            'mime_type' => 'image/png',
            'size' => 1024,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ]);

        $partner = Partner::create([
            'name' => 'Google Cloud',
            'logo_media_id' => $media->id,
            'website_url' => 'https://cloud.google.com',
            'category' => 'Mitra Strategis',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'name' => 'Google Cloud',
            'logo_media_id' => $media->id,
            'website_url' => 'https://cloud.google.com',
            'is_active' => true,
        ]);

        $this->assertNotNull($partner->getLogo());
        $this->assertEquals($media->id, $partner->logoMedia->id);
    }

    public function test_partner_status_can_be_toggled(): void
    {
        $partner = Partner::create([
            'name' => 'Tech Partner',
            'category' => 'Klien',
            'is_active' => true,
        ]);

        $partner->update(['is_active' => false]);

        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'is_active' => false,
        ]);
    }

    public function test_partner_index_ajax_returns_data(): void
    {
        $user = User::factory()->create();

        Partner::create([
            'name' => 'Partner ABC',
            'website_url' => 'https://abc.com',
            'category' => 'Sponsor',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson(route('admin.partners.index'), [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);
        $this->assertEquals(1, $response->json('recordsTotal'));
    }
}
