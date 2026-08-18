<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\MediaWarehouse;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_testimonial_can_be_created_with_avatar(): void
    {
        $warehouse = MediaWarehouse::firstOrCreate(['name' => 'General Warehouse']);

        $media = Media::create([
            'model_type' => MediaWarehouse::class,
            'model_id' => $warehouse->id,
            'collection_name' => 'default',
            'name' => 'avatar_client',
            'file_name' => 'client.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'size' => 1024,
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ]);

        $testimonial = Testimonial::create([
            'name' => 'Budi Santoso',
            'role_or_title' => 'CEO',
            'company' => 'PT Maju Bersama',
            'avatar_media_id' => $media->id,
            'content' => 'Layanan yang sangat memuaskan dan profesional!',
            'rating' => 5,
            'category' => 'Layanan Website',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'name' => 'Budi Santoso',
            'role_or_title' => 'CEO',
            'company' => 'PT Maju Bersama',
            'avatar_media_id' => $media->id,
            'rating' => 5,
            'is_active' => true,
        ]);

        $this->assertNotNull($testimonial->getAvatar());
        $this->assertEquals($media->id, $testimonial->avatarMedia->id);
    }

    public function test_testimonial_status_can_be_toggled(): void
    {
        $testimonial = Testimonial::create([
            'name' => 'Sarah Wijaya',
            'content' => 'Desain website sangat elegan dan responsif.',
            'rating' => 5,
            'is_active' => true,
        ]);

        $testimonial->update(['is_active' => false]);

        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'is_active' => false,
        ]);
    }

    public function test_testimonial_index_ajax_returns_data(): void
    {
        $user = User::factory()->create();

        Testimonial::create([
            'name' => 'John Doe',
            'role_or_title' => 'Director',
            'company' => 'Acme Corp',
            'content' => 'Great experience working with this team.',
            'rating' => 5,
            'category' => 'Konsultasi IT',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson(route('admin.testimonials.index'), [
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
