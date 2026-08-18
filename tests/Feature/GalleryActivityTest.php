<?php

namespace Tests\Feature;

use App\Models\GalleryActivity;
use App\Models\Media;
use App\Models\MediaWarehouse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_activity_can_be_created_with_photos(): void
    {
        $user = User::factory()->create();
        $warehouse = MediaWarehouse::firstOrCreate(['name' => 'General Warehouse']);

        // Create media items via warehouse
        $media1 = Media::create([
            'model_type' => MediaWarehouse::class,
            'model_id' => $warehouse->id,
            'collection_name' => 'default',
            'name' => 'photo1',
            'file_name' => 'photo1.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ]);

        $media2 = Media::create([
            'model_type' => MediaWarehouse::class,
            'model_id' => $warehouse->id,
            'collection_name' => 'default',
            'name' => 'photo2',
            'file_name' => 'photo2.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 2048,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ]);

        $gallery = GalleryActivity::create([
            'user_id' => $user->id,
            'title' => 'Kegiatan Workshop 2026',
            'slug' => 'kegiatan-workshop-2026',
            'activity_date' => '2026-08-18',
            'location' => 'Jakarta',
            'description' => 'Dokumentasi workshop...',
            'thumbnail_media_id' => $media1->id,
            'status' => 'published',
        ]);

        $gallery->photos()->create([
            'media_id' => $media1->id,
            'caption' => 'Foto Pembukaan',
            'order' => 0,
        ]);

        $gallery->photos()->create([
            'media_id' => $media2->id,
            'caption' => 'Foto Bersama',
            'order' => 1,
        ]);

        $this->assertDatabaseHas('gallery_activities', [
            'id' => $gallery->id,
            'title' => 'Kegiatan Workshop 2026',
            'slug' => 'kegiatan-workshop-2026',
        ]);

        $this->assertDatabaseHas('gallery_activity_photos', [
            'gallery_activity_id' => $gallery->id,
            'media_id' => $media1->id,
            'caption' => 'Foto Pembukaan',
        ]);

        $this->assertCount(2, $gallery->photos);
    }

    public function test_gallery_activity_slug_change_creates_301_redirect(): void
    {
        $user = User::factory()->create();

        $gallery = GalleryActivity::create([
            'user_id' => $user->id,
            'title' => 'Kegiatan Awal',
            'slug' => 'kegiatan-awal',
            'status' => 'published',
        ]);

        $gallery->update([
            'slug' => 'kegiatan-baru-2026',
        ]);

        $this->assertDatabaseHas('slug_redirects', [
            'source_path' => '/galleries/kegiatan-awal',
            'target_path' => '/galleries/kegiatan-baru-2026',
            'status_code' => 301,
        ]);

        $response = $this->get('/galleries/kegiatan-awal');
        $response->assertStatus(301);
        $response->assertRedirect('/galleries/kegiatan-baru-2026');
    }
}
