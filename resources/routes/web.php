<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\ProdiController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\TemplateControler;
use App\Http\Controllers\Admin\TtdSuratConroller;
use App\Http\Controllers\Admin\AkademikController;
use App\Http\Controllers\Admin\FakultasController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\SuratPKLController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\BAK\BAKSuratPKLController;
use App\Http\Controllers\BAK\BAKTtdSuratController;
use App\Http\Controllers\Admin\SuratAktifController;
use App\Http\Controllers\Admin\SuratLulusController;
use App\Http\Controllers\BAK\BAKSuratAktifController;
use App\Http\Controllers\BAK\BAKSuratLulusController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use App\Http\Controllers\Admin\SuratObservasiController;
use App\Http\Controllers\Admin\SSOSinkronisasiController;
use App\Http\Controllers\Admin\SuratPenelitianController;
use App\Http\Controllers\BAK\BAKSuratObservasiController;
use App\Http\Controllers\Admin\HistoryPengajuanController;
use App\Http\Controllers\Admin\SuratRekomendasiController;
use App\Http\Controllers\BAK\BAKSuratPenelitianController;
use App\Http\Controllers\BAK\BAKHistoryPengajuanController;
use App\Http\Controllers\BAK\BAKSuratRekomendasiController;
use App\Http\Controllers\Mahasiswa\MahasiswaHistoryPegajuan;
use App\Http\Controllers\Dekan\DekanHistoryPengajuanController;
use App\Http\Controllers\Mahasiswa\MahasiswaSuratPKLController;
use App\Http\Controllers\Mahasiswa\MahasiswaSuratAktifController;
use App\Http\Controllers\Mahasiswa\MahasiswaSuratLulusController;
use App\Http\Controllers\BAK\MitraController as BAKMitraController;
use App\Http\Controllers\Mahasiswa\MahasiswaSuratObservasiController;
use App\Http\Controllers\Mahasiswa\MahasiswaSuratPenelitianController;
use App\Http\Controllers\Mahasiswa\MahasiswaSuratRekomendasiController;
use App\Http\Controllers\BAK\DashboardController as BAKDashboardController;
use App\Http\Controllers\Dekan\DashboardController as DekanDashboardController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;

Route::get('/sso', [SsoController::class, 'sso']);
Route::get('/sso/logout/{sessionId}', [SsoController::class, 'logout']);
Route::get('log-viewer', [LogViewerController::class, 'index'])->name('log-viewer');

