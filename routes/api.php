<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\DokumenMagangController;
use App\Http\Controllers\JadwalPresentasiController;
use App\Http\Controllers\DokumenNilaiMitraController;
use App\Http\Controllers\DosbingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FotoMagangController;
use App\Http\Controllers\NilaiMitraController;
use App\Http\Controllers\ProgressMagangController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\TahunAjaranController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



/*
|--------------------------------------------------------------------------
| Auth Route
|--------------------------------------------------------------------------
*/


Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');
Route::apiResource('mahasiswa', MahasiswaController::class);
Route::apiResource('mitra', MitraController::class);
Route::apiResource('magang', MagangController::class);
Route::apiResource('logbook', LogbookController::class);
Route::apiResource('dokumen-magang', DokumenMagangController::class);
Route::apiResource('jadwal-presentasi', JadwalPresentasiController::class);
Route::apiResource('dokumen-penilaian-mitra', DokumenNilaiMitraController::class);
Route::apiResource('dosbing', DosbingController::class);
Route::apiResource('admin', AdminController::class);
Route::apiResource('foto-magang', FotoMagangController::class);
Route::apiResource('penilaian-mitra', NilaiMitraController::class);
Route::apiResource('progress-magang', ProgressMagangController::class);
Route::apiResource('supervisor', SupervisorController::class);
Route::apiResource('tahun-ajaran', TahunAjaranController::class);
Route::get('tahun-ajaran/aktif', [TahunAjaranController::class, 'aktif']);
