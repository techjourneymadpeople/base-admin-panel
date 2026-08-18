<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapService
{
    /**
     * Generate the complete sitemap.xml file.
     */
    public static function generate(): bool
    {
        try {
            $sitemap = Sitemap::create();

            // 1. Add Homepage
            $sitemap->add(
                Url::create(url('/'))
                    ->setLastModificationDate(now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setPriority(1.0)
            );

            // 2. Add Published Articles
            Article::published()->with(['category'])->get()->each(function (Article $article) use ($sitemap) {
                $articleUrl = url('/articles/' . $article->slug);

                $sitemap->add(
                    Url::create($articleUrl)
                        ->setLastModificationDate($article->updated_at ?? $article->published_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            });

            // 3. Add Active Article Categories
            ArticleCategory::where('is_active', true)->get()->each(function (ArticleCategory $category) use ($sitemap) {
                $categoryUrl = url('/articles/category/' . $category->slug);

                $sitemap->add(
                    Url::create($categoryUrl)
                        ->setLastModificationDate($category->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.6)
                );
            });

            // 4. Add Article Tags
            ArticleTag::all()->each(function (ArticleTag $tag) use ($sitemap) {
                $tagUrl = url('/articles/tag/' . $tag->slug);

                $sitemap->add(
                    Url::create($tagUrl)
                        ->setLastModificationDate($tag->updated_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.4)
                );
            });

            // 5. Add Published Gallery Activities
            \App\Models\GalleryActivity::published()->get()->each(function (\App\Models\GalleryActivity $gallery) use ($sitemap) {
                $galleryUrl = url('/galleries/' . $gallery->slug);

                $sitemap->add(
                    Url::create($galleryUrl)
                        ->setLastModificationDate($gallery->updated_at ?? $gallery->published_at ?? now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            });

            // Write to public/sitemap.xml
            $sitemap->writeToFile(public_path('sitemap.xml'));

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to generate sitemap.xml: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return false;
        }
    }
}
