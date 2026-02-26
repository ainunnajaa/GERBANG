<?php

namespace App\Http\Controllers;

use App\Exports\GuruRiwayatExport;
use App\Exports\RekapBulananExport;
use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatPresensiController extends Controller
{
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

		$month = (int) $request->input('bulan', now()->month);
		$year = (int) $request->input('tahun', now()->year);
		$requestedWeek = (int) $request->input('minggu', 0);

		$dateInMonth = Carbon::createFromDate($year, $month, 1);
		$daysInMonth = $dateInMonth->daysInMonth;
		$maxWeek = 1;
		for ($d = 1; $d <= $daysInMonth; $d++) {
			$w = Carbon::create($year, $month, $d)->weekOfMonth;
			if ($w > $maxWeek) {
				$maxWeek = $w;
			}
		}

		if ($requestedWeek > 0) {
			$week = min($requestedWeek, $maxWeek);
		} else {
			$today = Carbon::today();
			if ($today->year === $year && $today->month === $month) {
				$week = $today->weekOfMonth;
			} else {
				$week = 1;
			}
		}

		$datesInWeek = [];
		for ($d = 1; $d <= $daysInMonth; $d++) {
			$dt = Carbon::create($year, $month, $d);
			if ($dt->weekOfMonth === $week) {
				$datesInWeek[] = $dt;
			}
		}

		$startDate = null;
		$endDate = null;
		if (! empty($datesInWeek)) {
			$startDate = $datesInWeek[0]->toDateString();
			$endDate = end($datesInWeek)->toDateString();
			$query->whereBetween('tanggal', [$startDate, $endDate]);
		}

		$presensis = $query
			->orderByDesc('tanggal')
			->paginate(20)
			->appends($request->query());

		// Ambil data izin untuk tanggal-tanggal yang ada di halaman ini (satu izin per hari)
		$dates = $presensis->pluck('tanggal')
			->filter()
			->map(fn ($tanggal) => $tanggal instanceof Carbon ? $tanggal->toDateString() : Carbon::parse($tanggal)->toDateString())
			->unique()
			->values();

		$izinRecords = PresensiIzin::where('user_id', $user->id)
			->whereIn('tanggal', $dates)
			->get();

		$izinsByDate = $izinRecords->keyBy(function ($izin) {
			return $izin->tanggal instanceof Carbon
				? $izin->tanggal->toDateString()
				: Carbon::parse($izin->tanggal)->toDateString();
		});

		// Bangun daftar tahun berdasarkan data presensi guru ini
		$firstTanggal = Presensi::where('user_id', $user->id)->min('tanggal');
		$firstYear = $firstTanggal ? Carbon::parse($firstTanggal)->year : now()->year;
		$lastYear = now()->year + 1; // beri 1 tahun ke depan untuk antisipasi
		$years = range($firstYear, $lastYear);

		return view('guru.kehadiran', [
			'presensis' => $presensis,
			'settings' => $settings,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'month' => $month,
			'year' => $year,
			'week' => $week,
			'maxWeek' => $maxWeek,
			'years' => $years,
			'izinsByDate' => $izinsByDate,
		]);
	}

	public function guruKehadiranBulanan(Request $request)
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
		$month = (int) $request->input('bulan', now()->month);
		$year = (int) $request->input('tahun', now()->year);

		$date = Carbon::createFromDate($year, $month, 1);
		$daysInMonth = $date->daysInMonth;
		$days = range(1, $daysInMonth);

		$presensis = Presensi::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get();

		$izins = PresensiIzin::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get();

		$izinDays = [];
		foreach ($izins as $izin) {
			if (! $izin->tanggal) {
				continue;
			}

			$day = $izin->tanggal instanceof Carbon
				? $izin->tanggal->day
				: Carbon::parse($izin->tanggal)->day;

			$izinDays[$day] = true;
		}

		$matrix = [];
		// 1. Isi status dari presensi (H/T)
		foreach ($presensis as $presensi) {
			if (! $presensi->tanggal) {
				continue;
			}

			$day = $presensi->tanggal instanceof Carbon
				? $presensi->tanggal->day
				: Carbon::parse($presensi->tanggal)->day;

			$status = '-';
			if ($presensi->jam_masuk && $settings && $settings->jam_masuk_end) {
				$jamMasuk = Carbon::parse($presensi->jam_masuk);
				$batasHadir = Carbon::parse($settings->jam_masuk_end);
				$status = $jamMasuk->lte($batasHadir) ? 'H' : 'T';
			}

			$matrix[$day] = $status;
		}

		// 2. Hari yang hanya punya izin tanpa presensi
		foreach ($izinDays as $day => $_) {
			if (! isset($matrix[$day])) {
				$matrix[$day] = 'I';
			}
		}

		// Daftar tahun berdasarkan data presensi guru ini
		$firstTanggal = Presensi::where('user_id', $user->id)->min('tanggal');
		$firstYear = $firstTanggal ? Carbon::parse($firstTanggal)->year : now()->year;
		$lastYear = now()->year + 1;
		$years = range($firstYear, $lastYear);

		return view('guru.kehadiran_bulanan', [
			'settings' => $settings,
			'days' => $days,
			'month' => $month,
			'year' => $year,
			'matrix' => $matrix,
			'years' => $years,
			'user' => $user,
		]);
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

		$selectedDate = $request->input('tanggal');

		// Jika tidak ada filter tanggal dikirim, default ke hari ini
		if (! $selectedDate) {
			$selectedDate = Carbon::today()->toDateString();
		}

		// Filter hanya untuk satu tanggal
		$query->whereDate('tanggal', $selectedDate);

		$presensis = $query
			->orderByDesc('tanggal')
			->orderBy('user_id')
			->paginate(50)
			->appends($request->query());

		// Ambil data izin untuk tanggal yang sama (satu izin per guru per hari)
		$izinRecords = PresensiIzin::whereDate('tanggal', $selectedDate)->get();
		$izinsByUser = $izinRecords->keyBy('user_id');

		return view('admin.presensi.riwayat_presensi_blade', [
			'presensis' => $presensis,
			'settings' => $settings,
			'selectedDate' => $selectedDate,
			'izinsByUser' => $izinsByUser,
		]);
	}

	public function adminRiwayatBulanan(Request $request)
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

		$month = (int) $request->input('bulan', now()->month);
		$year = (int) $request->input('tahun', now()->year);

		$date = Carbon::createFromDate($year, $month, 1);
		$daysInMonth = $date->daysInMonth;
		$days = range(1, $daysInMonth);

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		$presensis = Presensi::with('user')
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get();

		// Ambil data izin untuk bulan yang sama
		$izins = PresensiIzin::whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get();

		// Susun hari-hari yang memiliki izin per guru
		$izinDays = [];
		foreach ($izins as $izin) {
			if (! $izin->tanggal) {
				continue;
			}

			$day = $izin->tanggal instanceof Carbon
				? $izin->tanggal->day
				: Carbon::parse($izin->tanggal)->day;

			$izinDays[$izin->user_id][$day] = true;
		}

		$matrix = [];
		foreach ($presensis as $presensi) {
			if (! $presensi->tanggal) {
				continue;
			}

			$day = $presensi->tanggal instanceof Carbon
				? $presensi->tanggal->day
				: Carbon::parse($presensi->tanggal)->day;

			$status = '-';
			// Status utama dari presensi jika ada
			if ($presensi->jam_masuk && $settings && $settings->jam_masuk_end) {
				$jamMasuk = Carbon::parse($presensi->jam_masuk);
				$batasHadir = Carbon::parse($settings->jam_masuk_end);
				$status = $jamMasuk->lte($batasHadir) ? 'H' : 'T';
			} elseif (isset($izinDays[$presensi->user_id][$day])) {
				$status = 'I';
			}

			$matrix[$presensi->user_id][$day] = $status;
		}

		// Pastikan hari yang hanya punya izin (tanpa record presensi) tetap muncul sebagai I
		foreach ($izinDays as $userId => $daysWithIzin) {
			foreach ($daysWithIzin as $day => $_) {
				if (! isset($matrix[$userId][$day])) {
					$matrix[$userId][$day] = 'I';
				}
			}
		}

		// Daftar tahun untuk admin: dari tahun pertama data presensi hingga tahun depan
		$firstTanggal = Presensi::min('tanggal');
		$firstYear = $firstTanggal ? Carbon::parse($firstTanggal)->year : now()->year;
		$lastYear = now()->year + 1;
		$years = range($firstYear, $lastYear);

		return view('admin.presensi.riwayat_bulanan', [
			'settings' => $settings,
			'gurus' => $gurus,
			'days' => $days,
			'month' => $month,
			'year' => $year,
			'matrix' => $matrix,
			'years' => $years,
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

		// Ambil data izin untuk tanggal-tanggal yang tampil di halaman ini
		$dates = $presensis->pluck('tanggal')
			->filter()
			->map(fn ($tanggal) => $tanggal instanceof Carbon ? $tanggal->toDateString() : Carbon::parse($tanggal)->toDateString())
			->unique()
			->values();

		$izinRecords = PresensiIzin::where('user_id', $guru->id)
			->whereIn('tanggal', $dates)
			->get();

		$izinsByDate = $izinRecords->keyBy(function ($izin) {
			return $izin->tanggal instanceof Carbon
				? $izin->tanggal->toDateString()
				: Carbon::parse($izin->tanggal)->toDateString();
		});

		return view('admin.presensi.presensi_guru', [
			'guru' => $guru,
			'presensis' => $presensis,
			'settings' => $settings,
			'izinsByDate' => $izinsByDate,
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

		// Ambil data izin untuk tanggal-tanggal yang tampil di export
		$dates = $presensis->pluck('tanggal')
			->filter()
			->map(fn ($tanggal) => $tanggal instanceof Carbon ? $tanggal->toDateString() : Carbon::parse($tanggal)->toDateString())
			->unique()
			->values();
		$izinRecords = PresensiIzin::where('user_id', $guru->id)
			->whereIn('tanggal', $dates)
			->get();
		$izinsByDate = $izinRecords->keyBy(function ($izin) {
			return $izin->tanggal instanceof Carbon
				? $izin->tanggal->toDateString()
				: Carbon::parse($izin->tanggal)->toDateString();
		});

		$sanitizedName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $guru->name ?? 'guru');
		$fileName = 'riwayat_presensi_' . $sanitizedName . '_' . now()->format('Ymd_His') . '.xlsx';

		// Siapkan data baris untuk export Excel
		$rows = [];
		$rows[] = ['Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Jam Izin', 'Keterangan'];

		$batasHadir = null;
		if ($settings && $settings->jam_masuk_end) {
			$batasHadir = Carbon::parse($settings->jam_masuk_end);
		}

		foreach ($presensis as $item) {
			$status = '-';
			if ($item->jam_masuk && $batasHadir) {
				$jamMasuk = Carbon::parse($item->jam_masuk);
				$status = $jamMasuk->lte($batasHadir) ? 'H' : 'T';
			}
			$tanggalKey = $item->tanggal instanceof Carbon
				? $item->tanggal->toDateString()
				: Carbon::parse($item->tanggal)->toDateString();
			$izin = $izinsByDate[$tanggalKey] ?? null;
			$jamIzin = $izin ? ($izin->created_at ? Carbon::parse($izin->created_at)->format('H:i') : '') : '';
			$keterangan = $izin ? ($izin->keterangan ?? '') : '';
			$rows[] = [
				$item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : '',
				$item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '',
				$item->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') : '',
				$status,
				$jamIzin,
				$keterangan,
			];
		}

		return Excel::download(new GuruRiwayatExport($rows), $fileName);
	}

	public function adminDeletePresensi(Presensi $presensi)
	{
		$guruId = $presensi->user_id;
		$tanggal = $presensi->tanggal; // tipe Carbon karena cast di model


		// Hapus semua data presensi guru tersebut pada tanggal yang sama (berdasarkan DATE saja)
		Presensi::where('user_id', $guruId)
			->whereDate('tanggal', $tanggal)
			->delete();

		// Hapus juga data izin pada tanggal dan user yang sama
		\App\Models\PresensiIzin::where('user_id', $guruId)
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

		$startDate = $request->input('tanggal_mulai');
		$endDate = $request->input('tanggal_selesai');

		// Default filter: jika tidak ada tanggal dikirim, gunakan hari ini
		if (! $startDate && ! $endDate) {
			$today = Carbon::today()->toDateString();
			$startDate = $today;
			$endDate = $today;
		}

		$start = Carbon::parse($startDate);
		$month = $start->month;
		$year = $start->year;
		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		$presensis = Presensi::with('user')
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get();

		$fileName = 'rekap_presensi_bulanan_' . $year . '_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.xlsx';

		// Bangun array baris matriks seperti tampilan rekap bulanan
		$rows = [];
		$header = ['Nama Guru', 'Kelas'];
		foreach ($days as $day) {
			$header[] = $day;
		}
		$rows[] = $header;

		$batasHadir = null;
		if ($settings && $settings->jam_masuk_end) {
			$batasHadir = Carbon::parse($settings->jam_masuk_end);
		}

		// Ambil data izin untuk bulan yang sama
		$izins = PresensiIzin::whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get();

		// Susun hari-hari yang memiliki izin per guru
		$izinDays = [];
		foreach ($izins as $izin) {
			if (! $izin->tanggal) {
				continue;
			}

			$day = $izin->tanggal instanceof Carbon
				? $izin->tanggal->day
				: Carbon::parse($izin->tanggal)->day;

			$izinDays[$izin->user_id][$day] = true;
		}

		$matrix = [];
		foreach ($presensis as $item) {
			if (! $item->tanggal) {
				continue;
			}

			$day = $item->tanggal instanceof Carbon
				? $item->tanggal->day
				: Carbon::parse($item->tanggal)->day;

			$status = '-';
			// Jika ada izin untuk guru & hari ini, status selalu I
			if (isset($izinDays[$item->user_id][$day])) {
				$status = 'I';
			} elseif ($item->jam_masuk && $batasHadir) {
				$jamMasuk = Carbon::parse($item->jam_masuk);
				$status = $jamMasuk->lte($batasHadir) ? 'H' : 'T';
			}

			$matrix[$item->user_id][$day] = $status;
		}

		// Pastikan hari yang hanya punya izin (tanpa record presensi) tetap muncul sebagai I
		foreach ($izinDays as $userId => $daysWithIzin) {
			foreach ($daysWithIzin as $day => $_) {
				if (! isset($matrix[$userId][$day])) {
					$matrix[$userId][$day] = 'I';
				}
			}
		}

		foreach ($gurus as $guru) {
			$row = [
				$guru->name,
				$guru->kelas,
			];

			foreach ($days as $day) {
				$row[] = $matrix[$guru->id][$day] ?? '-';
			}

			$rows[] = $row;
		}

		return Excel::download(new RekapBulananExport($rows), $fileName);
	}
}

