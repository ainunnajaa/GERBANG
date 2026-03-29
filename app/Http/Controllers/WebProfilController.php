<?php

namespace App\Http\Controllers;

use App\Models\SchoolBackground;
use App\Models\SchoolContent;
use App\Models\SchoolProgram;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class WebProfilController extends Controller
{
	public function index()
	{
		$profile = SchoolProfile::first();
		$programs = $profile
			? SchoolProgram::where('school_profile_id', $profile->id)->orderByDesc('created_at')->get()
			: collect();
		$contents = $profile
			? SchoolContent::where('school_profile_id', $profile->id)->where('platform', 'instagram')->orderByDesc('created_at')->get()
			: collect();
		$videos = $profile
			? SchoolContent::where('school_profile_id', $profile->id)->where('platform', 'youtube')->orderByDesc('created_at')->get()
			: collect();
		$backgrounds = $profile
			? SchoolBackground::where('school_profile_id', $profile->id)->orderByDesc('created_at')->get()
			: collect();

		return view('admin.profile_sekolah.kelola_web_profil', [
			'profile' => $profile,
			'programs' => $programs,
			'contents' => $contents,
			'videos' => $videos,
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

			return redirect()->route('admin.web_profil')->with([
				'status' => 'Data kepala sekolah berhasil disimpan.',
				'open_section' => 'principal',
			]);
		}

		if ($section === 'contact') {
			$instagramInput = $request->input('social_instagram_url');
			$normalizedInstagram = $this->normalizeInstagramInput($instagramInput);
			$mapsInput = $request->input('contact_maps_url');
			$normalizedMapsUrl = $this->normalizeMapsInput($mapsInput);

			if (!empty($normalizedInstagram) && !filter_var($normalizedInstagram, FILTER_VALIDATE_URL)) {
				return back()
					->withErrors(['social_instagram_url' => 'Format Instagram tidak valid. Gunakan username atau URL yang benar.'])
					->withInput();
			}

			if (!empty(trim((string) $mapsInput)) && empty($normalizedMapsUrl)) {
				return back()
					->withErrors(['contact_maps_url' => 'Format Google Maps tidak valid. Gunakan link Google Maps yang benar.'])
					->withInput();
			}

			$validated = $request->validate([
				'contact_address' => ['nullable', 'string', 'max:255'],
				'contact_email' => ['nullable', 'email', 'max:255'],
				'contact_phone' => ['nullable', 'string', 'max:50'],
				'contact_opening_hours' => ['nullable', 'string', 'max:255'],
				'social_facebook_url' => ['nullable', 'url', 'max:255'],
				'social_youtube_url' => ['nullable', 'url', 'max:255'],
				'contact_maps_url' => ['nullable', 'string', 'max:5000'],
			]);

			$profile = SchoolProfile::firstOrNew(['id' => 1]);
			$profile->contact_address = $validated['contact_address'] ?? null;
			$profile->contact_email = $validated['contact_email'] ?? null;
			$profile->contact_phone = $validated['contact_phone'] ?? null;
			$profile->contact_opening_hours = $validated['contact_opening_hours'] ?? null;
			$profile->social_facebook_url = $validated['social_facebook_url'] ?? null;
			$profile->social_instagram_url = $normalizedInstagram;
			$profile->social_youtube_url = $validated['social_youtube_url'] ?? null;
			$profile->contact_maps_url = $normalizedMapsUrl;
			$profile->updated_by = Auth::id();
			$profile->save();

			return redirect()->route('admin.web_profil')->with([
				'status' => 'Kontak sekolah berhasil disimpan.',
				'open_section' => 'contact',
			]);
		}

		$validated = $request->validate([
			'school_name' => ['nullable', 'string', 'max:255'],
			'school_logo' => ['nullable', 'image', 'max:4096'],
			'welcome_message' => ['required', 'string'],
			'school_profile' => ['nullable', 'string'],
			'vision' => ['nullable', 'string'],
			'mission' => ['nullable', 'string'],
		]);

		$profile = SchoolProfile::firstOrNew(['id' => 1]);
		if ($request->hasFile('school_logo')) {
			if ($profile->school_logo_path) {
				try {
					Storage::disk('public')->delete($profile->school_logo_path);
				} catch (\Throwable $e) {
					// ignore
				}
			}
			$logoPath = $request->file('school_logo')->store('school_logos', 'public');
			$profile->school_logo_path = $logoPath;
		}
		$profile->school_name = $validated['school_name'] ?? null;
		$profile->welcome_message = $validated['welcome_message'];
		$profile->school_profile = $validated['school_profile'] ?? null;
		$profile->vision = $validated['vision'] ?? null;
		$profile->mission = $validated['mission'] ?? null;
		$profile->updated_by = Auth::id();
		$profile->save();

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Profil sekolah berhasil disimpan.',
			'open_section' => 'profile',
		]);
	}

	private function normalizeInstagramInput(?string $value): ?string
	{
		if ($value === null) {
			return null;
		}

		$value = trim($value);
		if ($value === '') {
			return null;
		}

		if (preg_match('/^https?:\/\//i', $value)) {
			return $value;
		}

		$value = ltrim($value, '@');
		$value = preg_replace('/^instagram\.com\//i', '', $value);
		$value = trim((string) $value, '/');

		if ($value === '') {
			return null;
		}

		return 'https://instagram.com/' . $value;
	}

	private function normalizeMapsInput(?string $value): ?string
	{
		if ($value === null) {
			return null;
		}

		$value = trim($value);
		if ($value === '') {
			return null;
		}

		$decodedValue = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
		if (preg_match('/<iframe\b/i', $decodedValue)) {
			if (preg_match('/\bsrc\s*=\s*["\']([^"\']+)["\']/i', $decodedValue, $matches)) {
				$value = trim($matches[1]);
			} else {
				return null;
			}
		}

		if (!filter_var($value, FILTER_VALIDATE_URL)) {
			return null;
		}

		$resolvedUrl = $this->resolveRedirectUrl($value);

		if (!filter_var($resolvedUrl, FILTER_VALIDATE_URL)) {
			return null;
		}

		return $resolvedUrl;
	}

	private function resolveRedirectUrl(string $url): string
	{
		$currentUrl = $url;

		for ($i = 0; $i < 4; $i++) {
			try {
				$response = Http::withOptions(['allow_redirects' => false])
					->withHeaders(['User-Agent' => 'Mozilla/5.0'])
					->timeout(8)
					->get($currentUrl);

				if (!in_array($response->status(), [301, 302, 303, 307, 308], true)) {
					break;
				}

				$location = $response->header('Location');
				if (empty($location)) {
					break;
				}

				if (!preg_match('/^https?:\/\//i', $location)) {
					$base = parse_url($currentUrl, PHP_URL_SCHEME) . '://' . parse_url($currentUrl, PHP_URL_HOST);
					$location = rtrim((string) $base, '/') . '/' . ltrim($location, '/');
				}

				$currentUrl = $location;
			} catch (\Throwable $e) {
				break;
			}
		}

		return $currentUrl;
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

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Foto kepala sekolah dihapus.',
			'open_section' => 'principal',
		]);
	}

	public function deleteSchoolLogo()
	{
		$profile = SchoolProfile::first();
		if ($profile && $profile->school_logo_path) {
			$path = $profile->school_logo_path;
			$profile->school_logo_path = null;
			$profile->save();
			try {
				Storage::disk('public')->delete($path);
			} catch (\Throwable $e) {
				// ignore
			}
		}

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Logo sekolah dihapus.',
			'open_section' => 'profile',
		]);
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

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Program unggulan ditambahkan.',
			'open_section' => 'programs',
		]);
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

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Program unggulan diperbarui.',
			'open_section' => 'programs',
		]);
	}

	public function deleteProgram(SchoolProgram $program)
	{
		$program->delete();

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Program unggulan dihapus.',
			'open_section' => 'programs',
		]);
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

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Konten berhasil ditambahkan.',
			'open_section' => 'contents',
		]);
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

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Konten berhasil diperbarui.',
			'open_section' => 'contents',
		]);
	}

	public function deleteContent(SchoolContent $content)
	{
		$content->delete();

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Konten dihapus.',
			'open_section' => 'contents',
		]);
	}

	public function storeVideo(Request $request)
	{
		$validated = $request->validate([
			'youtube_url' => ['required', 'url'],
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'order' => ['nullable', 'integer'],
		]);

		$normalizedVideoUrl = $this->normalizeYouTubeUrl($validated['youtube_url']);
		if (!$normalizedVideoUrl) {
			return back()
				->withErrors(['youtube_url' => 'Link YouTube tidak valid. Gunakan link video YouTube yang benar.'])
				->withInput();
		}

		$profile = SchoolProfile::firstOrCreate(['id' => 1]);

		SchoolContent::create([
			'school_profile_id' => $profile->id,
			'platform' => 'youtube',
			'url' => $normalizedVideoUrl,
			'title' => $validated['title'],
			'description' => $validated['description'] ?? null,
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Video YouTube berhasil ditambahkan.',
			'open_section' => 'videos',
		]);
	}

	public function updateVideo(Request $request, SchoolContent $video)
	{
		$validated = $request->validate([
			'youtube_url' => ['required', 'url'],
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'order' => ['nullable', 'integer'],
		]);

		$normalizedVideoUrl = $this->normalizeYouTubeUrl($validated['youtube_url']);
		if (!$normalizedVideoUrl) {
			return back()
				->withErrors(['youtube_url' => 'Link YouTube tidak valid. Gunakan link video YouTube yang benar.'])
				->withInput();
		}

		$video->update([
			'platform' => 'youtube',
			'url' => $normalizedVideoUrl,
			'title' => $validated['title'],
			'description' => $validated['description'] ?? null,
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Video YouTube berhasil diperbarui.',
			'open_section' => 'videos',
		]);
	}

	public function deleteVideo(SchoolContent $video)
	{
		$video->delete();

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Video YouTube dihapus.',
			'open_section' => 'videos',
		]);
	}

	private function normalizeYouTubeUrl(string $url): ?string
	{
		$videoId = $this->extractYouTubeVideoId($url);
		if (!$videoId) {
			return null;
		}

		return 'https://www.youtube.com/watch?v=' . $videoId;
	}

	private function extractYouTubeVideoId(string $url): ?string
	{
		$patterns = [
			'~(?:youtube\.com/watch\?v=)([A-Za-z0-9_-]{11})~i',
			'~(?:youtu\.be/)([A-Za-z0-9_-]{11})~i',
			'~(?:youtube\.com/embed/)([A-Za-z0-9_-]{11})~i',
			'~(?:youtube\.com/shorts/)([A-Za-z0-9_-]{11})~i',
		];

		foreach ($patterns as $pattern) {
			if (preg_match($pattern, $url, $matches)) {
				return $matches[1];
			}
		}

		return null;
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

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Gambar background ditambahkan.',
			'open_section' => 'background',
		]);
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

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Gambar background dihapus.',
			'open_section' => 'background',
		]);
	}
}

