<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    use HasFactory, HasUlids, HasSlug;

    protected $table = 'articles';

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
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
     * Category of the article.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'category_id');
    }

    /**
     * Author/User of the article.
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
     * Tags associated with this article.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(ArticleTag::class, 'article_tag', 'article_id', 'tag_id');
    }

    /**
     * Scope for published articles.
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

        return null;
    }

    /**
     * Get resolved Canonical URL.
     */
    public function getCanonicalUrl(): string
    {
        return $this->canonical_url ?: url('/articles/' . $this->slug);
    }
}
