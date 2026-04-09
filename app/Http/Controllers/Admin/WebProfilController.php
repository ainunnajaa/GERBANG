<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolBackground;
use App\Models\SchoolContent;
use App\Models\SchoolProgram;
use App\Models\SchoolProfile;
use App\Models\User;
use Google\Client as GoogleClient;
use Google\Service\YouTube;
use Google\Service\YouTube\Video;
use Google\Service\YouTube\VideoSnippet;
use Google\Service\YouTube\VideoStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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
			'youtubeConnected' => $this->getValidYouTubeAccessToken() !== null,
			'youtubeUploadMaxBytes' => $this->getYouTubeUploadCapBytes(),
			'youtubeUploadMaxMb' => max(1, (int) floor($this->getYouTubeUploadCapBytes() / (1024 * 1024))),
		]);
	}

	public function connectYouTube()
	{
		try {
			if (empty((string) config('services.youtube.client_id')) || empty((string) config('services.youtube.client_secret'))) {
				return redirect()->route('admin.web_profil')
					->withErrors(['youtube_upload' => 'Konfigurasi YouTube API belum lengkap di file .env.'])
					->with('open_section', 'videos');
			}

			$client = $this->buildYouTubeClient();

			return redirect()->away($client->createAuthUrl());
		} catch (\Throwable $e) {
			report($e);

			return redirect()->route('admin.web_profil')
				->withErrors([
					'youtube_upload' => 'Gagal memulai proses koneksi YouTube: ' . $this->formatYouTubeApiError($e),
				])
				->with('open_section', 'videos');
		}
	}

	public function disconnectYouTube()
	{
		$token = $this->getStoredYouTubeToken();
		if (is_array($token) && !empty($token['access_token'])) {
			try {
				$client = $this->buildYouTubeClient($token);
				$client->revokeToken($token['access_token']);
			} catch (\Throwable $e) {
				// Jika revoke ke Google gagal, token lokal tetap dibersihkan.
			}
		}

		$this->clearStoredYouTubeToken();

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Koneksi YouTube berhasil diputus.',
			'open_section' => 'videos',
		]);
	}

	public function handleYouTubeCallback(Request $request)
	{
		try {
			$code = (string) $request->query('code', '');
			if ($code === '') {
				return redirect()->route('admin.web_profil')->with([
					'status' => 'Autorisasi YouTube dibatalkan atau gagal.',
					'open_section' => 'videos',
				]);
			}

			$client = $this->buildYouTubeClient();
			$token = $client->fetchAccessTokenWithAuthCode($code);

			if (isset($token['error'])) {
				$errorDescription = $token['error_description'] ?? $token['error'];

				return redirect()->route('admin.web_profil')
					->withErrors([
						'youtube_upload' => 'Gagal menghubungkan akun YouTube: ' . $errorDescription,
					])
					->with('open_section', 'videos');
			}

			$this->storeYouTubeAccessToken($token);

			return redirect()->route('admin.web_profil')->with([
				'status' => 'Akun YouTube berhasil terhubung. Sekarang Anda bisa upload video langsung.',
				'open_section' => 'videos',
			]);
		} catch (\Throwable $e) {
			report($e);

			return redirect()->route('admin.web_profil')
				->withErrors([
					'youtube_upload' => 'Gagal menyelesaikan callback YouTube: ' . $this->formatYouTubeApiError($e),
				])
				->with('open_section', 'videos');
		}
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
				'principal_phone' => ['nullable', 'string', 'max:50'],
				'contact_opening_hours' => ['nullable', 'string', 'max:255'],
				'social_facebook_url' => ['nullable', 'url', 'max:255'],
				'social_youtube_url' => ['nullable', 'url', 'max:255'],
				'contact_maps_url' => ['nullable', 'string', 'max:5000'],
			]);

			$profile = SchoolProfile::firstOrNew(['id' => 1]);
			$profile->contact_address = $validated['contact_address'] ?? null;
			$profile->contact_email = $validated['contact_email'] ?? null;
			$profile->contact_phone = $validated['contact_phone'] ?? null;
			$profile->principal_phone = $validated['principal_phone'] ?? null;
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
			'privacy_status' => ['required', 'in:public,unlisted,private'],
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
			'privacy_status' => $validated['privacy_status'],
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Video YouTube berhasil ditambahkan.',
			'open_section' => 'videos',
		]);
	}

	public function initDirectUpload(Request $request)
	{
		$token = $this->getValidYouTubeAccessToken();
		if ($token === null || empty($token['access_token'])) {
			return response()->json([
				'message' => 'Akun YouTube belum terhubung. Silakan hubungkan akun YouTube terlebih dahulu.',
				'redirect' => route('admin.youtube.connect'),
			], 401);
		}

		$validated = $request->validate([
			'title' => ['required', 'string', 'max:100'],
			'description' => ['nullable', 'string', 'max:5000'],
			'privacy' => ['required', 'in:public,unlisted,private'],
			'file_size' => ['required', 'integer', 'min:1'],
			'file_type' => ['required', 'string', 'max:100'],
		]);

		if (!str_starts_with((string) $validated['file_type'], 'video/')) {
			return response()->json([
				'message' => 'Tipe file tidak valid. Hanya file video yang diizinkan.',
			], 422);
		}

		$endpoint = 'https://www.googleapis.com/upload/youtube/v3/videos?uploadType=resumable&part=snippet,status';

		$response = Http::withToken((string) $token['access_token'])
			->acceptJson()
			->withHeaders([
				'X-Upload-Content-Length' => (string) $validated['file_size'],
				'X-Upload-Content-Type' => (string) $validated['file_type'],
			])
			->post($endpoint, [
				'snippet' => [
					'title' => $validated['title'],
					'description' => $validated['description'] ?? '',
					'categoryId' => '22',
				],
				'status' => [
					'privacyStatus' => $validated['privacy'],
					'embeddable' => true,
					'publicStatsViewable' => true,
				],
			]);

		$uploadUrl = (string) ($response->header('Location') ?? '');

		if (!$response->successful() || $uploadUrl === '') {
			$errorMessage = 'Gagal mendapatkan URL upload dari Google API.';
			$body = $response->json();
			if (is_array($body)) {
				$errorMessage = (string) data_get($body, 'error.message', $errorMessage);
			}

			return response()->json([
				'message' => $errorMessage,
			], $response->status() >= 400 ? $response->status() : 500);
		}

		return response()->json([
			'upload_url' => $uploadUrl,
		]);
	}

	public function saveDirectUpload(Request $request)
	{
		$validated = $request->validate([
			'youtube_video_id' => ['nullable', 'string', 'regex:/^[A-Za-z0-9_-]{11}$/'],
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'privacy_status' => ['nullable', 'in:public,unlisted,private'],
			'upload_url' => ['nullable', 'url', 'max:5000'],
			'file_size' => ['nullable', 'integer', 'min:1'],
		]);

		$youtubeVideoId = $validated['youtube_video_id'] ?? null;
		if (empty($youtubeVideoId) && !empty($validated['upload_url']) && !empty($validated['file_size'])) {
			$youtubeVideoId = $this->resolveVideoIdFromResumableSession((string) $validated['upload_url'], (int) $validated['file_size']);
		}

		if (empty($youtubeVideoId)) {
			return response()->json([
				'message' => 'Upload sudah terkirim, tetapi ID video belum bisa dipastikan. Silakan tunggu beberapa detik lalu coba simpan ulang.',
			], 422);
		}

		$profile = SchoolProfile::firstOrCreate(['id' => 1]);

		SchoolContent::create([
			'school_profile_id' => $profile->id,
			'platform' => 'youtube',
			'url' => 'https://www.youtube.com/watch?v=' . $youtubeVideoId,
			'title' => $validated['title'],
			'description' => $validated['description'] ?? null,
			'privacy_status' => $validated['privacy_status'] ?? 'unlisted',
			'order' => 0,
		]);

		return response()->json([
			'message' => 'Video berhasil disimpan ke daftar website.',
			'redirect' => route('admin.web_profil'),
		]);
	}

	private function resolveVideoIdFromResumableSession(string $uploadUrl, int $fileSize): ?string
	{
		$token = $this->getValidYouTubeAccessToken();
		if ($token === null || empty($token['access_token'])) {
			return null;
		}

		$response = Http::withToken((string) $token['access_token'])
			->withHeaders([
				'Content-Length' => '0',
				'Content-Range' => 'bytes */' . $fileSize,
			])
			->send('PUT', $uploadUrl);

		if (!$response->successful()) {
			return null;
		}

		$body = $response->json();

		if (is_array($body) && !empty($body['id']) && is_string($body['id'])) {
			return $body['id'];
		}

		return null;
	}

	public function uploadVideoToYouTube(Request $request)
	{
		$effectiveUploadLimitBytes = $this->getYouTubeUploadCapBytes();
		$maxRuleKb = (int) floor($effectiveUploadLimitBytes / 1024);

		$validated = $request->validate([
			'upload_title' => ['required', 'string', 'max:100'],
			'upload_description' => ['nullable', 'string', 'max:5000'],
			'upload_privacy_status' => ['required', 'in:public,unlisted,private'],
			'video_file' => ['required', 'file', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm', 'max:' . max(1, $maxRuleKb)],
		], [
			'video_file.uploaded' => 'File video gagal diupload ke server. Biasanya karena ukuran file melebihi batas server (saat ini sekitar ' . max(1, (int) floor($effectiveUploadLimitBytes / (1024 * 1024))) . ' MB).',
			'video_file.max' => 'Ukuran video melebihi batas server saat ini (sekitar ' . max(1, (int) floor($effectiveUploadLimitBytes / (1024 * 1024))) . ' MB).',
			'video_file.mimetypes' => 'Format video tidak didukung. Gunakan MP4, MOV, AVI, MKV, atau WEBM.',
		]);

		$token = $this->getValidYouTubeAccessToken();
		if ($token === null) {
			if ($request->expectsJson()) {
				return response()->json([
					'message' => 'Akun YouTube belum terhubung. Silakan hubungkan akun YouTube terlebih dahulu.',
					'redirect' => route('admin.youtube.connect'),
				], 401);
			}

			return redirect()->route('admin.youtube.connect');
		}

		try {
			$client = $this->buildYouTubeClient($token);
			$youtube = new YouTube($client);

			$videoSnippet = new VideoSnippet();
			$videoSnippet->setTitle($validated['upload_title']);
			$videoSnippet->setDescription($validated['upload_description'] ?? '');

			$videoStatus = new VideoStatus();
			$videoStatus->setPrivacyStatus($validated['upload_privacy_status']);
			$videoStatus->setEmbeddable(true);
			$videoStatus->setPublicStatsViewable(true);

			$video = new Video();
			$video->setSnippet($videoSnippet);
			$video->setStatus($videoStatus);

			$uploadedFile = $request->file('video_file');
			if (!($uploadedFile instanceof UploadedFile)) {
				throw new \RuntimeException('File video tidak valid.');
			}

			$fileBinary = file_get_contents($uploadedFile->getRealPath());
			if ($fileBinary === false) {
				throw new \RuntimeException('File video tidak dapat dibaca.');
			}

			$uploadResult = $youtube->videos->insert('snippet,status', $video, [
				'uploadType' => 'multipart',
				'data' => $fileBinary,
				'mimeType' => $uploadedFile->getMimeType() ?: 'video/*',
			]);

			$youtubeVideoId = $uploadResult->getId();

			if (empty($youtubeVideoId)) {
				throw new \RuntimeException('Upload ke YouTube gagal. ID video tidak ditemukan.');
			}

			$normalizedVideoUrl = 'https://www.youtube.com/watch?v=' . $youtubeVideoId;
			$profile = SchoolProfile::firstOrCreate(['id' => 1]);

			SchoolContent::create([
				'school_profile_id' => $profile->id,
				'platform' => 'youtube',
				'url' => $normalizedVideoUrl,
				'title' => $validated['upload_title'],
				'description' => $validated['upload_description'] ?? null,
				'privacy_status' => $validated['upload_privacy_status'],
				'order' => 0,
			]);

			if ($request->expectsJson()) {
				return response()->json([
					'message' => 'Video berhasil diupload ke YouTube dan tersimpan di daftar video.',
					'redirect' => route('admin.web_profil'),
				], 200);
			}

			return redirect()->route('admin.web_profil')->with([
				'status' => 'Video berhasil diupload ke YouTube dan tersimpan di daftar video.',
				'open_section' => 'videos',
			]);
		} catch (\Throwable $e) {
			report($e);

			if ($request->expectsJson()) {
				return response()->json([
					'message' => 'Upload video ke YouTube gagal: ' . $e->getMessage(),
				], 500);
			}

			return redirect()->route('admin.web_profil')
				->withErrors(['youtube_upload' => 'Upload video ke YouTube gagal: ' . $e->getMessage()])
				->withInput()
				->with('open_section', 'videos');
		}
	}

	public function updateVideo(Request $request, SchoolContent $video)
	{
		$validated = $request->validate([
			'youtube_url' => ['required', 'url'],
			'title' => ['required', 'string', 'max:255'],
			'description' => ['nullable', 'string'],
			'privacy_status' => ['required', 'in:public,unlisted,private'],
			'order' => ['nullable', 'integer'],
		]);

		$normalizedVideoUrl = $this->normalizeYouTubeUrl($validated['youtube_url']);
		if (!$normalizedVideoUrl) {
			return back()
				->withErrors(['youtube_url' => 'Link YouTube tidak valid. Gunakan link video YouTube yang benar.'])
				->withInput();
		}

		$videoId = $this->extractYouTubeVideoId($normalizedVideoUrl);
		if (!$videoId) {
			return back()
				->withErrors(['youtube_upload' => 'ID video YouTube tidak ditemukan untuk sinkronisasi.'])
				->withInput()
				->with('open_section', 'videos');
		}

		try {
			$this->updateYouTubeVideoMetadata(
				$videoId,
				$validated['title'],
				$validated['description'] ?? '',
				$validated['privacy_status']
			);
		} catch (\Throwable $e) {
			report($e);

			if ($this->isInsufficientScopeError($e)) {
				$this->clearStoredYouTubeToken();

				return redirect()->route('admin.youtube.connect')->with([
					'status' => 'Perlu izin tambahan YouTube untuk edit metadata. Silakan login ulang untuk melanjutkan.',
					'open_section' => 'videos',
				]);
			}

			if ($this->isYouTubeForbiddenOwnershipError($e)) {
				$video->update([
					'platform' => 'youtube',
					'url' => $normalizedVideoUrl,
					'title' => $validated['title'],
					'description' => $validated['description'] ?? null,
					'privacy_status' => $validated['privacy_status'],
					'order' => $validated['order'] ?? 0,
				]);

				return redirect()->route('admin.web_profil')->with([
					'status' => 'Perubahan disimpan di website, tetapi tidak bisa sinkron ke YouTube. Pastikan akun YouTube yang terhubung adalah pemilik video ini.',
					'open_section' => 'videos',
				]);
			}

			return back()
				->withErrors(['youtube_upload' => 'Gagal sinkron judul/deskripsi ke YouTube: ' . $this->formatYouTubeApiError($e)])
				->withInput()
				->with('open_section', 'videos');
		}

		$video->update([
			'platform' => 'youtube',
			'url' => $normalizedVideoUrl,
			'title' => $validated['title'],
			'description' => $validated['description'] ?? null,
			'privacy_status' => $validated['privacy_status'],
			'order' => $validated['order'] ?? 0,
		]);

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Video YouTube berhasil diperbarui.',
			'open_section' => 'videos',
		]);
	}

	public function deleteVideo(SchoolContent $video)
	{
		$videoId = $this->extractYouTubeVideoId((string) $video->url);
		if (!$videoId) {
			return redirect()->route('admin.web_profil')
				->withErrors(['youtube_upload' => 'Video tidak dapat dihapus dari YouTube karena ID video tidak valid.'])
				->with('open_section', 'videos');
		}

		try {
			$this->deleteYouTubeVideo($videoId);
		} catch (\Throwable $e) {
			report($e);

			if ($this->isInsufficientScopeError($e)) {
				$this->clearStoredYouTubeToken();

				return redirect()->route('admin.youtube.connect')->with([
					'status' => 'Perlu izin tambahan YouTube untuk hapus video. Silakan login ulang untuk melanjutkan.',
					'open_section' => 'videos',
				]);
			}

			if ($this->isYouTubeForbiddenOwnershipError($e)) {
				$video->delete();

				return redirect()->route('admin.web_profil')->with([
					'status' => 'Video dihapus dari daftar website, tetapi tidak dapat dihapus dari YouTube. Pastikan akun YouTube yang terhubung adalah pemilik video ini.',
					'open_section' => 'videos',
				]);
			}

			return redirect()->route('admin.web_profil')
				->withErrors(['youtube_upload' => 'Gagal menghapus video di YouTube: ' . $this->formatYouTubeApiError($e)])
				->with('open_section', 'videos');
		}

		$video->delete();

		return redirect()->route('admin.web_profil')->with([
			'status' => 'Video YouTube dihapus dari channel dan daftar website.',
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

	private function buildYouTubeClient(?array $accessToken = null): GoogleClient
	{
		$client = new GoogleClient();
		$client->setClientId((string) config('services.youtube.client_id'));
		$client->setClientSecret((string) config('services.youtube.client_secret'));
		$client->setRedirectUri($this->getYouTubeRedirectUri());
		$client->setAccessType('offline');
		$client->setPrompt('consent');
		$client->setIncludeGrantedScopes(true);
		$client->setScopes([YouTube::YOUTUBE_UPLOAD, YouTube::YOUTUBE]);

		if ($accessToken !== null) {
			$client->setAccessToken($accessToken);
		}

		return $client;
	}

	private function getYouTubeRedirectUri(): string
	{
		$configured = trim((string) config('services.youtube.redirect'));
		if ($configured !== '' && filter_var($configured, FILTER_VALIDATE_URL)) {
			return $configured;
		}

		return route('admin.youtube.callback');
	}

	private function storeYouTubeAccessToken(array $token): void
	{
		$existingToken = $this->getStoredYouTubeToken();
		if (!empty($existingToken['refresh_token']) && empty($token['refresh_token'])) {
			$token['refresh_token'] = $existingToken['refresh_token'];
		}

		$user = $this->getAuthenticatedUser();
		if ($user) {
			$user->youtube_token_payload = Crypt::encryptString(json_encode($token));
			$user->youtube_token_expires_at = Carbon::now()->addSeconds((int) ($token['expires_in'] ?? 3600));
			$user->save();

			return;
		}

		session(['youtube_access_token' => $token]);
	}

	private function getValidYouTubeAccessToken(): ?array
	{
		$token = $this->getStoredYouTubeToken();
		if (!is_array($token) || empty($token)) {
			return null;
		}

		$client = $this->buildYouTubeClient($token);
		if (!$client->isAccessTokenExpired()) {
			return $token;
		}

		$refreshToken = $client->getRefreshToken();
		if (empty($refreshToken)) {
			$this->clearStoredYouTubeToken();
			return null;
		}

		$newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
		if (isset($newToken['error'])) {
			$this->clearStoredYouTubeToken();
			return null;
		}

		$this->storeYouTubeAccessToken($newToken);

		return $this->getStoredYouTubeToken();
	}

	private function getStoredYouTubeToken(): ?array
	{
		$user = $this->getAuthenticatedUser();
		if ($user && !empty($user->youtube_token_payload)) {
			try {
				$payload = Crypt::decryptString($user->youtube_token_payload);
				$decoded = json_decode($payload, true);

				return is_array($decoded) ? $decoded : null;
			} catch (\Throwable $e) {
				$this->clearStoredYouTubeToken();
				return null;
			}
		}

		$sessionToken = session('youtube_access_token');

		return is_array($sessionToken) ? $sessionToken : null;
	}

	private function clearStoredYouTubeToken(): void
	{
		$user = $this->getAuthenticatedUser();
		if ($user) {
			$user->youtube_token_payload = null;
			$user->youtube_token_expires_at = null;
			$user->save();

			return;
		}

		session()->forget('youtube_access_token');
	}

	private function getEffectiveUploadLimitBytes(): int
	{
		$uploadMax = $this->parseIniSizeToBytes((string) ini_get('upload_max_filesize'));
		$postMax = $this->parseIniSizeToBytes((string) ini_get('post_max_size'));

		if ($uploadMax <= 0 && $postMax <= 0) {
			return 250 * 1024 * 1024;
		}

		if ($uploadMax <= 0) {
			return $postMax;
		}

		if ($postMax <= 0) {
			return $uploadMax;
		}

		return min($uploadMax, $postMax);
	}

	private function getYouTubeUploadCapBytes(): int
	{
		$serverLimit = $this->getEffectiveUploadLimitBytes();

		if ($serverLimit <= 0) {
			return 128 * 1024 * 1024;
		}

		return $serverLimit;
	}

	private function parseIniSizeToBytes(string $value): int
	{
		$value = trim($value);
		if ($value === '') {
			return 0;
		}

		$unit = strtolower(substr($value, -1));
		$number = (float) $value;

		switch ($unit) {
			case 'g':
				return (int) ($number * 1024 * 1024 * 1024);
			case 'm':
				return (int) ($number * 1024 * 1024);
			case 'k':
				return (int) ($number * 1024);
			default:
				return (int) $number;
		}
	}

	private function getAuthorizedYouTubeService(): YouTube
	{
		$token = $this->getValidYouTubeAccessToken();
		if ($token === null) {
			throw new \RuntimeException('Akun YouTube belum terhubung atau token sudah tidak valid. Silakan hubungkan ulang YouTube.');
		}

		$client = $this->buildYouTubeClient($token);

		return new YouTube($client);
	}

	private function updateYouTubeVideoMetadata(string $videoId, string $title, string $description, string $privacyStatus): void
	{
		$youtube = $this->getAuthorizedYouTubeService();
		$existingResponse = $youtube->videos->listVideos('snippet,status', ['id' => $videoId, 'maxResults' => 1]);
		$items = $existingResponse->getItems();

		if (empty($items)) {
			throw new \RuntimeException('Video tidak ditemukan di YouTube atau tidak dapat diakses oleh akun ini.');
		}

		$youtubeVideo = $items[0];
		$snippet = $youtubeVideo->getSnippet();
		$snippet->setTitle($title);
		$snippet->setDescription($description);

		// categoryId wajib tersedia saat update snippet.
		if (!$snippet->getCategoryId()) {
			$snippet->setCategoryId('22');
		}

		$status = $youtubeVideo->getStatus();
		if ($status === null) {
			$status = new VideoStatus();
		}
		$status->setPrivacyStatus($privacyStatus);
		$status->setEmbeddable(true);
		$status->setPublicStatsViewable(true);

		$youtubeVideo->setSnippet($snippet);
		$youtubeVideo->setStatus($status);
		$youtube->videos->update('snippet,status', $youtubeVideo);
	}

	private function deleteYouTubeVideo(string $videoId): void
	{
		$youtube = $this->getAuthorizedYouTubeService();
		$youtube->videos->delete($videoId);
	}

	private function formatYouTubeApiError(\Throwable $e): string
	{
		$message = trim($e->getMessage());
		if ($message !== '') {
			return $message;
		}

		return 'Terjadi kesalahan API YouTube.';
	}

	private function isInsufficientScopeError(\Throwable $e): bool
	{
		$message = strtoupper((string) $e->getMessage());

		return str_contains($message, 'ACCESS_TOKEN_SCOPE_INSUFFICIENT')
			|| str_contains($message, 'INSUFFICIENT AUTHENTICATION SCOPES')
			|| str_contains($message, 'INSUFFICIENT PERMISSION');
	}

	private function isYouTubeForbiddenOwnershipError(\Throwable $e): bool
	{
		$message = strtoupper((string) $e->getMessage());

		return str_contains($message, '"REASON":"FORBIDDEN"')
			|| str_contains($message, 'YOUTUBE.VIDEO')
			|| str_contains($message, 'CANNOT BE DELETED')
			|| str_contains($message, 'REQUEST MIGHT NOT BE PROPERLY AUTHORIZED');
	}

	private function getAuthenticatedUser(): ?User
	{
		$authUser = Auth::user();

		return $authUser instanceof User ? $authUser : null;
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

