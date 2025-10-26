<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\TemplateControler;
use App\Http\Controllers\Admin\AkademikController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\BAK\BAKHistoryPengajuanController;
use App\Http\Controllers\BAK\BAKSuratAktifController;
use App\Http\Controllers\BAK\BAKTemplateController;
use App\Http\Controllers\BAK\DashboardController as BAKDashboardController;
use App\Http\Controllers\BAK\MitraController as BAKMitraController;
use App\Http\Controllers\Dekan\DashboardController as DekanDashboardController;
use App\Http\Controllers\Dekan\DekanHistoryPengajuanController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\MahasiswaHistoryPegajuan;
use App\Http\Controllers\Mahasiswa\MahasiswaSuratAktifController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login-proses', [LoginController::class, 'login'])->name('login-proses');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/admin/data', [AdminController::class, 'getAdmin'])->name('admin.data');
        Route::resource('users', AdminController::class)->only(['index', 'create', 'store', 'destroy']);

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
        Route::get('template/download/{id}', [TemplateControler::class, 'downloadTemplate'])->name('template.download');

        Route::get('/jabatan/data', [JabatanController::class, 'getJabatan'])->name('jabatan.data');
        Route::resource('jabatan', JabatanController::class);
    });

    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        Route::get('/surat-aktif/data', [MahasiswaSuratAktifController::class, 'getSuratAktif'])->name('surat-aktif.data');
        Route::resource('surat-aktif', MahasiswaSuratAktifController::class);

        Route::get('/history-pengajuan', [MahasiswaHistoryPegajuan::class, 'index'])->name('history.index');
        Route::get('/history/data', [MahasiswaHistoryPegajuan::class, 'getHistory'])->name('history.data');
        Route::get('/history/{id}/detail', [MahasiswaHistoryPegajuan::class, 'show'])->name('history.detail');
    });

    Route::middleware(['role:DEKAN'])->prefix('dekan')->name('dekan.')->group(function () {

        Route::get('/dashboard', [DekanDashboardController::class, 'index'])->name('dashboard');

        Route::get('/history-pengajuan', [DekanHistoryPengajuanController::class, 'index'])->name('history.index');
        Route::get('/history/data', [DekanHistoryPengajuanController::class, 'historyData'])->name('history.data');
        Route::get('/history/{id}/detail', [DekanHistoryPengajuanController::class, 'show'])->name('history.detail');
        Route::post('/history/{id}/reject', [DekanHistoryPengajuanController::class, 'reject'])->name('history.reject');
    });

    Route::middleware(['role:BAK'])->prefix('bak')->name('bak.')->group(function () {

        Route::get('/dashboard', [BAKDashboardController::class, 'index'])->name('dashboard');

        Route::get('/mitra/data', [BAKMitraController::class, 'getMitra'])->name('mitra.data');
        Route::resource('mitra', BAKMitraController::class);

        Route::get('/surat-aktif/data', [BAKSuratAktifController::class, 'getSuratAktif'])->name('surat-aktif.data');
        Route::resource('surat-aktif', BAKSuratAktifController::class)->except(['destroy']);

        Route::get('/history-pengajuan', [BAKHistoryPengajuanController::class, 'index'])->name('history.index');
        Route::get('/history/data', [BAKHistoryPengajuanController::class, 'historyData'])->name('history.data');
        Route::get('/history/{id}/detail', [BAKHistoryPengajuanController::class, 'show'])->name('history.detail');
        Route::post('/history/{id}/approve', [BAKHistoryPengajuanController::class, 'approve'])->name('history.approve');
        Route::post('/history/{id}/reject', [BAKHistoryPengajuanController::class, 'reject'])->name('history.reject');
    });
});
