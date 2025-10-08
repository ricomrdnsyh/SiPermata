<?php

use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\ProdiController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/fakultas/data', [FakultasController::class, 'getFakultas'])->name('fakultas.data');
    Route::resource('fakultas', FakultasController::class);

    Route::get('/prodi/data', [ProdiController::class, 'getProdi'])->name('prodi.data');
    Route::resource('prodi', ProdiController::class);
});
