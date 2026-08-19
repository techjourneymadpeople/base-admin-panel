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
        if (Schema::hasTable('web_configurations') && !Schema::hasColumn('web_configurations', 'article_module_enabled')) {
            Schema::table('web_configurations', function (Blueprint $table) {
                $table->boolean('article_module_enabled')->default(true)->after('registration_enabled');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('web_configurations') && Schema::hasColumn('web_configurations', 'article_module_enabled')) {
            Schema::table('web_configurations', function (Blueprint $table) {
                $table->dropColumn('article_module_enabled');
            });
        }
    }
};
