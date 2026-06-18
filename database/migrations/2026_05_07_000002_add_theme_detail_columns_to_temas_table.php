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
        Schema::table('temas', function (Blueprint $table) {
            $table->string('header_bg_color', 7)->default('#87CEEB');
            $table->string('header_logo_border_color', 7)->default('#FFD700');
            $table->string('header_menu_button_color', 7)->default('#FF8C00');

            $table->string('nav_profile_color', 7)->default('#1E90FF');
            $table->string('nav_program_color', 7)->default('#32CD32');
            $table->string('nav_guru_color', 7)->default('#8A2BE2');
            $table->string('nav_konten_color', 7)->default('#DC143C');
            $table->string('nav_video_color', 7)->default('#6D28D9');
            $table->string('nav_visi_color', 7)->default('#FF8C00');
            $table->string('nav_berita_color', 7)->default('#00CED1');
            $table->string('nav_kontak_color', 7)->default('#FFD700');

            $table->string('footer_bg_color', 7)->default('#FFD700');
            $table->string('footer_border_color', 7)->default('#FF8C00');
            $table->string('footer_title_color', 7)->default('#DC143C');
            $table->string('footer_card_bg_color', 7)->default('#FFF9C4');
            $table->string('footer_card_border_color', 7)->default('#FF8C00');
            $table->string('footer_social_label_color', 7)->default('#1E90FF');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('temas', function (Blueprint $table) {
            $table->dropColumn([
                'header_bg_color',
                'header_logo_border_color',
                'header_menu_button_color',
                'nav_profile_color',
                'nav_program_color',
                'nav_guru_color',
                'nav_konten_color',
                'nav_video_color',
                'nav_visi_color',
                'nav_berita_color',
                'nav_kontak_color',
                'footer_bg_color',
                'footer_border_color',
                'footer_title_color',
                'footer_card_bg_color',
                'footer_card_border_color',
                'footer_social_label_color',
            ]);
        });
    }
};
