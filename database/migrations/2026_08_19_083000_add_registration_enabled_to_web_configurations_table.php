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
        Schema::table('web_configurations', function (Blueprint $table) {
            $table->boolean('registration_enabled')->default(true)->after('maintenance_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('web_configurations', function (Blueprint $table) {
            $table->dropColumn('registration_enabled');
        });
    }
};
