<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\SchoolProfile;

class BeritaPublikController extends Controller
{
	public function index()
	{
		$beritas = Berita::orderByDesc('tanggal_berita')->orderByDesc('created_at')->get();
		$schoolProfile = SchoolProfile::first();

		return view('publik.daftar_berita', compact('beritas', 'schoolProfile'));
	}

	public function show(Berita $berita)
	{
		$schoolProfile = SchoolProfile::first();

		return view('publik.baca_berita', compact('berita', 'schoolProfile'));
	}
}