Route::middleware('guest')->group(function () {
    // Route::get('/login', function () {
    //     return redirect()->away('https://sso.unuja.ac.id');
    // })->name('login');
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login-proses', [LoginController::class, 'login'])->name('login-proses');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/verifikasi/pdf/{jenis}/{id}', [VerifikasiController::class, 'streamPdf'])
    ->name('verifikasi.streamPdf');

Route::get('/verifikasi/surat-aktif/{id}', [VerifikasiController::class, 'verifySuratAktif'])
    ->name('verifikasi.surat-aktif');

Route::get('/verifikasi/surat-izin-penelitian/{id}', [VerifikasiController::class, 'verifySuratPenelitian'])
    ->name('verifikasi.surat-izin-penelitian');

Route::get('/verifikasi/surat-rekomendasi/{id}', [VerifikasiController::class, 'verifySuratRekomendasi'])
    ->name('verifikasi.surat-rekomendasi');

Route::get('/verifikasi/surat-pkl/{id}', [VerifikasiController::class, 'verifySuratPKL'])
    ->name('verifikasi.surat-pkl');

Route::get('/verifikasi/surat-observasi/{id}', [VerifikasiController::class, 'verifySuratObservasi'])
    ->name('verifikasi.surat-observasi');

Route::get('/verifikasi/surat-keterangan-lulus/{id}', [VerifikasiController::class, 'verifySuratLulus'])
    ->name('verifikasi.surat-keterangan-lulus');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/sso-sinkronisasi', [SSOSinkronisasiController::class, 'index'])->name('sso.sinkronisasi');
        Route::post('/sso/refresh-token', [SSOSinkronisasiController::class, 'refresh'])->name('sso.refresh-token');

        Route::get('/admin/data', [AdminController::class, 'getAdmin'])->name('admin.data');
        Route::resource('users', AdminController::class)->only(['index', 'create', 'store', 'destroy']);

        Route::get('/fakultas/data', [FakultasController::class, 'getFakultas'])->name('fakultas.data');
        Route::resource('fakultas', FakultasController::class)->only(['index', 'show']);
        Route::post('/fakultas/sync', [FakultasController::class, 'syncFromApi'])->name('fakultas.sync');

        Route::get('/prodi/data', [ProdiController::class, 'getProdi'])->name('prodi.data');
        Route::resource('prodi', ProdiController::class)->only(['index', 'show']);
        Route::post('/prodi/sync',  [ProdiController::class, 'syncFromApi'])->name('prodi.sync');

        Route::get('/akademik/data', [AkademikController::class, 'getAkademik'])->name('akademik.data');
        Route::resource('akademik', AkademikController::class);

        Route::get('/mitra/data', [MitraController::class, 'getMitra'])->name('mitra.data');
        Route::resource('mitra', MitraController::class);

        Route::post('/mahasiswa/data', [MahasiswaController::class, 'getMahasiswa'])->name('mahasiswa.data');
        Route::resource('mahasiswa', MahasiswaController::class)->only(['index', 'show']);
        Route::post('/mahasiswa/sync', [MahasiswaController::class, 'syncFromApi'])->name('mahasiswa.sync');

        Route::get('/penduduk/data', [PendudukController::class, 'getPenduduk'])->name('penduduk.data');
        Route::resource('penduduk', PendudukController::class)->only(['index', 'show']);
        Route::post('/penduduk/sync', [PendudukController::class, 'syncFromApi'])->name('penduduk.sync');

        Route::get('/template/data', [TemplateControler::class, 'getTemplate'])->name('template.data');
        Route::resource('template', TemplateControler::class);
        Route::get('template/download/{id}', [TemplateControler::class, 'downloadTemplate'])->name('template.download');

        Route::get('/ttdSurat/data', [TtdSuratConroller::class, 'getTtdSurat'])->name('ttdSurat.data');
        Route::resource('ttdSurat', TtdSuratConroller::class);

        Route::get('/jabatan/data', [JabatanController::class, 'getJabatan'])->name('jabatan.data');
        Route::resource('jabatan', JabatanController::class);

        Route::get('/surat-aktif/data', [SuratAktifController::class, 'getSuratAktif'])->name('surat-aktif.data');
        Route::resource('surat-aktif', SuratAktifController::class);

        Route::get('/surat-izin-penelitian/data', [SuratPenelitianController::class, 'getSuratPenelitian'])->name('surat-izin-penelitian.data');
        Route::resource('surat-izin-penelitian', SuratPenelitianController::class)->except(['destroy']);

        Route::get('/surat-rekomendasi/data', [SuratRekomendasiController::class, 'getSuratRekomendasi'])->name('surat-rekomendasi.data');
        Route::resource('surat-rekomendasi', SuratRekomendasiController::class)->except(['destroy']);

        Route::get('/surat-pkl/data', [SuratPKLController::class, 'getSuratPKL'])->name('surat-pkl.data');
        Route::resource('surat-pkl', SuratPKLController::class)->except(['destroy']);

        Route::get('/surat-observasi/data', [SuratObservasiController::class, 'getSuratObservasi'])->name('surat-observasi.data');
        Route::resource('surat-observasi', SuratObservasiController::class)->except(['destroy']);

        Route::get('/surat-keterangan-lulus/data', [SuratLulusController::class, 'getSuratLulus'])->name('surat-keterangan-lulus.data');
        Route::resource('surat-keterangan-lulus', SuratLulusController::class)->except(['destroy']);

        Route::get('/history/data', [HistoryPengajuanController::class, 'getHistory'])->name('history.data');
        Route::resource('history-pengajuan', HistoryPengajuanController::class)->only(['index', 'show']);
        Route::post('/history/{id}/approve', [HistoryPengajuanController::class, 'approve'])->name('history.approve');
        Route::post('/history/{id}/reject', [HistoryPengajuanController::class, 'reject'])->name('history.reject');
        Route::get('surat/view/{tabel}/{id}', [HistoryPengajuanController::class, 'viewGeneratedFile'])->name('surat.view');
    });

    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {

        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');

        Route::get('/surat-aktif/data', [MahasiswaSuratAktifController::class, 'getSuratAktif'])->name('surat-aktif.data');
        Route::resource('surat-aktif', MahasiswaSuratAktifController::class)->except(['destroy']);

        Route::get('/surat-izin-penelitian/data', [MahasiswaSuratPenelitianController::class, 'getSuratPenelitian'])->name('surat-izin-penelitian.data');
        Route::resource('surat-izin-penelitian', MahasiswaSuratPenelitianController::class)->except(['destroy']);

        Route::get('/surat-rekomendasi/data', [MahasiswaSuratRekomendasiController::class, 'getSuratRekomendasi'])->name('surat-rekomendasi.data');
        Route::resource('surat-rekomendasi', MahasiswaSuratRekomendasiController::class)->except(['destroy']);

        Route::get('/surat-pkl/data', [MahasiswaSuratPKLController::class, 'getSuratPKL'])->name('surat-pkl.data');
        Route::resource('surat-pkl', MahasiswaSuratPKLController::class)->except(['destroy']);

        Route::get('/surat-observasi/data', [MahasiswaSuratObservasiController::class, 'getSuratObservasi'])->name('surat-observasi.data');
        Route::resource('surat-observasi', MahasiswaSuratObservasiController::class)->except(['destroy']);

        Route::get('/surat-keterangan-lulus/data', [MahasiswaSuratLulusController::class, 'getSuratLulus'])->name('surat-keterangan-lulus.data');
        Route::resource('surat-keterangan-lulus', MahasiswaSuratLulusController::class)->except(['destroy']);

        Route::get('/history-pengajuan', [MahasiswaHistoryPegajuan::class, 'index'])->name('history.index');
        Route::get('/history/data', [MahasiswaHistoryPegajuan::class, 'getHistory'])->name('history.data');
        Route::get('/history/{id}/detail', [MahasiswaHistoryPegajuan::class, 'show'])->name('history.detail');
        Route::get('surat/view/{tabel}/{id}', [MahasiswaHistoryPegajuan::class, 'viewGeneratedFile'])->name('surat.view');
    });

    Route::middleware(['role:DEKAN'])->prefix('dekan')->name('dekan.')->group(function () {

        Route::get('/dashboard', [DekanDashboardController::class, 'index'])->name('dashboard');

        Route::get('/history-pengajuan', [DekanHistoryPengajuanController::class, 'index'])->name('history.index');
        Route::get('/history/data', [DekanHistoryPengajuanController::class, 'historyData'])->name('history.data');
        Route::get('/history/{id}/detail', [DekanHistoryPengajuanController::class, 'show'])->name('history.detail');
        Route::post('/history/{id}/approve', [DekanHistoryPengajuanController::class, 'approve'])->name('history.approve');
        Route::post('/history/{id}/reject', [DekanHistoryPengajuanController::class, 'reject'])->name('history.reject');
        Route::get('surat/view/{tabel}/{id}', [DekanHistoryPengajuanController::class, 'viewGeneratedFile'])->name('surat.view');
        Route::post('surat/kirim/{tabel}/{id}', [DekanHistoryPengajuanController::class, 'sendEmailMahasiswa'])->name('surat.send');
    });

    Route::middleware(['role:BAK'])->prefix('bak')->name('bak.')->group(function () {

        Route::get('/dashboard', [BAKDashboardController::class, 'index'])->name('dashboard');

        Route::get('/mitra/data', [BAKMitraController::class, 'getMitra'])->name('mitra.data');
        Route::resource('mitra', BAKMitraController::class);

        Route::get('/ttdSurat/data', [BAKTtdSuratController::class, 'getTtdSurat'])->name('ttdSurat.data');
        Route::resource('ttdSurat', BAKTtdSuratController::class);

        Route::get('/surat-aktif/data', [BAKSuratAktifController::class, 'getSuratAktif'])->name('surat-aktif.data');
        Route::resource('surat-aktif', BAKSuratAktifController::class)->except(['destroy']);

        Route::get('/surat-izin-penelitian/data', [BAKSuratPenelitianController::class, 'getSuratPenelitian'])->name('surat-izin-penelitian.data');
        Route::resource('surat-izin-penelitian', BAKSuratPenelitianController::class)->except(['destroy']);

        Route::get('/surat-rekomendasi/data', [BAKSuratRekomendasiController::class, 'getSuratRekomendasi'])->name('surat-rekomendasi.data');
        Route::resource('surat-rekomendasi', BAKSuratRekomendasiController::class)->except(['destroy']);

        Route::get('/surat-pkl/data', [BAKSuratPKLController::class, 'getSuratPKL'])->name('surat-pkl.data');
        Route::resource('surat-pkl', BAKSuratPKLController::class)->except(['destroy']);

        Route::get('/surat-observasi/data', [BAKSuratObservasiController::class, 'getSuratObservasi'])->name('surat-observasi.data');
        Route::resource('surat-observasi', BAKSuratObservasiController::class)->except(['destroy']);

        Route::get('/surat-keterangan-lulus/data', [BAKSuratLulusController::class, 'getSuratLulus'])->name('surat-keterangan-lulus.data');
        Route::resource('surat-keterangan-lulus', BAKSuratLulusController::class)->except(['destroy']);

        Route::get('/history-pengajuan', [BAKHistoryPengajuanController::class, 'index'])->name('history.index');
        Route::get('/history/data', [BAKHistoryPengajuanController::class, 'historyData'])->name('history.data');
        Route::get('/history/{id}/detail', [BAKHistoryPengajuanController::class, 'show'])->name('history.detail');
        Route::post('/history/{id}/approve', [BAKHistoryPengajuanController::class, 'approve'])->name('history.approve');
        Route::post('/history/{id}/reject', [BAKHistoryPengajuanController::class, 'reject'])->name('history.reject');
        Route::get('surat/view/{tabel}/{id}', [BAKHistoryPengajuanController::class, 'viewGeneratedFile'])->name('surat.view');
    });
});
