<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HasUlids, LogsActivity;

    /**
     * Activity log options for Media model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'file_name', 'mime_type', 'disk', 'size', 'collection_name'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('media')
            ->setDescriptionForEvent(fn(string $eventName) => "File Media {$eventName}");
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The primary key type for ULID string.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get relative URL for seamless same-origin rendering across localhost, 127.0.0.1, and domain names.
     */
    public function getUrl(string $conversionName = ''): string
    {
        $url = parent::getUrl($conversionName);
        $path = parse_url($url, PHP_URL_PATH);
        return $path ?: $url;
    }

    /**
     * Get the full absolute URL dynamically adhering to the current HTTP request host.
     */
    public function getFullUrl(string $conversionName = ''): string
    {
        return url($this->getUrl($conversionName));
    }
}
