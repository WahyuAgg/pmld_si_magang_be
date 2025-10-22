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
use App\Http\Controllers\SupervisorController;



/*
|--------------------------------------------------------------------------
| User Route
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth.api:sanctum');


Route::prefix('user')
    ->middleware(['auth.api:sanctum'])
    ->controller(MahasiswaController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


/*
|--------------------------------------------------------------------------
| Auth Route
|--------------------------------------------------------------------------
*/
Route::prefix('auth')
    ->controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');

    });

Route::prefix('auth')
    ->middleware(['auth.api:sanctum'])
    ->controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
    });


/*
|--------------------------------------------------------------------------
| Mahasiswa Routes
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')
    ->middleware(['auth.api:sanctum'])
    ->controller(MahasiswaController::class)->group(function () {
        Route::post('/update', 'update');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::delete('/{id}', 'destroy');

        // Custom
        Route::post('import/', 'import');
    });


/*
|--------------------------------------------------------------------------
| Mitra Routes
|--------------------------------------------------------------------------
*/
Route::prefix('mitra')
    ->middleware(['auth.api:sanctum'])
    ->controller(MitraController::class)->group(function () {
        Route::get('/magang/{id}', 'getMitraByMagang');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


/*
|--------------------------------------------------------------------------
| Magang Routes
|--------------------------------------------------------------------------
*/
Route::prefix('magang')
    ->middleware(['auth.api:sanctum'])
    ->controller(MagangController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


/*
|--------------------------------------------------------------------------
| Logbook Routes
|--------------------------------------------------------------------------
*/
Route::prefix('logbook')
    ->middleware(['auth.api:sanctum'])
    ->controller(LogbookController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


/*
|--------------------------------------------------------------------------
| Dokumen Magang Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dokumen-magang')
    ->middleware(['auth.api:sanctum'])
    ->controller(DokumenMagangController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::post('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


/*
|--------------------------------------------------------------------------
| Jadwal Presentasi Routes
|--------------------------------------------------------------------------
*/
Route::prefix('jadwal-presentasi')
    ->middleware(['auth.api:sanctum'])
    ->controller(JadwalPresentasiController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


/*
|--------------------------------------------------------------------------
| Dokumen Penilaian Mitra Routes
|--------------------------------------------------------------------------
*/
// Route::prefix('dokumen-penilaian-mitra')
// ->middleware(['auth.api:sanctum'])
// ->controller(DokumenNilaiMitraController::class)->group(function () {
//     Route::get('/', 'index');
//     Route::post('/', 'store');
//     Route::get('/{id}', 'show');
//     Route::put('/{id}', 'update');
//     Route::delete('/{id}', 'destroy');
// });


/*
|--------------------------------------------------------------------------
| Dosbing Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dosbing')
    ->middleware(['auth.api:sanctum'])
    ->controller(DosbingController::class)->group(function () {
        Route::get('/magang/{id}', 'getDosbingByMagangId');

        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth.api:sanctum'])
    ->controller(AdminController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

/*
|--------------------------------------------------------------------------
| Foto Magang Routes
|--------------------------------------------------------------------------
*/
Route::prefix('foto-magang')
    ->middleware(['auth.api:sanctum'])
    ->controller(FotoMagangController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

/*
|--------------------------------------------------------------------------
| Penilaian Mitra Routes
|--------------------------------------------------------------------------
*/
Route::prefix('penilaian-mitra')
    ->middleware(['auth.api:sanctum'])
    ->controller(NilaiMitraController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });



/*
|--------------------------------------------------------------------------
| Supervisor Routes
|--------------------------------------------------------------------------
*/
Route::prefix('supervisor')
    ->middleware(['auth.api:sanctum'])
    ->controller(SupervisorController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });


