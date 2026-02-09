<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelolaBeritaController extends Controller
{
	public function index()
	{
		$beritas = Berita::orderByDesc('tanggal_berita')->orderByDesc('created_at')->get();

		return view('admin.kelola_berita', compact('beritas'));
	}

	public function create()
	{
		return view('admin.berita.create_berita');
	}

	public function store(Request $request)
	{
		$validated = $request->validate([
			'tanggal_berita' => ['required', 'date'],
			'judul' => ['required', 'string', 'max:255'],
			'isi' => ['required', 'string'],
			'gambar' => ['nullable', 'image', 'max:2048'],
		]);

		$gambarPath = null;
		if ($request->hasFile('gambar')) {
			$gambarPath = $request->file('gambar')->store('berita-images', 'public');
		}

		Berita::create([
			'tanggal_berita' => $validated['tanggal_berita'],
			'judul' => $validated['judul'],
			'isi' => $validated['isi'],
			'gambar_path' => $gambarPath,
		]);

		return redirect()->route('admin.berita')->with('status', 'Berita berhasil dibuat.');
	}

	public function show(Berita $berita)
	{
		return view('admin.berita.read_berita', compact('berita'));
	}
}
