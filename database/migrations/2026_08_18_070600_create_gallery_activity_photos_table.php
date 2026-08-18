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
        Schema::create('gallery_activity_photos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('gallery_activity_id')->constrained('gallery_activities')->cascadeOnDelete();
            $table->foreignUlid('media_id')->constrained('media')->cascadeOnDelete();
            $table->string('caption')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index(['gallery_activity_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_activity_photos');
    }
};
