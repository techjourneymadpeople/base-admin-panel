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
            $table->string('social_tiktok')->nullable()->after('social_linkedin');
            $table->string('social_threads')->nullable()->after('social_tiktok');
            $table->string('google_analytics_id')->nullable()->after('social_threads');
            $table->text('custom_head_scripts')->nullable()->after('google_analytics_id');
            $table->text('custom_body_scripts')->nullable()->after('custom_head_scripts');
            $table->boolean('robots_indexing')->default(true)->after('custom_body_scripts');
            $table->boolean('cookie_consent_enabled')->default(false)->after('robots_indexing');
            $table->text('cookie_consent_text')->nullable()->after('cookie_consent_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'social_tiktok',
                'social_threads',
                'google_analytics_id',
                'custom_head_scripts',
                'custom_body_scripts',
                'robots_indexing',
                'cookie_consent_enabled',
                'cookie_consent_text',
            ]);
        });
    }
};
