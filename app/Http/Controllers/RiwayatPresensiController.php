<?php

namespace App\Http\Controllers;

use App\Exports\GuruRiwayatExport;
use App\Exports\RekapBulananExport;
use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiSetting;
use App\Models\PresensiStatusOverride;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatPresensiController extends Controller
{
	private const AVAILABLE_STATUSES = ['H', 'T', 'I', 'A', '-'];

	protected array $holidayDateCache = [];

	public function guruKehadiran(Request $request)
	{
		$settings = $this->ensureSettings();
		$user = Auth::user();

		$month = (int) $request->input('bulan', now()->month);
		$year = (int) $request->input('tahun', now()->year);
		$requestedWeek = (int) $request->input('minggu', 0);

		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$maxWeek = 1;
		for ($day = 1; $day <= $daysInMonth; $day++) {
			$weekOfMonth = Carbon::create($year, $month, $day)->weekOfMonth;
			if ($weekOfMonth > $maxWeek) {
				$maxWeek = $weekOfMonth;
			}
		}

		if ($requestedWeek > 0) {
			$week = min($requestedWeek, $maxWeek);
		} else {
			$today = Carbon::today();
			$week = ($today->year === $year && $today->month === $month) ? $today->weekOfMonth : 1;
		}

		$datesInWeek = [];
		for ($day = 1; $day <= $daysInMonth; $day++) {
			$date = Carbon::create($year, $month, $day);
			if ($date->weekOfMonth === $week) {
				$datesInWeek[] = $date;
			}
		}

		$startDate = null;
		$endDate = null;
		if (! empty($datesInWeek)) {
			$startDate = $datesInWeek[0]->toDateString();
			$endDate = end($datesInWeek)->toDateString();
		}

		$dateKeys = collect($datesInWeek)
			->map(fn (Carbon $date) => $date->toDateString())
			->values();

		$presensisByDate = Presensi::where('user_id', $user->id)
			->when($startDate && $endDate, fn ($query) => $query->whereBetween('tanggal', [$startDate, $endDate]))
			->get()
			->keyBy(fn ($presensi) => $this->normalizeDateKey($presensi->tanggal));

		$izinsByDate = PresensiIzin::where('user_id', $user->id)
			->when($dateKeys->isNotEmpty(), fn ($query) => $query->whereIn('tanggal', $dateKeys))
			->get()
			->keyBy(fn ($izin) => $this->normalizeDateKey($izin->tanggal));

		$manualStatuses = $this->getManualStatusOverrides([$user->id], $dateKeys->all());

		$attendanceRows = collect($datesInWeek)
			->sortByDesc(fn (Carbon $date) => $date->timestamp)
			->values()
			->map(function (Carbon $date) use ($izinsByDate, $manualStatuses, $presensisByDate, $settings, $user) {
				$dateKey = $date->toDateString();
				$presensi = $presensisByDate->get($dateKey);
				$izin = $izinsByDate->get($dateKey);

				return [
					'date' => $date,
					'presensi' => $presensi,
					'izin' => $izin,
					'status' => $this->resolveAttendanceStatus(
						$date,
						$settings,
						$presensi,
						$izin,
						$manualStatuses[$user->id][$dateKey] ?? null,
					),
				];
			});

		$firstTanggal = Presensi::where('user_id', $user->id)->min('tanggal');
		$firstYear = $firstTanggal ? Carbon::parse($firstTanggal)->year : now()->year;
		$years = range($firstYear, now()->year + 1);

		return view('guru.kehadiran', [
			'attendanceRows' => $attendanceRows,
			'settings' => $settings,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'month' => $month,
			'year' => $year,
			'week' => $week,
			'maxWeek' => $maxWeek,
			'years' => $years,
		]);
	}

	public function guruKehadiranBulanan(Request $request)
	{
		$settings = $this->ensureSettings();
		$user = Auth::user();
		$month = (int) $request->input('bulan', now()->month);
		$year = (int) $request->input('tahun', now()->year);

		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByDate = Presensi::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get()
			->keyBy(fn ($presensi) => $this->normalizeDateKey($presensi->tanggal));

		$izinsByDate = PresensiIzin::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get()
			->keyBy(fn ($izin) => $this->normalizeDateKey($izin->tanggal));

		$manualStatuses = $this->getManualStatusOverrides([$user->id], $dateKeys);

		$matrix = [];
		foreach ($days as $day) {
			$date = Carbon::create($year, $month, $day);
			$dateKey = $date->toDateString();
			$matrix[$day] = $this->resolveAttendanceStatus(
				$date,
				$settings,
				$presensisByDate->get($dateKey),
				$izinsByDate->get($dateKey),
				$manualStatuses[$user->id][$dateKey] ?? null,
			);
		}

		$firstTanggal = Presensi::where('user_id', $user->id)->min('tanggal');
		$firstYear = $firstTanggal ? Carbon::parse($firstTanggal)->year : now()->year;
		$years = range($firstYear, now()->year + 1);

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

	public function guruExportKehadiranBulanan(Request $request)
	{
		$settings = $this->ensureSettings();
		$user = Auth::user();
		$month = (int) $request->input('bulan', now()->month);
		$year = (int) $request->input('tahun', now()->year);

		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByDate = Presensi::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get()
			->keyBy(fn ($presensi) => $this->normalizeDateKey($presensi->tanggal));

		$izinsByDate = PresensiIzin::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->get()
			->keyBy(fn ($izin) => $this->normalizeDateKey($izin->tanggal));

		$manualStatuses = $this->getManualStatusOverrides([$user->id], $dateKeys);

		$rows = [];
		$header = ['Nama Guru', 'Kelas'];
		foreach ($days as $day) {
			$header[] = $day;
		}
		$rows[] = $header;

		$row = [$user->name, $user->kelas ?? '-'];
		foreach ($days as $day) {
			$date = Carbon::create($year, $month, $day);
			$dateKey = $date->toDateString();
			$row[] = $this->resolveAttendanceStatus(
				$date,
				$settings,
				$presensisByDate->get($dateKey),
				$izinsByDate->get($dateKey),
				$manualStatuses[$user->id][$dateKey] ?? null,
			);
		}
		$rows[] = $row;

		$fileName = 'rekap_presensi_saya_' . $year . '_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.xlsx';

		return Excel::download(new RekapBulananExport($rows), $fileName);
	}

	public function adminRiwayat()
	{
		$settings = $this->ensureSettings();
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
		$settings = $this->ensureSettings();
		$selectedDate = $request->input('tanggal', Carbon::today()->toDateString());
		$selectedDateCarbon = Carbon::parse($selectedDate);

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->paginate(50)
			->appends($request->query());

		$userIds = $gurus->getCollection()->pluck('id');
		$presensisByUser = Presensi::with('user')
			->whereDate('tanggal', $selectedDate)
			->whereIn('user_id', $userIds)
			->orderBy('user_id')
			->get()
			->keyBy('user_id');

		$izinsByUser = PresensiIzin::whereDate('tanggal', $selectedDate)
			->whereIn('user_id', $userIds)
			->get()
			->keyBy('user_id');

		$manualStatuses = $this->getManualStatusOverrides($userIds->all(), [$selectedDateCarbon->toDateString()]);

		$attendanceRows = $gurus->getCollection()->map(function ($guru) use ($izinsByUser, $manualStatuses, $presensisByUser, $selectedDateCarbon, $settings) {
			$presensi = $presensisByUser->get($guru->id);
			$izin = $izinsByUser->get($guru->id);

			return [
				'guru' => $guru,
				'presensi' => $presensi,
				'izin' => $izin,
				'status' => $this->resolveAttendanceStatus(
					$selectedDateCarbon,
					$settings,
					$presensi,
					$izin,
					$manualStatuses[$guru->id][$selectedDateCarbon->toDateString()] ?? null,
				),
			];
		});

		return view('admin.presensi.riwayat_presensi_blade', [
			'attendanceRows' => $attendanceRows,
			'gurus' => $gurus,
			'settings' => $settings,
			'selectedDate' => $selectedDate,
		]);
	}

	public function adminRiwayatBulanan(Request $request)
	{
		$settings = $this->ensureSettings();
		$month = (int) $request->input('bulan', now()->month);
		$year = (int) $request->input('tahun', now()->year);

		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);
		$gurus = User::where('role', 'guru')->orderBy('name')->get();
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByUserDate = [];
		foreach (Presensi::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get() as $presensi) {
			$presensisByUserDate[$presensi->user_id][$this->normalizeDateKey($presensi->tanggal)] = $presensi;
		}

		$izinsByUserDate = [];
		foreach (PresensiIzin::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get() as $izin) {
			$izinsByUserDate[$izin->user_id][$this->normalizeDateKey($izin->tanggal)] = $izin;
		}

		$manualStatuses = $this->getManualStatusOverrides($gurus->pluck('id')->all(), $dateKeys);

		$matrix = [];
		foreach ($gurus as $guru) {
			foreach ($days as $day) {
				$date = Carbon::create($year, $month, $day);
				$dateKey = $date->toDateString();
				$matrix[$guru->id][$day] = $this->resolveAttendanceStatus(
					$date,
					$settings,
					$presensisByUserDate[$guru->id][$dateKey] ?? null,
					$izinsByUserDate[$guru->id][$dateKey] ?? null,
					$manualStatuses[$guru->id][$dateKey] ?? null,
				);
			}
		}

		$firstTanggal = Presensi::min('tanggal');
		$firstYear = $firstTanggal ? Carbon::parse($firstTanggal)->year : now()->year;
		$years = range($firstYear, now()->year + 1);

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

	public function adminUpdateStatus(Request $request)
	{
		$validated = $request->validate([
			'user_id' => ['required', 'exists:users,id'],
			'tanggal' => ['required', 'date'],
			'status' => ['required', 'in:' . implode(',', self::AVAILABLE_STATUSES)],
		]);

		PresensiStatusOverride::updateOrCreate(
			[
				'user_id' => $validated['user_id'],
				'tanggal' => Carbon::parse($validated['tanggal'])->toDateString(),
			],
			[
				'status' => $validated['status'],
				'updated_by' => Auth::id(),
			],
		);

		return back()->with('success', 'Status kehadiran berhasil diperbarui.');
	}

	public function adminPresensiGuru(Request $request, User $guru)
	{
		$settings = $this->ensureSettings();
		$attendanceRows = $this->paginateCollection($this->buildAttendanceRowsForUser($guru, $settings), $request, 10);

		return view('admin.presensi.presensi_guru', [
			'guru' => $guru,
			'attendanceRows' => $attendanceRows,
			'settings' => $settings,
		]);
	}

	public function adminDownloadPresensiGuru(User $guru)
	{
		$settings = $this->ensureSettings();
		$attendanceRows = $this->buildAttendanceRowsForUser($guru, $settings)->sortBy('date')->values();

		$sanitizedName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $guru->name ?? 'guru');
		$fileName = 'riwayat_presensi_' . $sanitizedName . '_' . now()->format('Ymd_His') . '.xlsx';

		$rows = [];
		$rows[] = ['Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Jam Izin', 'Keterangan'];

		foreach ($attendanceRows as $row) {
			$item = $row['presensi'];
			$izin = $row['izin'];
			$rows[] = [
				$row['date']->format('Y-m-d'),
				optional($item)->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '',
				optional($item)->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') : '',
				$row['status'],
				$izin && $izin->created_at ? Carbon::parse($izin->created_at)->format('H:i') : '',
				$izin->keterangan ?? '',
			];
		}

		return Excel::download(new GuruRiwayatExport($rows), $fileName);
	}

	public function adminDeletePresensi(Presensi $presensi)
	{
		$guruId = $presensi->user_id;
		$tanggal = $presensi->tanggal;

		Presensi::where('user_id', $guruId)
			->whereDate('tanggal', $tanggal)
			->delete();

		PresensiIzin::where('user_id', $guruId)
			->whereDate('tanggal', $tanggal)
			->delete();

		return redirect()
			->route('admin.presensi.guru', $guruId)
			->with('success', 'Riwayat presensi untuk tanggal tersebut berhasil dihapus.');
	}

	public function adminExportPresensiSemua(Request $request)
	{
		$settings = $this->ensureSettings();
		$startDate = $request->input('tanggal_mulai');
		$endDate = $request->input('tanggal_selesai');

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
		$gurus = User::where('role', 'guru')->orderBy('name')->get();
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByUserDate = [];
		foreach (Presensi::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get() as $presensi) {
			$presensisByUserDate[$presensi->user_id][$this->normalizeDateKey($presensi->tanggal)] = $presensi;
		}

		$izinsByUserDate = [];
		foreach (PresensiIzin::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get() as $izin) {
			$izinsByUserDate[$izin->user_id][$this->normalizeDateKey($izin->tanggal)] = $izin;
		}

		$manualStatuses = $this->getManualStatusOverrides($gurus->pluck('id')->all(), $dateKeys);
		$fileName = 'rekap_presensi_bulanan_' . $year . '_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.xlsx';

		$rows = [];
		$header = ['Nama Guru', 'Kelas'];
		foreach ($days as $day) {
			$header[] = $day;
		}
		$rows[] = $header;

		foreach ($gurus as $guru) {
			$row = [$guru->name, $guru->kelas];
			foreach ($days as $day) {
				$date = Carbon::create($year, $month, $day);
				$dateKey = $date->toDateString();
				$row[] = $this->resolveAttendanceStatus(
					$date,
					$settings,
					$presensisByUserDate[$guru->id][$dateKey] ?? null,
					$izinsByUserDate[$guru->id][$dateKey] ?? null,
					$manualStatuses[$guru->id][$dateKey] ?? null,
				);
			}
			$rows[] = $row;
		}

		return Excel::download(new RekapBulananExport($rows), $fileName);
	}

	private function ensureSettings(): PresensiSetting
	{
		$settings = PresensiSetting::first();
		if ($settings) {
			return $settings;
		}

		return PresensiSetting::create([
			'jam_masuk_start' => '07:00:00',
			'jam_masuk_end' => '08:00:00',
			'jam_masuk_toleransi' => '07:15:00',
			'jam_pulang_start' => '13:00:00',
			'jam_pulang_end' => '14:30:00',
			'qr_text' => env('PRESENSI_QR_CODE', 'TKABA-PRESENSI'),
		]);
	}

	private function resolveAttendanceStatus(Carbon $date, ?PresensiSetting $settings, ?Presensi $presensi, ?PresensiIzin $izin, ?string $manualStatus = null): string
	{
		if ($manualStatus !== null && in_array($manualStatus, self::AVAILABLE_STATUSES, true)) {
			return $manualStatus;
		}

		if ($presensi && $presensi->jam_masuk && $settings && $settings->jam_masuk_end) {
			$jamMasuk = Carbon::parse($presensi->jam_masuk);
			$batasHadir = Carbon::parse($settings->jam_masuk_end);

			return $jamMasuk->lte($batasHadir) ? 'H' : 'T';
		}

		if ($izin) {
			return 'I';
		}

		return $this->shouldMarkAlpha($date) ? 'A' : '-';
	}

	private function getManualStatusOverrides(array $userIds, array $dateKeys): array
	{
		if (empty($userIds) || empty($dateKeys)) {
			return [];
		}

		$records = PresensiStatusOverride::whereIn('user_id', $userIds)
			->whereIn('tanggal', $dateKeys)
			->get();

		$overrides = [];
		foreach ($records as $record) {
			$overrides[$record->user_id][$this->normalizeDateKey($record->tanggal)] = $record->status;
		}

		return $overrides;
	}

	private function buildAttendanceRowsForUser(User $guru, ?PresensiSetting $settings)
	{
		$presensisByDate = Presensi::where('user_id', $guru->id)
			->orderByDesc('tanggal')
			->get()
			->keyBy(fn ($presensi) => $this->normalizeDateKey($presensi->tanggal));

		$izinsByDate = PresensiIzin::where('user_id', $guru->id)
			->get()
			->keyBy(fn ($izin) => $this->normalizeDateKey($izin->tanggal));

		$manualStatusesByDate = PresensiStatusOverride::where('user_id', $guru->id)
			->get()
			->mapWithKeys(fn ($override) => [$this->normalizeDateKey($override->tanggal) => $override->status]);

		$dateKeys = collect()
			->merge($presensisByDate->keys())
			->merge($izinsByDate->keys())
			->merge($manualStatusesByDate->keys())
			->unique()
			->sortDesc()
			->values();

		return $dateKeys->map(function (string $dateKey) use ($izinsByDate, $manualStatusesByDate, $presensisByDate, $settings) {
			$date = Carbon::parse($dateKey);
			$presensi = $presensisByDate->get($dateKey);
			$izin = $izinsByDate->get($dateKey);

			return [
				'date' => $date,
				'presensi' => $presensi,
				'izin' => $izin,
				'status' => $this->resolveAttendanceStatus(
					$date,
					$settings,
					$presensi,
					$izin,
					$manualStatusesByDate->get($dateKey),
				),
			];
		});
	}

	private function paginateCollection($items, Request $request, int $perPage): LengthAwarePaginator
	{
		$collection = collect($items);
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

		return new LengthAwarePaginator(
			$currentItems,
			$collection->count(),
			$perPage,
			$currentPage,
			[
				'path' => $request->url(),
				'query' => $request->query(),
			],
		);
	}

	private function normalizeDateKey($date): string
	{
		return $date instanceof Carbon
			? $date->toDateString()
			: Carbon::parse($date)->toDateString();
	}

	private function shouldMarkAlpha(Carbon $date): bool
	{
		return $date->copy()->startOfDay()->lt(Carbon::today())
			&& ! $date->isSunday()
			&& ! $this->isHariLiburNasional($date);
	}

	private function isHariLiburNasional(Carbon $date): bool
	{
		$dateKey = $date->toDateString();
		$holidayDates = $this->getHariLiburNasionalDates($date->year);

		return isset($holidayDates[$dateKey]);
	}

	private function getHariLiburNasionalDates(int $year): array
	{
		if (isset($this->holidayDateCache[$year])) {
			return $this->holidayDateCache[$year];
		}

		$cacheKey = "hari_libur_nasional_{$year}";
		$cached = Cache::get($cacheKey);

		if (! is_array($cached)) {
			$cached = $this->fetchHariLiburNasional($year);
		}

		$dateSet = [];
		foreach ($cached as $holiday) {
			$startDate = $holiday['start'] ?? null;
			if (! $startDate) {
				continue;
			}

			$dateSet[Carbon::parse($startDate)->toDateString()] = true;
		}

		return $this->holidayDateCache[$year] = $dateSet;
	}

	private function fetchHariLiburNasional(int $year): array
	{
		$apiKey = config('services.api_co_id.key');

		if (empty($apiKey)) {
			Log::warning('API_CO_ID_KEY belum diatur di .env');
			Cache::put("hari_libur_nasional_{$year}", [], now()->addDays(60));

			return [];
		}

		try {
			/** @var Response $response */
			$response = Http::withHeaders([
				'x-api-co-id' => $apiKey,
			])->timeout(10)->get('https://use.api.co.id/holidays/indonesia/', [
				'year' => $year,
			]);

			if (! $response->successful()) {
				Log::error('API Holiday gagal: HTTP ' . $response->status());
				Cache::put("hari_libur_nasional_{$year}", [], now()->addDays(60));

				return [];
			}

			$json = $response->json();

			if (! ($json['is_success'] ?? false) || empty($json['data'])) {
				Log::warning('API Holiday response tidak berhasil atau data kosong untuk tahun ' . $year);
				Cache::put("hari_libur_nasional_{$year}", [], now()->addDays(60));

				return [];
			}

			$allowedTypes = ['public holiday', 'national holiday', 'joint holiday'];
			$holidays = collect($json['data'])
				->filter(function ($item) use ($allowedTypes) {
					return in_array(strtolower($item['type'] ?? ''), $allowedTypes, true);
				})
				->map(function ($item) {
					return ['start' => $item['date'] ?? null];
				})
				->filter(fn ($item) => ! empty($item['start']))
				->values()
				->toArray();

			Cache::put("hari_libur_nasional_{$year}", $holidays, now()->addDays(60));

			return $holidays;
		} catch (\Exception $e) {
			Log::error('API Holiday error: ' . $e->getMessage());
			Cache::put("hari_libur_nasional_{$year}", [], now()->addDays(60));

			return [];
		}
	}
}