<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WebConfiguration extends Model
{
    use HasFactory, HasUlids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'web_configurations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'site_name',
        'site_tagline',
        'site_description',
        'logo_path',
        'favicon_path',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'contact_address',
        'footer_text',
        'meta_keywords',
        'meta_author',
        'social_facebook',
        'social_instagram',
        'social_twitter',
        'social_youtube',
        'social_linkedin',
        'social_tiktok',
        'social_threads',
        'google_analytics_id',
        'custom_head_scripts',
        'custom_body_scripts',
        'robots_indexing',
        'cookie_consent_enabled',
        'cookie_consent_text',
        'maintenance_mode',
        'registration_enabled',
        'article_module_enabled',
        'testimonial_module_enabled',
        'partner_module_enabled',
        'faq_module_enabled',
        'gallery_module_enabled',
        'limit_media_storage_mb',
        'limit_users_count',
        'limit_articles_count',
        'limit_gallery_activities_count',
        'limit_faqs_count',
        'limit_partners_count',
        'limit_testimonials_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'maintenance_mode' => 'boolean',
        'registration_enabled' => 'boolean',
        'article_module_enabled' => 'boolean',
        'testimonial_module_enabled' => 'boolean',
        'partner_module_enabled' => 'boolean',
        'faq_module_enabled' => 'boolean',
        'gallery_module_enabled' => 'boolean',
        'robots_indexing' => 'boolean',
        'cookie_consent_enabled' => 'boolean',
        'limit_media_storage_mb' => 'integer',
        'limit_users_count' => 'integer',
        'limit_articles_count' => 'integer',
        'limit_gallery_activities_count' => 'integer',
        'limit_faqs_count' => 'integer',
        'limit_partners_count' => 'integer',
        'limit_testimonials_count' => 'integer',
    ];

    /**
     * Get or create the singleton instance of web configuration.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'site_name' => 'Lentera Pasar',
            'site_tagline' => 'Sistem Informasi Manajemen & Administrasi Terpadu',
            'site_description' => 'Platform tata kelola administrasi modern, terpusat, dan terintegrasi.',
            'contact_email' => 'support@lenterapasar.id',
            'contact_phone' => '+62 812-3456-7890',
            'contact_whatsapp' => '+62 812-3456-7890',
            'contact_address' => 'Jl. Jenderal Sudirman Kav. 52-53, Jakarta Selatan, DKI Jakarta 12190',
            'footer_text' => '© 2026 Lentera Pasar. All Rights Reserved.',
            'meta_keywords' => 'admin panel, lentera pasar, manajemen pasar, dashboard',
            'meta_author' => 'Lentera Pasar Tech Team',
            'maintenance_mode' => false,
            'registration_enabled' => true,
            'article_module_enabled' => true,
            'testimonial_module_enabled' => true,
            'partner_module_enabled' => true,
            'faq_module_enabled' => true,
            'gallery_module_enabled' => true,
            'limit_media_storage_mb' => 1024,
            'limit_users_count' => 50,
            'limit_articles_count' => 100,
            'limit_gallery_activities_count' => 50,
            'limit_faqs_count' => 50,
            'limit_partners_count' => 50,
            'limit_testimonials_count' => 50,
        ]);
    }

    /**
     * Get the full URL for the logo or a default fallback.
     */
    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return Storage::disk('public')->url($this->logo_path);
        }
        return null;
    }

    /**
     * Get the full URL for the favicon or a default fallback.
     */
    public function getFaviconUrlAttribute(): ?string
    {
        if ($this->favicon_path && Storage::disk('public')->exists($this->favicon_path)) {
            return Storage::disk('public')->url($this->favicon_path);
        }
        return null;
    }
}
