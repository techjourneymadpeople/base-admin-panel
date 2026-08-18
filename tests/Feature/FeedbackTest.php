<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_feedback_can_be_created(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo(['view-feedbacks', 'create-feedbacks']);

        $response = $this->actingAs($user)->post(route('admin.feedbacks.store'), [
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'subject' => 'Saran UI Baru',
            'type' => 'saran',
            'message' => 'Tampilan admin panel sangat bagus dan modern!',
            'rating' => 5,
            'status' => 'unread',
            'is_starred' => true,
        ]);

        $response->assertRedirect(route('admin.feedbacks.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('feedbacks', [
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
            'type' => 'saran',
            'rating' => 5,
            'is_starred' => 1,
        ]);
    }

    public function test_feedback_status_can_be_updated_via_ajax(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo(['view-feedbacks', 'edit-feedbacks']);

        $feedback = Feedback::create([
            'name' => 'Rina Wijaya',
            'email' => 'rina@example.com',
            'type' => 'keluhan',
            'message' => 'Ada kendala pada menu pembayaran.',
            'status' => 'unread',
        ]);

        $response = $this->actingAs($user)->postJson(route('admin.feedbacks.update-status', $feedback->id), [
            'status' => 'resolved',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'status' => 'resolved',
        ]);

        $this->assertDatabaseHas('feedbacks', [
            'id' => $feedback->id,
            'status' => 'resolved',
        ]);
    }

    public function test_feedback_star_can_be_toggled_via_ajax(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo(['view-feedbacks', 'edit-feedbacks']);

        $feedback = Feedback::create([
            'name' => 'Andi Saputra',
            'email' => 'andi@example.com',
            'type' => 'pertanyaan',
            'message' => 'Apakah ada paket langganan tahunan?',
            'status' => 'read',
            'is_starred' => false,
        ]);

        $response = $this->actingAs($user)->postJson(route('admin.feedbacks.toggle-star', $feedback->id));

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'is_starred' => true,
        ]);

        $this->assertDatabaseHas('feedbacks', [
            'id' => $feedback->id,
            'is_starred' => 1,
        ]);
    }

    public function test_feedback_index_ajax_returns_data(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->givePermissionTo(['view-feedbacks']);

        Feedback::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'type' => 'saran',
            'message' => 'Mohon tambahkan ekspor ke PDF.',
            'status' => 'in_progress',
        ]);

        $response = $this->actingAs($user)->get(route('admin.feedbacks.index'), [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['data', 'recordsTotal', 'recordsFiltered']);
    }
}
