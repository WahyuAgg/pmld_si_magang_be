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



/*
|--------------------------------------------------------------------------
| User Route
|--------------------------------------------------------------------------
*/
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('user')
    ->middleware([])
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
->middleware([])
->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login');
    Route::post('/logout', 'logout');
});


/*
|--------------------------------------------------------------------------
| Mahasiswa Routes
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')
->middleware([])
->controller(MahasiswaController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});


/*
|--------------------------------------------------------------------------
| Mitra Routes
|--------------------------------------------------------------------------
*/
Route::prefix('mitra')
->middleware([])
->controller(MitraController::class)->group(function () {
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
->middleware([])
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
->middleware([])
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
->middleware([])
->controller(DokumenMagangController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});


/*
|--------------------------------------------------------------------------
| Jadwal Presentasi Routes
|--------------------------------------------------------------------------
*/
Route::prefix('jadwal-presentasi')
->middleware([])
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
Route::prefix('dokumen-penilaian-mitra')
->middleware([])
->controller(DokumenNilaiMitraController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{id}', 'show');
    Route::put('/{id}', 'update');
    Route::delete('/{id}', 'destroy');
});


/*
|--------------------------------------------------------------------------
| Dosbing Routes
|--------------------------------------------------------------------------
*/
Route::prefix('dosbing')
->middleware([])
->controller(DosbingController::class)->group(function () {
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
    ->middleware([])
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
    ->middleware([])
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
    ->middleware([])
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
| Progress Magang Routes
|--------------------------------------------------------------------------
*/
Route::prefix('progress-magang')
    ->middleware([])
    ->controller(ProgressMagangController::class)
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
    ->middleware([])
    ->controller(SupervisorController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });

/*
|--------------------------------------------------------------------------
| Tahun Ajaran Routes
|--------------------------------------------------------------------------
*/
Route::prefix('tahun-ajaran')
    ->middleware([])
    ->controller(TahunAjaranController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');

        // Custom route tambahan
        Route::get('/aktif', 'aktif');
    });
