# Rollout of Official Document Card View to All Mahasiswa Letters Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Roll out the document card view, instant reactive filtering, search-as-you-type, client-side pagination, dark mode, and mobile/tablet responsiveness from `surat_aktif` to all remaining 5 letter types for Mahasiswa (`surat_penelitian`, `surat_observasi`, `surat_rekomendasi`, `surat_pkl`, and `surat_lulus`).

**Architecture:** Each letter controller's `index()` method will load all student submissions via Eloquent with relations and precalculate status counts in memory. Each letter's `index.blade.php` will adopt the SKPI-inspired Official Document Card design, with full dark mode (`[data-bs-theme="dark"]`), responsive styling (<768px, 768-1024px, >1024px), clean comment-free markup, and letter-specific metadata (mitra, judul, tanggal observasi/PKL/pelaksanaan, anggota kelompok).

**Tech Stack:** Laravel 11 / Blade, jQuery, Bootstrap 5 / Metronic 8 Theme, FontAwesome 5/6, SweetAlert2.

## Global Constraints
- Do NOT run `git commit` ("jangan langsung commit semua perubahan").
- Maintain backward compatibility: keep existing `getSuratXxx()` methods intact.
- Follow SKPI design tokens: `border-radius: 0.85rem` on cards and banners, `0.55rem` on buttons, `border-gray-400 border border-dashed` on filter toolbars.
- Clean code: No verbose development comments in Blade templates.
- Fully support Dark Mode (`[data-bs-theme="dark"]` & `[data-theme="dark"]`) matching Metronic 8's `#1e1e2d` palette.
- Verify each view with `php artisan view:clear` and `php artisan view:cache` after editing.

---

### Task 1: Surat Izin Penelitian (`surat_penelitian`)

**Files:**
- Modify: `app/Http/Controllers/Mahasiswa/MahasiswaSuratPenelitianController.php`
- Modify: `resources/views/mahasiswa/surat_penelitian/index.blade.php`

**Interfaces:**
- Consumes: `SuratPenelitian::with(['akademik', 'mahasiswa', 'mahasiswa.prodi', 'mitra'])`
- Produces: `$suratPenelitian` (Collection) and `$stats` (array) passed to `mahasiswa.surat_penelitian.index`

- [ ] **Step 1: Update `MahasiswaSuratPenelitianController@index`**
  Fetch `$suratPenelitian` and compute `$stats` (`total`, `pengajuan`, `proses`, `diterima`, `ditolak`). Pass both to view. Keep `getSuratPenelitian()` intact.
- [ ] **Step 2: Update `resources/views/mahasiswa/surat_penelitian/index.blade.php`**
  Implement Hero Banner with `fas fa-flask`, filter toolbar card with live search, `.letter-card` displaying `judul_penelitian`, `mitra`, and `periode penelitian`, timeline steps, dark mode, responsive styles, and clean comment-free code.
- [ ] **Step 3: Verify View Compilation**
  Run `php artisan view:clear` and `php artisan view:cache`.

---

### Task 2: Surat Permohonan Observasi (`surat_observasi`)

**Files:**
- Modify: `app/Http/Controllers/Mahasiswa/MahasiswaSuratObservasiController.php`
- Modify: `resources/views/mahasiswa/surat_observasi/index.blade.php`

**Interfaces:**
- Consumes: `SuratObservasi::with(['akademik', 'mahasiswa', 'mahasiswa.prodi', 'mitra'])`
- Produces: `$suratObservasi` (Collection) and `$stats` (array) passed to `mahasiswa.surat_observasi.index`

- [ ] **Step 1: Update `MahasiswaSuratObservasiController@index`**
  Fetch `$suratObservasi` and compute `$stats`. Pass both to view.
- [ ] **Step 2: Update `resources/views/mahasiswa/surat_observasi/index.blade.php`**
  Implement Hero Banner with `fas fa-eye`, filter toolbar card with live search, `.letter-card` displaying `keperluan`, `mitra`, `tgl_observasi`, and `anggota_kelompok` badge, timeline steps, dark mode, responsive styles, and clean code.
- [ ] **Step 3: Verify View Compilation**
  Run `php artisan view:clear` and `php artisan view:cache`.

