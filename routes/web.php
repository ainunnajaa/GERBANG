<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebProfilController;
use App\Http\Controllers\KelolaBeritaController;
use App\Http\Controllers\BeritaPublikController;
use App\Http\Controllers\KelolaPenggunaController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index']);

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
});

require __DIR__.'/auth.php';

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/kelola-pengguna', [KelolaPenggunaController::class, 'index'])->name('admin.users');
    
        Route::get('/admin/pengguna/create', [KelolaPenggunaController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/pengguna', [KelolaPenggunaController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/pengguna/{user}/edit', [KelolaPenggunaController::class, 'edit'])->name('admin.users.edit');
        Route::patch('/admin/pengguna/{user}', [KelolaPenggunaController::class, 'update'])->name('admin.users.update');

    Route::get('/admin/kelola-presensi', [PresensiController::class, 'adminIndex'])->name('admin.presensi');
    Route::post('/admin/kelola-presensi/jam', [PresensiController::class, 'updateSettings'])->name('admin.presensi.settings.update');
    Route::get('/admin/riwayat-presensi', [PresensiController::class, 'adminRiwayat'])->name('admin.riwayat');
    Route::get('/admin/riwayat-presensi/guru/{guru}', [PresensiController::class, 'adminPresensiGuru'])->name('admin.presensi.guru');
    Route::delete('/admin/presensi/{presensi}', [PresensiController::class, 'adminDeletePresensi'])->name('admin.presensi.delete');

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

    // Backgrounds CRUD
    Route::post('/admin/background', [WebProfilController::class, 'storeBackground'])->name('admin.backgrounds.store');
    Route::delete('/admin/background/{bg}', [WebProfilController::class, 'deleteBackground'])->name('admin.backgrounds.delete');

    // Kelola Berita
    Route::get('/admin/kelola-berita', [KelolaBeritaController::class, 'index'])->name('admin.berita');
    Route::get('/admin/kelola-berita/create', [KelolaBeritaController::class, 'create'])->name('admin.berita.create');
    Route::get('/admin/kelola-berita/{berita}', [KelolaBeritaController::class, 'show'])->name('admin.berita.show');
    Route::post('/admin/kelola-berita', [KelolaBeritaController::class, 'store'])->name('admin.berita.store');
});


// Guru routes
Route::middleware(['auth', 'verified', 'role:guru'])->group(function () {
    Route::get('/guru/presensi', [PresensiController::class, 'guruIndex'])->name('guru.presensi');
    Route::post('/guru/presensi/scan', [PresensiController::class, 'scan'])->name('guru.presensi.scan');
    Route::get('/guru/kehadiran', fn() => view('guru.kehadiran'))->name('guru.kehadiran');
});

// Wali Murid routes
Route::middleware(['auth', 'verified', 'role:wali_murid'])->group(function () {
    Route::get('/wali/daftar', fn() => view('wali_murid.daftar'))->name('wali.daftar');
    Route::get('/wali/aktivitas', fn() => view('wali_murid.aktivitas'))->name('wali.aktivitas');
});
