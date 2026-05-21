<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TemaController extends Controller
{
	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'secondary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'accent_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'background_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'welcome_card_bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'welcome_card_border_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'welcome_label_bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'welcome_title_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'hero_overlay_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'slider_bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'header_bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'header_logo_border_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'header_menu_button_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_profile_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_program_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_guru_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_konten_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_video_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_visi_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_berita_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_download_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'nav_kontak_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'footer_bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'footer_border_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'footer_title_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'footer_card_bg_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'footer_card_border_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
			'footer_social_label_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
		]);

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput()
				->with('open_section', 'tema');
		}

		$validated = $validator->validated();

		$tema = Tema::firstOrCreate(
			['id' => 1],
			[
				'primary_color' => '#3b82f6',
				'secondary_color' => '#facc15',
				'accent_color' => '#22c55e',
				'background_color' => '#fefce8',
				'welcome_card_bg_color' => '#FFFFFF',
				'welcome_card_border_color' => '#FF4500',
				'welcome_label_bg_color' => '#FFD700',
				'welcome_title_color' => '#DC143C',
				'hero_overlay_color' => '#87CEEB',
				'slider_bg_color' => '#E5E7EB',
				'header_bg_color' => '#87CEEB',
				'header_logo_border_color' => '#FFD700',
				'header_menu_button_color' => '#FF8C00',
				'nav_profile_color' => '#1E90FF',
				'nav_program_color' => '#32CD32',
				'nav_guru_color' => '#8A2BE2',
				'nav_konten_color' => '#DC143C',
				'nav_video_color' => '#6D28D9',
				'nav_visi_color' => '#FF8C00',
				'nav_berita_color' => '#00CED1',
				'nav_download_color' => '#2563EB',
				'nav_kontak_color' => '#FFD700',
				'footer_bg_color' => '#FFD700',
				'footer_border_color' => '#FF8C00',
				'footer_title_color' => '#DC143C',
				'footer_card_bg_color' => '#FFF9C4',
				'footer_card_border_color' => '#FF8C00',
				'footer_social_label_color' => '#1E90FF',
			]
		);

		$tema->update($validated);

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Tema berhasil disimpan.',
			'open_section' => 'tema',
		]);
	}
}
