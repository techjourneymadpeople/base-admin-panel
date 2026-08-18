<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\SlugRedirect;
use App\Services\SitemapService;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the Article "updating" event.
     */
    public function updating(Article $article): void
    {
        if ($article->isDirty('slug')) {
            $oldSlug = $article->getOriginal('slug');
            $newSlug = $article->slug;

            if (!empty($oldSlug) && !empty($newSlug) && $oldSlug !== $newSlug) {
                SlugRedirect::createRedirect(
                    model: $article,
                    sourcePath: '/articles/' . $oldSlug,
                    targetPath: '/articles/' . $newSlug,
                    statusCode: 301
                );
            }
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the Article "forceDeleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        SlugRedirect::where('redirectable_type', Article::class)
            ->where('redirectable_id', (string) $article->id)
            ->delete();

        SitemapService::generate();
    }
}
