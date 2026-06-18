<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadSetting;
use App\Models\SchoolProfile;
use App\Models\Tema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DownloadController extends Controller
{
	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'link_berita' => ['required', 'url'],
			'link_gerbang' => ['required', 'url'],
			'install_guide_link' => ['nullable', 'url'],
		]);

		if ($validator->fails()) {
			return back()
				->withErrors($validator)
				->withInput()
				->with('open_section', 'download');
		}

		$validated = $validator->validated();

		$settings = DownloadSetting::firstOrCreate(
			['id' => 1],
			[
				'link_berita' => 'https://tkaba54semarang.my.id/download/berita',
				'link_gerbang' => 'https://tkaba54semarang.my.id/download/gerbang',
				'install_guide_link' => null,
			]
		);

		$settings->update($validated);

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Link unduhan berhasil disimpan.',
			'open_section' => 'download',
		]);
	}

     public function index()
    {
        $downloadSettings = DownloadSetting::first();
        $schoolProfile = SchoolProfile::first();
        $tema = Tema::first();

        return view('publik.download.download', [
            'downloadSettings' => $downloadSettings,
            'schoolProfile' => $schoolProfile,
            'tema' => $tema,
        ]);
    }
}
