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
        Schema::create('testimonials', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('role_or_title')->nullable();
            $table->string('company')->nullable();
            $table->ulid('avatar_media_id')->nullable();
            $table->string('avatar_url')->nullable();
            $table->longText('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('category')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('avatar_media_id')
                ->references('id')
                ->on('media')
                ->nullOnDelete();

            $table->index(['category', 'order']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
