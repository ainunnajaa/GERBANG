<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
				'latitude' => null,
				'longitude' => null,
				'radius_meter' => null,
			]);
		}

		return view('guru.presensi', [
			'settings' => $settings,
		]);
	}

	public function guruKehadiran(Request $request)
	{
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_masuk_toleransi' => '07:15:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			]);
		}

		$user = Auth::user();
		$query = Presensi::where('user_id', $user->id);

		$startDate = $request->input('tanggal_mulai');
		$endDate = $request->input('tanggal_selesai');

		if ($startDate && $endDate) {
			$query->whereBetween('tanggal', [$startDate, $endDate]);
		} elseif ($startDate) {
			$query->whereDate('tanggal', '>=', $startDate);
		} elseif ($endDate) {
			$query->whereDate('tanggal', '<=', $endDate);
		}

		$presensis = $query
			->orderByDesc('tanggal')
			->paginate(20)
			->appends($request->query());

		return view('guru.kehadiran', [
			'presensis' => $presensis,
			'settings' => $settings,
			'startDate' => $startDate,
			'endDate' => $endDate,
		]);
	}

	public function adminIndex()
	{
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			]);
		}

		$qrCodeText = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');

		$today = now()->toDateString();
		$presensis = Presensi::with('user')
			->whereDate('tanggal', $today)
			->get();

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		return view('admin.kelola_presensi', [
			'qrCodeText' => $qrCodeText,
			'presensis' => $presensis,
			'today' => $today,
			'settings' => $settings,
			'gurus' => $gurus,
		]);
	}

	public function updateSettings(Request $request)
	{
		$data = $request->validate([
			'jam_masuk_start' => ['required', 'date_format:H:i'],
			'jam_masuk_end' => ['required', 'date_format:H:i'],
			'jam_masuk_toleransi' => ['nullable', 'date_format:H:i'],
			'jam_pulang_start' => ['required', 'date_format:H:i'],
			'jam_pulang_end' => ['required', 'date_format:H:i'],
			'qr_text' => ['nullable', 'string', 'max:255'],
			'latitude' => ['nullable', 'numeric', 'between:-90,90'],
			'longitude' => ['nullable', 'numeric', 'between:-180,180'],
			'radius_meter' => ['nullable', 'integer', 'min:10'],
		]);

		$settings = PresensiSetting::first() ?? new PresensiSetting();
		$settings->jam_masuk_start = $data['jam_masuk_start'] . ':00';
		$settings->jam_masuk_end = $data['jam_masuk_end'] . ':00';
		$settings->jam_masuk_toleransi = $data['jam_masuk_toleransi']
			? $data['jam_masuk_toleransi'] . ':00'
			: null;
		$settings->jam_pulang_start = $data['jam_pulang_start'] . ':00';
		$settings->jam_pulang_end = $data['jam_pulang_end'] . ':00';
		$settings->qr_text = $data['qr_text'] ?? env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
		$settings->latitude = $data['latitude'] ?? null;
		$settings->longitude = $data['longitude'] ?? null;
		$settings->radius_meter = $data['radius_meter'] ?? null;
		$settings->save();

		return back()->with('success', 'Jam presensi berhasil diperbarui.');
	}

	public function adminRiwayat()
	{
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			]);
		}

		$today = now()->toDateString();
		$todayPresensis = Presensi::with('user')
			->whereDate('tanggal', $today)
			->get();

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		$presensis = Presensi::with('user')
			->orderByDesc('tanggal')
			->orderBy('user_id')
			->paginate(20);

		return view('admin.riwayat_presensi', [
			'presensis' => $presensis,
			'todayPresensis' => $todayPresensis,
			'today' => $today,
			'settings' => $settings,
			'gurus' => $gurus,
		]);
	}

	public function adminRiwayatSemua(Request $request)
	{
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_masuk_toleransi' => '07:15:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			]);
		}

		$query = Presensi::with('user');

		$startDate = $request->input('tanggal_mulai');
		$endDate = $request->input('tanggal_selesai');

		if ($startDate && $endDate) {
			$query->whereBetween('tanggal', [$startDate, $endDate]);
		} elseif ($startDate) {
			$query->whereDate('tanggal', '>=', $startDate);
		} elseif ($endDate) {
			$query->whereDate('tanggal', '<=', $endDate);
		}

		$presensis = $query
			->orderByDesc('tanggal')
			->orderBy('user_id')
			->paginate(50)
			->appends($request->query());

		return view('admin.presensi.riwayat_presensi_blade', [
			'presensis' => $presensis,
			'settings' => $settings,
			'startDate' => $startDate,
			'endDate' => $endDate,
		]);
	}

	public function adminPresensiGuru(User $guru)
	{
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_masuk_toleransi' => '07:15:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			]);
		}

		$presensis = Presensi::where('user_id', $guru->id)
			->orderByDesc('tanggal')
			->orderByDesc('created_at')
			->paginate(30);

		return view('admin.presensi.presensi_guru', [
			'guru' => $guru,
			'presensis' => $presensis,
			'settings' => $settings,
		]);
	}

	public function adminDownloadPresensiGuru(User $guru)
	{
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_masuk_toleransi' => '07:15:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			]);
		}

		$presensis = Presensi::where('user_id', $guru->id)
			->orderBy('tanggal')
			->get();

		$fileName = 'riwayat_presensi_' . $guru->id . '_' . now()->format('Ymd_His') . '.csv';

		return response()->streamDownload(function () use ($presensis, $settings) {
			$handle = fopen('php://output', 'w');
			// Header CSV
			fputcsv($handle, ['Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status']);

			$tol = null;
			if ($settings) {
				$tol = $settings->jam_masuk_toleransi
					? Carbon::parse($settings->jam_masuk_toleransi)
					: ($settings->jam_masuk_end ? Carbon::parse($settings->jam_masuk_end) : null);
			}

			foreach ($presensis as $item) {
				$status = '-';
				if ($item->jam_masuk && $tol) {
					$jamMasuk = Carbon::parse($item->jam_masuk);
					$status = $jamMasuk->lt($tol) ? 'H' : 'T';
				}

				fputcsv($handle, [
					$item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : '',
					$item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '',
					$item->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') : '',
					$status,
				]);
			}

			fclose($handle);
		}, $fileName, [
			'Content-Type' => 'text/csv',
		]);
	}

	public function adminDeletePresensi(Presensi $presensi)
	{
		$guruId = $presensi->user_id;
		$tanggal = $presensi->tanggal; // tipe Carbon karena cast di model

		// Hapus semua data presensi guru tersebut pada tanggal yang sama (berdasarkan DATE saja)
		Presensi::where('user_id', $guruId)
			->whereDate('tanggal', $tanggal)
			->delete();

		return redirect()
			->route('admin.presensi.guru', $guruId)
			->with('success', 'Riwayat presensi untuk tanggal tersebut berhasil dihapus.');
	}

	public function adminExportPresensiSemua(Request $request)
	{
		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_masuk_toleransi' => '07:15:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
			]);
		}

		$query = Presensi::with('user');

		$startDate = $request->input('tanggal_mulai');
		$endDate = $request->input('tanggal_selesai');

		if ($startDate && $endDate) {
			$query->whereBetween('tanggal', [$startDate, $endDate]);
		} elseif ($startDate) {
			$query->whereDate('tanggal', '>=', $startDate);
		} elseif ($endDate) {
			$query->whereDate('tanggal', '<=', $endDate);
		}

		$presensis = $query
			->orderBy('tanggal')
			->orderBy('user_id')
			->get();

		$fileName = 'riwayat_presensi_semua_' . now()->format('Ymd_His') . '.csv';

		return response()->streamDownload(function () use ($presensis, $settings) {
			$handle = fopen('php://output', 'w');
			// Header CSV
			fputcsv($handle, ['Tanggal', 'Nama Guru', 'Kelas', 'Jam Masuk', 'Jam Pulang', 'Status']);

			$tol = null;
			if ($settings) {
				$tol = $settings->jam_masuk_toleransi
					? Carbon::parse($settings->jam_masuk_toleransi)
					: ($settings->jam_masuk_end ? Carbon::parse($settings->jam_masuk_end) : null);
			}

			foreach ($presensis as $item) {
				$status = '-';
				if ($item->jam_masuk && $tol) {
					$jamMasuk = Carbon::parse($item->jam_masuk);
					$status = $jamMasuk->lt($tol) ? 'H' : 'T';
				}

				fputcsv($handle, [
					$item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : '',
					optional($item->user)->name,
					optional($item->user)->kelas,
					$item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '',
					$item->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') : '',
					$status,
				]);
			}

			fclose($handle);
		}, $fileName, [
			'Content-Type' => 'text/csv',
		]);
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

		$settings = PresensiSetting::first();
		if (! $settings) {
			$settings = PresensiSetting::create([
				'jam_masuk_start' => '07:00:00',
				'jam_masuk_end' => '08:00:00',
				'jam_pulang_start' => '13:00:00',
				'jam_pulang_end' => '14:30:00',
				'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
				'latitude' => null,
				'longitude' => null,
				'radius_meter' => null,
			]);
		}

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
		$pulangStart = Carbon::createFromFormat('H:i', substr($settings->jam_pulang_start, 0, 5));
		$pulangEnd = Carbon::createFromFormat('H:i', substr($settings->jam_pulang_end, 0, 5));

		$current = Carbon::createFromFormat('H:i', $currentTime);

		$isMasuk = $current->between($masukStart, $masukEnd);
		$isPulang = $current->between($pulangStart, $pulangEnd);

		if (! $isMasuk && ! $isPulang) {
			return back()->with('error', 'Bukan jam presensi. Jam masuk: ' . $masukStart->format('H:i') . '-' . $masukEnd->format('H:i') . ', jam pulang: ' . $pulangStart->format('H:i') . '-' . $pulangEnd->format('H:i'));
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
				return back()->with('error', 'Presensi masuk Anda hari ini sudah tercatat.');
			}

			if (! $presensi) {
				// Belum ada data hari ini, buat baru dengan jam masuk
				Presensi::create([
					'user_id' => $user->id,
					'tanggal' => $today,
					'jam_masuk' => $now->format('H:i:s'),
				]);
			} else {
				// Sudah ada record hari ini tapi belum ada jam masuk
				$presensi->jam_masuk = $now->format('H:i:s');
				$presensi->save();
			}

			return back()->with('success', 'Presensi masuk berhasil tercatat pada pukul ' . $now->format('H:i'));
		}

		if ($isPulang) {
			// Jika sudah pernah presensi pulang hari ini, blok dan tampilkan merah
			if ($presensi && $presensi->jam_pulang) {
				return back()->with('error', 'Presensi pulang Anda hari ini sudah tercatat.');
			}

			if (! $presensi) {
				// Belum ada data hari ini, buat baru dengan jam pulang
				Presensi::create([
					'user_id' => $user->id,
					'tanggal' => $today,
					'jam_pulang' => $now->format('H:i:s'),
				]);
			} else {
				// Sudah ada record hari ini tapi belum ada jam pulang
				$presensi->jam_pulang = $now->format('H:i:s');
				$presensi->save();
			}

			return back()->with('success', 'Presensi pulang berhasil tercatat pada pukul ' . $now->format('H:i'));
		}

		return back()->with('error', 'Terjadi kesalahan saat mencatat presensi.');
	}
}

