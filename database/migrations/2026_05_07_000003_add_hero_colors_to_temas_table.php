<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temas', function (Blueprint $table) {
            $table->string('hero_overlay_color')->default('#87CEEB');
            $table->string('slider_bg_color')->default('#E5E7EB');
        });
    }

    public function down(): void
    {
        Schema::table('temas', function (Blueprint $table) {
            $table->dropColumn(['hero_overlay_color', 'slider_bg_color']);
        });
    }
};
