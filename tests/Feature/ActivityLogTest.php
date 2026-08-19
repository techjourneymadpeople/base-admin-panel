<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\BusinessIdentity;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\GalleryActivity;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WebConfiguration;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_log_records_model_creation_update_and_deletion(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create(['name' => 'Admin Utama']);
        $admin->assignRole('Super Admin');

        $this->actingAs($admin);

        // 1. Test Article Category creation & update
        $category = ArticleCategory::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'article_category',
            'event' => 'created',
            'subject_id' => $category->id,
            'causer_id' => $admin->id,
        ]);

        $category->update(['name' => 'Teknologi Informasi']);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'article_category',
            'event' => 'updated',
            'subject_id' => $category->id,
        ]);

        // 2. Test FAQ creation
        $faq = Faq::create([
            'question' => 'Apa itu Lentera Pasar?',
            'answer' => 'Platform manajemen pasar.',
            'category' => 'Umum',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'faq',
            'event' => 'created',
            'subject_id' => $faq->id,
        ]);

        // 3. Test Partner creation & deletion
        $partner = Partner::create([
            'name' => 'Bank Mandiri',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'partner',
            'event' => 'created',
            'subject_id' => $partner->id,
        ]);

        $partnerId = $partner->id;
        $partner->delete();

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'partner',
            'event' => 'deleted',
            'subject_id' => $partnerId,
        ]);

        // 4. Test Testimonial creation
        $testimonial = Testimonial::create([
            'name' => 'Budi Santoso',
            'role_or_title' => 'Direktur',
            'content' => 'Sistem sangat membantu operasional kami.',
            'rating' => 5,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'testimonial',
            'event' => 'created',
            'subject_id' => $testimonial->id,
        ]);

        // 5. Test Web Configuration update
        $config = WebConfiguration::current();
        $config->update(['site_tagline' => 'Tagline Baru Lentera Pasar']);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'web_configuration',
            'event' => 'updated',
            'subject_id' => $config->id,
        ]);

        // 6. Test Article creation
        $article = Article::create([
            'category_id' => $category->id,
            'user_id' => $admin->id,
            'title' => 'Judul Artikel Log',
            'slug' => 'judul-artikel-log',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'article',
            'event' => 'created',
            'subject_id' => $article->id,
        ]);

        // 7. Test Gallery Activity creation
        $gallery = GalleryActivity::create([
            'user_id' => $admin->id,
            'title' => 'Galeri Foto Kegiatan',
            'slug' => 'galeri-foto-kegiatan',
            'status' => 'published',
            'event_date' => now(),
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'gallery_activity',
            'event' => 'created',
            'subject_id' => $gallery->id,
        ]);

        // 8. Test Feedback creation
        $feedback = Feedback::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Saran Fitur',
            'message' => 'Tolong tambahkan fitur export.',
            'type' => 'saran',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'feedback',
            'event' => 'created',
            'subject_id' => $feedback->id,
        ]);

        // 9. Test Business Identity update
        $identity = BusinessIdentity::current();
        $identity->update(['company_name' => 'PT Lentera Digital Nusantara']);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'business_identity',
            'event' => 'updated',
            'subject_id' => $identity->id,
        ]);

        // 10. Test User update (without sensitive attributes like password)
        $user = User::factory()->create(['name' => 'Staff Baru']);
        $user->update(['status' => 'nonactive']);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'user',
            'event' => 'updated',
            'subject_id' => $user->id,
        ]);
    }
}
