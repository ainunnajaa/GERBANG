<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('download_settings', function (Blueprint $table) {
            $table->string('install_guide_link')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('download_settings', function (Blueprint $table) {
            $table->dropColumn('install_guide_link');
        });
    }
};
