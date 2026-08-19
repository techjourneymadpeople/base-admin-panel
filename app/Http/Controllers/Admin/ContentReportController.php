<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Faq;
use App\Models\GalleryActivity;
use App\Models\Media;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WebConfiguration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentReportController extends Controller
{
    /**
     * Display the Content Production & Team Activity Report.
     */
    public function index(Request $request): View
    {
        $config = WebConfiguration::current();

        // 1. Overall Summary Totals (Conditionally counted only if module is enabled)
        $totalArticles = $config->article_module_enabled ? Article::count() : 0;
        $totalGalleries = $config->gallery_module_enabled ? GalleryActivity::count() : 0;
        $totalFaqs = $config->faq_module_enabled ? Faq::count() : 0;
        $totalPartners = $config->partner_module_enabled ? Partner::count() : 0;
        $totalTestimonials = $config->testimonial_module_enabled ? Testimonial::count() : 0;
        $totalMedia = Media::count();

        $totalAllContent = $totalArticles + $totalGalleries + $totalFaqs + $totalPartners + $totalTestimonials;

        $startOfMonth = now()->startOfMonth();
        $startOfWeek = now()->startOfWeek();
        $today = now()->startOfDay();

        // This Month Content Stats
        $articlesThisMonth = $config->article_module_enabled ? Article::where('created_at', '>=', $startOfMonth)->count() : 0;
        $galleriesThisMonth = $config->gallery_module_enabled ? GalleryActivity::where('created_at', '>=', $startOfMonth)->count() : 0;
        $faqsThisMonth = $config->faq_module_enabled ? Faq::where('created_at', '>=', $startOfMonth)->count() : 0;
        $partnersThisMonth = $config->partner_module_enabled ? Partner::where('created_at', '>=', $startOfMonth)->count() : 0;
        $testimonialsThisMonth = $config->testimonial_module_enabled ? Testimonial::where('created_at', '>=', $startOfMonth)->count() : 0;
        $totalThisMonth = $articlesThisMonth + $galleriesThisMonth + $faqsThisMonth + $partnersThisMonth + $testimonialsThisMonth;

        // This Week & Today Content Stats
        $articlesThisWeek = $config->article_module_enabled ? Article::where('created_at', '>=', $startOfWeek)->count() : 0;
        $galleriesThisWeek = $config->gallery_module_enabled ? GalleryActivity::where('created_at', '>=', $startOfWeek)->count() : 0;
        $faqsThisWeek = $config->faq_module_enabled ? Faq::where('created_at', '>=', $startOfWeek)->count() : 0;
        $partnersThisWeek = $config->partner_module_enabled ? Partner::where('created_at', '>=', $startOfWeek)->count() : 0;
        $testimonialsThisWeek = $config->testimonial_module_enabled ? Testimonial::where('created_at', '>=', $startOfWeek)->count() : 0;
        $totalThisWeek = $articlesThisWeek + $galleriesThisWeek + $faqsThisWeek + $partnersThisWeek + $testimonialsThisWeek;

        $articlesToday = $config->article_module_enabled ? Article::where('created_at', '>=', $today)->count() : 0;
        $galleriesToday = $config->gallery_module_enabled ? GalleryActivity::where('created_at', '>=', $today)->count() : 0;
        $faqsToday = $config->faq_module_enabled ? Faq::where('created_at', '>=', $today)->count() : 0;
        $partnersToday = $config->partner_module_enabled ? Partner::where('created_at', '>=', $today)->count() : 0;
        $testimonialsToday = $config->testimonial_module_enabled ? Testimonial::where('created_at', '>=', $today)->count() : 0;
        $totalToday = $articlesToday + $galleriesToday + $faqsToday + $partnersToday + $testimonialsToday;

        $totalArticleViews = $config->article_module_enabled ? Article::sum('views_count') : 0;

        // 2. Monthly Trend for Last 6 Months (Chart.js Line/Bar Chart)
        $months = [];
        $articlesTrend = [];
        $galleriesTrend = [];
        $faqsTrend = [];
        $partnersTrend = [];
        $testimonialsTrend = [];
        $totalTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthLabel = $monthDate->translatedFormat('M Y');
            $start = (clone $monthDate)->startOfMonth();
            $end = (clone $monthDate)->endOfMonth();

            $artCount = $config->article_module_enabled ? Article::whereBetween('created_at', [$start, $end])->count() : 0;
            $galCount = $config->gallery_module_enabled ? GalleryActivity::whereBetween('created_at', [$start, $end])->count() : 0;
            $faqCount = $config->faq_module_enabled ? Faq::whereBetween('created_at', [$start, $end])->count() : 0;
            $partCount = $config->partner_module_enabled ? Partner::whereBetween('created_at', [$start, $end])->count() : 0;
            $testiCount = $config->testimonial_module_enabled ? Testimonial::whereBetween('created_at', [$start, $end])->count() : 0;

            $months[] = $monthLabel;
            $articlesTrend[] = $artCount;
            $galleriesTrend[] = $galCount;
            $faqsTrend[] = $faqCount;
            $partnersTrend[] = $partCount;
            $testimonialsTrend[] = $testiCount;
            $totalTrend[] = ($artCount + $galCount + $faqCount + $partCount + $testiCount);
        }

        // Dynamic trend datasets based on active modules
        $trendDatasets = [];
        if ($config->article_module_enabled) {
            $trendDatasets[] = [
                'label' => 'Artikel SEO',
                'data' => $articlesTrend,
                'backgroundColor' => '#1d3e35',
                'borderRadius' => 6,
            ];
        }
        if ($config->gallery_module_enabled) {
            $trendDatasets[] = [
                'label' => 'Galeri Kegiatan',
                'data' => $galleriesTrend,
                'backgroundColor' => '#31725e',
                'borderRadius' => 6,
            ];
        }
        if ($config->faq_module_enabled) {
            $trendDatasets[] = [
                'label' => 'FAQ',
                'data' => $faqsTrend,
                'backgroundColor' => '#cca06e',
                'borderRadius' => 6,
            ];
        }
        if ($config->partner_module_enabled) {
            $trendDatasets[] = [
                'label' => 'Brand / Mitra',
                'data' => $partnersTrend,
                'backgroundColor' => '#99cab7',
                'borderRadius' => 6,
            ];
        }
        if ($config->testimonial_module_enabled) {
            $trendDatasets[] = [
                'label' => 'Testimoni',
                'data' => $testimonialsTrend,
                'backgroundColor' => '#784732',
                'borderRadius' => 6,
            ];
        }

        // 3. Module Distribution (Chart.js Doughnut Chart)
        $distributionLabels = [];
        $distributionCounts = [];
        $distributionColors = [];

        if ($config->article_module_enabled) {
            $distributionLabels[] = 'Artikel SEO';
            $distributionCounts[] = $totalArticles;
            $distributionColors[] = '#1d3e35';
        }
        if ($config->gallery_module_enabled) {
            $distributionLabels[] = 'Galeri Kegiatan';
            $distributionCounts[] = $totalGalleries;
            $distributionColors[] = '#31725e';
        }
        if ($config->faq_module_enabled) {
            $distributionLabels[] = 'FAQ';
            $distributionCounts[] = $totalFaqs;
            $distributionColors[] = '#cca06e';
        }
        if ($config->partner_module_enabled) {
            $distributionLabels[] = 'Brand / Mitra';
            $distributionCounts[] = $totalPartners;
            $distributionColors[] = '#99cab7';
        }
        if ($config->testimonial_module_enabled) {
            $distributionLabels[] = 'Testimoni';
            $distributionCounts[] = $totalTestimonials;
            $distributionColors[] = '#784732';
        }

        $distributionData = [
            'labels' => $distributionLabels,
            'data' => $distributionCounts,
            'colors' => $distributionColors,
        ];

        // 4. Team / Contributor Performance Table (Admin, Editor, Super Admin, etc.)
        $contributors = collect();
        if ($config->article_module_enabled) {
            $contributors = User::with(['roles', 'articles'])
                ->get()
                ->map(function (User $user) use ($startOfMonth) {
                    $articles = $user->articles;
                    $articlesCount = $articles->count();
                    $articlesPublished = $articles->where('status', 'published')->count();
                    $articlesDraft = $articles->where('status', 'draft')->count();
                    $articlesFeatured = $articles->where('is_featured', true)->count();
                    $articlesThisMonth = $articles->where('created_at', '>=', $startOfMonth)->count();
                    $totalViews = $articles->sum('views_count');
                    $lastArticle = $articles->sortByDesc('created_at')->first();

                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => null,
                        'roles' => $user->getRoleNames(),
                        'total_articles' => $articlesCount,
                        'published_articles' => $articlesPublished,
                        'draft_articles' => $articlesDraft,
                        'featured_articles' => $articlesFeatured,
                        'articles_this_month' => $articlesThisMonth,
                        'total_views' => $totalViews,
                        'last_activity' => $lastArticle ? $lastArticle->created_at : $user->updated_at,
                    ];
                })
                ->sortByDesc('total_articles')
                ->values();
        }

        // 5. Recent Content Production Stream (Audit Trail / Activity Feed)
        $recentActivitiesCollection = collect();

        if ($config->article_module_enabled) {
            $recentArticles = Article::with('author')
                ->latest('created_at')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'module' => 'Artikel SEO',
                        'module_badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'title' => $item->title,
                        'creator' => $item->author ? $item->author->name : 'Sistem',
                        'creator_avatar' => $item->author ? ($item->author->avatar_url ?? null) : null,
                        'status' => $item->status === 'published' ? 'Terbit' : ($item->status === 'draft' ? 'Draft' : 'Arsip'),
                        'status_class' => $item->status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200',
                        'views' => $item->views_count,
                        'created_at' => $item->created_at,
                        'url' => route('admin.articles.show', $item->id),
                    ];
                });
            $recentActivitiesCollection = $recentActivitiesCollection->concat($recentArticles);
        }

        if ($config->gallery_module_enabled) {
            $recentGalleries = GalleryActivity::latest('created_at')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'module' => 'Galeri Kegiatan',
                        'module_badge' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'title' => $item->title,
                        'creator' => 'Tim Konten',
                        'creator_avatar' => null,
                        'status' => $item->status === 'published' ? 'Terbit' : 'Draft',
                        'status_class' => $item->status === 'published' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-amber-50 text-amber-700 border-amber-200',
                        'views' => '-',
                        'created_at' => $item->created_at,
                        'url' => route('admin.gallery-activities.edit', $item->id),
                    ];
                });
            $recentActivitiesCollection = $recentActivitiesCollection->concat($recentGalleries);
        }

        if ($config->faq_module_enabled) {
            $recentFaqs = Faq::latest('created_at')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'module' => 'FAQ',
                        'module_badge' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'title' => $item->question,
                        'creator' => 'Tim Support',
                        'creator_avatar' => null,
                        'status' => $item->is_active ? 'Aktif' : 'Nonaktif',
                        'status_class' => $item->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-600 border-stone-200',
                        'views' => '-',
                        'created_at' => $item->created_at,
                        'url' => route('admin.faqs.edit', $item->id),
                    ];
                });
            $recentActivitiesCollection = $recentActivitiesCollection->concat($recentFaqs);
        }

        if ($config->partner_module_enabled) {
            $recentPartners = Partner::latest('created_at')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'module' => 'Brand / Mitra',
                        'module_badge' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'title' => $item->name,
                        'creator' => 'Tim Relasi',
                        'creator_avatar' => null,
                        'status' => $item->is_active ? 'Aktif' : 'Nonaktif',
                        'status_class' => $item->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-600 border-stone-200',
                        'views' => '-',
                        'created_at' => $item->created_at,
                        'url' => route('admin.partners.edit', $item->id),
                    ];
                });
            $recentActivitiesCollection = $recentActivitiesCollection->concat($recentPartners);
        }

        if ($config->testimonial_module_enabled) {
            $recentTestimonials = Testimonial::latest('created_at')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return [
                        'module' => 'Testimoni',
                        'module_badge' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'title' => $item->name . ' - ' . ($item->company ?? 'Klien'),
                        'creator' => 'Tim Layanan',
                        'creator_avatar' => null,
                        'status' => $item->is_active ? 'Aktif' : 'Nonaktif',
                        'status_class' => $item->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-stone-100 text-stone-600 border-stone-200',
                        'views' => '-',
                        'created_at' => $item->created_at,
                        'url' => route('admin.testimonials.edit', $item->id),
                    ];
                });
            $recentActivitiesCollection = $recentActivitiesCollection->concat($recentTestimonials);
        }

        $recentActivities = $recentActivitiesCollection
            ->sortByDesc('created_at')
            ->take(15)
            ->values();

        return view('admin.reports.content', compact(
            'config',
            'totalArticles',
            'totalGalleries',
            'totalFaqs',
            'totalPartners',
            'totalTestimonials',
            'totalMedia',
            'totalAllContent',
            'totalThisMonth',
            'totalThisWeek',
            'totalToday',
            'totalArticleViews',
            'months',
            'trendDatasets',
            'totalTrend',
            'distributionData',
            'contributors',
            'recentActivities'
        ));
    }
}
