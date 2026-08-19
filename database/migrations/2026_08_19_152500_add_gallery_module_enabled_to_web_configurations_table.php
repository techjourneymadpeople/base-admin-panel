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
        if (Schema::hasTable('web_configurations') && !Schema::hasColumn('web_configurations', 'gallery_module_enabled')) {
            Schema::table('web_configurations', function (Blueprint $table) {
                $table->boolean('gallery_module_enabled')->default(true)->after('faq_module_enabled');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('web_configurations') && Schema::hasColumn('web_configurations', 'gallery_module_enabled')) {
            Schema::table('web_configurations', function (Blueprint $table) {
                $table->dropColumn('gallery_module_enabled');
            });
        }
    }
};
