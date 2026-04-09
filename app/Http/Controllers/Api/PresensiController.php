<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use App\Models\PresensiSetting;
use App\Models\SchoolProfile;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function getDashboardSummary(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'year' => ['nullable', 'integer', 'min:2020', 'max:2100'],
        ]);

        $month = (int) ($data['month'] ?? now()->month);
        $year = (int) ($data['year'] ?? now()->year);

        $presensiQuery = Presensi::query()
            ->where('user_id', $user->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month);

        $items = $presensiQuery->orderByDesc('tanggal')->get();

        $ringkasan = [
            'hadir' => $items->where('status', 'H')->count(),
            'terlambat' => $items->where('status', 'T')->count(),
            'izin' => $items->where('status', 'I')->count(),
            'tanpa_keterangan' => $items->where('status', 'A')->count(),
            'total_tercatat' => $items->count(),
        ];

        $recent = $items->take(5)->map(function (Presensi $item) {
            return [
                'id' => $item->id,
                'tanggal' => $item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : null,
                'jam_masuk' => $item->jam_masuk,
                'jam_pulang' => $item->jam_pulang,
                'status' => $item->status,
                'keterangan' => $item->keterangan,
            ];
        })->values();

        $activePeriod = $this->getActivePeriod();

        return response()->json([
            'status' => 'success',
            'data' => [
                'bulan' => $month,
                'tahun' => $year,
                'ringkasan' => $ringkasan,
                'riwayat_terbaru' => $recent,
                'periode_aktif' => $activePeriod ? [
                    'name' => $activePeriod->name,
                    'start_date' => $activePeriod->start_date ? Carbon::parse($activePeriod->start_date)->format('Y-m-d') : null,
                    'end_date' => $activePeriod->end_date ? Carbon::parse($activePeriod->end_date)->format('Y-m-d') : null,
                    'active_days' => $activePeriod->activeDayLabels(),
                ] : null,
            ],
        ]);
    }

    public function getProfilSaya(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = Carbon::now()->toDateString();

        $todayPresensi = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'kelas' => $user->kelas,
                'phone' => $user->phone,
                'profile_photo_url' => $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null,
                'presensi_hari_ini' => $todayPresensi ? [
                    'tanggal' => $todayPresensi->tanggal ? Carbon::parse($todayPresensi->tanggal)->format('Y-m-d') : null,
                    'jam_masuk' => $todayPresensi->jam_masuk,
                    'jam_pulang' => $todayPresensi->jam_pulang,
                    'status' => $todayPresensi->status,
                    'keterangan' => $todayPresensi->keterangan,
                ] : null,
            ],
        ]);
    }

    public function getRiwayatPresensi(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $limit = (int) ($data['limit'] ?? 30);

        $query = Presensi::query()
            ->where('user_id', $user->id)
            ->orderByDesc('tanggal');

        if (! empty($data['from'])) {
            $query->whereDate('tanggal', '>=', $data['from']);
        }

        if (! empty($data['to'])) {
            $query->whereDate('tanggal', '<=', $data['to']);
        }

        $riwayat = $query->limit($limit)->get()->map(function (Presensi $item) {
            return [
                'id' => $item->id,
                'tanggal' => $item->tanggal ? Carbon::parse($item->tanggal)->format('Y-m-d') : null,
                'jam_masuk' => $item->jam_masuk,
                'jam_pulang' => $item->jam_pulang,
                'status' => $item->status,
                'keterangan' => $item->keterangan,
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'count' => $riwayat->count(),
                'items' => $riwayat,
            ],
        ]);
    }

    public function storeIzin(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'keterangan' => ['required', 'string', 'max:1000'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $tanggal = $data['tanggal'] ?? now()->toDateString();
        $targetDate = Carbon::parse($tanggal);
        $activePeriod = $this->getActivePeriod();

        if (! $activePeriod) {
            return response()->json([
                'status' => 'error',
                'message' => 'Belum ada periode presensi aktif. Hubungi admin untuk mengatur periode presensi.',
            ], 422);
        }

        if (! $activePeriod->isOperationalOn($targetDate)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tanggal izin berada di luar periode atau hari operasional presensi aktif.',
            ], 422);
        }

        PresensiIzin::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => $tanggal,
            ],
            [
                'keterangan' => $data['keterangan'],
            ]
        );

        $presensi = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if ($presensi && $presensi->jam_masuk) {
            $presensi->keterangan = $data['keterangan'];
            $presensi->status = 'H';
            $presensi->save();
        } else {
            Presensi::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => 'I',
                    'keterangan' => $data['keterangan'],
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Izin berhasil dikirim.',
            'data' => [
                'tanggal' => $tanggal,
                'keterangan' => $data['keterangan'],
            ],
        ]);
    }

    public function getRiwayatIzin(Request $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $limit = (int) ($data['limit'] ?? 30);

        $query = PresensiIzin::query()
            ->where('user_id', $user->id)
            ->orderByDesc('tanggal');

        if (! empty($data['from'])) {
            $query->whereDate('tanggal', '>=', $data['from']);
        }

        if (! empty($data['to'])) {
            $query->whereDate('tanggal', '<=', $data['to']);
        }

        $items = $query->limit($limit)->get()->map(function (PresensiIzin $izin) {
            return [
                'id' => $izin->id,
                'tanggal' => $izin->tanggal ? Carbon::parse($izin->tanggal)->format('Y-m-d') : null,
                'keterangan' => $izin->keterangan,
                'created_at' => $izin->created_at?->toDateTimeString(),
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'data' => [
                'count' => $items->count(),
                'items' => $items,
            ],
        ]);
    }

    public function storePresensi(Request $request): JsonResponse
    {
        $data = $request->validate([
            'qr_code' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'foto_bukti' => ['nullable', 'image', 'max:4096'],
        ]);

        $now = Carbon::now();
        $today = $now->toDateString();
        $settings = $this->getOrCreateSettings();
        $activePeriod = $this->getActivePeriod();

        if (! $activePeriod) {
            return response()->json([
                'status' => 'error',
                'message' => 'Presensi belum dapat digunakan karena belum ada periode presensi aktif.',
            ], 422);
        }

        if (! $activePeriod->includesDate($now) || ! $activePeriod->isOperationalOn($now)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hari ini berada di luar periode atau hari operasional presensi yang aktif.',
            ], 422);
        }

        $expectedCode = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
        if ($data['qr_code'] !== $expectedCode) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code tidak valid.',
            ], 422);
        }

        if ($settings->latitude !== null && $settings->longitude !== null && $settings->radius_meter !== null) {
            $distance = $this->haversineDistance(
                (float) $settings->latitude,
                (float) $settings->longitude,
                (float) $data['latitude'],
                (float) $data['longitude']
            );

            if ($distance > (float) $settings->radius_meter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda berada di luar area yang diizinkan untuk presensi.',
                    'distance_meter' => round($distance, 2),
                    'radius_meter' => (float) $settings->radius_meter,
                ], 422);
            }
        }

        $masukStart = Carbon::createFromFormat('H:i', substr((string) $settings->jam_masuk_start, 0, 5));
        $masukEnd = Carbon::createFromFormat('H:i', substr((string) $settings->jam_masuk_end, 0, 5));
        $masukAcceptEnd = $settings->jam_masuk_toleransi
            ? Carbon::createFromFormat('H:i', substr((string) $settings->jam_masuk_toleransi, 0, 5))
            : $masukEnd;

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

        $pulangStart = Carbon::createFromFormat('H:i', substr((string) $pulangStartRaw, 0, 5));
        $pulangEnd = Carbon::createFromFormat('H:i', substr((string) $pulangEndRaw, 0, 5));

        $current = Carbon::createFromFormat('H:i', $now->format('H:i'));
        $isMasuk = $current->between($masukStart, $masukAcceptEnd);
        $isPulang = $current->between($pulangStart, $pulangEnd);

        if (! $isMasuk && ! $isPulang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bukan jam presensi.',
                'jam_masuk' => [
                    'start' => $masukStart->format('H:i'),
                    'end' => $masukEnd->format('H:i'),
                    'toleransi' => $masukAcceptEnd->format('H:i'),
                ],
                'jam_pulang' => [
                    'start' => $pulangStart->format('H:i'),
                    'end' => $pulangEnd->format('H:i'),
                ],
            ], 422);
        }

        $user = $request->user();
        $presensi = Presensi::query()
            ->where('user_id', $user->id)
            ->whereDate('tanggal', $today)
            ->first();

        $fotoBuktiPath = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoBuktiPath = $request->file('foto_bukti')->store('presensi-bukti', 'public');
        }

        if ($presensi && $presensi->jam_masuk && $presensi->jam_pulang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Presensi hari ini sudah lengkap.',
            ], 409);
        }

        if ($isMasuk) {
            if ($presensi && $presensi->jam_masuk) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Presensi masuk sudah tercatat hari ini.',
                ], 409);
            }

            $statusCode = $current->lte($masukEnd) ? 'H' : 'T';
            $statusText = $statusCode === 'T' ? 'terlambat' : 'hadir';

            if (! $presensi) {
                $presensi = Presensi::query()->create([
                    'user_id' => $user->id,
                    'tanggal' => $today,
                    'jam_masuk' => $now->format('H:i:s'),
                    'status' => $statusCode,
                    'keterangan' => $fotoBuktiPath ? 'Foto bukti: ' . $fotoBuktiPath : null,
                ]);
            } else {
                $presensi->jam_masuk = $now->format('H:i:s');
                $presensi->status = $statusCode;
                if ($fotoBuktiPath) {
                    $presensi->keterangan = trim((string) $presensi->keterangan . '\nFoto bukti: ' . $fotoBuktiPath);
                }
                $presensi->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Presensi masuk berhasil dicatat dengan status ' . $statusText . '.',
                'data' => [
                    'presensi_id' => $presensi->id,
                    'jenis' => 'masuk',
                    'tanggal' => $today,
                    'jam' => $presensi->jam_masuk,
                    'status' => $presensi->status,
                    'foto_bukti_url' => $fotoBuktiPath ? Storage::url($fotoBuktiPath) : null,
                ],
            ]);
        }

        if (! $presensi || ! $presensi->jam_masuk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda belum melakukan presensi masuk hari ini.',
            ], 422);
        }

        if ($presensi->jam_pulang) {
            return response()->json([
                'status' => 'error',
                'message' => 'Presensi pulang sudah tercatat hari ini.',
            ], 409);
        }

        $presensi->jam_pulang = $now->format('H:i:s');
        if ($fotoBuktiPath) {
            $presensi->keterangan = trim((string) $presensi->keterangan . '\nFoto bukti pulang: ' . $fotoBuktiPath);
        }
        $presensi->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Presensi pulang berhasil dicatat.',
            'data' => [
                'presensi_id' => $presensi->id,
                'jenis' => 'pulang',
                'tanggal' => $today,
                'jam' => $presensi->jam_pulang,
                'status' => $presensi->status,
                'foto_bukti_url' => $fotoBuktiPath ? Storage::url($fotoBuktiPath) : null,
            ],
        ]);
    }

    public function getProfilSekolah(): JsonResponse
    {
        $profile = SchoolProfile::query()->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'school_name' => $profile?->school_name,
                'school_logo_url' => $profile?->school_logo_path ? asset('storage/' . $profile->school_logo_path) : null,
                'contact_address' => $profile?->contact_address,
                'contact_phone' => $profile?->contact_phone,
                'contact_email' => $profile?->contact_email,
                'welcome_message' => $profile?->welcome_message,
                'vision' => $profile?->vision,
                'mission' => $profile?->mission,
            ],
        ]);
    }

    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;

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

    private function getOrCreateSettings(): PresensiSetting
    {
        $settings = PresensiSetting::query()->first();

        if ($settings) {
            return $settings;
        }

        return PresensiSetting::query()->create([
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
        ]);
    }

    private function getActivePeriod(): ?PresensiPeriod
    {
        return PresensiPeriod::query()->active()->orderByDesc('start_date')->first();
    }
}
