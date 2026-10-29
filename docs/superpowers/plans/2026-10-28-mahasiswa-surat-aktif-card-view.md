# Tampilan Index Surat Keterangan Aktif Mahasiswa Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.
> **Note:** Per user's instruction, do NOT commit to git automatically.

**Goal:** Mengubah tampilan index pengajuan Surat Keterangan Aktif pada role Mahasiswa dari format tabel DataTables menjadi format grid kartu dokumen resmi (Official Document Card View) yang interaktif, elegan, dan informatif.

**Architecture:** Controller `MahasiswaSuratAktifController@index` diperbarui untuk melayani data pengajuan terpaginasi beserta statistik status dan filter pencarian, kemudian view Blade `mahasiswa.surat_aktif.index` didesain ulang dengan kartu bergaya surat resmi (Kop mini, cap status, kutipan keperluan, timeline 4-tahap, dan tombol aksi terintegrasi).

**Tech Stack:** Laravel 10 / PHP 8.2, Blade, Bootstrap 5 & Metronic 8 Theme, FontAwesome Icons.

## Global Constraints
- Jangan lakukan git commit (sesuai instruksi user: "jangan langsung commit semua perubahan").
- Pertahankan method `getSuratAktif()` di controller agar tidak merusak kompatibilitas.
- Pertahankan fungsionalitas tombol "Tambah Pengajuan", "Detail", dan "Edit" (jika ditolak).

---

### Task 1: Update Controller `MahasiswaSuratAktifController`
- File: `app/Http/Controllers/Mahasiswa/MahasiswaSuratAktifController.php`
- [ ] Inject `Request $request` pada method `index(Request $request)`.
- [ ] Dapatkan identitas mahasiswa login via `Auth::user()->mahasiswa?->nim`.
- [ ] Buat query `SuratAktif` dengan relasi `akademik`, `mahasiswa`, `mahasiswa.prodi`.
- [ ] Implementasikan filter berdasarkan `status` (`pengajuan`, `proses`, `diterima`, `ditolak`).
- [ ] Implementasikan pencarian berdasarkan keyword `search` pada kolom `keperluan`, `kategori`, dan `no_surat`.
- [ ] Hitung statistik hitungan per status untuk tab filter (`total`, `pengajuan`, `proses`, `diterima`, `ditolak`).
- [ ] Jalankan paginasi data `paginate(6)->withQueryString()`.
- [ ] Kirim `$suratAktif` dan `$stats` ke view `mahasiswa.surat_aktif.index`.
- [ ] Jalankan linter / syntax check `php -l` untuk memastikan tidak ada kesalahan sintaks.

### Task 2: Redesain View `resources/views/mahasiswa/surat_aktif/index.blade.php`
- File: `resources/views/mahasiswa/surat_aktif/index.blade.php`
- [ ] Bersihkan dependensi DataTables CSS dan JS dari view index ini.
- [ ] Tambahkan custom CSS pendukung untuk estetika lembar dokumen (efek kertas surat, watermark samar, cap stempel status, mini timeline).
- [ ] Buat Header Section dengan Title, deskripsi, dan tombol utama **"+ Tambah Pengajuan"**.
- [ ] Buat Toolbar Filter Status (Pills button dengan counter badge) dan Search Bar interaktif.
- [ ] Buat Grid Kartu Lembar Dokumen Resmi:
  - Header kartu: Aksen garis kop surat ganda, ikon dokumen, judul, badge kategori (UMUM/PNS/PPPK), dan Stamp Badge Status.
  - Body kartu: Blok kutipan keperluan surat, meta tahun akademik dan tanggal pengajuan, serta mini progress tracker (Diajukan ➜ Verifikasi BAK ➜ TTD Dekan ➜ Selesai).
  - Alert box untuk catatan revisi/alasan penolakan jika status `ditolak`.
  - Footer kartu: Tombol Lihat Detail dan Tombol Edit (jika ditolak).
- [ ] Buat Empty State yang menarik jika data kosong atau hasil pencarian tidak ditemukan.
- [ ] Tambahkan navigasi paginasi Bootstrap 5 (`{{ $suratAktif->links() }}`).
- [ ] Pertahankan notifikasi SweetAlert success/failed dari session.

### Task 3: Verifikasi & Uji Tampilan
- [ ] Jalankan syntax check `php -l app/Http/Controllers/Mahasiswa/MahasiswaSuratAktifController.php`.
- [ ] Jalankan pengujian render view menggunakan Artisan tinker atau curl/route check untuk memastikan view berhasil di-compile tanpa error.
- [ ] Periksa responsivitas dan konsistensi styling tema Metronic 8.
