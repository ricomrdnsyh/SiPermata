# Design Spec: Tampilan Index Surat Keterangan Aktif Mahasiswa (Official Document Card View)

**Tanggal:** 28 Oktober 2026  
**Status:** Disetujui  
**Target:** `MahasiswaSuratAktifController` & `resources/views/mahasiswa/surat_aktif/index.blade.php`  
**Proyek:** SiPermata (Universitas Nurul Jadid)

---

## 1. Latar Belakang & Tujuan
Tampilan daftar (*index*) pengajuan surat pada role Mahasiswa saat ini menggunakan format tabel standar DataTables. Format ini dinilai kaku dan kurang menarik untuk mahasiswa. 

Tujuan dari pembaruan ini adalah mengubah tampilan daftar pengajuan Surat Keterangan Aktif menjadi tampilan berbasis kartu (*card grid*) yang elegan dan menyerupai fisik lembaran dokumen surat resmi (*Official Document Card*), lengkap dengan cap stempel status, mini linimasa progres persetujuan (Diajukan ➜ BAK ➜ Dekan ➜ Selesai), filter status cepat, pencarian, dan tombol aksi terintegrasi.

---

## 2. Arsitektur & Alur Data

### 2.1 Controller (`app/Http/Controllers/Mahasiswa/MahasiswaSuratAktifController.php`)
- **Method `index(Request $request)`**:
  - Mengambil identitas mahasiswa login (`Auth::user()->mahasiswa->nim`).
  - Menyiapkan kueri `SuratAktif` dengan relasi `akademik`, `mahasiswa`, dan `mahasiswa.prodi`.
  - Menerapkan filter status jika parameter `status` dipilih (`pengajuan`, `proses`, `diterima`, `ditolak`).
  - Menerapkan filter pencarian jika parameter `search` diisi (mencari pada kolom `keperluan`, `kategori`, atau nomor surat jika ada).
  - Mengambil data dengan pengurutan terbaru (`latest('created_at')`) dan paginasi `paginate(6)->withQueryString()`.
  - Menghitung statistik ringkasan status (`total`, `pengajuan`, `proses`, `diterima`, `ditolak`) untuk ditampilkan pada tombol filter tab.
  - Melempar variabel `$suratAktif` dan `$stats` ke view `mahasiswa.surat_aktif.index`.
- **Method `getSuratAktif()`**:
  - Tetap dipertahankan untuk menjamin *backward-compatibility* bila ada komponen lain atau endpoint API yang masih mengaksesnya.

### 2.2 View (`resources/views/mahasiswa/surat_aktif/index.blade.php`)
- Mengganti elemen `<table>` DataTables dengan komponen tata letak:
  1. **Top Header & Action Bar**:
     - Judul halaman & deskripsi singkat.
     - Tombol aksi utama **"+ Tambah Pengajuan"** (`btn-primary` dengan ikon plus).
  2. **Filter & Search Bar**:
     - Filter tab berbentuk *pill buttons* dengan counter jumlah pengajuan per status (Semua, Menunggu BAK, Menunggu Dekan, Disetujui, Ditolak).
     - Kolom input pencarian dengan tombol submit dan reset.
  3. **Grid Kartu Surat (Responsive 1/2/3 Columns)**:
     - Kartu bergaya lembar dokumen resmi kertas putih bersih, border halus, bayangan lembut, dan ornamen garis kop surat.
     - **Header Kartu**:
       - Ikon dokumen & label jenis surat ("Surat Keterangan Aktif").
       - Badge kategori: UMUM / PNS / PPPK.
       - Nomor identifikasi pengajuan.
       - Badge cap stempel status (Menunggu BAK / Menunggu Dekan / Disetujui / Ditolak) dengan ikon dan warna yang kontras.
     - **Isi Kartu**:
       - Kotak kutipan keperluan surat (*"Keperluan: ..."*).
       - Meta informasi: Tahun Akademik & Tanggal Pengajuan (format tanggal Indonesia + waktu relatif).
       - **Mini Progress Tracker**: Indikator 4 tahapan status (1. Diajukan ➜ 2. Verifikasi BAK ➜ 3. TTD Dekan ➜ 4. Selesai).
       - Alert catatan revisi/alasan penolakan jika status `ditolak` atau memiliki catatan.
     - **Footer Kartu**:
       - Tombol **Lihat Detail** (`route('mahasiswa.surat-aktif.show', $id)`).
       - Tombol **Perbaiki / Edit** (`route('mahasiswa.surat-aktif.edit', $id)`) khusus jika status `ditolak`.
  4. **Empty State**:
     - Ditampilkan jika belum ada pengajuan atau hasil pencarian tidak ditemukan.
     - Ilustrasi dokumen & pesan ramah beserta tombol untuk membuat pengajuan baru.
  5. **Paginasi**:
     - Link navigasi halaman Bootstrap 5 bawaan Laravel (`$suratAktif->links()`).

---

## 3. Komponen Visual & Desain UI

### 3.1 Styling & Estetika Lembaran Surat
- **Latar & Garis**: Latar putih `#ffffff` dengan aksen border atas tipis yang melengkung dan garis dobel kop surat (`border-top: 3px solid var(--bs-primary)` atau warna status).
- **Stempel Status**:
  - `pengajuan`: Badge amber/warning bertuliskan "Menunggu BAK" dengan ikon `fas fa-clock`.
  - `proses`: Badge cyan/info bertuliskan "Menunggu Dekan" dengan ikon `fas fa-file-signature`.
  - `diterima`: Badge emerald/success bertuliskan "Disetujui" dengan ikon `fas fa-check-circle`.
  - `ditolak`: Badge ruby/danger bertuliskan "Ditolak" dengan ikon `fas fa-times-circle`.
- **Mini Alur Progress Tracker**:
  - Baris horisontal 4 titik bulat kecil dengan garis penghubung yang aktif sesuai status tahapan surat.

---

## 4. Penanganan Kesalahan & Edge Cases
- **Mahasiswa belum memiliki profil/NIM**: Tampilkan pesan peringatan yang elegan tanpa memicu fatal error.
- **Pencarian tanpa hasil**: Tampilkan empty state spesifik pencarian ("Tidak ada surat yang cocok dengan kata kunci pencarian") dan tombol "Reset Filter".
- **Catatan kosong**: Jika tidak ada catatan, elemen catatan disembunyikan agar kartu tetap ringkas dan padat informasi.

---

## 5. Rencana Verifikasi
- Uji sintaks PHP pada controller menggunakan `php -l`.
- Uji render view dengan memeriksa sintaks Blade dan memastikan tidak ada variabel yang *undefined*.
- Verifikasi filter status dan pencarian berfungsi dengan baik.
- Pastikan tombol navigasi ("Detail", "Edit", "Paginasi") mengarah ke URL yang tepat.
