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
        Schema::table('business_identities', function (Blueprint $table) {
            $table->string('social_instagram')->nullable()->after('operational_hours');
            $table->string('social_tiktok')->nullable()->after('social_instagram');
            $table->string('social_youtube')->nullable()->after('social_tiktok');
            $table->string('social_linkedin')->nullable()->after('social_youtube');
            $table->string('social_facebook')->nullable()->after('social_linkedin');
            $table->string('social_twitter')->nullable()->after('social_facebook');
            $table->string('social_threads')->nullable()->after('social_twitter');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_identities', function (Blueprint $table) {
            $table->dropColumn([
                'social_instagram',
                'social_tiktok',
                'social_youtube',
                'social_linkedin',
                'social_facebook',
                'social_twitter',
                'social_threads',
            ]);
        });
    }
};
