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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->string('type')->default('saran'); // saran, kritik, pertanyaan, keluhan, lainnya
            $table->longText('message');
            $table->unsignedTinyInteger('rating')->nullable(); // 1 - 5
            $table->string('status')->default('unread'); // unread, read, in_progress, resolved, archived
            $table->text('admin_notes')->nullable();
            $table->boolean('is_starred')->default(false);
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('is_starred');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
