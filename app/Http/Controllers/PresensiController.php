<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresensiController extends Controller
{
	public function guruIndex()
	{
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

		return view('guru.presensi', [
			'settings' => $settings,
		]);
	}

	public function adminIndex()
	{
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

		$qrCodeText = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');

		$today = now()->toDateString();
		$presensis = Presensi::with('user')
			->whereDate('tanggal', $today)
			->get();

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		return view('admin.kelola_presensi', [
			'qrCodeText' => $qrCodeText,
			'presensis' => $presensis,
			'today' => $today,
			'settings' => $settings,
			'gurus' => $gurus,
		]);
	}

	public function updateSettings(Request $request)
	{
		$data = $request->validate([
			'jam_masuk_start' => ['required', 'date_format:H:i'],
			'jam_masuk_end' => ['required', 'date_format:H:i'],
			'jam_pulang_start' => ['required', 'date_format:H:i'],
			'jam_pulang_end' => ['required', 'date_format:H:i'],
			'qr_text' => ['nullable', 'string', 'max:255'],
		]);

		$settings = PresensiSetting::first() ?? new PresensiSetting();
		$settings->jam_masuk_start = $data['jam_masuk_start'] . ':00';
		$settings->jam_masuk_end = $data['jam_masuk_end'] . ':00';
		$settings->jam_pulang_start = $data['jam_pulang_start'] . ':00';
		$settings->jam_pulang_end = $data['jam_pulang_end'] . ':00';
		$settings->qr_text = $data['qr_text'] ?? env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
		$settings->save();

		return back()->with('success', 'Jam presensi berhasil diperbarui.');
	}

	public function adminRiwayat()
	{
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

		$today = now()->toDateString();
		$todayPresensis = Presensi::with('user')
			->whereDate('tanggal', $today)
			->get();

		$gurus = User::where('role', 'guru')
			->orderBy('name')
			->get();

		$presensis = Presensi::with('user')
			->orderByDesc('tanggal')
			->orderBy('user_id')
			->paginate(20);

		return view('admin.riwayat_presensi', [
			'presensis' => $presensis,
			'todayPresensis' => $todayPresensis,
			'today' => $today,
			'settings' => $settings,
			'gurus' => $gurus,
		]);
	}

	public function adminPresensiGuru(User $guru)
	{
		$presensis = Presensi::where('user_id', $guru->id)
			->orderByDesc('tanggal')
			->orderByDesc('created_at')
			->paginate(30);

		return view('admin.presensi.presensi_guru', [
			'guru' => $guru,
			'presensis' => $presensis,
		]);
	}

	public function adminDeletePresensi(Presensi $presensi)
	{
		$guruId = $presensi->user_id;
		$presensi->delete();

		return redirect()
			->route('admin.presensi.guru', $guruId)
			->with('success', 'Riwayat presensi berhasil dihapus.');
	}

	public function scan(Request $request)
	{
		$request->validate([
			'qr_code' => 'required|string',
		]);

		$now = Carbon::now();
		$currentTime = $now->format('H:i');
		$today = $now->toDateString();

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

		$expectedCode = $settings->qr_text ?: env('PRESENSI_QR_CODE', 'TKABA-PRESENSI');
		if ($request->qr_code !== $expectedCode) {
			return back()->with('error', 'QR code tidak valid.');
		}

		$masukStart = Carbon::createFromFormat('H:i', substr($settings->jam_masuk_start, 0, 5));
		$masukEnd = Carbon::createFromFormat('H:i', substr($settings->jam_masuk_end, 0, 5));
		$pulangStart = Carbon::createFromFormat('H:i', substr($settings->jam_pulang_start, 0, 5));
		$pulangEnd = Carbon::createFromFormat('H:i', substr($settings->jam_pulang_end, 0, 5));

		$current = Carbon::createFromFormat('H:i', $currentTime);

		$isMasuk = $current->between($masukStart, $masukEnd);
		$isPulang = $current->between($pulangStart, $pulangEnd);

		if (! $isMasuk && ! $isPulang) {
			return back()->with('error', 'Bukan jam presensi. Jam masuk: ' . $masukStart->format('H:i') . '-' . $masukEnd->format('H:i') . ', jam pulang: ' . $pulangStart->format('H:i') . '-' . $pulangEnd->format('H:i'));
		}

		$user = Auth::user();

		$presensi = Presensi::firstOrCreate([
			'user_id' => $user->id,
			'tanggal' => $today,
		]);

		if ($isMasuk) {
			if ($presensi->jam_masuk) {
				return back()->with('error', 'Anda sudah melakukan presensi masuk hari ini.');
			}

			$presensi->jam_masuk = $now->format('H:i:s');
			$presensi->save();

			return back()->with('success', 'Presensi masuk berhasil tercatat pada pukul ' . $now->format('H:i')); 
		}

		if ($isPulang) {
			if ($presensi->jam_pulang) {
				return back()->with('error', 'Anda sudah melakukan presensi pulang hari ini.');
			}

			$presensi->jam_pulang = $now->format('H:i:s');
			$presensi->save();

			return back()->with('success', 'Presensi pulang berhasil tercatat pada pukul ' . $now->format('H:i'));
		}

		return back()->with('error', 'Terjadi kesalahan saat mencatat presensi.');
	}
}

