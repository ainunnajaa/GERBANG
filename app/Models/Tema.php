<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
	use HasFactory;

	protected $table = 'temas';

	protected $fillable = [
		'primary_color',
		'secondary_color',
		'accent_color',
		'background_color',
		'welcome_card_bg_color',
		'welcome_card_border_color',
		'welcome_label_bg_color',
		'welcome_title_color',
		'hero_overlay_color',
		'slider_bg_color',
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
		'nav_download_color',
		'nav_kontak_color',
		'footer_bg_color',
		'footer_border_color',
		'footer_title_color',
		'footer_card_bg_color',
		'footer_card_border_color',
		'footer_social_label_color',
	];
}
