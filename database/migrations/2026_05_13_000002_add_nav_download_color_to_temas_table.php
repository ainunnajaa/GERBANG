<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temas', function (Blueprint $table) {
            $table->string('nav_download_color')->default('#2563EB');
        });
    }

    public function down(): void
    {
        Schema::table('temas', function (Blueprint $table) {
            $table->dropColumn('nav_download_color');
        });
    }
};
