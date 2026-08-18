<?php

namespace App\Observers;

use App\Models\ArticleCategory;
use App\Models\SlugRedirect;
use App\Services\SitemapService;

class ArticleCategoryObserver
{
    /**
     * Handle the ArticleCategory "created" event.
     */
    public function created(ArticleCategory $category): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the ArticleCategory "updating" event.
     */
    public function updating(ArticleCategory $category): void
    {
        if ($category->isDirty('slug')) {
            $oldSlug = $category->getOriginal('slug');
            $newSlug = $category->slug;

            if (!empty($oldSlug) && !empty($newSlug) && $oldSlug !== $newSlug) {
                // Record 301 redirect for category paths
                SlugRedirect::createRedirect(
                    model: $category,
                    sourcePath: '/articles/category/' . $oldSlug,
                    targetPath: '/articles/category/' . $newSlug,
                    statusCode: 301
                );

                SlugRedirect::createRedirect(
                    model: $category,
                    sourcePath: '/category/' . $oldSlug,
                    targetPath: '/articles/category/' . $newSlug,
                    statusCode: 301
                );
            }
        }
    }

    /**
     * Handle the ArticleCategory "updated" event.
     */
    public function updated(ArticleCategory $category): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the ArticleCategory "deleted" event.
     */
    public function deleted(ArticleCategory $category): void
    {
        SitemapService::generate();
    }

    /**
     * Handle the ArticleCategory "forceDeleted" event.
     */
    public function forceDeleted(ArticleCategory $category): void
    {
        SlugRedirect::where('redirectable_type', ArticleCategory::class)
            ->where('redirectable_id', (string) $category->id)
            ->delete();

        SitemapService::generate();
    }
}
