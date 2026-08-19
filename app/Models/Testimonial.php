<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Testimonial extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    /**
     * Activity log options for Testimonial model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('testimonial')
            ->setDescriptionForEvent(fn(string $eventName) => "Testimoni {$eventName}");
    }

    protected $table = 'testimonials';

    protected $fillable = [
        'name',
        'role_or_title',
        'company',
        'avatar_media_id',
        'avatar_url',
        'content',
        'rating',
        'category',
        'order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the avatar media associated with the testimonial.
     */
    public function avatarMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'avatar_media_id');
    }

    /**
     * Get resolved avatar URL.
     */
    public function getAvatar(): ?string
    {
        if ($this->avatarMedia) {
            return $this->avatarMedia->getUrl();
        }

        return $this->avatar_url;
    }

    /**
     * Scope for active testimonials.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered testimonials.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('created_at', 'desc');
    }
}