---

### Task 3: Surat Rekomendasi (`surat_rekomendasi`)

**Files:**
- Modify: `app/Http/Controllers/Mahasiswa/MahasiswaSuratRekomendasiController.php`
- Modify: `resources/views/mahasiswa/surat_rekomendasi/index.blade.php`

**Interfaces:**
- Consumes: `SuratRekomendasi::with(['akademik', 'mahasiswa', 'mahasiswa.prodi'])`
- Produces: `$suratRekomendasi` (Collection) and `$stats` (array) passed to `mahasiswa.surat_rekomendasi.index`

- [ ] **Step 1: Update `MahasiswaSuratRekomendasiController@index`**
  Fetch `$suratRekomendasi` and compute `$stats`. Pass both to view.
- [ ] **Step 2: Update `resources/views/mahasiswa/surat_rekomendasi/index.blade.php`**
  Implement Hero Banner with `fas fa-thumbs-up`, filter toolbar card with live search, `.letter-card` displaying `keperluan`, `penyelenggara`, and `tgl_pelaksanaan`, timeline steps, dark mode, responsive styles, and clean code.
- [ ] **Step 3: Verify View Compilation**
  Run `php artisan view:clear` and `php artisan view:cache`.

---

### Task 4: Surat Permohonan PKL (`surat_pkl`)

**Files:**
- Modify: `app/Http/Controllers/Mahasiswa/MahasiswaSuratPKLController.php`
- Modify: `resources/views/mahasiswa/surat_pkl/index.blade.php`

**Interfaces:**
- Consumes: `SuratPKL::with(['akademik', 'mahasiswa', 'mahasiswa.prodi', 'mitra'])`
- Produces: `$suratPKL` (Collection) and `$stats` (array) passed to `mahasiswa.surat_pkl.index`

- [ ] **Step 1: Update `MahasiswaSuratPKLController@index`**
  Fetch `$suratPKL` and compute `$stats`. Pass both to view.
- [ ] **Step 2: Update `resources/views/mahasiswa/surat_pkl/index.blade.php`**
  Implement Hero Banner with `fas fa-briefcase`, filter toolbar card with live search, `.letter-card` displaying `mitra`, `tgl_mulai` s/d `tgl_selesai`, and `anggota_kelompok` badge, timeline steps, dark mode, responsive styles, and clean code.
- [ ] **Step 3: Verify View Compilation**
  Run `php artisan view:clear` and `php artisan view:cache`.

---

### Task 5: Surat Keterangan Lulus (`surat_lulus`)

**Files:**
- Modify: `app/Http/Controllers/Mahasiswa/MahasiswaSuratLulusController.php`
- Modify: `resources/views/mahasiswa/surat_lulus/index.blade.php`

**Interfaces:**
- Consumes: `SuratLulus::with(['akademik', 'mahasiswa', 'mahasiswa.prodi'])` and `$isEligible` check
- Produces: `$suratLulus` (Collection), `$stats` (array), and `$isEligible` (bool) passed to `mahasiswa.surat_lulus.index`

- [ ] **Step 1: Update `MahasiswaSuratLulusController@index`**
  Preserve `$isEligible` calculation, fetch `$suratLulus` and compute `$stats`. Pass all to view.
- [ ] **Step 2: Update `resources/views/mahasiswa/surat_lulus/index.blade.php`**
  Implement Hero Banner with `fas fa-graduation-cap`, conditional eligibility notice if `!$isEligible`, filter toolbar card with live search, `.letter-card` displaying `judul_penelitian` (Skripsi/TA), `tempat_lahir`, and `tgl_lahir`, timeline steps, dark mode, responsive styles, and clean code.
- [ ] **Step 3: Verify View Compilation**
  Run `php artisan view:clear` and `php artisan view:cache`.

---

### Task 6: Comprehensive Verification & Consistency Check

- [ ] **Step 1: Run Full Blade Cache Check**
  `php artisan view:clear && php artisan view:cache`
- [ ] **Step 2: Render Check via Scratch Script**
  Verify all 5 views compile and render expected HTML with mock models.
- [ ] **Step 3: Git Status Check**
  Confirm only intended files are modified and no commits were made.
