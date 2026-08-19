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

    public function test_feedback_can_be_created_with_new_category(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Owner'); // Non-support role can create

        $response = $this->actingAs($user)->post(route('admin.feedbacks.store'), [
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
            'phone' => '081234567890',
            'subject' => 'Saran UI Baru',
            'type' => 'saran_masukan',
            'message' => 'Tampilan admin panel sangat bagus dan modern!',
            'rating' => 5,
        ]);

        $response->assertRedirect(route('admin.feedbacks.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('feedbacks', [
            'name' => 'Budi Pratama',
            'email' => 'budi@example.com',
            'type' => 'saran_masukan',
            'rating' => 5,
            'status' => 'unread',
        ]);
    }

    public function test_feedback_status_can_be_updated_by_super_admin_or_support(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Support');

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

    public function test_feedback_status_cannot_be_updated_by_non_support_role(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Owner'); // Owner tidak berhak update status feedback

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

        $response->assertStatus(403);
    }

    public function test_feedback_cannot_be_modified_when_already_resolved(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $feedback = Feedback::create([
            'name' => 'Rina Wijaya',
            'email' => 'rina@example.com',
            'type' => 'keluhan',
            'message' => 'Ada kendala pada menu pembayaran.',
            'status' => 'resolved',
            'replied_at' => now(),
        ]);

        // Trying to update status on resolved item
        $response = $this->actingAs($user)->postJson(route('admin.feedbacks.update-status', $feedback->id), [
            'status' => 'in_progress',
        ]);

        $response->assertStatus(422);
    }

    public function test_feedback_star_can_be_toggled_by_support(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('Support');

        $feedback = Feedback::create([
            'name' => 'Andi Saputra',
            'email' => 'andi@example.com',
            'type' => 'keluhan',
            'message' => 'Layanan respons lambat.',
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
}
