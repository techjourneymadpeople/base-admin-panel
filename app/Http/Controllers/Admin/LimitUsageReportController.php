<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\GalleryActivity;
use App\Models\GalleryActivityPhoto;
use App\Models\Media;
use App\Models\MediaWarehouse;
use App\Models\Partner;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WebConfiguration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LimitUsageReportController extends Controller
{
    /**
     * Display the system limit & resource usage report.
     */
    public function index(Request $request): View
    {
        $config = WebConfiguration::current();

        // 1. Media Storage Calculation
        $totalMediaBytes = Media::where('model_type', MediaWarehouse::class)->sum('size');
        $totalMediaMb = round($totalMediaBytes / 1048576, 2);
        $limitMediaMb = $config->limit_media_storage_mb ?? 1024;
        $mediaUsagePercent = $limitMediaMb > 0 ? min(100, round(($totalMediaMb / $limitMediaMb) * 100, 1)) : 0;
        $totalMediaFiles = MediaWarehouse::count();

        // 2. Users Calculation
        $totalUsers = User::count();
        $limitUsers = $config->limit_users_count ?? 50;
        $usersUsagePercent = $limitUsers > 0 ? min(100, round(($totalUsers / $limitUsers) * 100, 1)) : 0;
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'nonactive')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();

        // 3. Articles Calculation
        $totalArticles = Article::count();
        $limitArticles = $config->limit_articles_count ?? 100;
        $articlesUsagePercent = $limitArticles > 0 ? min(100, round(($totalArticles / $limitArticles) * 100, 1)) : 0;
        $publishedArticles = Article::where('status', 'published')->count();
        $draftArticles = Article::where('status', 'draft')->count();
        $totalCategories = ArticleCategory::count();
        $totalTags = ArticleTag::count();

        // 4. Gallery Activity Calculation
        $totalGalleries = GalleryActivity::count();
        $limitGalleries = $config->limit_gallery_activities_count ?? 50;
        $galleriesUsagePercent = $limitGalleries > 0 ? min(100, round(($totalGalleries / $limitGalleries) * 100, 1)) : 0;
        $totalPhotos = GalleryActivityPhoto::count();

        // 5. FAQ Calculation
        $totalFaqs = Faq::count();
        $limitFaqs = $config->limit_faqs_count ?? 50;
        $faqsUsagePercent = $limitFaqs > 0 ? min(100, round(($totalFaqs / $limitFaqs) * 100, 1)) : 0;
        $activeFaqs = Faq::where('is_active', true)->count();
        $inactiveFaqs = Faq::where('is_active', false)->count();

        // 6. Brand / Partner Calculation
        $totalPartners = Partner::count();
        $limitPartners = $config->limit_partners_count ?? 50;
        $partnersUsagePercent = $limitPartners > 0 ? min(100, round(($totalPartners / $limitPartners) * 100, 1)) : 0;
        $activePartners = Partner::where('is_active', true)->count();
        $inactivePartners = Partner::where('is_active', false)->count();

        // 7. Testimonial Calculation
        $totalTestimonials = Testimonial::count();
        $limitTestimonials = $config->limit_testimonials_count ?? 50;
        $testimonialsUsagePercent = $limitTestimonials > 0 ? min(100, round(($totalTestimonials / $limitTestimonials) * 100, 1)) : 0;
        $activeTestimonials = Testimonial::where('is_active', true)->count();
        $avgRating = round(Testimonial::avg('rating') ?: 0, 1);

        // 8. Feedbacks Calculation
        $totalFeedbacks = Feedback::count();
        $starredFeedbacks = Feedback::where('is_starred', true)->count();
        $pendingFeedbacks = Feedback::where('status', 'pending')->count();
        $reviewedFeedbacks = Feedback::where('status', 'reviewed')->count();

        // Compile comprehensive report items
        $reportItems = [
            [
                'key' => 'storage',
                'name' => 'Kapasitas Media Storage',
                'category' => 'File & Penyimpanan',
                'icon' => 'hard-drive',
                'current' => $totalMediaMb,
                'current_formatted' => $totalMediaMb . ' MB (' . $totalMediaFiles . ' berkas)',
                'limit' => $limitMediaMb,
                'limit_formatted' => $limitMediaMb > 0 ? $limitMediaMb . ' MB' : 'Tidak Terbatas',
                'percentage' => $mediaUsagePercent,
                'remaining' => $limitMediaMb > 0 ? max(0, round($limitMediaMb - $totalMediaMb, 2)) . ' MB' : '∞',
                'unit' => 'MB',
                'details' => $totalMediaFiles . ' berkas gambar/media terunggah',
            ],
            [
                'key' => 'users',
                'name' => 'Akun Pengguna Terdaftar',
                'category' => 'Pengguna & Akses',
                'icon' => 'users',
                'current' => $totalUsers,
                'current_formatted' => $totalUsers . ' Pengguna',
                'limit' => $limitUsers,
                'limit_formatted' => $limitUsers > 0 ? $limitUsers . ' Akun' : 'Tidak Terbatas',
                'percentage' => $usersUsagePercent,
                'remaining' => $limitUsers > 0 ? max(0, $limitUsers - $totalUsers) . ' Akun' : '∞',
                'unit' => 'User',
                'details' => $activeUsers . ' Aktif • ' . $inactiveUsers . ' Non-Aktif • ' . $suspendedUsers . ' Suspended',
            ],
        ];

        if ($config->article_module_enabled) {
            $reportItems[] = [
                'key' => 'articles',
                'name' => 'Artikel & Berita (SEO)',
                'category' => 'Konten Publikasi',
                'icon' => 'file-text',
                'current' => $totalArticles,
                'current_formatted' => $totalArticles . ' Artikel',
                'limit' => $limitArticles,
                'limit_formatted' => $limitArticles > 0 ? $limitArticles . ' Artikel' : 'Tidak Terbatas',
                'percentage' => $articlesUsagePercent,
                'remaining' => $limitArticles > 0 ? max(0, $limitArticles - $totalArticles) . ' Artikel' : '∞',
                'unit' => 'Artikel',
                'details' => $publishedArticles . ' Publikasi • ' . $draftArticles . ' Draf • ' . $totalCategories . ' Kategori • ' . $totalTags . ' Tag',
            ];
        }

        $reportItems[] = [
            'key' => 'galleries',
            'name' => 'Galeri Kegiatan (Album)',
            'category' => 'Konten Publikasi',
            'icon' => 'images',
            'current' => $totalGalleries,
            'current_formatted' => $totalGalleries . ' Album',
            'limit' => $limitGalleries,
            'limit_formatted' => $limitGalleries > 0 ? $limitGalleries . ' Album' : 'Tidak Terbatas',
            'percentage' => $galleriesUsagePercent,
            'remaining' => $limitGalleries > 0 ? max(0, $limitGalleries - $totalGalleries) . ' Album' : '∞',
            'unit' => 'Album',
            'details' => $totalPhotos . ' foto dokumentasi di seluruh album',
        ];

        $reportItems[] = [
            'key' => 'faqs',
            'name' => 'Tanya Jawab (FAQ)',
            'category' => 'Informasi & Layanan',
            'icon' => 'help-circle',
            'current' => $totalFaqs,
            'current_formatted' => $totalFaqs . ' Pertanyaan',
            'limit' => $limitFaqs,
            'limit_formatted' => $limitFaqs > 0 ? $limitFaqs . ' FAQ' : 'Tidak Terbatas',
            'percentage' => $faqsUsagePercent,
            'remaining' => $limitFaqs > 0 ? max(0, $limitFaqs - $totalFaqs) . ' FAQ' : '∞',
            'unit' => 'FAQ',
            'details' => $activeFaqs . ' Tampil Aktif • ' . $inactiveFaqs . ' Dinonaktifkan',
        ];

        if ($config->partner_module_enabled) {
            $reportItems[] = [
                'key' => 'partners',
                'name' => 'Mitra / Brand Partner',
                'category' => 'Relasi Bisnis',
                'icon' => 'handshake',
                'current' => $totalPartners,
                'current_formatted' => $totalPartners . ' Mitra',
                'limit' => $limitPartners,
                'limit_formatted' => $limitPartners > 0 ? $limitPartners . ' Mitra' : 'Tidak Terbatas',
                'percentage' => $partnersUsagePercent,
                'remaining' => $limitPartners > 0 ? max(0, $limitPartners - $totalPartners) . ' Mitra' : '∞',
                'unit' => 'Mitra',
                'details' => $activePartners . ' Tampil Aktif • ' . $inactivePartners . ' Disembunyikan',
            ];
        }

        if ($config->testimonial_module_enabled) {
            $reportItems[] = [
                'key' => 'testimonials',
                'name' => 'Testimoni Klien',
                'category' => 'Ulasan & Reputasi',
                'icon' => 'quote',
                'current' => $totalTestimonials,
                'current_formatted' => $totalTestimonials . ' Testimoni',
                'limit' => $limitTestimonials,
                'limit_formatted' => $limitTestimonials > 0 ? $limitTestimonials . ' Testimoni' : 'Tidak Terbatas',
                'percentage' => $testimonialsUsagePercent,
                'remaining' => $limitTestimonials > 0 ? max(0, $limitTestimonials - $totalTestimonials) . ' Testimoni' : '∞',
                'unit' => 'Testimoni',
                'details' => $activeTestimonials . ' Tampil Aktif • Rating rata-rata: ' . $avgRating . '/5.0',
            ];
        }

        // Overall system health score
        $limitedItems = array_filter($reportItems, fn($i) => $i['limit'] > 0);
        $avgUsagePercent = count($limitedItems) > 0 
            ? round(array_sum(array_column($limitedItems, 'percentage')) / count($limitedItems), 1) 
            : 0;

        return view('admin.reports.limit-usage', compact(
            'config',
            'reportItems',
            'avgUsagePercent',
            'totalFeedbacks',
            'starredFeedbacks',
            'pendingFeedbacks',
            'reviewedFeedbacks'
        ));
    }
}
