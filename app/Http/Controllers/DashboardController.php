<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiSetting;
use App\Models\User;
use App\Models\Berita;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role ?? 'wali_murid';

        return match ($role) {
            'admin' => view('dashboard.admin', [
                'jumlahGuru' => User::where('role', 'guru')->count(),
                'jumlahBerita' => Berita::count(),
            ]),
            'guru' => $this->guruDashboard($request),
            default => view('dashboard.wali_murid'),
        };
    }

    protected function guruDashboard(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

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

        $presensis = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', '<=', $today)
            ->get();

        $tol = null;
        if ($settings) {
            $tol = $settings->jam_masuk_toleransi
                ? Carbon::parse($settings->jam_masuk_toleransi)
                : ($settings->jam_masuk_end ? Carbon::parse($settings->jam_masuk_end) : null);
        }

        // Hitung jumlah hadir: tanggal unik dengan jam masuk < toleransi
        $jumlahHadir = $presensis
            ->filter(function ($presensi) use ($tol) {
                if (! $presensi->jam_masuk || ! $tol) return false;
                $jamMasuk = Carbon::parse($presensi->jam_masuk);
                return $jamMasuk->lt($tol);
            })
            ->pluck('tanggal')
            ->unique()
            ->count();

        // Hitung jumlah terlambat: tanggal unik dengan jam masuk >= toleransi
        $jumlahTerlambat = $presensis
            ->filter(function ($presensi) use ($tol) {
                if (! $presensi->jam_masuk || ! $tol) return false;
                $jamMasuk = Carbon::parse($presensi->jam_masuk);
                return $jamMasuk->gte($tol);
            })
            ->pluck('tanggal')
            ->unique()
            ->count();

        // Hitung jumlah izin berdasarkan tabel presensi_izins
        $jumlahIzin = PresensiIzin::where('user_id', $user->id)
            ->whereDate('tanggal', '<=', $today)
            ->count();

        $jumlahTidakHadir = 0;
        $bulanOptions = [];
        $years = [];
        $selectedMonth = $request->query('bulan');
        $selectedYear = $request->query('tahun');
        $selectedMonthLabel = null;
        $weeklyLabels = [];
        $weeklyValues = [];

        if ($presensis->isNotEmpty()) {
            // Daftar bulan (1-12) dan tahun dinamis berdasarkan data presensi
            $bulanOptions = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];

            $firstDate = Carbon::parse($presensis->min('tanggal'));
            $firstYear = $firstDate->year;
            $lastYear = $today->year + 1;
            $years = range($firstYear, $lastYear);

            if (! $selectedMonth) {
                $selectedMonth = $today->month;
            }
            if (! $selectedYear) {
                $selectedYear = $today->year;
            }

            $selectedMonth = max(1, min(12, (int) $selectedMonth));
            if (! in_array((int) $selectedYear, $years, true)) {
                $selectedYear = $today->year;
            }

            $monthDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();

            $selectedMonthLabel = $monthDate->translatedFormat('F Y');
            $startOfMonth = $monthDate->copy()->startOfMonth();
            $endOfMonth = $monthDate->copy()->endOfMonth();

            // Hitung tidak hadir berdasarkan hari kerja dari tanggal pertama sampai hari ini
            $period = CarbonPeriod::create($presensis->min('tanggal'), $today);
            $jumlahHariKerja = collect($period)
                ->filter(fn (Carbon $date) => $date->isWeekday())
                ->count();

            $hariHadir = $presensis
                ->filter(fn ($p) => $p->jam_masuk)
                ->pluck('tanggal')
                ->unique()
                ->count();

            $jumlahTidakHadir = max(0, $jumlahHariKerja - $hariHadir);

            // Data grafik batang: jumlah hadir (H+T) per minggu dalam bulan terpilih
            $presensiBulan = Presensi::where('user_id', $user->id)
                ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                ->get();

            // Jika bulan terpilih tidak punya data presensi, biarkan grafik kosong
            if ($presensiBulan->isNotEmpty()) {
                $daysInMonth = $endOfMonth->day;
                // Minggu 1-4 selalu ada, Minggu 5 hanya jika bulan > 28 hari
                $totalWeeks = $daysInMonth > 28 ? 5 : 4;

                $weeklyCounts = array_fill(1, $totalWeeks, 0);
                foreach ($presensiBulan as $p) {
                    if (! $p->jam_masuk) {
                        continue;
                    }
                    $day = Carbon::parse($p->tanggal)->day;
                    // 1-7 = Minggu 1, 8-14 = Minggu 2, 15-21 = Minggu 3, 22-28 = Minggu 4, 29+ = Minggu 5
                    $week = min($totalWeeks, (int) ceil($day / 7));
                    $weeklyCounts[$week]++;
                }

                for ($w = 1; $w <= $totalWeeks; $w++) {
                    $weeklyLabels[] = 'Minggu ' . $w;
                    $weeklyValues[] = $weeklyCounts[$w];
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
            'jumlahHadir' => $jumlahHadir,
            'jumlahIzin' => $jumlahIzin,
            'jumlahTerlambat' => $jumlahTerlambat,
            'jumlahTidakHadir' => $jumlahTidakHadir,
            'bulanOptions' => $bulanOptions,
            'years' => $years,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
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

    /**
     * Ambil data hari libur nasional Indonesia dari API api.co.id.
     * Data di-cache selama 60 hari agar sangat hemat limit API.
     */
    protected function getHariLiburNasional(int $year): array
    {
        $cacheKey = "hari_libur_nasional_{$year}";

        // Cek cache dulu
        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $apiKey = config('services.api_co_id.key');

        if (empty($apiKey)) {
            Log::warning('API_CO_ID_KEY belum diatur di .env');
            return [];
        }

        try {
            $response = Http::withHeaders([
                'x-api-co-id' => $apiKey,
            ])->timeout(10)->get('https://use.api.co.id/holidays/indonesia/', [
                'year' => $year,
            ]);

            if (! $response->successful()) {
                Log::error('API Holiday gagal: HTTP ' . $response->status());
                return [];
            }

            $json = $response->json();

            // Jika respon API sukses tapi datanya kosong (seperti kasus 2027)
            if (! ($json['is_success'] ?? false) || empty($json['data'])) {
                Log::warning('API Holiday response tidak berhasil atau data kosong untuk tahun ' . $year);
                
                // Simpan memori KOSONG selama 60 hari agar tidak memanggil API terus menerus
                Cache::put($cacheKey, [], now()->addDays(60)); 
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

            // Jika datanya ADA, simpan di ingatan selama 60 hari
            if (count($holidays) > 0) {
                Cache::put($cacheKey, $holidays, now()->addDays(60));
            } 
            // Jika setelah difilter ternyata KOSONG, tetap simpan ingatan kosong selama 60 hari
            else {
                Cache::put($cacheKey, [], now()->addDays(60)); 
            }

            return $holidays;

        } catch (\Exception $e) {
            Log::error('API Holiday error: ' . $e->getMessage());
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