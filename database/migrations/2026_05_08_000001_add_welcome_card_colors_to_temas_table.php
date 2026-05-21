<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temas', function (Blueprint $table) {
            $table->string('welcome_card_bg_color')->default('#FFFFFF');
            $table->string('welcome_card_border_color')->default('#FF4500');
            $table->string('welcome_label_bg_color')->default('#FFD700');
            $table->string('welcome_title_color')->default('#DC143C');
        });
    }

    public function down(): void
    {
        Schema::table('temas', function (Blueprint $table) {
            $table->dropColumn([
                'welcome_card_bg_color',
                'welcome_card_border_color',
                'welcome_label_bg_color',
                'welcome_title_color',
            ]);
        });
    }
};
