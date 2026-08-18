<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_can_be_created(): void
    {
        $faq = Faq::create([
            'question' => 'Bagaimana cara registrasi?',
            'answer' => 'Silakan klik tombol Daftar di pojok kanan atas.',
            'category' => 'Akun',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'question' => 'Bagaimana cara registrasi?',
            'category' => 'Akun',
            'is_active' => true,
        ]);
    }

    public function test_faq_status_can_be_toggled(): void
    {
        $faq = Faq::create([
            'question' => 'Pertanyaan Contoh',
            'answer' => 'Jawaban Contoh',
            'category' => 'Umum',
            'is_active' => true,
        ]);

        $faq->update(['is_active' => false]);

        $this->assertDatabaseHas('faqs', [
            'id' => $faq->id,
            'is_active' => false,
        ]);
    }

    public function test_faq_index_ajax_returns_data(): void
    {
        $user = User::factory()->create();

        Faq::create([
            'question' => 'Pertanyaan 1',
            'answer' => 'Jawaban 1',
            'category' => 'Umum',
            'order' => 1,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson(route('admin.faqs.index'), [
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
