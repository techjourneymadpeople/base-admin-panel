<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class GalleryActivity extends Model
{
    use HasFactory, HasUlids, HasSlug;

    protected $table = 'gallery_activities';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'activity_date',
        'location',
        'description',
        'thumbnail_media_id',
        'thumbnail_url',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'status',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Author/User of the gallery activity.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Media Library relationship for thumbnail.
     */
    public function thumbnailMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'thumbnail_media_id');
    }

    /**
     * Photos belonging to this gallery activity.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(GalleryActivityPhoto::class, 'gallery_activity_id')->orderBy('order');
    }

    /**
     * Scope for published galleries.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Get resolved thumbnail URL.
     */
    public function getThumbnail(): ?string
    {
        if ($this->thumbnailMedia) {
            return $this->thumbnailMedia->getUrl();
        }

        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }

        // Fallback: use first photo in gallery if thumbnail not set
        $firstPhoto = $this->photos->first();
        if ($firstPhoto && $firstPhoto->media) {
            return $firstPhoto->media->getUrl();
        }

        return null;
    }

    /**
     * Get resolved Canonical URL.
     */
    public function getCanonicalUrl(): string
    {
        return $this->canonical_url ?: url('/galleries/' . $this->slug);
    }
}
