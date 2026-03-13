<?php

namespace Tests\Feature\Presensi;

use App\Models\Presensi;
use App\Models\PresensiIzin;
use App\Models\PresensiPeriod;
use App\Models\PresensiStatusOverride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PresensiPeriodTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_an_active_presensi_period(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.presensi.periods.store'), [
            'name' => 'Semester Ganjil 2026/2027',
            'period_type' => 'semester_ganjil',
            'start_date' => '2026-07-15',
            'end_date' => '2026-12-20',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => '1',
            'description' => 'Periode semester ganjil.',
        ]);

        $response->assertRedirect(route('admin.presensi.periods.index', absolute: false));

        $this->assertDatabaseHas('presensi_periods', [
            'name' => 'Semester Ganjil 2026/2027',
            'period_type' => 'semester_ganjil',
            'is_active' => true,
        ]);
    }

    public function test_guru_scan_is_blocked_when_no_active_period_exists(): void
    {
        $guru = User::factory()->create([
            'role' => 'guru',
        ]);

        $response = $this->actingAs($guru)
            ->from(route('guru.presensi', absolute: false))
            ->post(route('guru.presensi.scan'), [
                'qr_code' => 'TKABA-PRESENSI',
            ]);

        $response->assertRedirect(route('guru.presensi', absolute: false));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('presensis', 0);
    }

    public function test_admin_history_landing_page_shows_period_cards(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        PresensiPeriod::create([
            'name' => 'Semester Ganjil 2026/2027',
            'period_type' => 'semester_ganjil',
            'start_date' => '2026-07-15',
            'end_date' => '2026-12-20',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.riwayat'));

        $response->assertOk();
        $response->assertSee('Pilih Periode Riwayat', false);
        $response->assertSee('Semester Ganjil 2026/2027', false);
    }

    public function test_admin_daily_history_redirects_back_to_period_cards_without_period_selection(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.presensi.all'));

        $response->assertRedirect(route('admin.riwayat', absolute: false));
    }

    public function test_admin_can_view_guru_history_scoped_to_selected_period(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $guru = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru Periode',
        ]);

        $period = PresensiPeriod::create([
            'name' => 'Semester Genap 2026',
            'period_type' => 'semester_genap',
            'start_date' => '2026-01-12',
            'end_date' => '2026-01-16',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => true,
        ]);

        Presensi::create([
            'user_id' => $guru->id,
            'tanggal' => '2026-01-09',
            'jam_masuk' => '07:05:00',
            'jam_pulang' => '13:10:00',
            'status' => 'H',
        ]);

        Presensi::create([
            'user_id' => $guru->id,
            'tanggal' => '2026-01-12',
            'jam_masuk' => '07:03:00',
            'jam_pulang' => '13:08:00',
            'status' => 'H',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.presensi.guru', [
            'guru' => $guru->id,
            'period_id' => $period->id,
        ]));

        $response->assertOk();
        $response->assertSee('2026-01-12', false);
        $response->assertDontSee('2026-01-09', false);
    }

    public function test_admin_can_bulk_update_status_for_multiple_gurus(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $guruSatu = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru Satu',
        ]);

        $guruDua = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru Dua',
        ]);

        $period = PresensiPeriod::create([
            'name' => 'Semester Genap 2026',
            'period_type' => 'semester_genap',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.presensi.status.bulk-update'), [
            'period_id' => $period->id,
            'month_key' => '2026-01',
            'apply_scope' => 'selected',
            'user_ids' => [$guruSatu->id, $guruDua->id],
            'tanggal_mulai' => '2026-01-12',
            'tanggal_selesai' => '2026-01-14',
            'status' => 'I',
        ]);

        $response->assertRedirect(route('admin.presensi.bulanan', [
            'period_id' => $period->id,
            'month_key' => '2026-01',
        ], absolute: false));

        $this->assertSame(6, PresensiStatusOverride::count());
        $this->assertTrue(DB::table('presensi_status_overrides')
            ->where('user_id', $guruSatu->id)
            ->where('tanggal', '2026-01-12 00:00:00')
            ->where('status', 'I')
            ->exists());
        $this->assertTrue(DB::table('presensi_status_overrides')
            ->where('user_id', $guruDua->id)
            ->where('tanggal', '2026-01-14 00:00:00')
            ->where('status', 'I')
            ->exists());
    }

    public function test_bulk_update_skips_sundays_and_national_holidays(): void
    {
        Cache::put('hari_libur_nasional_2026', [
            ['start' => '2026-01-01'],
        ], now()->addMonth());

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $guru = User::factory()->create([
            'role' => 'guru',
            'name' => 'Guru Libur',
        ]);

        $period = PresensiPeriod::create([
            'name' => 'Semester Genap 2026',
            'period_type' => 'semester_genap',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-05',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.presensi.status.bulk-update'), [
            'period_id' => $period->id,
            'month_key' => '2026-01',
            'apply_scope' => 'selected',
            'user_ids' => [$guru->id],
            'tanggal_mulai' => '2026-01-01',
            'tanggal_selesai' => '2026-01-05',
            'status' => 'H',
        ]);

        $response->assertRedirect(route('admin.presensi.bulanan', [
            'period_id' => $period->id,
            'month_key' => '2026-01',
        ], absolute: false));

        $this->assertFalse(DB::table('presensi_status_overrides')
            ->where('user_id', $guru->id)
            ->where('tanggal', '2026-01-01 00:00:00')
            ->exists());
        $this->assertFalse(DB::table('presensi_status_overrides')
            ->where('user_id', $guru->id)
            ->where('tanggal', '2026-01-04 00:00:00')
            ->exists());
        $this->assertTrue(DB::table('presensi_status_overrides')
            ->where('user_id', $guru->id)
            ->where('tanggal', '2026-01-02 00:00:00')
            ->where('status', 'H')
            ->exists());
        $this->assertTrue(DB::table('presensi_status_overrides')
            ->where('user_id', $guru->id)
            ->where('tanggal', '2026-01-05 00:00:00')
            ->where('status', 'H')
            ->exists());
    }

    public function test_admin_can_delete_period_and_related_attendance_history(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $guru = User::factory()->create([
            'role' => 'guru',
        ]);

        $periodToDelete = PresensiPeriod::create([
            'name' => 'Semester Genap 2026',
            'period_type' => 'semester_genap',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => true,
        ]);

        $replacementPeriod = PresensiPeriod::create([
            'name' => 'Semester Ganjil 2026',
            'period_type' => 'semester_ganjil',
            'start_date' => '2026-07-01',
            'end_date' => '2026-12-31',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => false,
        ]);

        Presensi::create([
            'user_id' => $guru->id,
            'tanggal' => '2026-01-15',
            'jam_masuk' => '07:05:00',
            'jam_pulang' => '13:10:00',
            'status' => 'H',
        ]);

        PresensiIzin::create([
            'user_id' => $guru->id,
            'tanggal' => '2026-01-16',
            'keterangan' => 'Izin sakit',
        ]);

        PresensiStatusOverride::create([
            'user_id' => $guru->id,
            'tanggal' => '2026-01-17',
            'status' => 'A',
            'updated_by' => $admin->id,
        ]);

        Presensi::create([
            'user_id' => $guru->id,
            'tanggal' => '2026-07-10',
            'jam_masuk' => '07:00:00',
            'jam_pulang' => '13:00:00',
            'status' => 'H',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.presensi.periods.destroy', $periodToDelete));

        $response->assertRedirect(route('admin.presensi.periods.index', absolute: false));
        $this->assertDatabaseMissing('presensi_periods', ['id' => $periodToDelete->id]);
        $this->assertDatabaseMissing('presensis', ['tanggal' => '2026-01-15']);
        $this->assertDatabaseMissing('presensi_izins', ['tanggal' => '2026-01-16']);
        $this->assertFalse(DB::table('presensi_status_overrides')->where('tanggal', '2026-01-17 00:00:00')->exists());
        $this->assertTrue(DB::table('presensis')->where('tanggal', '2026-07-10 00:00:00')->exists());
        $this->assertDatabaseHas('presensi_periods', ['id' => $replacementPeriod->id, 'is_active' => true]);
    }

    public function test_admin_can_deactivate_an_active_period(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $period = PresensiPeriod::create([
            'name' => 'Semester Genap 2026',
            'period_type' => 'semester_genap',
            'start_date' => '2026-01-01',
            'end_date' => '2026-01-31',
            'active_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.presensi.periods.deactivate', $period));

        $response->assertRedirect();
        $this->assertDatabaseHas('presensi_periods', [
            'id' => $period->id,
            'is_active' => false,
        ]);
    }
}