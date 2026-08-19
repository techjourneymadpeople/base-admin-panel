<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('web_configurations', function (Blueprint $table) {
            $table->unsignedInteger('limit_media_storage_mb')->nullable()->default(1024)->after('maintenance_mode');
            $table->unsignedInteger('limit_users_count')->nullable()->default(50)->after('limit_media_storage_mb');
            $table->unsignedInteger('limit_articles_count')->nullable()->default(100)->after('limit_users_count');
            $table->unsignedInteger('limit_gallery_activities_count')->nullable()->default(50)->after('limit_articles_count');
            $table->unsignedInteger('limit_faqs_count')->nullable()->default(50)->after('limit_gallery_activities_count');
            $table->unsignedInteger('limit_partners_count')->nullable()->default(50)->after('limit_faqs_count');
            $table->unsignedInteger('limit_testimonials_count')->nullable()->default(50)->after('limit_partners_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'limit_media_storage_mb',
                'limit_users_count',
                'limit_articles_count',
                'limit_gallery_activities_count',
                'limit_faqs_count',
                'limit_partners_count',
                'limit_testimonials_count',
            ]);
        });
    }
};
