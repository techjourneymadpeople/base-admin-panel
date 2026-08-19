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
        if (Schema::hasTable('web_configurations') && !Schema::hasColumn('web_configurations', 'app_version')) {
            Schema::table('web_configurations', function (Blueprint $table) {
                $table->string('app_version')->nullable()->default('v1.0 Viho Edition')->after('footer_text');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('web_configurations') && Schema::hasColumn('web_configurations', 'app_version')) {
            Schema::table('web_configurations', function (Blueprint $table) {
                $table->dropColumn('app_version');
            });
        }
    }
};
