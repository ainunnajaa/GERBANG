<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use App\Models\PresensiStatusOverride;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PresensiPeriodController extends Controller
{
    public function index(): View
    {
        return view('admin.presensi.list_tahun_ajar_presensi', [
            'periods' => PresensiPeriod::query()
                ->orderByDesc('is_active')
                ->orderByDesc('start_date')
                ->paginate(10),
            'dayOptions' => PresensiPeriod::DAY_OPTIONS,
            'typeOptions' => PresensiPeriod::TYPE_OPTIONS,
        ]);
    }

    public function create(): View
    {
        return view('admin.presensi.create_tahun_ajar_presensi', [
            'dayOptions' => PresensiPeriod::DAY_OPTIONS,
            'typeOptions' => PresensiPeriod::TYPE_OPTIONS,
            'defaultActiveDays' => PresensiPeriod::defaultActiveDays(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePeriod($request);

        $period = PresensiPeriod::create([
            ...$data,
            'is_active' => $request->boolean('is_active') || ! PresensiPeriod::query()->where('is_active', true)->exists(),
        ]);

        if ($period->is_active) {
            $this->activateOnly($period);
        }

        return redirect()->route('admin.presensi.periods.index')->with('success', 'Periode presensi berhasil ditambahkan.');
    }

    public function edit(PresensiPeriod $period): View
    {
        return view('admin.presensi.edit_tahun_ajar_presensi', [
            'period' => $period,
            'dayOptions' => PresensiPeriod::DAY_OPTIONS,
            'typeOptions' => PresensiPeriod::TYPE_OPTIONS,
        ]);
    }

    public function update(Request $request, PresensiPeriod $period): RedirectResponse
    {
        $data = $this->validatePeriod($request, $period->id);
        $isActive = $request->boolean('is_active');

        $period->update([
            ...$data,
            'is_active' => $isActive,
        ]);

        if ($isActive) {
            $this->activateOnly($period);
        }

        return redirect()->route('admin.presensi.periods.index')->with('success', 'Periode presensi berhasil diperbarui.');
    }

    public function activate(PresensiPeriod $period): RedirectResponse
    {
        $this->activateOnly($period);

        return back()->with('success', 'Periode presensi aktif berhasil diperbarui.');
    }

	public function deactivate(PresensiPeriod $period): RedirectResponse
	{
		$period->update(['is_active' => false]);

		return back()->with('success', 'Periode presensi berhasil dinonaktifkan.');
	}

    public function destroy(PresensiPeriod $period): RedirectResponse
    {
        $dateRange = [Carbon::parse($period->start_date)->toDateString(), Carbon::parse($period->end_date)->toDateString()];
        $wasActive = (bool) $period->is_active;

        DB::transaction(function () use ($dateRange, $period, $wasActive) {
            Presensi::query()->whereBetween('tanggal', $dateRange)->delete();
            PresensiIzin::query()->whereBetween('tanggal', $dateRange)->delete();
            PresensiStatusOverride::query()->whereBetween('tanggal', $dateRange)->delete();
            $period->delete();

            if ($wasActive) {
                $replacementPeriod = PresensiPeriod::query()
                    ->orderByDesc('start_date')
                    ->first();

                if ($replacementPeriod) {
                    $this->activateOnly($replacementPeriod);
                }
            }
        });

        return redirect()->route('admin.presensi.periods.index')->with('success', 'Periode presensi dan seluruh riwayat pada rentang tanggalnya berhasil dihapus.');
    }

    private function validatePeriod(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'period_type' => ['required', 'in:' . implode(',', array_keys(PresensiPeriod::TYPE_OPTIONS))],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'active_days' => ['required', 'array', 'min:1'],
            'active_days.*' => ['required', 'in:' . implode(',', array_keys(PresensiPeriod::DAY_OPTIONS))],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $overlapExists = PresensiPeriod::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->whereDate('start_date', '<=', $data['end_date'])
            ->whereDate('end_date', '>=', $data['start_date'])
            ->exists();

        if ($overlapExists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'start_date' => 'Rentang tanggal bertabrakan dengan periode presensi lain. Ubah tanggal mulai atau selesai.',
            ]);
        }

        $data['active_days'] = array_values(array_unique($data['active_days']));

        return $data;
    }

    private function activateOnly(PresensiPeriod $period): void
    {
        PresensiPeriod::query()->update(['is_active' => false]);

        PresensiPeriod::query()->whereKey($period->id)->update(['is_active' => true]);
        $period->forceFill(['is_active' => true]);
    }
}