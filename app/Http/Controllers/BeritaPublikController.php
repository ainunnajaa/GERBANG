<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;

class BeritaPublikController extends Controller
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
			->paginate(15)
			->withQueryString();

		$recentBeritas = Berita::orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->limit(7)
			->get();

		$schoolProfile = SchoolProfile::first();

		return view('publik.berita.daftar_berita', [
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
			->limit(7)
			->get();

		return view('publik.berita.baca_berita', [
			'berita' => $berita,
			'schoolProfile' => $schoolProfile,
			'recentBeritas' => $recentBeritas,
			'currentSearch' => null,
		]);
	}
}
