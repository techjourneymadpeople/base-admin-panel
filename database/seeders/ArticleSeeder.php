<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\Media;
use App\Models\User;
use App\Services\SitemapService;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();
        $media = Media::first();

        // 1. Create Categories
        $categories = [
            [
                'name' => 'Bisnis & UMKM',
                'description' => 'Strategi pengembangan bisnis pasar, digitalisasi UMKM, dan manajemen keuangan modern.',
                'order' => 1,
            ],
            [
                'name' => 'Teknologi & Digital',
                'description' => 'Inovasi teknologi aplikasi pasar, sistem kasir pintar, dan otomatisasi operasional.',
                'order' => 2,
            ],
            [
                'name' => 'Tips & Edukasi Pasar',
                'description' => 'Panduan praktis berdagang, strategi pemasaran lokal, dan kepuasan pelanggan.',
                'order' => 3,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = ArticleCategory::firstOrCreate(
                ['name' => $cat['name']],
                $cat
            );
        }

        // 2. Create Tags
        $tags = ['SEO Friendly', 'Digitalisasi Pasar', 'UMKM Indonesia', 'Tips Bisnis', 'Manajemen Stok', 'Aplikasi Kasir'];
        $createdTags = [];
        foreach ($tags as $tagName) {
            $createdTags[] = ArticleTag::firstOrCreate(['name' => $tagName]);
        }

        // 3. Create Sample Articles
        $sampleArticles = [
            [
                'category_id' => $createdCategories[0]->id,
                'user_id' => $user->id,
                'title' => 'Strategi Meningkatkan Penjualan Pedagang Pasar di Era Digital',
                'excerpt' => 'Pelajari cara mudah para pedagang pasar tradisional memanfaatkan teknologi digital untuk melipatgandakan omset penjualan harian.',
                'content' => "Era digital membawa peluang emas bagi para pelaku usaha di pasar tradisional dan modern. Dengan memanfaatkan ekosistem terpadu seperti katalog produk digital, sistem pencatatan otomatis, serta promosi terarah, pedagang dapat menjangkau pelanggan baru tanpa meninggalkan kenyamanan berdagang secara langsung.\n\nLangkah-langkah strategis:\n1. Menerapkan sistem inventaris stok barang secara real-time.\n2. Menggunakan saluran komunikasi pesan instan untuk pemesanan rutin pelanggan loyal.\n3. Memanfaatkan aplikasi manajemen transaksi yang cepat dan transparan.",
                'thumbnail_media_id' => $media?->id,
                'thumbnail_url' => $media?->getUrl(),
                'meta_title' => 'Strategi Penjualan Pedagang Pasar Era Digital | Lentera Pasar',
                'meta_description' => 'Panduan lengkap dan praktis meningkatkan omset penjualan pedagang pasar melalui digitalisasi dan manajemen stok modern.',
                'meta_keywords' => 'pedagang pasar, digitalisasi umkm, tips bisnis pasar, lentera pasar',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'views_count' => 142,
            ],
            [
                'category_id' => $createdCategories[1]->id,
                'user_id' => $user->id,
                'title' => 'Pentingnya Optimasi SEO untuk Website dan Portal Informasi',
                'excerpt' => 'Mengapa struktur SEO friendly, auto-slug, dan sitemap.xml otomatis sangat penting bagi pertumbuhan visibilitas platform Anda.',
                'content' => "Search Engine Optimization (SEO) adalah pondasi penting dalam membangun kehadiran online yang berkelanjutan. Dengan artikel terstruktur, meta description yang tepat sasaran, serta file sitemap.xml yang selalu terupdate secara otomatis, mesin pencari seperti Google dapat melakukan perayapan dan pengindeksan halaman dengan sangat cepat dan akurat.",
                'thumbnail_media_id' => $media?->id,
                'thumbnail_url' => $media?->getUrl(),
                'meta_title' => 'Optimasi SEO Friendly untuk Pertumbuhan Website',
                'meta_description' => 'Pelajari pentingnya struktur SEO friendly, pengelolaan sitemap otomatis, dan arsitektur konten yang optimal.',
                'meta_keywords' => 'seo friendly, sitemap xml, panduan seo, artikel seo',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'views_count' => 88,
            ],
        ];

        foreach ($sampleArticles as $artData) {
            $article = Article::firstOrCreate(
                ['title' => $artData['title']],
                $artData
            );
            $article->tags()->sync(collect($createdTags)->pluck('id'));
        }

        // Generate Sitemap XML
        SitemapService::generate();
    }
}
