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
        Schema::create('web_configurations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('site_name')->default('Lentera Pasar');
            $table->string('site_tagline')->nullable()->default('Sistem Informasi Manajemen & Administrasi Terpadu');
            $table->text('site_description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('contact_email')->nullable()->default('support@lenterapasar.id');
            $table->string('contact_phone')->nullable()->default('+62 812-3456-7890');
            $table->string('contact_whatsapp')->nullable()->default('+62 812-3456-7890');
            $table->text('contact_address')->nullable();
            $table->string('footer_text')->nullable()->default('© 2026 Lentera Pasar. All Rights Reserved.');
            $table->string('meta_keywords')->nullable();
            $table->string('meta_author')->nullable()->default('Lentera Pasar Tech Team');
            $table->string('social_facebook')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->boolean('maintenance_mode')->default(false);
            $table->boolean('article_module_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_configurations');
    }
};
