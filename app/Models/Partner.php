<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Partner extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    /**
     * Activity log options for Partner model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('partner')
            ->setDescriptionForEvent(fn(string $eventName) => "Brand / Partner {$eventName}");
    }

    protected $table = 'partners';

    protected $fillable = [
        'name',
        'logo_media_id',
        'logo_url',
        'website_url',
        'category',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the logo media associated with the partner.
     */
    public function logoMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'logo_media_id');
    }

    /**
     * Get resolved logo URL.
     */
    public function getLogo(): ?string
    {
        if ($this->logoMedia) {
            return $this->logoMedia->getUrl();
        }

        return $this->logo_url;
    }

    /**
     * Scope for active partners.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered partners.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order', 'asc')->orderBy('name', 'asc');
    }
}
