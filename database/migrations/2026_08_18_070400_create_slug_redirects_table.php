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
        Schema::create('slug_redirects', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('redirectable_type')->nullable();
            $table->string('redirectable_id')->nullable();
            $table->string('source_path')->unique();
            $table->string('target_path');
            $table->smallInteger('status_code')->default(301);
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamps();

            $table->index(['redirectable_type', 'redirectable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slug_redirects');
    }
};
