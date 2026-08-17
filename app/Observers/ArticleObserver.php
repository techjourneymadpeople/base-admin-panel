<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
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
        SitemapService::generate();
    }
}
