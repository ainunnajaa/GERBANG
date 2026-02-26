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
                $weeklyCounts = [];
                foreach ($presensiBulan as $p) {
                    if (! $p->jam_masuk) {
                        continue;
                    }
                    $week = Carbon::parse($p->tanggal)->weekOfMonth;
                    $weeklyCounts[$week] = ($weeklyCounts[$week] ?? 0) + 1;
                }

                $maxWeek = ! empty($weeklyCounts) ? max(array_keys($weeklyCounts)) : $endOfMonth->weekOfMonth;
                for ($w = 1; $w <= $maxWeek; $w++) {
                    $weeklyLabels[] = 'Minggu ' . $w;
                    $weeklyValues[] = $weeklyCounts[$w] ?? 0;
                }
            }
        }

        $beritas = \App\Models\Berita::orderByDesc('tanggal_berita')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

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
        ]);
    }
}
