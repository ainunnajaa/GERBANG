<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IzinNotificationController extends Controller
{
    public function history(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $historyItems = PresensiIzin::query()
            ->with(['user:id,name,role,profile_photo_path'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'guru');
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('keterangan', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.riwayat.riwayat_notifikasi', [
            'historyItems' => $historyItems,
            'search' => $search,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $limit = min(max((int) $request->query('limit', 10), 1), 10);

        $notifications = PresensiIzin::query()
            ->with(['user:id,name,role,profile_photo_path'])
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
                    'guru_photo_url' => ! empty($izin->user?->profile_photo_path)
                        ? asset('storage/' . $izin->user->profile_photo_path)
                        : null,
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
