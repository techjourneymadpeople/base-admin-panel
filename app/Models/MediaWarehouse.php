<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MediaWarehouse extends Model implements HasMedia
{
    use HasFactory, HasUlids, InteractsWithMedia, LogsActivity;

    /**
     * Activity log options for MediaWarehouse model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('media_warehouse')
            ->setDescriptionForEvent(fn(string $eventName) => "Gudang Media {$eventName}");
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media_warehouses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get or create the default instance of MediaWarehouse.
     */
    public static function getInstance(): self
    {
        return static::firstOrCreate(['name' => 'Gudang Media Utama']);
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('default')
            ->useDisk('public');
    }
}
