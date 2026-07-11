# SiPermata (Sistem Informasi Pengajuan Surat Mahasiswa Terpadu)

SiPermata adalah aplikasi berbasis web yang dikembangkan menggunakan framework Laravel untuk memfasilitasi dan mengotomatisasi proses pengajuan, verifikasi, dan penerbitan berbagai jenis surat akademik bagi mahasiswa. Aplikasi ini juga terintegrasi dengan Single Sign-On (SSO) untuk autentikasi yang lebih aman dan terpusat.

## 🚀 Fitur Utama

- **Pengajuan Surat Mandiri**: Mahasiswa dapat mengajukan berbagai jenis surat secara online.
- **Sistem Verifikasi Bertingkat**: Proses persetujuan surat melibatkan alur yang jelas mulai dari BAK, hingga Dekan.
- **Tanda Tangan Elektronik & QR Code**: Dilengkapi dengan QR code untuk verifikasi keaslian dokumen secara online.
- **Integrasi SSO (Single Sign-On)**: Menggunakan akun SSO Universitas untuk login ke dalam sistem.
- **Sinkronisasi Data API**: Tersedia fitur sinkronisasi data mahasiswa, program studi, fakultas, dan penduduk langsung dari API pusat (SIMPT).
- **Generate Dokumen Otomatis**: Mendukung pengisian dan ekspor surat otomatis sesuai template institusi ke dalam format dokumen standar (`phpoffice/phpword`).
- **Notifikasi via Email**: Sistem secara otomatis mengirimkan pemberitahuan dan dokumen lampiran surat ke email mahasiswa ketika pengajuan surat telah mendapatkan persetujuan akhir dari Dekan.
- **Rekapitulasi & Ekspor Laporan**: Fitur unduh rekapitulasi surat dan ekspor seluruh log pengajuan ke file Excel (`maatwebsite/excel`).

## 📑 Jenis Surat yang Dilayani

1. Surat Keterangan Aktif Kuliah (UMUM, PNS, PPPK)
2. Surat Izin Penelitian
3. Surat Rekomendasi
4. Surat Pengantar PKL (Praktik Kerja Lapangan)
5. Surat Izin Observasi
6. Surat Keterangan Lulus (SKL)

## 👥 Hak Akses per Role

**MAHASISWA**

- Dapat mengajukan permohonan berbagai jenis surat
- Dapat melihat progres dan riwayat pengajuan surat miliknya
- Dapat mengunduh (download) surat yang telah selesai disetujui
- Tidak dapat melakukan _approve_/_reject_ data pengajuan apa pun

**ADMIN**

- Mengelola pengaturan sistem dan data master (template surat, jabatan, TTD)
- Menjalankan sinkronisasi data dari API pusat (SIMPT)
- Mengelola data kelayakan kelulusan mahasiswa
- Dapat melakukan _approve_/_reject_ pengajuan surat dari mahasiswa

**BAK Fakultas (Biro Administrasi Akademik/Kemahasiswaan Fakultas)**

- Bertanggung jawab mengecek kelengkapan dan memvalidasi pengajuan surat
- Mengelola rekapitulasi data persuratan dan manajemen mitra
- Merupakan tahap pertama dalam alur persetujuan surat

**DEKAN**

- Melakukan tinjauan dan persetujuan akhir (_approve_ / _bulk approve_) pada pengajuan surat
- Secara otomatis memicu pengiriman notifikasi via email ke mahasiswa setelah disetujui
- Menjadi penentu akhir terbitnya dokumen surat

## 🔐 Alur Integrasi SSO (Single Sign-On)

Aplikasi SiPermata bergantung kuat pada SSO pusat untuk memverifikasi autentikasi user (Mahasiswa, BAK, Dekan, dll).

- **Login**: Jika pengguna mengakses rute yang dilindungi dan belum memiliki sesi (termasuk jika sesi _expired_), sistem akan mengarahkan (_redirect_) ke `https://sso.unuja.ac.id`.
- **Logout**: Fitur logout SiPermata difungsikan khusus sebagai **Local Logout**. Ketika Anda menekan logout di SiPermata, hanya sesi lokal aplikasi yang akan dihancurkan, dan selanjutnya Anda kembali ke halaman SSO tanpa keluar dari _session_ global SSO tersebut.
