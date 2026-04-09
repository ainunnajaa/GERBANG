<?php

namespace App\Http\Controllers\Presensi;

use App\Exports\GuruRiwayatExport;
use App\Exports\RekapBulananExport;
use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
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
	private const HOLIDAY_CACHE_MONTHS = 2;

	protected array $holidayDateCache = [];
	protected $presensiPeriods = null;

	public function guruKehadiranPeriods()
	{
		$periods = $this->getPresensiPeriods();

		return view('guru.riwayat_kehadiran.periode', [
			'periods' => $periods,
		]);
	}

	public function guruKehadiran(Request $request)
	{
		$settings = $this->ensureSettings();
		$user = Auth::user();
		$selectedPeriod = $this->getSelectedGuruPeriod($request);
		if (! $selectedPeriod) {
			return redirect()->route('guru.kehadiran.periods')->with('error', 'Pilih periode kehadiran terlebih dahulu.');
		}

		$monthOptions = $this->getMonthOptionsForPeriod($selectedPeriod);
		$selectedMonthKey = $this->resolveSelectedMonthKey($request->input('month_key'), $selectedPeriod);
		$monthRange = $this->getMonthDateRangeWithinPeriod($selectedMonthKey, $selectedPeriod);
		$month = (int) substr($selectedMonthKey, 5, 2);
		$year = (int) substr($selectedMonthKey, 0, 4);
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
			if ($date->weekOfMonth === $week && $date->betweenIncluded($monthRange['start'], $monthRange['end'])) {
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
			->map(function (Carbon $date) use ($izinsByDate, $manualStatuses, $presensisByDate, $selectedPeriod, $settings, $user) {
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
						$selectedPeriod,
					),
				];
			});

		return view('guru.riwayat_kehadiran.kehadiran', [
			'attendanceRows' => $attendanceRows,
			'settings' => $settings,
			'startDate' => $startDate,
			'endDate' => $endDate,
			'month' => $month,
			'year' => $year,
			'week' => $week,
			'maxWeek' => $maxWeek,
			'selectedPeriod' => $selectedPeriod,
			'monthOptions' => $monthOptions,
			'selectedMonthKey' => $selectedMonthKey,
		]);
	}

	public function guruKehadiranBulanan(Request $request)
	{
		$settings = $this->ensureSettings();
		$user = Auth::user();
		$selectedPeriod = $this->getSelectedGuruPeriod($request);

		if (! $selectedPeriod) {
			return redirect()->route('guru.kehadiran.periods')->with('error', 'Pilih periode kehadiran terlebih dahulu.');
		}

		$monthOptions = $this->getMonthOptionsForPeriod($selectedPeriod);
		$selectedMonthKey = $this->resolveSelectedMonthKey($request->input('month_key'), $selectedPeriod);
		$month = (int) substr($selectedMonthKey, 5, 2);
		$year = (int) substr($selectedMonthKey, 0, 4);

		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByDate = Presensi::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->whereBetween('tanggal', [Carbon::parse($selectedPeriod->start_date)->toDateString(), Carbon::parse($selectedPeriod->end_date)->toDateString()])
			->get()
			->keyBy(fn ($presensi) => $this->normalizeDateKey($presensi->tanggal));

		$izinsByDate = PresensiIzin::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->whereBetween('tanggal', [Carbon::parse($selectedPeriod->start_date)->toDateString(), Carbon::parse($selectedPeriod->end_date)->toDateString()])
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
				$selectedPeriod,
			);
		}

		return view('guru.riwayat_kehadiran.kehadiran_bulanan', [
			'settings' => $settings,
			'days' => $days,
			'month' => $month,
			'year' => $year,
			'matrix' => $matrix,
			'user' => $user,
			'selectedPeriod' => $selectedPeriod,
			'monthOptions' => $monthOptions,
			'selectedMonthKey' => $selectedMonthKey,
		]);
	}

	public function guruExportKehadiranBulanan(Request $request)
	{
		$settings = $this->ensureSettings();
		$user = Auth::user();
		$selectedPeriod = $this->getSelectedGuruPeriod($request);

		if (! $selectedPeriod) {
			return back()->with('error', 'Pilih periode kehadiran terlebih dahulu sebelum export.');
		}

		$selectedMonthKey = $this->resolveSelectedMonthKey($request->input('month_key'), $selectedPeriod);
		$month = (int) substr($selectedMonthKey, 5, 2);
		$year = (int) substr($selectedMonthKey, 0, 4);

		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByDate = Presensi::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->whereBetween('tanggal', [Carbon::parse($selectedPeriod->start_date)->toDateString(), Carbon::parse($selectedPeriod->end_date)->toDateString()])
			->get()
			->keyBy(fn ($presensi) => $this->normalizeDateKey($presensi->tanggal));

		$izinsByDate = PresensiIzin::where('user_id', $user->id)
			->whereYear('tanggal', $year)
			->whereMonth('tanggal', $month)
			->whereBetween('tanggal', [Carbon::parse($selectedPeriod->start_date)->toDateString(), Carbon::parse($selectedPeriod->end_date)->toDateString()])
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
				$selectedPeriod,
			);
		}
		$rows[] = $row;

		$fileName = 'rekap_presensi_saya_' . $year . '_' . str_pad((string) $month, 2, '0', STR_PAD_LEFT) . '.xlsx';

		return Excel::download(new RekapBulananExport($rows), $fileName);
	}

	private function getSelectedGuruPeriod(Request $request): ?PresensiPeriod
	{
		$periodId = $request->integer('period_id');

		if (! $periodId) {
			return null;
		}

		return $this->getPresensiPeriods()->firstWhere('id', $periodId);
	}

	public function adminRiwayat()
	{
		$periods = $this->getPresensiPeriods();

		return view('admin.riwayat.riwayat_periode', [
			'periods' => $periods,
		]);
	}

	public function adminRiwayatPeriode(PresensiPeriod $period)
	{
		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		return view('admin.riwayat.riwayat_presensi', [
			'gurus' => $gurus,
			'selectedPeriod' => $period,
		]);
	}

	public function adminRiwayatSemua(Request $request)
	{
		$settings = $this->ensureSettings();
		$selectedPeriod = $this->getSelectedAdminPeriod($request);
		$selectedDate = null;
		$selectedDateCarbon = null;

		if (! $selectedPeriod) {
			return redirect()->route('admin.riwayat')->with('error', 'Pilih periode presensi terlebih dahulu dari halaman riwayat periode.');
		}

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->paginate(50)
			->appends($request->query());

		$selectedDateCarbon = $this->resolveSelectedDateWithinPeriod($request->input('tanggal'), $selectedPeriod);
		$selectedDate = $selectedDateCarbon->toDateString();

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

		$attendanceRows = $gurus->getCollection()->map(function ($guru) use ($izinsByUser, $manualStatuses, $presensisByUser, $selectedDateCarbon, $selectedPeriod, $settings) {
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
					$selectedPeriod,
				),
			];
		});

		return view('admin.riwayat.riwayat_presensi_blade', [
			'attendanceRows' => $attendanceRows,
			'gurus' => $gurus,
			'settings' => $settings,
			'selectedDate' => $selectedDate,
			'selectedPeriod' => $selectedPeriod,
			'dateMin' => Carbon::parse($selectedPeriod->start_date)->format('Y-m-d'),
			'dateMax' => Carbon::parse($selectedPeriod->end_date)->format('Y-m-d'),
		]);
	}

	public function adminRiwayatBulanan(Request $request)
	{
		$settings = $this->ensureSettings();
		$selectedPeriod = $this->getSelectedAdminPeriod($request);

		if (! $selectedPeriod) {
			return redirect()->route('admin.riwayat')->with('error', 'Pilih periode presensi terlebih dahulu dari halaman riwayat periode.');
		}

		$monthOptions = $selectedPeriod ? $this->getMonthOptionsForPeriod($selectedPeriod) : [];
		$selectedMonthKey = $selectedPeriod ? $this->resolveSelectedMonthKey($request->input('month_key'), $selectedPeriod) : null;
		$monthRange = $selectedPeriod && $selectedMonthKey ? $this->getMonthDateRangeWithinPeriod($selectedMonthKey, $selectedPeriod) : null;
		$month = $selectedMonthKey ? (int) substr($selectedMonthKey, 5, 2) : (int) now()->month;
		$year = $selectedMonthKey ? (int) substr($selectedMonthKey, 0, 4) : (int) now()->year;

		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);
		$gurus = User::where('role', 'guru')->orderBy('name')->get();
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByUserDate = [];
		$presensiQuery = Presensi::whereYear('tanggal', $year)->whereMonth('tanggal', $month);
		$izinQuery = PresensiIzin::whereYear('tanggal', $year)->whereMonth('tanggal', $month);

		if ($selectedPeriod) {
			$presensiQuery->whereBetween('tanggal', [Carbon::parse($selectedPeriod->start_date)->toDateString(), Carbon::parse($selectedPeriod->end_date)->toDateString()]);
			$izinQuery->whereBetween('tanggal', [Carbon::parse($selectedPeriod->start_date)->toDateString(), Carbon::parse($selectedPeriod->end_date)->toDateString()]);
		}

		foreach ($presensiQuery->get() as $presensi) {
			$presensisByUserDate[$presensi->user_id][$this->normalizeDateKey($presensi->tanggal)] = $presensi;
		}

		$izinsByUserDate = [];
		foreach ($izinQuery->get() as $izin) {
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
					$selectedPeriod,
				);
			}
		}

		return view('admin.riwayat.riwayat_bulanan', [
			'settings' => $settings,
			'gurus' => $gurus,
			'days' => $days,
			'month' => $month,
			'year' => $year,
			'matrix' => $matrix,
			'selectedPeriod' => $selectedPeriod,
			'monthOptions' => $monthOptions,
			'selectedMonthKey' => $selectedMonthKey,
			'monthStartDate' => $monthRange['start'] ?? Carbon::create($year, $month, 1)->startOfMonth(),
			'monthEndDate' => $monthRange['end'] ?? Carbon::create($year, $month, 1)->endOfMonth(),
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

	public function adminBulkUpdateStatus(Request $request)
	{
		$selectedPeriod = $this->getSelectedAdminPeriod($request);

		if (! $selectedPeriod) {
			return redirect()->route('admin.riwayat')->with('error', 'Pilih periode presensi terlebih dahulu dari halaman riwayat periode.');
		}

		$validated = $request->validate([
			'period_id' => ['required', 'integer'],
			'month_key' => ['nullable', 'string'],
			'apply_scope' => ['required', 'in:all,selected'],
			'user_ids' => ['nullable', 'array'],
			'user_ids.*' => ['integer', 'exists:users,id'],
			'tanggal_mulai' => ['required', 'date'],
			'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
			'status' => ['required', 'in:' . implode(',', self::AVAILABLE_STATUSES)],
		]);

		$selectedMonthKey = $this->resolveSelectedMonthKey($validated['month_key'] ?? null, $selectedPeriod);
		$monthRange = $this->getMonthDateRangeWithinPeriod($selectedMonthKey, $selectedPeriod);
		$rangeStart = Carbon::parse($validated['tanggal_mulai'])->startOfDay();
		$rangeEnd = Carbon::parse($validated['tanggal_selesai'])->startOfDay();
		$allowedStart = $monthRange['start']->copy()->startOfDay();
		$allowedEnd = $monthRange['end']->copy()->startOfDay();

		if ($rangeStart->lt($allowedStart) || $rangeEnd->gt($allowedEnd)) {
			return back()
				->withErrors(['tanggal_mulai' => 'Rentang tanggal harus berada di dalam bulan periode yang sedang ditampilkan.'])
				->withInput();
		}

		$userIds = $validated['apply_scope'] === 'all'
			? User::where('role', 'guru')->pluck('id')->all()
			: User::where('role', 'guru')->whereIn('id', $validated['user_ids'] ?? [])->pluck('id')->all();

		if (empty($userIds)) {
			return back()
				->withErrors(['user_ids' => 'Pilih minimal satu guru atau gunakan opsi semua guru.'])
				->withInput();
		}

		$updatedCount = 0;
		$skippedCount = 0;
		$cursor = $rangeStart->copy();

		while ($cursor->lte($rangeEnd)) {
			$canApplyStatus = $this->canApplyBulkStatusOnDate($cursor, $selectedPeriod);

			foreach ($userIds as $userId) {
				if (! $canApplyStatus) {
					PresensiStatusOverride::where('user_id', $userId)
						->whereDate('tanggal', $cursor->toDateString())
						->delete();

					$skippedCount++;
					continue;
				}

				PresensiStatusOverride::updateOrCreate(
					[
						'user_id' => $userId,
						'tanggal' => $cursor->toDateString(),
					],
					[
						'status' => $validated['status'],
						'updated_by' => Auth::id(),
					],
				);

				$updatedCount++;
			}

			$cursor->addDay();
		}

		$message = 'Bulk update status berhasil diterapkan ke ' . $updatedCount . ' entri kehadiran.';

		if ($skippedCount > 0) {
			$message .= ' ' . $skippedCount . ' entri dilewati karena hari Minggu, hari non-operasional, atau hari libur nasional.';
		}

		return redirect()->route('admin.presensi.bulanan', [
			'period_id' => $selectedPeriod->id,
			'month_key' => $selectedMonthKey,
		])->with('success', $message);
	}

	public function adminPresensiGuru(Request $request, User $guru)
	{
		$settings = $this->ensureSettings();
		$selectedPeriod = $this->getSelectedAdminPeriod($request);

		if (! $selectedPeriod) {
			return redirect()->route('admin.riwayat')->with('error', 'Pilih periode presensi terlebih dahulu dari halaman riwayat periode.');
		}

		$monthOptions = $this->getMonthOptionsForPeriod($selectedPeriod);
		$selectedMonthKey = $this->resolveSelectedMonthKeyForRequest($request, $selectedPeriod);
		$monthRange = $this->getMonthDateRangeWithinPeriod($selectedMonthKey, $selectedPeriod);

		$attendanceRows = $this->buildAttendanceRowsForUser($guru, $settings, $selectedPeriod)
			->filter(function (array $row) use ($monthRange) {
				$date = data_get($row, 'date');

				return $date instanceof Carbon
					&& $date->betweenIncluded($monthRange['start'], $monthRange['end']);
			})
			->sortBy(fn (array $row) => data_get($row, 'date')?->timestamp)
			->values();

		return view('admin.riwayat.presensi_guru', [
			'guru' => $guru,
			'attendanceRows' => $attendanceRows,
			'settings' => $settings,
			'selectedPeriod' => $selectedPeriod,
			'monthOptions' => $monthOptions,
			'selectedMonthKey' => $selectedMonthKey,
			'monthStartDate' => $monthRange['start'],
			'monthEndDate' => $monthRange['end'],
		]);
	}

	public function adminDownloadPresensiGuru(Request $request, User $guru)
	{
		$settings = $this->ensureSettings();
		$selectedPeriod = $this->getSelectedAdminPeriod($request);

		if (! $selectedPeriod) {
			return back()->with('error', 'Pilih periode presensi terlebih dahulu sebelum mengunduh riwayat.');
		}

		$attendanceRows = $this->buildAttendanceRowsForUser($guru, $settings, $selectedPeriod)->sortBy('date')->values();

		$sanitizedName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $guru->name ?? 'guru');
		$fileName = 'riwayat_presensi_' . $sanitizedName . '_' . Carbon::parse($selectedPeriod->start_date)->format('Ymd') . '_' . Carbon::parse($selectedPeriod->end_date)->format('Ymd') . '.xlsx';

		$rows = [];
		$rows[] = ['Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Jam Izin', 'Keterangan'];

		foreach ($attendanceRows as $row) {
			$item = data_get($row, 'presensi');
			$izin = data_get($row, 'izin');
			$date = Carbon::parse(data_get($row, 'date'));
			$rows[] = [
				$date->format('Y-m-d'),
				optional($item)->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') : '',
				optional($item)->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') : '',
				data_get($row, 'status'),
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

		return back()->with('success', 'Riwayat presensi untuk tanggal tersebut berhasil dihapus.');
	}

	public function adminExportPresensiSemua(Request $request)
	{
		$settings = $this->ensureSettings();
		$selectedPeriod = $this->getSelectedAdminPeriod($request);

		if (! $selectedPeriod) {
			return back()->with('error', 'Pilih periode presensi terlebih dahulu sebelum export riwayat.');
		}

		$startDate = $request->input('tanggal_mulai');
		$endDate = $request->input('tanggal_selesai');

		if (! $startDate && ! $endDate) {
			$startDate = Carbon::parse($selectedPeriod->start_date)->toDateString();
			$endDate = Carbon::parse($selectedPeriod->end_date)->toDateString();
		}

		$start = $this->resolveSelectedDateWithinPeriod($startDate, $selectedPeriod);
		$end = $this->resolveSelectedDateWithinPeriod($endDate, $selectedPeriod);
		$month = $start->month;
		$year = $start->year;
		$daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
		$days = range(1, $daysInMonth);
		$gurus = User::where('role', 'guru')->orderBy('name')->get();
		$dateKeys = collect($days)
			->map(fn (int $day) => Carbon::create($year, $month, $day)->toDateString())
			->all();

		$presensisByUserDate = [];
		foreach (Presensi::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])->get() as $presensi) {
			$presensisByUserDate[$presensi->user_id][$this->normalizeDateKey($presensi->tanggal)] = $presensi;
		}

		$izinsByUserDate = [];
		foreach (PresensiIzin::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])->get() as $izin) {
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
					$selectedPeriod,
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

	private function resolveAttendanceStatus(Carbon $date, ?PresensiSetting $settings, ?Presensi $presensi, ?PresensiIzin $izin, ?string $manualStatus = null, ?PresensiPeriod $selectedPeriod = null): string
	{
		if ($selectedPeriod && ! $selectedPeriod->includesDate($date)) {
			return '-';
		}

		$periodForDate = $selectedPeriod ?? $this->getPresensiPeriodForDate($date);
		if (! $periodForDate || ! $this->canApplyBulkStatusOnDate($date, $periodForDate)) {
			return '-';
		}

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

		return $this->shouldMarkAlpha($date, $periodForDate) ? 'A' : '-';
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

	private function buildAttendanceRowsForUser(User $guru, ?PresensiSetting $settings, ?PresensiPeriod $selectedPeriod = null)
	{
		$presensiQuery = Presensi::where('user_id', $guru->id);
		$izinQuery = PresensiIzin::where('user_id', $guru->id);
		$overrideQuery = PresensiStatusOverride::where('user_id', $guru->id);

		if ($selectedPeriod) {
			$dateRange = [Carbon::parse($selectedPeriod->start_date)->toDateString(), Carbon::parse($selectedPeriod->end_date)->toDateString()];
			$presensiQuery->whereBetween('tanggal', $dateRange);
			$izinQuery->whereBetween('tanggal', $dateRange);
			$overrideQuery->whereBetween('tanggal', $dateRange);
		}

		$presensisByDate = $presensiQuery
			->orderByDesc('tanggal')
			->get()
			->keyBy(fn ($presensi) => $this->normalizeDateKey($presensi->tanggal));

		$izinsByDate = $izinQuery
			->get()
			->keyBy(fn ($izin) => $this->normalizeDateKey($izin->tanggal));

		$manualStatusesByDate = $overrideQuery
			->get()
			->mapWithKeys(fn ($override) => [$this->normalizeDateKey($override->tanggal) => $override->status]);

		$periodDateKeys = $selectedPeriod
			? collect($this->getOperationalDateKeysForPeriod($selectedPeriod))
			: collect();

		$dateKeys = collect()
			->merge($periodDateKeys)
			->merge($presensisByDate->keys())
			->merge($izinsByDate->keys())
			->merge($manualStatusesByDate->keys())
			->unique()
			->filter(function (string $dateKey) use ($selectedPeriod) {
				if (! $selectedPeriod) {
					return true;
				}

				return $selectedPeriod->includesDate(Carbon::parse($dateKey));
			})
			->sortDesc()
			->values();

		return $dateKeys->map(function (string $dateKey) use ($izinsByDate, $manualStatusesByDate, $presensisByDate, $selectedPeriod, $settings) {
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
					$selectedPeriod,
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

	private function shouldMarkAlpha(Carbon $date, ?PresensiPeriod $selectedPeriod = null): bool
	{
		$period = $selectedPeriod;

		if ($period && ! $period->includesDate($date)) {
			return false;
		}

		$period ??= $this->getPresensiPeriodForDate($date);

		return $date->copy()->startOfDay()->lt(Carbon::today())
			&& $period !== null
			&& $period->isOperationalOn($date)
			&& ! $this->isHariLiburNasional($date);
	}

	private function canApplyBulkStatusOnDate(Carbon $date, PresensiPeriod $period): bool
	{
		return $period->includesDate($date)
			&& ! $date->isSunday()
			&& $period->isOperationalOn($date)
			&& ! $this->isHariLiburNasional($date);
	}

	private function getSelectedAdminPeriod(Request $request): ?PresensiPeriod
	{
		$periodId = $request->integer('period_id');

		if (! $periodId) {
			return null;
		}

		return $this->getPresensiPeriods()->firstWhere('id', $periodId);
	}

	private function resolveSelectedDateWithinPeriod(?string $requestedDate, PresensiPeriod $period): Carbon
	{
		if ($requestedDate) {
			$date = Carbon::parse($requestedDate);
		} else {
			$today = Carbon::today();
			$date = $period->includesDate($today) ? $today : Carbon::parse($period->start_date);
		}

		$startDate = Carbon::parse($period->start_date)->startOfDay();
		$endDate = Carbon::parse($period->end_date)->endOfDay();

		if ($date->lt($startDate)) {
			return $startDate;
		}

		if ($date->gt($endDate)) {
			return $endDate;
		}

		return $date;
	}

	private function getMonthOptionsForPeriod(PresensiPeriod $period): array
	{
		$options = [];
		$cursor = Carbon::parse($period->start_date)->startOfMonth();
		$end = Carbon::parse($period->end_date)->startOfMonth();

		while ($cursor->lte($end)) {
			$key = $cursor->format('Y-m');
			$options[$key] = $cursor->translatedFormat('F Y');
			$cursor->addMonthNoOverflow();
		}

		return $options;
	}

	private function resolveSelectedMonthKey(?string $requestedMonthKey, PresensiPeriod $period): string
	{
		$options = $this->getMonthOptionsForPeriod($period);
		$todayKey = Carbon::today()->format('Y-m');

		if ($requestedMonthKey && array_key_exists($requestedMonthKey, $options)) {
			return $requestedMonthKey;
		}

		if (array_key_exists($todayKey, $options)) {
			return $todayKey;
		}

		return array_key_last($options);
	}

	private function resolveSelectedMonthKeyForRequest(Request $request, PresensiPeriod $period): string
	{
		return $this->resolveSelectedMonthKey($request->input('month_key'), $period);
	}

	private function paginateMonthOptions(array $monthOptions, string $selectedMonthKey, Request $request): LengthAwarePaginator
	{
		$monthKeys = array_keys($monthOptions);
		$currentPage = array_search($selectedMonthKey, $monthKeys, true);
		$currentPage = $currentPage === false ? 1 : $currentPage + 1;

		return new LengthAwarePaginator(
			[$selectedMonthKey],
			count($monthKeys),
			1,
			$currentPage,
			[
				'path' => $request->url(),
				'query' => collect($request->query())
					->except(['page', 'month_key'])
					->all(),
			],
		);
	}

	private function getMonthDateRangeWithinPeriod(string $monthKey, PresensiPeriod $period): array
	{
		$monthDate = Carbon::createFromFormat('!Y-m', $monthKey)->startOfMonth();
		$periodStart = Carbon::parse($period->start_date)->startOfDay();
		$periodEnd = Carbon::parse($period->end_date)->endOfDay();
		$monthStart = $monthDate->copy()->startOfMonth();
		$monthEnd = $monthDate->copy()->endOfMonth();

		return [
			'start' => $monthStart->lt($periodStart) ? $periodStart : $monthStart,
			'end' => $monthEnd->gt($periodEnd) ? $periodEnd : $monthEnd,
		];
	}

	private function getOperationalDateKeysForPeriod(PresensiPeriod $period): array
	{
		$dateKeys = [];
		$cursor = Carbon::parse($period->start_date)->startOfDay();
		$end = Carbon::parse($period->end_date)->startOfDay();

		while ($cursor->lte($end)) {
			if ($period->isOperationalOn($cursor)) {
				$dateKeys[] = $cursor->toDateString();
			}

			$cursor->addDay();
		}

		return $dateKeys;
	}

	private function getPresensiPeriodForDate(Carbon $date): ?PresensiPeriod
	{
		return $this->getPresensiPeriods()->first(function (PresensiPeriod $period) use ($date) {
			return $period->includesDate($date);
		});
	}

	private function getPresensiPeriods()
	{
		if ($this->presensiPeriods !== null) {
			return $this->presensiPeriods;
		}

		return $this->presensiPeriods = PresensiPeriod::query()
			->orderByDesc('is_active')
			->orderByDesc('start_date')
			->get();
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

		$cached = $this->getHariLiburNasionalGlobal();

		$dateSet = [];
		foreach ($cached as $holiday) {
			$startDate = $holiday['start'] ?? null;
			if (! $startDate) {
				continue;
			}

			if (! str_starts_with((string) $startDate, (string) $year . '-')) {
				continue;
			}

			$dateSet[Carbon::parse($startDate)->toDateString()] = true;
		}

		return $this->holidayDateCache[$year] = $dateSet;
	}

	private function getHariLiburNasionalGlobal(): array
	{
		$cacheKey = 'hari_libur_nasional_global';
		$cached = Cache::get($cacheKey);

		if (is_array($cached)) {
			return $cached;
		}

		$cached = $this->fetchHariLiburNasionalGlobal();
		Cache::put($cacheKey, $cached, now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

		return $cached;
	}

	private function fetchHariLiburNasionalGlobal(): array
	{
		$cacheKey = 'hari_libur_nasional_global';
		$apiKey = config('services.api_co_id.key');

		if (empty($apiKey)) {
			Log::warning('API_CO_ID_KEY belum diatur di .env');
			Cache::put($cacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

			return [];
		}

		try {
			/** @var Response $response */
			$response = Http::withHeaders([
				'x-api-co-id' => $apiKey,
			])->timeout(10)->get('https://use.api.co.id/holidays/indonesia/');

			if (! $response->successful()) {
				Log::error('API Holiday gagal: HTTP ' . $response->status());
				Cache::put($cacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

				return [];
			}

			$json = $response->json();

			if (! ($json['is_success'] ?? false) || empty($json['data'])) {
				Log::warning('API Holiday response tidak berhasil atau data kosong.');
				Cache::put($cacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

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

			Cache::put($cacheKey, $holidays, now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

			return $holidays;
		} catch (\Exception $e) {
			Log::error('API Holiday error: ' . $e->getMessage());
			Cache::put($cacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

			return [];
		}
	}
}