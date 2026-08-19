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
        // 1. Overall Summary Totals
        $totalArticles = Article::count();
        $totalGalleries = GalleryActivity::count();
        $totalFaqs = Faq::count();
        $totalPartners = Partner::count();
        $totalTestimonials = Testimonial::count();
        $totalMedia = Media::count();
        $totalAllContent = $totalArticles + $totalGalleries + $totalFaqs + $totalPartners + $totalTestimonials;

        $startOfMonth = now()->startOfMonth();
        $startOfWeek = now()->startOfWeek();
        $today = now()->startOfDay();

        // This Month Content Stats
        $articlesThisMonth = Article::where('created_at', '>=', $startOfMonth)->count();
        $galleriesThisMonth = GalleryActivity::where('created_at', '>=', $startOfMonth)->count();
        $faqsThisMonth = Faq::where('created_at', '>=', $startOfMonth)->count();
        $partnersThisMonth = Partner::where('created_at', '>=', $startOfMonth)->count();
        $testimonialsThisMonth = Testimonial::where('created_at', '>=', $startOfMonth)->count();
        $totalThisMonth = $articlesThisMonth + $galleriesThisMonth + $faqsThisMonth + $partnersThisMonth + $testimonialsThisMonth;

        // This Week & Today Content Stats
        $totalThisWeek = Article::where('created_at', '>=', $startOfWeek)->count()
            + GalleryActivity::where('created_at', '>=', $startOfWeek)->count()
            + Faq::where('created_at', '>=', $startOfWeek)->count()
            + Partner::where('created_at', '>=', $startOfWeek)->count()
            + Testimonial::where('created_at', '>=', $startOfWeek)->count();

        $totalToday = Article::where('created_at', '>=', $today)->count()
            + GalleryActivity::where('created_at', '>=', $today)->count()
            + Faq::where('created_at', '>=', $today)->count()
            + Partner::where('created_at', '>=', $today)->count()
            + Testimonial::where('created_at', '>=', $today)->count();

        $totalArticleViews = Article::sum('views_count');

        // 2. Monthly Trend for Last 6 Months (Chart.js Line/Bar Chart)
        $months = [];
        $articlesTrend = [];
        $galleriesTrend = [];
        $faqsTrend = [];
        $otherTrend = [];
        $totalTrend = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthLabel = $monthDate->translatedFormat('M Y');
            $start = (clone $monthDate)->startOfMonth();
            $end = (clone $monthDate)->endOfMonth();

            $artCount = Article::whereBetween('created_at', [$start, $end])->count();
            $galCount = GalleryActivity::whereBetween('created_at', [$start, $end])->count();
            $faqCount = Faq::whereBetween('created_at', [$start, $end])->count();
            $othCount = Partner::whereBetween('created_at', [$start, $end])->count()
                + Testimonial::whereBetween('created_at', [$start, $end])->count();

            $months[] = $monthLabel;
            $articlesTrend[] = $artCount;
            $galleriesTrend[] = $galCount;
            $faqsTrend[] = $faqCount;
            $otherTrend[] = $othCount;
            $totalTrend[] = ($artCount + $galCount + $faqCount + $othCount);
        }

        // 3. Module Distribution (Chart.js Doughnut Chart)
        $distributionData = [
            'labels' => ['Artikel SEO', 'Galeri Kegiatan', 'FAQ', 'Brand / Mitra', 'Testimoni'],
            'data' => [$totalArticles, $totalGalleries, $totalFaqs, $totalPartners, $totalTestimonials],
        ];

        // 4. Team / Contributor Performance Table (Admin, Editor, Super Admin, Support, etc.)
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
            // Sort by total articles desc, then last_activity desc
            ->sortByDesc('total_articles')
            ->values();

        // 5. Recent Content Production Stream (Audit Trail / Activity Feed)
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

        $recentActivities = $recentArticles
            ->concat($recentGalleries)
            ->concat($recentFaqs)
            ->sortByDesc('created_at')
            ->take(15)
            ->values();

        return view('admin.reports.content', compact(
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
            'articlesTrend',
            'galleriesTrend',
            'faqsTrend',
            'otherTrend',
            'totalTrend',
            'distributionData',
            'contributors',
            'recentActivities'
        ));
    }
}
