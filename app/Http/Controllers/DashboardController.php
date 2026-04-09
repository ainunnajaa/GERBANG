<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use App\Models\PresensiSetting;
use App\Models\PresensiStatusOverride;
use App\Models\User;
use App\Models\Berita;
use Carbon\Carbon;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    private const HOLIDAY_CACHE_MONTHS = 2;
    private const AVAILABLE_STATUSES = ['H', 'T', 'I', 'A', '-'];

    protected array $holidayDateSetCache = [];

    public function index(Request $request)
    {
        $role = $request->user()->role ?? 'wali_murid';
        $today = Carbon::today();

        $adminAttendance = $this->getAdminTodayAttendanceSummary();

        return match ($role) {
            'admin' => view('dashboard.admin', [
                'jumlahGuru' => User::where('role', 'guru')->count(),
                'jumlahWaliMurid' => User::where('role', 'wali_murid')->count(),
                'jumlahBerita' => Berita::count(),
                'jumlahPeriode' => PresensiPeriod::count(),
                'activePeriod' => PresensiPeriod::query()->active()->orderByDesc('start_date')->first(),
                'periodTypeOptions' => PresensiPeriod::TYPE_OPTIONS,
                'todayAttendanceSummary' => $adminAttendance,
                'hariLibur' => array_merge(
                    $this->getHariLiburNasional($today->year),
                    $this->getHariLiburNasional($today->year + 1)
                ),
            ]),
            'guru' => $this->guruDashboard($request),
            default => view('dashboard.wali_murid'),
        };
    }

    protected function getAdminTodayAttendanceSummary(): array
    {
        $today = Carbon::today();
        $activePeriod = PresensiPeriod::query()->active()->orderByDesc('start_date')->first();
        $guruIds = User::where('role', 'guru')->pluck('id');

        $summary = [
            'H' => 0,
            'T' => 0,
            'I' => 0,
            'A' => 0,
            'total' => $guruIds->count(),
            'isOperationalDay' => false,
        ];

        if (! $activePeriod || ! $activePeriod->isOperationalOn($today) || $summary['total'] === 0) {
            return $summary;
        }

        $summary['isOperationalDay'] = true;

        $presensiByUser = Presensi::whereDate('tanggal', $today->toDateString())
            ->whereIn('user_id', $guruIds)
            ->get()
            ->keyBy('user_id');

        $izinByUser = PresensiIzin::whereDate('tanggal', $today->toDateString())
            ->whereIn('user_id', $guruIds)
            ->get()
            ->keyBy('user_id');

        $overrideByUser = PresensiStatusOverride::whereDate('tanggal', $today->toDateString())
            ->whereIn('user_id', $guruIds)
            ->get()
            ->keyBy('user_id');

        foreach ($guruIds as $guruId) {
            $overrideStatus = $overrideByUser->get($guruId)?->status;
            if ($overrideStatus !== null && in_array($overrideStatus, ['H', 'T', 'I', 'A'], true)) {
                $summary[$overrideStatus]++;
                continue;
            }

            $presensi = $presensiByUser->get($guruId);
            if ($presensi && in_array($presensi->status, ['H', 'T', 'I', 'A'], true)) {
                $summary[$presensi->status]++;
                continue;
            }

            if ($izinByUser->has($guruId)) {
                $summary['I']++;
                continue;
            }

            $summary['A']++;
        }

        return $summary;
    }

    protected function guruDashboard(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();
        $activePeriod = PresensiPeriod::query()->active()->orderByDesc('start_date')->first();

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

        $jumlahHadir = 0;
        $jumlahTerlambat = 0;
        $jumlahIzin = 0;
        $jumlahTidakHadir = 0;
        $bulanOptions = [];
        $selectedMonthKey = null;
        $selectedMonthLabel = null;
        $weeklyLabels = [];
        $weeklyValues = [];

        if ($activePeriod) {
            $periodStart = Carbon::parse($activePeriod->start_date)->startOfDay();
            $periodEnd = Carbon::parse($activePeriod->end_date)->endOfDay();
            $effectiveEnd = $today->lte($periodEnd) ? $today->copy()->endOfDay() : $periodEnd->copy();

            $bulanOptions = $this->getMonthOptionsForPeriod($activePeriod);
            $selectedMonthKey = $this->resolveSelectedMonthKey($request->query('month_key'), $activePeriod, $today);
            $selectedMonthLabel = $bulanOptions[$selectedMonthKey] ?? null;

            if ($effectiveEnd->gte($periodStart)) {
                $dateRange = [$periodStart->toDateString(), $effectiveEnd->toDateString()];
                $presensisByDate = Presensi::where('user_id', $user->id)
                    ->whereBetween('tanggal', $dateRange)
                    ->get()
                    ->keyBy(fn ($presensi) => Carbon::parse($presensi->tanggal)->toDateString());

                $izinByDate = PresensiIzin::where('user_id', $user->id)
                    ->whereBetween('tanggal', $dateRange)
                    ->get()
                    ->keyBy(fn ($izin) => Carbon::parse($izin->tanggal)->toDateString());

                $overrideByDate = PresensiStatusOverride::where('user_id', $user->id)
                    ->whereBetween('tanggal', $dateRange)
                    ->get()
                    ->mapWithKeys(fn ($override) => [Carbon::parse($override->tanggal)->toDateString() => $override->status]);

                $operationalDates = $this->getOperationalDatesForPeriod($activePeriod, $periodStart, $effectiveEnd);
                $statusByDate = [];

                foreach ($operationalDates as $date) {
                    $dateKey = $date->toDateString();
                    $status = $this->resolveDashboardAttendanceStatus(
                        $date,
                        $settings,
                        $presensisByDate->get($dateKey),
                        $izinByDate->get($dateKey),
                        $overrideByDate->get($dateKey),
                    );

                    $statusByDate[$dateKey] = $status;

                    match ($status) {
                        'H' => $jumlahHadir++,
                        'T' => $jumlahTerlambat++,
                        'I' => $jumlahIzin++,
                        'A' => $jumlahTidakHadir++,
                        default => null,
                    };
                }

                if ($selectedMonthKey) {
                    $monthDate = Carbon::createFromFormat('!Y-m', $selectedMonthKey)->startOfMonth();
                    $monthStart = $monthDate->copy()->startOfMonth()->lt($periodStart) ? $periodStart->copy() : $monthDate->copy()->startOfMonth();
                    $monthEnd = $monthDate->copy()->endOfMonth()->gt($periodEnd) ? $periodEnd->copy() : $monthDate->copy()->endOfMonth();
                    $totalWeeks = 1;
                    for ($day = 1; $day <= $monthDate->daysInMonth; $day++) {
                        $weekOfMonth = Carbon::create($monthDate->year, $monthDate->month, $day)->weekOfMonth;
                        if ($weekOfMonth > $totalWeeks) {
                            $totalWeeks = $weekOfMonth;
                        }
                    }
                    $weeklyCounts = array_fill(1, $totalWeeks, 0);
                    $chartDateRange = [$monthStart->toDateString(), $monthEnd->toDateString()];
                    $chartPresensisByDate = Presensi::where('user_id', $user->id)
                        ->whereBetween('tanggal', $chartDateRange)
                        ->get()
                        ->keyBy(fn ($presensi) => Carbon::parse($presensi->tanggal)->toDateString());

                    $chartIzinByDate = PresensiIzin::where('user_id', $user->id)
                        ->whereBetween('tanggal', $chartDateRange)
                        ->get()
                        ->keyBy(fn ($izin) => Carbon::parse($izin->tanggal)->toDateString());

                    $chartOverrideByDate = PresensiStatusOverride::where('user_id', $user->id)
                        ->whereBetween('tanggal', $chartDateRange)
                        ->get()
                        ->mapWithKeys(fn ($override) => [Carbon::parse($override->tanggal)->toDateString() => $override->status]);

                    $chartOperationalDates = $this->getOperationalDatesForPeriod($activePeriod, $monthStart, $monthEnd);

                    foreach ($chartOperationalDates as $date) {
                        $dateKey = $date->toDateString();
                        $chartStatus = $this->resolveDashboardAttendanceStatus(
                            $date,
                            $settings,
                            $chartPresensisByDate->get($dateKey),
                            $chartIzinByDate->get($dateKey),
                            $chartOverrideByDate->get($dateKey),
                        );

                        if (! in_array($chartStatus, ['H', 'T'], true)) {
                            continue;
                        }

                        $week = $date->weekOfMonth;
                        $weeklyCounts[$week]++;
                    }

                    for ($w = 1; $w <= $totalWeeks; $w++) {
                        $weeklyLabels[] = 'Minggu ' . $w;
                        $weeklyValues[] = $weeklyCounts[$w];
                    }
                }
            }
        }

        $beritas = \App\Models\Berita::orderByDesc('tanggal_berita')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Daftar guru (max 4 for preview card)
        $daftarGuru = User::where('role', 'guru')
            ->select('id', 'name', 'kelas', 'profile_photo_path')
            ->orderBy('name')
            ->limit(4)
            ->get();
        $totalGuru = User::where('role', 'guru')->count();

        // Instagram content from admin (SchoolContent)
        $instagramContents = \App\Models\SchoolContent::where('platform', 'instagram')
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        // Hari Libur Nasional Indonesia (tahun ini + tahun depan agar navigasi kalender lancar)
        $hariLibur = array_merge(
            $this->getHariLiburNasional($today->year),
            $this->getHariLiburNasional($today->year + 1)
        );

        return view('dashboard.guru', [
            'activePeriod' => $activePeriod,
            'jumlahHadir' => $jumlahHadir,
            'jumlahIzin' => $jumlahIzin,
            'jumlahTerlambat' => $jumlahTerlambat,
            'jumlahTidakHadir' => $jumlahTidakHadir,
            'bulanOptions' => $bulanOptions,
            'selectedMonthKey' => $selectedMonthKey,
            'selectedMonthLabel' => $selectedMonthLabel,
            'weeklyLabels' => $weeklyLabels,
            'weeklyValues' => $weeklyValues,
            'beritas' => $beritas,
            'daftarGuru' => $daftarGuru,
            'totalGuru' => $totalGuru,
            'instagramContents' => $instagramContents,
            'hariLibur' => $hariLibur,
        ]);
    }

    protected function getMonthOptionsForPeriod(PresensiPeriod $period): array
    {
        $options = [];
        $cursor = Carbon::parse($period->start_date)->startOfMonth();
        $end = Carbon::parse($period->end_date)->startOfMonth();

        while ($cursor->lte($end)) {
            $options[$cursor->format('Y-m')] = $cursor->translatedFormat('F Y');
            $cursor->addMonthNoOverflow();
        }

        return $options;
    }

    protected function resolveSelectedMonthKey(?string $requestedMonthKey, PresensiPeriod $period, Carbon $today): string
    {
        $options = $this->getMonthOptionsForPeriod($period);

        if ($requestedMonthKey && array_key_exists($requestedMonthKey, $options)) {
            return $requestedMonthKey;
        }

        $todayKey = $today->format('Y-m');
        if (array_key_exists($todayKey, $options)) {
            return $todayKey;
        }

        $periodStart = Carbon::parse($period->start_date);

        return $today->lt($periodStart)
            ? array_key_first($options)
            : array_key_last($options);
    }

    protected function getOperationalDatesForPeriod(PresensiPeriod $period, Carbon $rangeStart, Carbon $rangeEnd)
    {
        $dates = collect();
        $periodStart = Carbon::parse($period->start_date)->startOfDay();
        $periodEnd = Carbon::parse($period->end_date)->startOfDay();
        $cursor = $rangeStart->copy()->startOfDay()->lt($periodStart) ? $periodStart : $rangeStart->copy()->startOfDay();
        $end = $rangeEnd->copy()->startOfDay()->gt($periodEnd) ? $periodEnd : $rangeEnd->copy()->startOfDay();

        while ($cursor->lte($end)) {
            if ($period->isOperationalOn($cursor) && ! $this->isHariLiburNasionalDate($cursor)) {
                $dates->push($cursor->copy());
            }

            $cursor->addDay();
        }

        return $dates;
    }

    protected function resolveDashboardAttendanceStatus(Carbon $date, ?PresensiSetting $settings, ?Presensi $presensi, ?PresensiIzin $izin, ?string $manualStatus = null): string
    {
        if ($manualStatus !== null && in_array($manualStatus, self::AVAILABLE_STATUSES, true)) {
            return $manualStatus;
        }

        if ($presensi && $presensi->jam_masuk) {
            if (! $settings || ! $settings->jam_masuk_end) {
                return 'H';
            }

            $jamMasuk = Carbon::parse($presensi->jam_masuk);
            $batasHadir = Carbon::parse($settings->jam_masuk_end);

            return $jamMasuk->lte($batasHadir) ? 'H' : 'T';
        }

        if ($izin) {
            return 'I';
        }

        return $date->copy()->startOfDay()->lt(Carbon::today()) ? 'A' : '-';
    }

    protected function isHariLiburNasionalDate(Carbon $date): bool
    {
        $year = $date->year;

        if (! isset($this->holidayDateSetCache[$year])) {
            $this->holidayDateSetCache[$year] = collect($this->getHariLiburNasional($year))
                ->pluck('start')
                ->filter()
                ->map(fn ($holidayDate) => Carbon::parse($holidayDate)->toDateString())
                ->flip()
                ->all();
        }

        return isset($this->holidayDateSetCache[$year][$date->toDateString()]);
    }

    /**
     * Ambil data hari libur nasional Indonesia dari API api.co.id.
     * Data di-cache selama 2 bulan agar sangat hemat limit API.
     */
    protected function getHariLiburNasional(int $year): array
    {
        $globalCacheKey = 'hari_libur_nasional_global';
        $globalHolidays = Cache::get($globalCacheKey);

        // Single API fetch for all holiday consumers, cached for 2 months.
        if (! is_array($globalHolidays)) {
            $globalHolidays = $this->fetchHariLiburNasionalGlobal();
            Cache::put($globalCacheKey, $globalHolidays, now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));
        }

        return collect($globalHolidays)
            ->filter(function ($holiday) use ($year) {
                $start = (string) ($holiday['start'] ?? '');

                return str_starts_with($start, (string) $year . '-');
            })
            ->values()
            ->toArray();
    }

    protected function fetchHariLiburNasionalGlobal(): array
    {
        $globalCacheKey = 'hari_libur_nasional_global';

        $apiKey = config('services.api_co_id.key');

        if (empty($apiKey)) {
            Log::warning('API_CO_ID_KEY belum diatur di .env');
            Cache::put($globalCacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

            return [];
        }

        try {
			/** @var Response $response */
            $response = Http::withHeaders([
                'x-api-co-id' => $apiKey,
            ])->timeout(10)->get('https://use.api.co.id/holidays/indonesia/');

            if (! $response->successful()) {
                Log::error('API Holiday gagal: HTTP ' . $response->status());
                Cache::put($globalCacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

                return [];
            }

            $json = $response->json();

            // Jika respon API sukses tapi datanya kosong (seperti kasus 2027)
            if (! ($json['is_success'] ?? false) || empty($json['data'])) {
                Log::warning('API Holiday response tidak berhasil atau data kosong.');

                Cache::put($globalCacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));
                return [];
            }

            // Filter: hanya Public Holiday, National Holiday, Joint Holiday
            $allowedTypes = ['public holiday', 'national holiday', 'joint holiday'];

            $holidays = collect($json['data'])
                ->filter(function ($item) use ($allowedTypes) {
                    return in_array(strtolower($item['type'] ?? ''), $allowedTypes);
                })
                ->map(function ($item) {
                    $name = $this->translateHolidayName($item['name'] ?? 'Hari Libur');
                    $color = $this->getHolidayColor($item['type'] ?? '', $item['name'] ?? '');

                    return [
                        'title' => $name,
                        'start' => $item['date'],
                        'color' => $color,
                        'allDay' => true,
                        'display' => 'block',
                    ];
                })
                ->values()
                ->toArray();

            return $holidays;

        } catch (\Exception $e) {
            Log::error('API Holiday error: ' . $e->getMessage());
            Cache::put($globalCacheKey, [], now()->addMonthsNoOverflow(self::HOLIDAY_CACHE_MONTHS));

            return [];
        }
    }

    /**
     * Terjemahkan nama hari libur dari bahasa Inggris ke bahasa Indonesia.
     */
    protected function translateHolidayName(string $name): string
    {
        // Mapping langsung nama-nama yang sering muncul dari API
        $translations = [
            "New Year's Day" => "Tahun Baru Masehi",
            "Chinese New Year's Day" => "Tahun Baru Imlek",
            "Chinese New Year Joint Holiday" => "Cuti Bersama Imlek",
            "Good Friday" => "Wafat Isa Almasih",
            "Easter Sunday" => "Hari Paskah",
            "International Labor Day" => "Hari Buruh Internasional",
            "Ascension Day of Jesus Christ" => "Kenaikan Isa Almasih",
            "Joint Holiday after Ascension Day" => "Cuti Bersama Kenaikan Isa Almasih",
            "Christmas Day" => "Hari Natal",
            "Christmas Eve Joint Holiday" => "Cuti Bersama Natal",
            "Pancasila Day" => "Hari Lahir Pancasila",
            "Indonesian Independence Day" => "Hari Kemerdekaan RI",
            "New Year's Eve" => "Malam Tahun Baru",
        ];

        if (isset($translations[$name])) {
            return $translations[$name];
        }

        $patterns = [
            '/Ascension of the Prophet Muhammad/i' => 'Isra Mi\'raj Nabi Muhammad SAW',
            '/Idul Fitri Joint Holiday/i' => 'Cuti Bersama Idul Fitri',
            '/Idul Fitri Holiday/i' => 'Hari Raya Idul Fitri',
            '/Idul Fitri/i' => 'Hari Raya Idul Fitri',
            '/Idul Adha/i' => 'Hari Raya Idul Adha',
            '/Joint Holiday for Idul Adha/i' => 'Cuti Bersama Idul Adha',
            '/Maulid Nabi Muhammad/i' => 'Maulid Nabi Muhammad SAW',
            '/Muharram.*Islamic New Year/i' => 'Tahun Baru Islam (1 Muharram)',
            '/Waisak.*Buddha/i' => 'Hari Raya Waisak',
            '/Bali.*Silence.*Nyepi/i' => 'Hari Raya Nyepi',
            '/Joint Holiday for Bali.*Nyepi/i' => 'Cuti Bersama Nyepi',
            '/Nuzulul Quran/i' => 'Nuzulul Quran',
            '/Ramadan Start/i' => 'Awal Ramadhan',
        ];

        foreach ($patterns as $pattern => $translation) {
            if (preg_match($pattern, $name)) {
                if (str_contains($name, 'Tentative')) {
                    return $translation;
                }
                return $translation;
            }
        }

        return $name;
    }

    /**
     * Tentukan warna event berdasarkan tipe dan nama hari libur.
     */
    protected function getHolidayColor(string $type, string $name): string
    {
        $nameLower = strtolower($name);

        if (strtolower($type) === 'joint holiday' || str_contains($nameLower, 'joint holiday') || str_contains($nameLower, 'cuti bersama')) {
            return '#3B82F6';
        }

        $islamKeywords = ['idul fitri', 'idul adha', 'isra', 'ascension of the prophet', 'mi\'raj', 'maulid', 'nabi muhammad', 'islamic new year', 'muharram', 'nuzulul', 'quran', 'ramadan'];
        foreach ($islamKeywords as $keyword) {
            if (str_contains($nameLower, strtolower($keyword))) {
                return '#10B981';
            }
        }

        $otherReligionKeywords = ['nyepi', 'silence', 'waisak', 'buddha', 'christmas', 'natal', 'good friday', 'easter', 'ascension day of jesus', 'paskah'];
        foreach ($otherReligionKeywords as $keyword) {
            if (str_contains($nameLower, strtolower($keyword))) {
                return '#8B5CF6';
            }
        }

        if (str_contains($nameLower, 'chinese new year') || str_contains($nameLower, 'imlek')) {
            return '#F59E0B';
        }

        return '#EF4444';
    }
}