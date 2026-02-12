<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\SchoolProfile;

class BeritaController extends Controller
{
	public function index()
	{
		$beritas = Berita::orderByDesc('tanggal_berita')
			->orderByDesc('created_at')
			->get();

		$schoolProfile = SchoolProfile::first();

		return view('guru.daftar_berita', compact('beritas', 'schoolProfile'));
	}

	public function show(Berita $berita)
	{
		$schoolProfile = SchoolProfile::first();

		return view('guru.baca_berita', compact('berita', 'schoolProfile'));
	}
}
