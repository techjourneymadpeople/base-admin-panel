<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessIdentity extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    /**
     * Activity log options for BusinessIdentity model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('business_identity')
            ->setDescriptionForEvent(fn(string $eventName) => "Identitas Bisnis {$eventName}");
    }

    protected $table = 'business_identities';

    protected $fillable = [
        // Identitas & Legalitas Usaha
        'company_name',
        'brand_name',
        'tagline',
        'legal_type',
        'business_category',
        'npwp',
        'nib',
        'founded_year',
        'director_name',

        // Informasi Perbankan & Rekening Resmi
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'bank_branch',

        // Tentang Perusahaan, Visi & Misi
        'about_summary',
        'about_story',
        'vision',
        'mission',
        'core_values',

        // Aset Visual (Media Library)
        'logo_light_media_id',
        'logo_light_url',
        'logo_dark_media_id',
        'logo_dark_url',
        'favicon_media_id',
        'favicon_url',
        'hero_banner_media_id',
        'hero_banner_url',

        // Kontak & Lokasi Kantor
        'email',
        'phone',
        'whatsapp',
        'address',
        'city',
        'province',
        'postal_code',
        'google_maps_embed',
        'google_maps_url',
        'operational_hours',

        // Sosial Media Resmi
        'social_instagram',
        'social_tiktok',
        'social_youtube',
        'social_linkedin',
        'social_facebook',
        'social_twitter',
        'social_threads',
    ];

    /**
     * Get or create the singleton instance of Business Identity.
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'company_name' => config('app.name', 'PT Lentera Digital Nusantara'),
            'brand_name' => config('app.name', 'Lentera Pasar'),
            'tagline' => 'Solusi Digital & Ekosistem Bisnis Terpercaya',
            'legal_type' => 'Perseroan Terbatas (PT)',
            'business_category' => 'Teknologi Informasi & Digital',
        ]);
    }

    /**
     * Media relations
     */
    public function logoLightMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_light_media_id');
    }

    public function logoDarkMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_dark_media_id');
    }

    public function faviconMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'favicon_media_id');
    }

    public function heroBannerMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'hero_banner_media_id');
    }

    /**
     * URL Resolvers
     */
    public function getLogoLight(): ?string
    {
        return $this->logoLightMedia?->getUrl() ?? $this->logo_light_url;
    }

    public function getLogoDark(): ?string
    {
        return $this->logoDarkMedia?->getUrl() ?? $this->logo_dark_url;
    }

    public function getFavicon(): ?string
    {
        return $this->faviconMedia?->getUrl() ?? $this->favicon_url;
    }

    public function getHeroBanner(): ?string
    {
        return $this->heroBannerMedia?->getUrl() ?? $this->hero_banner_url;
    }

    /**
     * Get OpenGraph image fallback: Hero Banner -> Logo Light -> null.
     */
    public function getOgImage(): ?string
    {
        return $this->getHeroBanner() ?: ($this->getLogoLight() ?: $this->getLogoDark());
    }

    /**
     * Get Brand Display Name (fallback to company_name or app.name).
     */
    public function getBrandDisplayName(): string
    {
        return $this->brand_name ?: ($this->company_name ?: config('app.name', 'Lentera Pasar'));
    }

    /**
     * Get Brand 2-letter Initials for fallback badge.
     */
    public function getBrandInitials(): string
    {
        $words = preg_split('/\s+/', trim($this->getBrandDisplayName()));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->getBrandDisplayName(), 0, 2));
    }
}
