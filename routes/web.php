<?php

use App\Http\Controllers\Admin\AkademikController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\ProdiController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/fakultas/data', [FakultasController::class, 'getFakultas'])->name('fakultas.data');
    Route::resource('fakultas', FakultasController::class);

    Route::get('/prodi/data', [ProdiController::class, 'getProdi'])->name('prodi.data');
    Route::resource('prodi', ProdiController::class);

    Route::get('/akademik/data', [AkademikController::class, 'getAkademik'])->name('akademik.data');
    Route::resource('akademik', AkademikController::class);

    Route::get('/mitra/data', [MitraController::class, 'getMitra'])->name('mitra.data');
    Route::resource('mitra', MitraController::class);
});
