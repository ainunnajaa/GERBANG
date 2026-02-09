<?php

namespace App\Http\Controllers;

use App\Models\SchoolBackground;
use App\Models\SchoolContent;
use App\Models\SchoolProgram;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class WebProfilController extends Controller
{
	public function index()
	{
		$profile = SchoolProfile::first();
		$programs = $profile
			? SchoolProgram::where('school_profile_id', $profile->id)->orderBy('order')->get()
			: collect();
		$contents = $profile
			? SchoolContent::where('school_profile_id', $profile->id)->orderBy('order')->get()
			: collect();
		$backgrounds = $profile
			? SchoolBackground::where('school_profile_id', $profile->id)->orderBy('order')->get()
			: collect();

		return view('admin.kelola_web_profil', [
			'profile' => $profile,
			'programs' => $programs,
			'contents' => $contents,
			'backgrounds' => $backgrounds,
		]);
	}

	public function save(Request $request)
	{
		$section = $request->input('section', 'profile');

		if ($section === 'principal') {
			$validated = $request->validate([
				'principal_name' => ['nullable', 'string', 'max:255'],
				'principal_photo' => ['nullable', 'image', 'max:4096'],
				'principal_greeting' => ['nullable', 'string'],
			]);

			$profile = SchoolProfile::firstOrCreate(['id' => 1]);
			$profile->principal_name = $validated['principal_name'] ?? null;
			$profile->principal_greeting = $validated['principal_greeting'] ?? null;

			if ($request->hasFile('principal_photo')) {
				if ($profile->principal_photo_path) {
					try {
						Storage::disk('public')->delete($profile->principal_photo_path);
					} catch (\Throwable $e) {
						// ignore
					}
				}
				$path = $request->file('principal_photo')->store('principal_photos', 'public');
				$profile->principal_photo_path = $path;
			}

			$profile->updated_by = Auth::id();
			$profile->save();

			return redirect()->route('admin.web_profil')->with('status', 'Data kepala sekolah berhasil disimpan.');
		}

		if ($section === 'contact') {
			$validated = $request->validate([
				'contact_address' => ['nullable', 'string', 'max:255'],
				'contact_email' => ['nullable', 'email', 'max:255'],
				'contact_phone' => ['nullable', 'string', 'max:50'],
				'contact_opening_hours' => ['nullable', 'string', 'max:255'],
			]);

			$profile = SchoolProfile::firstOrNew(['id' => 1]);
			$profile->contact_address = $validated['contact_address'] ?? null;
			$profile->contact_email = $validated['contact_email'] ?? null;
			$profile->contact_phone = $validated['contact_phone'] ?? null;
			$profile->contact_opening_hours = $validated['contact_opening_hours'] ?? null;
			$profile->updated_by = Auth::id();
			$profile->save();

			return redirect()->route('admin.web_profil')->with('status', 'Kontak sekolah berhasil disimpan.');
		}

		$validated = $request->validate([
			'school_name' => ['nullable', 'string', 'max:255'],
			'welcome_message' => ['required', 'string'],
			'vision' => ['nullable', 'string'],
			'mission' => ['nullable', 'string'],
		]);

		$profile = SchoolProfile::firstOrNew(['id' => 1]);
		$profile->school_name = $validated['school_name'] ?? null;
		$profile->welcome_message = $validated['welcome_message'];
		$profile->vision = $validated['vision'] ?? null;
		$profile->mission = $validated['mission'] ?? null;
		$profile->updated_by = Auth::id();
		$profile->save();

		return redirect()->route('admin.web_profil')->with('status', 'Profil sekolah berhasil disimpan.');
	}

	public function deletePrincipalPhoto()
	{
		$profile = SchoolProfile::first();
		if ($profile && $profile->principal_photo_path) {
			$path = $profile->principal_photo_path;
			$profile->principal_photo_path = null;
			$profile->save();
			try {
				Storage::disk('public')->delete($path);
			} catch (\Throwable $e) {
				// ignore
			}
		}

		return redirect()->route('admin.web_profil')->with('status', 'Foto kepala sekolah dihapus.');
	}

	// Program Unggulan CRUD
	public function storeProgram(Request $request)
	{
		$validated = $request->validate([
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'icon' => ['nullable', 'string', 'max:255'],
			'order' => ['nullable', 'integer'],
		]);

		$profile = SchoolProfile::firstOrCreate(['id' => 1]);

		SchoolProgram::create([
			'school_profile_id' => $profile->id,
			'title' => $validated['title'],
			'description' => $validated['description'] ?? null,
			'icon' => $validated['icon'] ?? null,
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with('status', 'Program unggulan ditambahkan.');
	}

	public function updateProgram(Request $request, SchoolProgram $program)
	{
		$validated = $request->validate([
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'icon' => ['nullable', 'string', 'max:255'],
			'order' => ['nullable', 'integer'],
		]);

		$program->update([
			'title' => $validated['title'],
			'description' => $validated['description'] ?? null,
			'icon' => $validated['icon'] ?? null,
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with('status', 'Program unggulan diperbarui.');
	}

	public function deleteProgram(SchoolProgram $program)
	{
		$program->delete();

		return redirect()->route('admin.web_profil')->with('status', 'Program unggulan dihapus.');
	}

	// Konten Sosial Media CRUD
	public function storeContent(Request $request)
	{
		$validated = $request->validate([
			'url' => ['required', 'url'],
			'title' => ['nullable', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'order' => ['nullable', 'integer'],
		]);

		$profile = SchoolProfile::firstOrCreate(['id' => 1]);

		SchoolContent::create([
			'school_profile_id' => $profile->id,
			'platform' => 'instagram',
			'url' => $validated['url'],
			'title' => $validated['title'] ?? null,
			'description' => $validated['description'] ?? null,
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with('status', 'Konten berhasil ditambahkan.');
	}

	public function updateContent(Request $request, SchoolContent $content)
	{
		$validated = $request->validate([
			'url' => ['required', 'url'],
			'title' => ['nullable', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'order' => ['nullable', 'integer'],
		]);

		$content->update([
			'url' => $validated['url'],
			'title' => $validated['title'] ?? null,
			'description' => $validated['description'] ?? null,
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with('status', 'Konten berhasil diperbarui.');
	}

	public function deleteContent(SchoolContent $content)
	{
		$content->delete();

		return redirect()->route('admin.web_profil')->with('status', 'Konten dihapus.');
	}

	// Background CRUD
	public function storeBackground(Request $request)
	{
		$validated = $request->validate([
			'image' => ['required', 'image', 'max:4096'],
			'order' => ['nullable', 'integer'],
		]);

		$profile = SchoolProfile::firstOrCreate(['id' => 1]);
		$path = $request->file('image')->store('school_backgrounds', 'public');

		SchoolBackground::create([
			'school_profile_id' => $profile->id,
			'path' => $path,
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with('status', 'Gambar background ditambahkan.');
	}

	public function deleteBackground(SchoolBackground $bg)
	{
		$path = $bg->path;
		$bg->delete();

		try {
			Storage::disk('public')->delete($path);
		} catch (\Throwable $e) {
			// ignore
		}

		return redirect()->route('admin.web_profil')->with('status', 'Gambar background dihapus.');
	}
}

