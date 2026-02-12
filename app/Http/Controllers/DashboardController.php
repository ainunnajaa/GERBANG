<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiSetting;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->user()->role ?? 'wali_murid';

        return match ($role) {
            'admin' => view('dashboard.admin'),
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

        $jumlahHadir = 0;
        $jumlahTerlambat = 0;

        foreach ($presensis as $presensi) {
            if (! $presensi->jam_masuk || ! $tol) {
                continue;
            }

            $jamMasuk = Carbon::parse($presensi->jam_masuk);
            if ($jamMasuk->lt($tol)) {
                $jumlahHadir++;
            } else {
                $jumlahTerlambat++;
            }
        }

        $jumlahTidakHadir = 0;
        $bulanOptions = [];
        $selectedMonth = $request->query('bulan');
        $selectedMonthLabel = null;
        $weeklyLabels = [];
        $weeklyValues = [];

        if ($presensis->isNotEmpty()) {
            $firstDate = Carbon::parse($presensis->min('tanggal'))->startOfMonth();
            $lastMonth = $today->copy()->startOfMonth();
            $monthsPeriod = CarbonPeriod::create($firstDate, '1 month', $lastMonth);

            foreach ($monthsPeriod as $month) {
                $bulanOptions[] = [
                    'value' => $month->format('Y-m'),
                    'label' => $month->translatedFormat('F Y'),
                ];
            }

            if (! $selectedMonth) {
                $selectedMonth = $lastMonth->format('Y-m');
            }

            try {
                $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            } catch (\Exception $e) {
                $monthDate = $lastMonth;
                $selectedMonth = $lastMonth->format('Y-m');
            }

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

        return view('dashboard.guru', [
            'jumlahHadir' => $jumlahHadir,
            'jumlahTerlambat' => $jumlahTerlambat,
            'jumlahTidakHadir' => $jumlahTidakHadir,
            'bulanOptions' => $bulanOptions,
            'selectedMonth' => $selectedMonth,
            'selectedMonthLabel' => $selectedMonthLabel,
            'weeklyLabels' => $weeklyLabels,
            'weeklyValues' => $weeklyValues,
        ]);
    }
}
