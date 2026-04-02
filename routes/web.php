<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebProfilController;
use App\Http\Controllers\KelolaBeritaController;
use App\Http\Controllers\BeritaPublikController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KelolaPenggunaController;
use App\Http\Controllers\PresensiPeriodController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\RiwayatPresensiController;
use App\Http\Controllers\PwaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/pwa/manifest.webmanifest', [PwaController::class, 'manifest'])->name('pwa.manifest');
Route::get('/pwa/icon/{size}.png', [PwaController::class, 'icon'])
    ->whereNumber('size')
    ->name('pwa.icon');
Route::get('/apple-touch-icon.png', [PwaController::class, 'appleTouchIcon'])->name('pwa.apple-touch-icon');
Route::get('/visi-misi', [WelcomeController::class, 'visiMisi'])->name('publik.visi_misi');
Route::get('/kontak', [WelcomeController::class, 'kontak'])->name('publik.kontak');
Route::get('/video', [WelcomeController::class, 'video'])->name('publik.video');
Route::post('/contact', [WelcomeController::class, 'contact'])->name('contact.send');

// Route untuk Halaman Daftar Guru (BARU DITAMBAHKAN)
Route::get('/guru', [WelcomeController::class, 'guru'])->name('publik.guru.index');

// Public berita
Route::get('/berita', [BeritaPublikController::class, 'index'])->name('publik.berita.index');
Route::get('/berita/{berita}', [BeritaPublikController::class, 'show'])->name('publik.berita.show');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
});

require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/kelola-pengguna', [KelolaPenggunaController::class, 'index'])->name('admin.users');
    
        Route::get('/admin/pengguna/create', [KelolaPenggunaController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/pengguna', [KelolaPenggunaController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/pengguna/{user}/edit', [KelolaPenggunaController::class, 'edit'])->name('admin.users.edit');
        Route::patch('/admin/pengguna/{user}', [KelolaPenggunaController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/pengguna/{user}', [KelolaPenggunaController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/admin/kelola-presensi', [PresensiController::class, 'adminIndex'])->name('admin.presensi');
    Route::post('/admin/kelola-presensi/jam', [PresensiController::class, 'updateSettings'])->name('admin.presensi.settings.update');
    Route::get('/admin/kelola-presensi/periode', [PresensiPeriodController::class, 'index'])->name('admin.presensi.periods.index');
    Route::get('/admin/kelola-presensi/periode/create', [PresensiPeriodController::class, 'create'])->name('admin.presensi.periods.create');
    Route::post('/admin/kelola-presensi/periode', [PresensiPeriodController::class, 'store'])->name('admin.presensi.periods.store');
    Route::get('/admin/kelola-presensi/periode/{period}/edit', [PresensiPeriodController::class, 'edit'])->name('admin.presensi.periods.edit');
    Route::patch('/admin/kelola-presensi/periode/{period}', [PresensiPeriodController::class, 'update'])->name('admin.presensi.periods.update');
    Route::delete('/admin/kelola-presensi/periode/{period}', [PresensiPeriodController::class, 'destroy'])->name('admin.presensi.periods.destroy');
    Route::post('/admin/kelola-presensi/periode/{period}/activate', [PresensiPeriodController::class, 'activate'])->name('admin.presensi.periods.activate');
    Route::post('/admin/kelola-presensi/periode/{period}/deactivate', [PresensiPeriodController::class, 'deactivate'])->name('admin.presensi.periods.deactivate');
    Route::get('/admin/riwayat-presensi', [RiwayatPresensiController::class, 'adminRiwayat'])->name('admin.riwayat');
    Route::get('/admin/riwayat-presensi/periode/{period}', [RiwayatPresensiController::class, 'adminRiwayatPeriode'])->name('admin.riwayat.period');
    Route::get('/admin/riwayat-presensi-semua', [RiwayatPresensiController::class, 'adminRiwayatSemua'])->name('admin.presensi.all');
    Route::get('/admin/riwayat-presensi-semua/export', [RiwayatPresensiController::class, 'adminExportPresensiSemua'])->name('admin.presensi.all.export');
    Route::get('/admin/riwayat-presensi-bulanan', [RiwayatPresensiController::class, 'adminRiwayatBulanan'])->name('admin.presensi.bulanan');
    Route::post('/admin/riwayat-presensi/status', [RiwayatPresensiController::class, 'adminUpdateStatus'])->name('admin.presensi.status.update');
    Route::post('/admin/riwayat-presensi/status/bulk', [RiwayatPresensiController::class, 'adminBulkUpdateStatus'])->name('admin.presensi.status.bulk-update');
    Route::get('/admin/riwayat-presensi/guru/{guru}', [RiwayatPresensiController::class, 'adminPresensiGuru'])->name('admin.presensi.guru');
    Route::get('/admin/riwayat-presensi/guru/{guru}/download', [RiwayatPresensiController::class, 'adminDownloadPresensiGuru'])->name('admin.presensi.guru.download');
    Route::delete('/admin/presensi/{presensi}', [RiwayatPresensiController::class, 'adminDeletePresensi'])->name('admin.presensi.delete');

    Route::get('/admin/kelola-web-profil', [WebProfilController::class, 'index'])->name('admin.web_profil');
    Route::post('/admin/kelola-web-profil', [WebProfilController::class, 'save'])->name('admin.web_profil.save');
    Route::delete('/admin/kelola-web-profil/principal-photo', [WebProfilController::class, 'deletePrincipalPhoto'])->name('admin.web_profil.principal_photo.delete');
    Route::delete('/admin/kelola-web-profil/school-logo', [WebProfilController::class, 'deleteSchoolLogo'])->name('admin.web_profil.school_logo.delete');

    // Program Unggulan CRUD
    Route::post('/admin/program-unggulan', [WebProfilController::class, 'storeProgram'])->name('admin.programs.store');
    Route::patch('/admin/program-unggulan/{program}', [WebProfilController::class, 'updateProgram'])->name('admin.programs.update');
    Route::delete('/admin/program-unggulan/{program}', [WebProfilController::class, 'deleteProgram'])->name('admin.programs.delete');

    // Konten CRUD
    Route::post('/admin/konten', [WebProfilController::class, 'storeContent'])->name('admin.contents.store');
    Route::patch('/admin/konten/{content}', [WebProfilController::class, 'updateContent'])->name('admin.contents.update');
    Route::delete('/admin/konten/{content}', [WebProfilController::class, 'deleteContent'])->name('admin.contents.delete');

    // Video YouTube CRUD
    Route::get('/admin/youtube/connect', [WebProfilController::class, 'connectYouTube'])->name('admin.youtube.connect');
    Route::delete('/admin/youtube/disconnect', [WebProfilController::class, 'disconnectYouTube'])->name('admin.youtube.disconnect');
    Route::get('/youtube/callback', [WebProfilController::class, 'handleYouTubeCallback'])->name('admin.youtube.callback');
    Route::post('/admin/video/upload', [WebProfilController::class, 'uploadVideoToYouTube'])->name('admin.videos.upload');
    Route::post('/admin/video', [WebProfilController::class, 'storeVideo'])->name('admin.videos.store');
    Route::patch('/admin/video/{video}', [WebProfilController::class, 'updateVideo'])->name('admin.videos.update');
    Route::delete('/admin/video/{video}', [WebProfilController::class, 'deleteVideo'])->name('admin.videos.delete');

    // Backgrounds CRUD
    Route::post('/admin/background', [WebProfilController::class, 'storeBackground'])->name('admin.backgrounds.store');
    Route::delete('/admin/background/{bg}', [WebProfilController::class, 'deleteBackground'])->name('admin.backgrounds.delete');

    // Kelola Berita
    Route::get('/admin/kelola-berita', [KelolaBeritaController::class, 'index'])->name('admin.berita');
    Route::get('/admin/kelola-berita/create', [KelolaBeritaController::class, 'create'])->name('admin.berita.create');
    Route::get('/admin/kelola-berita/{berita}/edit', [KelolaBeritaController::class, 'edit'])->name('admin.berita.edit');
    Route::get('/admin/kelola-berita/{berita}', [KelolaBeritaController::class, 'show'])->name('admin.berita.show');
    Route::post('/admin/kelola-berita', [KelolaBeritaController::class, 'store'])->name('admin.berita.store');
    Route::patch('/admin/kelola-berita/{berita}', [KelolaBeritaController::class, 'update'])->name('admin.berita.update');
    Route::delete('/admin/kelola-berita/{berita}', [KelolaBeritaController::class, 'destroy'])->name('admin.berita.delete');
});


