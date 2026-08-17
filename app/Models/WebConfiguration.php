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
        'maintenance_mode',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'maintenance_mode' => 'boolean',
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
