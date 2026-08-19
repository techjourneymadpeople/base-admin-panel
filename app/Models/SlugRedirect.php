<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SlugRedirect extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    /**
     * Activity log options for SlugRedirect model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('slug_redirect')
            ->setDescriptionForEvent(fn(string $eventName) => "Slug Redirect {$eventName}");
    }

    protected $table = 'slug_redirects';

    protected $fillable = [
        'redirectable_type',
        'redirectable_id',
        'source_path',
        'target_path',
        'status_code',
        'hits',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'hits' => 'integer',
    ];

    /**
     * Get the owning redirectable model.
     */
    public function redirectable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Record or update a permanent 301 redirect.
     */
    public static function createRedirect($model, string $sourcePath, string $targetPath, int $statusCode = 301): ?self
    {
        $source = '/' . ltrim(trim($sourcePath), '/');
        $target = '/' . ltrim(trim($targetPath), '/');

        // Do not redirect to self
        if ($source === $target) {
            return null;
        }

        // Avoid loops: if there was a redirect from the new target to something else, remove it
        static::where('source_path', $target)->delete();

        // Avoid chains: update existing redirects that pointed to the old source to point directly to the new target
        static::where('target_path', $source)->update(['target_path' => $target]);

        // Create or update the redirect record
        return static::updateOrCreate(
            ['source_path' => $source],
            [
                'redirectable_type' => $model ? get_class($model) : null,
                'redirectable_id' => $model ? (string) $model->getKey() : null,
                'target_path' => $target,
                'status_code' => $statusCode,
            ]
        );
    }
}
