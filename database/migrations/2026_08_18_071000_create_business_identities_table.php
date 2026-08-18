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
        Schema::create('business_identities', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Identitas & Legalitas Usaha
            $table->string('company_name');
            $table->string('brand_name')->nullable();
            $table->string('tagline')->nullable();
            $table->string('legal_type')->nullable(); // PT, CV, Firma, Yayasan, etc.
            $table->string('business_category')->nullable();
            $table->string('npwp')->nullable();
            $table->string('nib')->nullable();
            $table->string('founded_year', 10)->nullable();
            $table->string('director_name')->nullable();

            // Informasi Perbankan & Rekening Resmi
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->string('bank_branch')->nullable();

            // Tentang Perusahaan, Visi & Misi
            $table->text('about_summary')->nullable();
            $table->longText('about_story')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('core_values')->nullable();

            // Aset Visual (Media Library)
            $table->ulid('logo_light_media_id')->nullable();
            $table->string('logo_light_url')->nullable();
            $table->ulid('logo_dark_media_id')->nullable();
            $table->string('logo_dark_url')->nullable();
            $table->ulid('favicon_media_id')->nullable();
            $table->string('favicon_url')->nullable();
            $table->ulid('hero_banner_media_id')->nullable();
            $table->string('hero_banner_url')->nullable();

            // Kontak & Lokasi Kantor
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->text('google_maps_embed')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->string('operational_hours')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('logo_light_media_id')->references('id')->on('media')->nullOnDelete();
            $table->foreign('logo_dark_media_id')->references('id')->on('media')->nullOnDelete();
            $table->foreign('favicon_media_id')->references('id')->on('media')->nullOnDelete();
            $table->foreign('hero_banner_media_id')->references('id')->on('media')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_identities');
    }
};
