<?php

namespace Tests\Feature\Dashboard;

use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use App\Models\PresensiSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GuruDashboardTest extends TestCase
{
	use RefreshDatabase;

	protected function tearDown(): void
	{
		Carbon::setTestNow();

		parent::tearDown();
	}

	public function test_guru_dashboard_counts_only_active_period_operational_days(): void
	{
		Carbon::setTestNow('2026-03-06 09:00:00');
		Cache::put('hari_libur_nasional_2026', [], now()->addMonth());

		$guru = User::factory()->create([
			'role' => 'guru',
		]);

		PresensiSetting::create([
			'jam_masuk_start' => '07:00:00',
			'jam_masuk_end' => '08:00:00',
			'jam_masuk_toleransi' => '08:30:00',
			'jam_pulang_start' => '13:00:00',
			'jam_pulang_end' => '14:30:00',
			'qr_text' => 'TKABA-PRESENSI',
		]);

		PresensiPeriod::create([
			'name' => 'Periode Aktif Maret 2026',
			'period_type' => 'custom',
			'start_date' => '2026-03-01',
			'end_date' => '2026-03-31',
			'active_days' => ['monday', 'tuesday', 'wednesday', 'friday'],
			'is_active' => true,
		]);

		Presensi::create([
			'user_id' => $guru->id,
			'tanggal' => '2026-03-02',
			'jam_masuk' => '07:10:00',
			'jam_pulang' => '13:00:00',
			'status' => 'H',
		]);

		Presensi::create([
			'user_id' => $guru->id,
			'tanggal' => '2026-03-04',
			'jam_masuk' => '08:05:00',
			'jam_pulang' => '13:00:00',
			'status' => 'T',
		]);

		PresensiIzin::create([
			'user_id' => $guru->id,
			'tanggal' => '2026-03-03',
			'keterangan' => 'Izin keluarga',
		]);

		Presensi::create([
			'user_id' => $guru->id,
			'tanggal' => '2026-02-27',
			'jam_masuk' => '07:00:00',
			'jam_pulang' => '13:00:00',
			'status' => 'H',
		]);

		Presensi::create([
			'user_id' => $guru->id,
			'tanggal' => '2026-03-17',
			'jam_masuk' => '07:15:00',
			'jam_pulang' => '13:00:00',
			'status' => 'H',
		]);

		Presensi::create([
			'user_id' => $guru->id,
			'tanggal' => '2026-03-25',
			'jam_masuk' => '08:10:00',
			'jam_pulang' => '13:00:00',
			'status' => 'T',
		]);

		$response = $this->actingAs($guru)->get(route('dashboard'));

		$response->assertOk();
		$response->assertViewHas('jumlahHadir', 1);
		$response->assertViewHas('jumlahTerlambat', 1);
		$response->assertViewHas('jumlahIzin', 1);
		$response->assertViewHas('jumlahTidakHadir', 0);
		$response->assertViewHas('selectedMonthKey', '2026-03');
		$response->assertViewHas('weeklyValues', [2, 0, 1, 1, 0]);
	}
}