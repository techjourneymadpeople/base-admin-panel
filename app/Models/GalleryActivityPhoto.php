<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GalleryActivityPhoto extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    /**
     * Activity log options for GalleryActivityPhoto model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('gallery_activity_photo')
            ->setDescriptionForEvent(fn(string $eventName) => "Foto Galeri {$eventName}");
    }

    protected $table = 'gallery_activity_photos';

    protected $fillable = [
        'gallery_activity_id',
        'media_id',
        'caption',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * The gallery activity this photo belongs to.
     */
    public function galleryActivity(): BelongsTo
    {
        return $this->belongsTo(GalleryActivity::class, 'gallery_activity_id');
    }

    /**
     * The Media Library item for this photo.
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    /**
     * Get image URL.
     */
    public function getUrl(): ?string
    {
        return $this->media ? $this->media->getUrl() : null;
    }
}
