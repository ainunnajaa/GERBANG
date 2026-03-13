<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
	public function index(Request $request)
	{
		$search = $request->input('q');

		$query = Berita::query();

		if ($search) {
			$query->where('judul', 'like', '%' . $search . '%');
		}

		$beritas = $query
			->orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->get();

		$recentBeritas = Berita::orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->limit(5)
			->get();

		$schoolProfile = SchoolProfile::first();

		return view('guru.berita.daftar_berita', [
			'beritas' => $beritas,
			'recentBeritas' => $recentBeritas,
			'schoolProfile' => $schoolProfile,
			'currentSearch' => $search,
		]);
	}

	public function show(Berita $berita)
	{
		$schoolProfile = SchoolProfile::first();

		$recentBeritas = Berita::orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->limit(5)
			->get();

		return view('guru.berita.baca_berita', [
			'berita' => $berita,
			'schoolProfile' => $schoolProfile,
			'recentBeritas' => $recentBeritas,
			'currentSearch' => null,
		]);
	}
}
