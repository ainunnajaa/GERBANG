<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IzinNotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->query('limit', 15), 1), 30);

        $notifications = PresensiIzin::query()
            ->with(['user:id,name,role'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'guru');
            })
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(function (PresensiIzin $izin) {
                $tanggal = Carbon::parse($izin->tanggal);
                $period = PresensiPeriod::activeForDate($tanggal);

                $targetUrl = $period
                    ? route('admin.presensi.guru', ['guru' => $izin->user_id, 'period_id' => $period->id])
                    : route('admin.riwayat');

                return [
                    'id' => $izin->id,
                    'guru_id' => $izin->user_id,
                    'guru_name' => $izin->user?->name ?? 'Guru',
                    'title' => ($izin->user?->name ?? 'Guru') . ' mengirim izin',
                    'description' => Str::limit((string) ($izin->keterangan ?? ''), 90),
                    'tanggal_label' => $tanggal->translatedFormat('d M Y'),
                    'created_at_iso' => optional($izin->created_at)->toIso8601String(),
                    'created_at_human' => optional($izin->created_at)->diffForHumans(),
                    'has_attachment' => ! empty($izin->lampiran_path),
                    'url' => $targetUrl,
                ];
            })
            ->values();

        return response()->json([
            'notifications' => $notifications,
            'fetched_at' => now()->toIso8601String(),
        ]);
    }
}
