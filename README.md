# SiPermata (Sistem Informasi Pelayanan Persuratan Mahasiswa)

SiPermata adalah aplikasi berbasis web yang dikembangkan menggunakan framework Laravel untuk memfasilitasi dan mengotomatisasi proses pengajuan, verifikasi, dan penerbitan berbagai jenis surat akademik bagi mahasiswa. Aplikasi ini juga terintegrasi dengan Single Sign-On (SSO) untuk autentikasi yang lebih aman dan terpusat.

## 🚀 Fitur Utama

- **Pengajuan Surat Mandiri**: Mahasiswa dapat mengajukan berbagai jenis surat secara online.
- **Sistem Verifikasi Bertingkat**: Proses persetujuan surat melibatkan alur yang jelas mulai dari Admin, BAK, hingga Dekan.
- **Tanda Tangan Elektronik & QR Code**: Dilengkapi dengan QR code untuk verifikasi keaslian dokumen secara online.
- **Integrasi SSO (Single Sign-On)**: Menggunakan akun SSO Universitas untuk login ke dalam sistem.
- **Sinkronisasi Data API**: Tersedia fitur sinkronisasi data mahasiswa, program studi, fakultas, dan penduduk langsung dari API pusat (SIMPT).
- **Generate Dokumen Otomatis**: Mendukung pengisian dan ekspor surat otomatis sesuai template institusi ke dalam format dokumen standar (`phpoffice/phpword`).
- **Rekapitulasi & Ekspor Laporan**: Fitur unduh rekapitulasi surat dan ekspor seluruh log pengajuan ke file Excel (`maatwebsite/excel`).

## 📑 Jenis Surat yang Dilayani

1. Surat Keterangan Aktif Kuliah
2. Surat Izin Penelitian
3. Surat Rekomendasi
4. Surat Pengantar PKL (Praktik Kerja Lapangan)
5. Surat Izin Observasi
6. Surat Keterangan Lulus (SKL)

## 👥 Hak Akses (Roles)

Sistem ini memiliki 4 peran (roles) utama dengan akses dashboard dan wewenang yang berbeda:

1. **Mahasiswa**: Dapat mengajukan permohonan surat, melihat progres (riwayat) pengajuan, serta mengunduh (download) surat yang telah selesai disetujui.
2. **Admin**: Mengelola pengaturan sistem, data master (template surat, jabatan, TTD), menjalankan sinkronisasi data API, mengelola kelayakan kelulusan mahasiswa, dan bisa menyetujui/menolak pengajuan surat dari mahasiswa.
3. **BAK (Biro Administrasi Akademik/Kemahasiswaan)**: Bertanggung jawab mengecek kelengkapan dan memvalidasi pengajuan surat, merekapitulasi data persuratan, dan manajemen mitra.
4. **Dekan**: Melakukan tinjauan dan persetujuan akhir (*approve* / *bulk approve*) pada pengajuan surat dan selanjutnya mengirimkan notifikasi via email ke mahasiswa.

## 🛠️ Tech Stack

- **Framework Backend**: Laravel 12.x
- **Bahasa Pemrograman**: PHP ^8.2
- **Database**: MySQL / SQLite (Bisa disesuaikan pada file env)
- **Library Tambahan**:
  - `phpoffice/phpword` (Manajemen dan Template Microsoft Word)
  - `maatwebsite/excel` (Ekspor/Impor ke Microsoft Excel)
  - `chillerlan/php-qrcode` (Pembuatan kode unik QR untuk verifikasi)
  - `rap2hpoutre/laravel-log-viewer` (Pemantauan Log Aplikasi secara GUI)
  - `yajra/laravel-datatables-oracle` (Optimalisasi manajemen query tabel)

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- Composer versi terbaru
- Node.js & NPM (untuk modul *build* aset frontend)
- Web Server (Apache/Nginx) atau Laragon/XAMPP
- Ekstensi PHP yang dibutuhkan: `mbstring`, `zip`, `gd`, `xml`, `curl`

## 💻 Panduan Instalasi & Konfigurasi

1. **Clone repositori** atau letakkan source code di dalam direktori server Anda (misalnya: `htdocs` atau `www`).
2. Masuk ke direktori proyek dan buka terminal.
3. **Install semua dependensi backend via Composer**:
   ```bash
   composer install
   ```
4. **Install dependensi Frontend**:
   ```bash
   npm install && npm run build
   ```
5. **Konfigurasi Environment (*Environment Variables*)**:
   - Salin dan ubah nama file `.env.example` menjadi `.env`.
   - Sesuaikan konfigurasi `DB_*` dengan database milik Anda.
   - Perhatikan pengaturan SSO pada file `.env` (misal variabel `SSO_ME_URL`, `SSO_API_URL`, dll).
6. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```
7. **Jalankan Migrasi Database**:
   Pastikan Anda sudah membuat *schema* database, lalu jalankan perintah:
   ```bash
   php artisan migrate
   ```
8. **Jalankan Aplikasi Lokal**:
   ```bash
   php artisan serve
   ```
   Akses aplikasi pada alamat: `http://localhost:8000`

## 🔐 Alur Integrasi SSO (Single Sign-On)

Aplikasi SiPermata bergantung kuat pada SSO pusat untuk memverifikasi autentikasi user (Mahasiswa, BAK, Dekan, dll).
* **Login**: Jika pengguna mengakses rute yang dilindungi dan belum memiliki sesi (termasuk jika sesi *expired*), sistem akan mengarahkan (*redirect*) ke `https://sso.unuja.ac.id`.
* **Logout**: Fitur logout SiPermata difungsikan khusus sebagai **Local Logout**. Ketika Anda menekan logout di SiPermata, hanya sesi lokal aplikasi yang akan dihancurkan, dan selanjutnya Anda kembali ke halaman SSO tanpa keluar dari *session* global SSO tersebut.

---
*Dikembangkan menggunakan kerangka Laravel dan dirancang untuk mengoptimalkan layanan administrasi persuratan akademik.*
