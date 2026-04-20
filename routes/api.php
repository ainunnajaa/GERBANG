<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PresensiController;
use Illuminate\Support\Facades\Route;

Route::post('/login/google', [AuthController::class, 'googleLogin']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('/dashboard/ringkasan', [PresensiController::class, 'getDashboardSummary']);
    Route::get('/me', [PresensiController::class, 'getProfilSaya']);
    Route::post('/izin', [PresensiController::class, 'storeIzin']);
    Route::get('/izin/riwayat', [PresensiController::class, 'getRiwayatIzin']);
    Route::get('/presensi/riwayat', [PresensiController::class, 'getRiwayatPresensi']);
    Route::post('/presensi/masuk', [PresensiController::class, 'storePresensi']);
    Route::post('/presensi/scan', [PresensiController::class, 'storePresensi']);
    Route::get('/profil-sekolah', [PresensiController::class, 'getProfilSekolah']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
