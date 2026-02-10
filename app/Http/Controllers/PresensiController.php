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
	public function guruIndex()
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

		return view('guru.presensi', [
			'settings' => $settings,
		]);
	}

	public function guruKehadiran(Request $request)
	{
		$user = Auth::user();

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

		$selectedDate = $request->input('tanggal');
		$selectedMonth = $request->input('bulan');
		$selectedYear = $request->input('tahun');

		$query = Presensi::where('user_id', $user->id);

		if ($selectedDate) {
			$query->whereDate('tanggal', $selectedDate);
		} else {
			if ($selectedYear) {
				$query->whereYear('tanggal', $selectedYear);
			} else {
				$selectedYear = now()->year;
				$query->whereYear('tanggal', $selectedYear);
			}

			if ($selectedMonth) {
				$query->whereMonth('tanggal', $selectedMonth);
			}
		}

		$presensis = $query
			->orderByDesc('tanggal')
			->orderByDesc('created_at')
			->get();

		return view('guru.kehadiran', [
			'presensis' => $presensis,
			'selectedDate' => $selectedDate,
			'selectedMonth' => $selectedMonth,
			'selectedYear' => $selectedYear,
			'settings' => $settings,
		]);
	}

	public function adminIndex()
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
			'jam_masuk_toleransi' => ['required', 'date_format:H:i'],
			'jam_pulang_start' => ['required', 'date_format:H:i'],
			'jam_pulang_end' => ['required', 'date_format:H:i'],
			'qr_text' => ['nullable', 'string', 'max:255'],
		]);

		$settings = PresensiSetting::first() ?? new PresensiSetting();
		$settings->jam_masuk_start = $data['jam_masuk_start'] . ':00';
		$settings->jam_masuk_end = $data['jam_masuk_end'] . ':00';
		$settings->jam_masuk_toleransi = $data['jam_masuk_toleransi'] . ':00';
		$settings->jam_pulang_start = $data['jam_pulang_start'] . ':00';
		$settings->jam_pulang_end = $data['jam_pulang_end'] . ':00';
		$settings->qr_text = $data['qr_text'] ?? env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
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
				'jam_masuk_toleransi' => '07:15:00',
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

	public function adminPresensiGuru(User $guru)
	{
		$presensis = Presensi::where('user_id', $guru->id)
			->orderByDesc('tanggal')
			->orderByDesc('created_at')
			->paginate(30);

		return view('admin.presensi.presensi_guru', [
			'guru' => $guru,
			'presensis' => $presensis,
			'settings' => PresensiSetting::first(),
		]);
	}

	public function adminDownloadPresensiGuru(User $guru)
	{
		$settings = PresensiSetting::first();
		$presensis = Presensi::where('user_id', $guru->id)
			->orderBy('tanggal')
			->orderBy('created_at')
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
					$status = $jamMasuk->lte($tol) ? 'H' : 'T';
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

		$selectedDate = $request->input('tanggal');
		$selectedMonth = $request->input('bulan');
		$selectedYear = $request->input('tahun');

		if ($selectedDate) {
			$query->whereDate('tanggal', $selectedDate);
			$parsed = Carbon::parse($selectedDate);
			$selectedYear = $parsed->year;
			$selectedMonth = $parsed->format('m');
		} else {
			if ($selectedYear) {
				$query->whereYear('tanggal', $selectedYear);
			} else {
				$selectedYear = now()->year;
				$query->whereYear('tanggal', $selectedYear);
			}

			if ($selectedMonth) {
				$query->whereMonth('tanggal', $selectedMonth);
			}
		}

		$presensis = $query
			->orderByDesc('tanggal')
			->orderBy('user_id')
			->paginate(50)
			->appends($request->query());

		$years = Presensi::selectRaw('YEAR(tanggal) as year')
			->distinct()
			->orderByDesc('year')
			->pluck('year');

		return view('admin.presensi.riwayat_presensi_blade', [
			'presensis' => $presensis,
			'settings' => $settings,
			'years' => $years,
			'selectedDate' => $selectedDate,
			'selectedMonth' => $selectedMonth,
			'selectedYear' => $selectedYear,
		]);
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

		$selectedDate = $request->input('tanggal');
		$selectedMonth = $request->input('bulan');
		$selectedYear = $request->input('tahun');

		if ($selectedDate) {
			$query->whereDate('tanggal', $selectedDate);
		} else {
			if ($selectedYear) {
				$query->whereYear('tanggal', $selectedYear);
			}
			if ($selectedMonth) {
				$query->whereMonth('tanggal', $selectedMonth);
			}
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
					$status = $jamMasuk->lte($tol) ? 'H' : 'T';
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

	public function adminDeletePresensi(Presensi $presensi)
	{
		$guruId = $presensi->user_id;
		$presensi->delete();

		return redirect()
			->route('admin.presensi.guru', $guruId)
			->with('success', 'Riwayat presensi berhasil dihapus.');
	}

	public function scan(Request $request)
	{
		$request->validate([
			'qr_code' => 'required|string',
		]);

		$now = Carbon::now();
		$currentTime = $now->format('H:i');
		$today = $now->toDateString();

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

		$expectedCode = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
		if ($request->qr_code !== $expectedCode) {
			return back()->with('error', 'QR code tidak valid.');
		}

		$masukStart = Carbon::createFromFormat('H:i', substr($settings->jam_masuk_start, 0, 5));
		$masukEndConfig = Carbon::createFromFormat('H:i', substr($settings->jam_masuk_end, 0, 5));
		$masukToleransi = Carbon::createFromFormat('H:i', substr(($settings->jam_masuk_toleransi ?? $settings->jam_masuk_end), 0, 5));
		// Batas akhir jam masuk yang diizinkan: ambil nilai terbesar antara jam_masuk_end dan jam_masuk_toleransi.
		$masukEndAllowed = $masukEndConfig->greaterThan($masukToleransi) ? $masukEndConfig : $masukToleransi;
		$pulangStart = Carbon::createFromFormat('H:i', substr($settings->jam_pulang_start, 0, 5));
		$pulangEnd = Carbon::createFromFormat('H:i', substr($settings->jam_pulang_end, 0, 5));

		$current = Carbon::createFromFormat('H:i', $currentTime);

		// Guru masih boleh presensi masuk selama masih dalam rentang jam_masuk_start s.d batas akhir yang diizinkan.
		$isMasuk = $current->between($masukStart, $masukEndAllowed);
		$isPulang = $current->between($pulangStart, $pulangEnd);

		if (! $isMasuk && ! $isPulang) {
			return back()->with('error', 'Bukan jam presensi. Jam masuk: ' . $masukStart->format('H:i') . '-' . $masukEndAllowed->format('H:i') . ', jam pulang: ' . $pulangStart->format('H:i') . '-' . $pulangEnd->format('H:i'));
		}

		$user = Auth::user();

		$presensi = Presensi::firstOrCreate([
			'user_id' => $user->id,
			'tanggal' => $today,
		]);

		if ($isMasuk) {
			if ($presensi->jam_masuk) {
				return back()->with('error', 'Anda sudah melakukan presensi masuk hari ini.');
			}

			$presensi->jam_masuk = $now->format('H:i:s');
			$presensi->save();

			// Tentukan apakah terlambat berdasarkan jam toleransi
			$isLate = $now->gt($masukToleransi);
			$message = 'Presensi masuk berhasil tercatat pada pukul ' . $now->format('H:i');
			if ($isLate) {
				$message .= ' (TERLAMBAT)';
			}

			return back()->with('success', $message); 
		}

		if ($isPulang) {
			if ($presensi->jam_pulang) {
				return back()->with('error', 'Anda sudah melakukan presensi pulang hari ini.');
			}

			$presensi->jam_pulang = $now->format('H:i:s');
			$presensi->save();

			return back()->with('success', 'Presensi pulang berhasil tercatat pada pukul ' . $now->format('H:i'));
		}

		return back()->with('error', 'Terjadi kesalahan saat mencatat presensi.');
	}
}

