<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AkademikController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\TemplateControler;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/admin/data', [AdminController::class, 'getAdmin'])->name('admin.data');
    Route::resource('admin', AdminController::class);

    Route::get('/fakultas/data', [FakultasController::class, 'getFakultas'])->name('fakultas.data');
    Route::resource('fakultas', FakultasController::class);

    Route::get('/prodi/data', [ProdiController::class, 'getProdi'])->name('prodi.data');
    Route::resource('prodi', ProdiController::class);

    Route::get('/akademik/data', [AkademikController::class, 'getAkademik'])->name('akademik.data');
    Route::resource('akademik', AkademikController::class);

    Route::get('/mitra/data', [MitraController::class, 'getMitra'])->name('mitra.data');
    Route::resource('mitra', MitraController::class);

    Route::get('/mahasiswa/data', [MahasiswaController::class, 'getMahasiswa'])->name('mahasiswa.data');
    Route::get('/get-prodim/{fakultas_id}', [MahasiswaController::class, 'getProdi'])->name('getProdi');
    Route::resource('mahasiswa', MahasiswaController::class);

    Route::get('/penduduk/data', [PendudukController::class, 'getPenduduk'])->name('penduduk.data');
    Route::get('/get-prodip/{fakultas_id}', [PendudukController::class, 'getProdi'])->name('getProdi');
    Route::resource('penduduk', PendudukController::class);

    Route::get('/template/data', [TemplateControler::class, 'getTemplate'])->name('template.data');
    Route::get('/get-prodit/{fakultas_id}', [TemplateControler::class, 'getProdi'])->name('getProdi');
    Route::resource('template', TemplateControler::class);
});
