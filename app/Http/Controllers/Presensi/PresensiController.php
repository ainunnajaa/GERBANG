<?php

namespace App\Http\Controllers\Presensi;

use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use App\Models\PresensiSetting;
use App\Models\SchoolProfile;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
	/**
	 * Hitung jarak antara dua titik koordinat (meter) menggunakan rumus Haversine.
	 */
	protected function haversineDistance($lat1, $lon1, $lat2, $lon2): float
	{
		$earthRadius = 6371000; // meter

		$latFrom = deg2rad($lat1);
		$lonFrom = deg2rad($lon1);
		$latTo = deg2rad($lat2);
		$lonTo = deg2rad($lon2);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lonDelta / 2) ** 2;
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));

		return $earthRadius * $c;
	}

	public function guruIndex()
	{
		$settings = $this->getOrCreateSettings();
		$activePeriod = $this->getActivePeriod();

		return view('guru.presensi', [
			'settings' => $settings,
			'activePeriod' => $activePeriod,
			'activePeriodDayLabels' => $activePeriod?->activeDayLabels() ?? [],
		]);
	}

	public function guruIzin(Request $request)
	{
		$user = Auth::user();
		$now = now();
		$today = $now->toDateString();
		$activePeriod = $this->getActivePeriod();

		if (! $activePeriod) {
			return back()->with('error', 'Belum ada periode presensi aktif. Hubungi admin untuk mengatur periode presensi.');
		}

		if (! $activePeriod->isOperationalOn($now)) {
			return back()->with('error', 'Hari ini berada di luar periode atau hari operasional presensi yang aktif.');
		}

		$data = $request->validate([
			'keterangan' => ['required', 'string', 'max:1000'],
			'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
		]);

		$existingIzin = PresensiIzin::where('user_id', $user->id)
			->whereDate('tanggal', $today)
			->first();

		$lampiranPath = $existingIzin?->lampiran_path;
		$lampiranNama = $existingIzin?->lampiran_nama;

		if ($request->hasFile('lampiran')) {
			if (!empty($lampiranPath)) {
				Storage::disk('public')->delete($lampiranPath);
			}

			$uploadedFile = $request->file('lampiran');
			$lampiranPath = $uploadedFile->store('izin-lampiran', 'public');
			$lampiranNama = $uploadedFile->getClientOriginalName();
		}

		// Simpan/Update data izin
		PresensiIzin::updateOrCreate(
			[
				'user_id' => $user->id,
				'tanggal' => $today,
			],
			[
				'keterangan' => $data['keterangan'],
				'lampiran_path' => $lampiranPath,
				'lampiran_nama' => $lampiranNama,
			]
		);

		// Cek apakah sudah presensi masuk hari ini
		$presensi = Presensi::where('user_id', $user->id)
			->whereDate('tanggal', $today)
			->first();

		if ($presensi && $presensi->jam_masuk) {
			// Sudah presensi masuk: update keterangan dan pastikan status H
			$presensi->keterangan = $data['keterangan'];
			$presensi->status = 'H';
			$presensi->save();
		} else {
			// Belum presensi masuk: buat presensi status I (izin)
			Presensi::updateOrCreate(
				[
					'user_id' => $user->id,
					'tanggal' => $today,
				],
				[
					'status' => 'I',
					'keterangan' => $data['keterangan'],
				]
			);
		}

		return back()->with('success', 'Izin Anda untuk hari ini berhasil dikirim ke admin.');
	}

	public function adminIndex()
	{
		$settings = $this->getOrCreateSettings();
		$activePeriod = $this->getActivePeriod();
		$schoolProfile = SchoolProfile::first();

		$qrCodeText = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');

		$today = now()->toDateString();
		$presensis = Presensi::with('user')
			->whereDate('tanggal', $today)
			->get();

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		return view('admin.presensi.kelola_presensi', [
			'qrCodeText' => $qrCodeText,
			'qrTemplateConfig' => $this->resolveQrTemplateConfig($settings),
			'schoolLogoUrl' => !empty($schoolProfile?->school_logo_path) ? asset('storage/' . $schoolProfile->school_logo_path) : null,
			'presensis' => $presensis,
			'today' => $today,
			'settings' => $settings,
			'gurus' => $gurus,
			'activePeriod' => $activePeriod,
			'activePeriodDayLabels' => $activePeriod?->activeDayLabels() ?? [],
		]);
	}

	public function editQrTemplate()
	{
		$settings = $this->getOrCreateSettings();
		$schoolProfile = SchoolProfile::first();
		$qrCodeText = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');

		return view('admin.presensi.crud_template_qr', [
			'settings' => $settings,
			'qrCodeText' => $qrCodeText,
			'qrTemplateConfig' => $this->resolveQrTemplateConfig($settings),
			'schoolLogoUrl' => !empty($schoolProfile?->school_logo_path) ? asset('storage/' . $schoolProfile->school_logo_path) : null,
		]);
	}

	public function updateQrTemplate(Request $request)
	{
		$data = $request->validate([
			'qr_template_image' => ['nullable', 'image', 'max:5120'],
			'remove_qr_template' => ['nullable', 'boolean'],
			'qr_template_x' => ['required', 'numeric', 'between:0,100'],
			'qr_template_y' => ['required', 'numeric', 'between:0,100'],
			'qr_template_size' => ['required', 'numeric', 'between:5,90'],
		]);

		$settings = $this->getOrCreateSettings();

		if (!empty($data['remove_qr_template']) && $settings->qr_template_path) {
			Storage::disk('public')->delete($settings->qr_template_path);
			$settings->qr_template_path = null;
		}

		if ($request->hasFile('qr_template_image')) {
			if ($settings->qr_template_path) {
				Storage::disk('public')->delete($settings->qr_template_path);
			}

			$settings->qr_template_path = $request->file('qr_template_image')->store('presensi-qr-templates', 'public');
		}

		$settings->qr_template_x = round((float) $data['qr_template_x'], 2);
		$settings->qr_template_y = round((float) $data['qr_template_y'], 2);
		$settings->qr_template_size = round((float) $data['qr_template_size'], 2);
		$settings->save();

		return redirect()->route('admin.presensi.template.edit')->with('success', 'Template QR berhasil diperbarui.');
	}

	public function updateSettings(Request $request)
	{
		if (! $this->getActivePeriod()) {
			return back()->withInput()->with('error', 'Atur dan aktifkan periode presensi terlebih dahulu sebelum menyimpan jam presensi.');
		}

		$data = $request->validate([
			'jam_masuk_start' => ['required', 'date_format:H:i'],
			'jam_masuk_end' => ['required', 'date_format:H:i'],
			'jam_masuk_toleransi' => ['nullable', 'date_format:H:i'],
			'jam_pulang_start' => ['required', 'date_format:H:i'],
			'jam_pulang_end' => ['required', 'date_format:H:i'],
			'jam_pulang_start_jumat' => ['nullable', 'date_format:H:i'],
			'jam_pulang_end_jumat' => ['nullable', 'date_format:H:i'],
			'jam_pulang_start_sabtu' => ['nullable', 'date_format:H:i'],
			'jam_pulang_end_sabtu' => ['nullable', 'date_format:H:i'],
			'qr_text' => ['nullable', 'string', 'max:255'],
			'latitude' => ['nullable', 'numeric', 'between:-90,90'],
			'longitude' => ['nullable', 'numeric', 'between:-180,180'],
			'radius_meter' => ['nullable', 'integer', 'min:10'],
		]);

		$settings = $this->getOrCreateSettings();
		$settings->jam_masuk_start = $data['jam_masuk_start'] . ':00';
		$settings->jam_masuk_end = $data['jam_masuk_end'] . ':00';
		$settings->jam_masuk_toleransi = $data['jam_masuk_toleransi']
			? $data['jam_masuk_toleransi'] . ':00'
			: null;
		$settings->jam_pulang_start = $data['jam_pulang_start'] . ':00';
		$settings->jam_pulang_end = $data['jam_pulang_end'] . ':00';
		$settings->jam_pulang_start_jumat = !empty($data['jam_pulang_start_jumat']) ? $data['jam_pulang_start_jumat'] . ':00' : null;
		$settings->jam_pulang_end_jumat = !empty($data['jam_pulang_end_jumat']) ? $data['jam_pulang_end_jumat'] . ':00' : null;
		$settings->jam_pulang_start_sabtu = !empty($data['jam_pulang_start_sabtu']) ? $data['jam_pulang_start_sabtu'] . ':00' : null;
		$settings->jam_pulang_end_sabtu = !empty($data['jam_pulang_end_sabtu']) ? $data['jam_pulang_end_sabtu'] . ':00' : null;
		$settings->qr_text = $data['qr_text'] ?? env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
		$settings->latitude = $data['latitude'] ?? null;
		$settings->longitude = $data['longitude'] ?? null;
		$settings->radius_meter = $data['radius_meter'] ?? null;
		$settings->save();

		return back()->with('success', 'Jam presensi berhasil diperbarui.');
	}


	public function scan(Request $request)
	{
		$request->validate([
			'qr_code' => 'required|string',
			'latitude' => ['nullable', 'numeric', 'between:-90,90'],
			'longitude' => ['nullable', 'numeric', 'between:-180,180'],
		]);

		$now = Carbon::now();
		$currentTime = $now->format('H:i');
		$today = $now->toDateString();
		$activePeriod = $this->getActivePeriod();

		if (! $activePeriod) {
			return back()->with('error', 'Presensi belum dapat digunakan karena belum ada periode presensi aktif.');
		}

		if (! $activePeriod->includesDate($now)) {
			return back()->with('error', 'Tanggal hari ini berada di luar rentang periode presensi aktif: ' . $activePeriod->name . '.');
		}

		if (! $activePeriod->isOperationalOn($now)) {
			return back()->with('error', 'Hari ini tidak termasuk hari operasional presensi. Hari aktif: ' . implode(', ', $activePeriod->activeDayLabels()) . '.');
		}

		$settings = $this->getOrCreateSettings();

		// Jika admin mengatur batas lokasi, cek apakah guru berada dalam radius
		if ($settings->latitude !== null && $settings->longitude !== null && $settings->radius_meter !== null) {
			$lat = $request->input('latitude');
			$lng = $request->input('longitude');

			if ($lat === null || $lng === null) {
				return back()->with('error', 'Lokasi tidak terdeteksi. Aktifkan GPS dan izinkan akses lokasi.');
			}

			$distance = $this->haversineDistance($settings->latitude, $settings->longitude, $lat, $lng);
			if ($distance > $settings->radius_meter) {
				return back()->with('error', 'Anda berada di luar area yang diizinkan untuk presensi.');
			}
		}

		$expectedCode = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
		if ($request->qr_code !== $expectedCode) {
			return back()->with('error', 'QR code tidak valid.');
		}

		$masukStart = Carbon::createFromFormat('H:i', substr($settings->jam_masuk_start, 0, 5));
		$masukEnd = Carbon::createFromFormat('H:i', substr($settings->jam_masuk_end, 0, 5));
		$pulangStartRaw = $settings->jam_pulang_start;
		$pulangEndRaw = $settings->jam_pulang_end;
		$dayKey = strtolower($now->englishDayOfWeek);
		if ($dayKey === 'friday' && $settings->jam_pulang_start_jumat && $settings->jam_pulang_end_jumat) {
			$pulangStartRaw = $settings->jam_pulang_start_jumat;
			$pulangEndRaw = $settings->jam_pulang_end_jumat;
		}
		if ($dayKey === 'saturday' && $settings->jam_pulang_start_sabtu && $settings->jam_pulang_end_sabtu) {
			$pulangStartRaw = $settings->jam_pulang_start_sabtu;
			$pulangEndRaw = $settings->jam_pulang_end_sabtu;
		}

		$pulangStart = Carbon::createFromFormat('H:i', substr($pulangStartRaw, 0, 5));
		$pulangEnd = Carbon::createFromFormat('H:i', substr($pulangEndRaw, 0, 5));

		$current = Carbon::createFromFormat('H:i', $currentTime);

		// Jam presensi masuk yang diterima:
		// - Normal: antara jam_masuk_start s.d jam_masuk_end (status "hadir").
		// - Terlambat tapi masih diterima: > jam_masuk_end s.d jam_masuk_toleransi (status "terlambat").
		// - Di luar itu: ditolak.
		$masukAcceptEnd = $settings->jam_masuk_toleransi
			? Carbon::createFromFormat('H:i', substr($settings->jam_masuk_toleransi, 0, 5))
			: $masukEnd;

		$isMasuk = $current->between($masukStart, $masukAcceptEnd);
		$isPulang = $current->between($pulangStart, $pulangEnd);

		if (! $isMasuk && ! $isPulang) {
			$jamMasukText = $masukStart->format('H:i') . '-' . $masukEnd->format('H:i');
			if ($settings->jam_masuk_toleransi) {
				$jamMasukText .= ' (toleransi sampai ' . Carbon::createFromFormat('H:i', substr($settings->jam_masuk_toleransi, 0, 5))->format('H:i') . ')';
			}

			return back()->with('error', 'Bukan jam presensi. Jam masuk: ' . $jamMasukText . ', jam pulang: ' . $pulangStart->format('H:i') . '-' . $pulangEnd->format('H:i'));
		}

		$user = Auth::user();

		// Cari data presensi hari ini (jika ada)
		$presensi = Presensi::where('user_id', $user->id)
			->whereDate('tanggal', $today)
			->first();

		// Jika sudah ada jam masuk dan jam pulang, presensi untuk hari ini dianggap lengkap
		if ($presensi && $presensi->jam_masuk && $presensi->jam_pulang) {
			return back()->with('error', 'Presensi hari ini sudah lengkap. Tidak dapat melakukan presensi lagi.');
		}

		if ($isMasuk) {
			// Jika sudah pernah presensi masuk hari ini, blok dan tampilkan merah
			if ($presensi && $presensi->jam_masuk) {
				return back()->with('error', 'Presensi masuk Anda hari ini sudah tercatat. Tidak dapat melakukan presensi masuk dua kali.');
			}

			// Tentukan status hadir/terlambat:
			// - Dalam rentang jam_masuk_start s.d jam_masuk_end  -> "hadir" (H)
			// - Setelah jam_masuk_end s.d jam_masuk_toleransi      -> "terlambat" (T)
			$statusBoundary = $masukEnd; // batas akhir status hadir
			$statusCode = $current->lte($statusBoundary) ? 'H' : 'T';
			$statusText = $statusCode === 'T' ? 'terlambat' : 'hadir';

			if (! $presensi) {
				// Belum ada data hari ini, buat baru dengan jam masuk, status hadir/terlambat
				Presensi::create([
					'user_id' => $user->id,
					'tanggal' => $today,
					'jam_masuk' => $now->format('H:i:s'),
					'status' => $statusCode,
					'keterangan' => null,
				]);
			} else {
				// Sudah ada record hari ini tapi belum ada jam masuk
				$presensi->jam_masuk = $now->format('H:i:s');
				$presensi->status = $statusCode;
				$presensi->save();
			}

			$pesan = 'Presensi masuk berhasil tercatat dengan status ' . $statusText . ' pada pukul ' . $now->format('H:i');
			session()->forget('error');
			return back()->with('success', $pesan);
		}

		if ($isPulang) {
			// Wajib sudah presensi masuk terlebih dahulu
			if (! $presensi || ! $presensi->jam_masuk) {
				return back()->with('error', 'Anda belum melakukan presensi masuk hari ini. Silakan presensi masuk terlebih dahulu sebelum presensi pulang.');
			}

			// Jika sudah pernah presensi pulang hari ini, tampilkan info sukses
			if ($presensi->jam_pulang) {
				session()->forget('error');
				return back()->with('success', 'Presensi pulang Anda hari ini sudah tercatat.');
			}

			// Sudah ada record hari ini dengan jam_masuk, tapi belum ada jam_pulang
			$presensi->jam_pulang = $now->format('H:i:s');
			$presensi->save();
			session()->forget('error');
			return back()->with('success', 'Presensi pulang berhasil tercatat pada pukul ' . $now->format('H:i'));
		}

		return back()->with('error', 'Terjadi kesalahan saat mencatat presensi.');
	}

	private function getOrCreateSettings(): PresensiSetting
	{
		$settings = PresensiSetting::first();

		if ($settings) {
			return $settings;
		}

		return PresensiSetting::create([
			'jam_masuk_start' => '07:00:00',
			'jam_masuk_end' => '08:00:00',
			'jam_pulang_start' => '13:00:00',
			'jam_pulang_end' => '14:30:00',
			'jam_pulang_start_jumat' => null,
			'jam_pulang_end_jumat' => null,
			'jam_pulang_start_sabtu' => null,
			'jam_pulang_end_sabtu' => null,
			'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			'latitude' => null,
			'longitude' => null,
			'radius_meter' => null,
			'qr_template_path' => null,
			'qr_template_x' => 50,
			'qr_template_y' => 50,
			'qr_template_size' => 28,
		]);
	}

	private function resolveQrTemplateConfig(PresensiSetting $settings): array
	{
		return [
			'path' => $settings->qr_template_path,
			'url' => $settings->qr_template_path ? asset('storage/' . $settings->qr_template_path) : null,
			'x' => (float) ($settings->qr_template_x ?? 50),
			'y' => (float) ($settings->qr_template_y ?? 50),
			'size' => (float) ($settings->qr_template_size ?? 28),
		];
	}

	private function getActivePeriod(): ?PresensiPeriod
	{
		return PresensiPeriod::query()->active()->orderByDesc('start_date')->first();
	}
}