// Guru routes
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/presensi', [PresensiController::class, 'guruIndex'])->name('guru.presensi');
    Route::post('/guru/presensi/scan', [PresensiController::class, 'scan'])->name('guru.presensi.scan');
    Route::get('/guru/izin', fn() => view('guru.Izin_guru'))->name('guru.izin.form');
    Route::post('/guru/izin', [PresensiController::class, 'guruIzin'])->name('guru.izin');
    Route::get('/guru/kehadiran/periode', [RiwayatPresensiController::class, 'guruKehadiranPeriods'])->name('guru.kehadiran.periods');
    Route::get('/guru/kehadiran', [RiwayatPresensiController::class, 'guruKehadiran'])->name('guru.kehadiran');
    Route::get('/guru/kehadiran-bulanan', [RiwayatPresensiController::class, 'guruKehadiranBulanan'])->name('guru.kehadiran.bulanan');
    Route::get('/guru/kehadiran-bulanan/export', [RiwayatPresensiController::class, 'guruExportKehadiranBulanan'])->name('guru.kehadiran.bulanan.export');
    Route::get('/guru/berita', [BeritaController::class, 'index'])->name('guru.berita.index');
    Route::get('/guru/berita/{berita}', [BeritaController::class, 'show'])->name('guru.berita.show');
    Route::get('/guru/daftar-guru', function () {
        $gurus = \App\Models\User::where('role', 'guru')
            ->select('id', 'name', 'kelas', 'profile_photo_path', 'email', 'phone')
            ->orderBy('name')
            ->get();
        return view('guru.daftar_guru', compact('gurus'));
    })->name('guru.daftar-guru');
});

// Wali Murid routes
Route::middleware(['auth', 'role:wali_murid'])->group(function () {
    Route::get('/wali/daftar', fn() => view('wali_murid.daftar'))->name('wali.daftar');
    Route::get('/wali/aktivitas', fn() => view('wali_murid.aktivitas'))->name('wali.aktivitas');
});
