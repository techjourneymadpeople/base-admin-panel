<?php

namespace App\Observers;

use App\Models\GalleryActivity;
use App\Models\SlugRedirect;
use App\Services\SitemapService;

class GalleryActivityObserver
{
    /**
     * Handle the GalleryActivity "created" event.
     */
    public function created(GalleryActivity $gallery): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the GalleryActivity "updating" event.
     */
    public function updating(GalleryActivity $gallery): void
    {
        if ($gallery->isDirty('slug')) {
            $oldSlug = $gallery->getOriginal('slug');
            $newSlug = $gallery->slug;

            if (!empty($oldSlug) && !empty($newSlug) && $oldSlug !== $newSlug) {
                SlugRedirect::createRedirect(
                    model: $gallery,
                    sourcePath: '/galleries/' . $oldSlug,
                    targetPath: '/galleries/' . $newSlug,
                    statusCode: 301
                );
            }
        }
    }

    /**
     * Handle the GalleryActivity "updated" event.
     */
    public function updated(GalleryActivity $gallery): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the GalleryActivity "deleted" event.
     */
    public function deleted(GalleryActivity $gallery): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the GalleryActivity "restored" event.
     */
    public function restored(GalleryActivity $gallery): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the GalleryActivity "forceDeleted" event.
     */
    public function forceDeleted(GalleryActivity $gallery): void
    {
        SlugRedirect::where('redirectable_type', GalleryActivity::class)
            ->where('redirectable_id', (string) $gallery->id)
            ->delete();

        SitemapService::generate();
    }
}
