<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>SiPermata | Portal Pengajuan Surat Mahasiswa</title>
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/unuja.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#0b3b7a',
                            dark: '#081f4d',
                            soft: '#e6eefb',
                        },
                        accent: '#0b132b',
                        muted: '#5c6475',
                    },
                    boxShadow: {
                        soft: '0 14px 40px rgba(11,18,43,0.12)',
                        lift: '0 20px 60px rgba(11,18,43,0.18)',
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 60px rgba(11, 18, 43, 0.18);
        }
        .dot-grid {
            background-image: radial-gradient(circle at 1px 1px, rgba(11, 59, 122, 0.14) 1px, transparent 0);
            background-size: 26px 26px;
        }
    </style>
</head>

<body class="bg-[#f5f7fb] text-accent">
    <div class="min-h-screen flex flex-col">
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute w-80 h-80 bg-primary/15 rounded-full blur-3xl -top-24 -left-10"></div>
            <div class="absolute w-96 h-96 bg-primary.dark/20 rounded-full blur-3xl top-20 -right-16"></div>
        </div>

        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-primary.soft">
            <div class="container mx-auto px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/media/logos/sipermata-dark.png') }}" class="h-10" alt="Logo SiPermata">
                </div>
                <div class="hidden md:flex items-center gap-6 text-sm font-semibold text-accent">
                    <a href="#layanan" class="hover:text-primary">Layanan</a>
                    <a href="#alur" class="hover:text-primary">Alur</a>
                    <a href="#faq" class="hover:text-primary">Info Penting</a>
                </div>
                <a href="https://sso.unuja.ac.id" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-primary text-white font-semibold shadow-soft hover:shadow-lift transition">
                    Masuk
                </a>
            </div>
        </header>

        <main class="flex-1">
            <!-- HERO -->
            <section class="relative overflow-hidden pb-14">
                <div class="absolute inset-0 bg-gradient-to-br from-white via-primary.soft to-white opacity-85"></div>
                <div class="relative container mx-auto px-6 pt-4 lg:pt-10">
                    <div class="grid lg:grid-cols-12 gap-10 items-center">
                        <div class="lg:col-span-7 space-y-6">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary.soft text-primary font-semibold shadow-soft">
                                <span class="text-sm">Portal Surat Mahasiswa UNUJA</span>
                                <span class="w-1 h-1 rounded-full bg-primary"></span>
                                <span class="text-sm">Tanpa unggah berkas</span>
                            </div>
                            <div class="space-y-3">
                                <h1 class="text-3xl lg:text-5xl font-bold leading-tight">Ajukan surat resmi dengan alur digital yang jelas, transparan, dan cepat.</h1>
                                <p class="text-lg text-muted max-w-3xl">SiPermata memadukan verifikasi BAAK, persetujuan Dekan, dan pengiriman email otomatis. Data mahasiswa tersinkron (NIM, prodi, semester), tanpa unggah dokumen, dan surat siap cetak dengan QR verifikasi.</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="https://sso.unuja.ac.id" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-primary text-white font-semibold shadow-soft hover:shadow-lift transition">Mulai ajukan</a>
                                <a href="#layanan" class="inline-flex items-center justify-center px-6 py-3 rounded-lg border border-primary text-primary font-semibold hover:bg-primary.soft transition">Lihat layanan</a>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-2 rounded-full bg-white shadow-soft text-sm font-semibold text-accent">Data mahasiswa otomatis</span>
                                <span class="px-3 py-2 rounded-full bg-white shadow-soft text-sm font-semibold text-accent">QR & jejak disposisi</span>
                                <span class="px-3 py-2 rounded-full bg-white shadow-soft text-sm font-semibold text-accent">Email otomatis & siap cetak</span>
                            </div>
                        </div>
                        <div class="lg:col-span-5 relative">
                            <div class="absolute -top-6 -right-10 w-36 h-36 rounded-full bg-primary/15 blur-3xl"></div>
                            <div class="relative glass-card rounded-2xl p-6 dot-grid">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="text-sm text-muted">Status langsung</p>
                                        <p class="font-semibold text-accent">Contoh pengajuan</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-primary.soft text-primary text-xs font-semibold">Live</span>
                                </div>
                                <div class="space-y-3">
                                    <div class="rounded-xl bg-white p-3 shadow-soft">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-muted">Surat Aktif Mahasiswa</p>
                                                <p class="font-semibold text-accent">Varian Umum</p>
                                            </div>
                                            <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">Sedang diverifikasi</span>
                                        </div>
                                        <div class="mt-3 space-y-2 text-sm text-muted">
                                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-green-500"></span> Data mahasiswa tersinkron (NIM, Prodi)</div>
                                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> Menunggu persetujuan Dekan</div>
                                            <div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-gray-300"></span> Cetak & email</div>
                                        </div>
                                    </div>
                                    <div class="rounded-xl bg-white p-3 shadow-soft">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm text-muted">Surat Keterangan Lulus</p>
                                                <p class="font-semibold text-accent">Otomatis QR</p>
                                            </div>
                                            <span class="text-xs text-muted">Siap cetak</span>
                                        </div>
                                        <div class="mt-2 text-sm text-muted">Dikirim ke email pemohon dan dapat diunduh kapan saja.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- WHY -->
            <section class="py-10">
                <div class="container mx-auto px-6">
                    <div class="grid lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5 space-y-3">
                            <p class="text-primary font-semibold text-sm uppercase tracking-wide">Kenapa SiPermata</p>
                            <h2 class="text-2xl lg:text-3xl font-bold">Untuk mahasiswa, BAAK, dan Dekan: alur rapi, progres jelas.</h2>
                            <p class="text-muted">Setiap pengajuan mengikuti rute BAAK -> Dekan -> Cetak/Email dengan catatan disposisi. Data mahasiswa tersinkron otomatis, tidak perlu unggah dokumen.</p>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 rounded-full bg-white shadow-soft text-sm font-semibold text-accent">Dashboard & tracking</span>
                                <span class="px-3 py-1 rounded-full bg-white shadow-soft text-sm font-semibold text-accent">QR validasi</span>
                                <span class="px-3 py-1 rounded-full bg-white shadow-soft text-sm font-semibold text-accent">Jejak persetujuan</span>
                            </div>
                        </div>
                        <div class="lg:col-span-7 grid sm:grid-cols-3 gap-4">
                            <div class="glass-card rounded-xl p-5">
                                <p class="text-primary font-semibold text-sm mb-2">01</p>
                                <h3 class="text-lg font-semibold mb-1">Tanpa unggah berkas</h3>
                                <p class="text-muted text-sm">Data mahasiswa tersinkron otomatis. Cukup isi tujuan dan instansi.</p>
                            </div>
                            <div class="glass-card rounded-xl p-5">
                                <p class="text-primary font-semibold text-sm mb-2">02</p>
                                <h3 class="text-lg font-semibold mb-1">Disposisi tercatat</h3>
                                <p class="text-muted text-sm">BAAK memverifikasi, Dekan menyetujui, status tercatat real-time.</p>
                            </div>
                            <div class="glass-card rounded-xl p-5">
                                <p class="text-primary font-semibold text-sm mb-2">03</p>
                                <h3 class="text-lg font-semibold mb-1">Cetak & email</h3>
                                <p class="text-muted text-sm">Surat final bisa diunduh, dicetak, dan dikirim otomatis dengan QR.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SERVICES -->
            <section id="layanan" class="py-12 bg-white">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
                        <div>
                            <p class="text-primary font-semibold text-sm uppercase tracking-wide">Layanan</p>
                            <h2 class="text-2xl lg:text-3xl font-bold text-accent">Enam surat utama dalam satu portal</h2>
                            <p class="text-muted mt-2 max-w-2xl">Semua surat mengikuti alur BAAK & Dekan. Surat Aktif tersedia untuk Umum, PNS, dan P3K, dengan data mahasiswa tersinkron otomatis.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-full bg-primary.soft text-primary text-sm font-semibold">Tidak perlu upload</span>
                            <span class="px-3 py-1 rounded-full bg-primary.soft text-primary text-sm font-semibold">Keluaran resmi UNUJA</span>
                            <span class="px-3 py-1 rounded-full bg-primary.soft text-primary text-sm font-semibold">Status real-time</span>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="glass-card rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-12 h-12 rounded-xl bg-primary.soft text-primary font-bold flex items-center justify-center">AK</span>
                                <div>
                                    <p class="font-semibold text-accent text-lg mb-1">Surat Keterangan Aktif</p>
                                    <p class="text-muted text-sm mb-0">Keterangan aktif kuliah untuk beasiswa, bank, instansi.</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-white shadow-soft text-accent">Umum</span>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-white shadow-soft text-accent">PNS</span>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-white shadow-soft text-accent">P3K</span>
                            </div>
                        </div>
                        <div class="glass-card rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-12 h-12 rounded-xl bg-primary.soft text-primary font-bold flex items-center justify-center">PL</span>
                                <div>
                                    <p class="font-semibold text-accent text-lg mb-1">Surat Izin Penelitian</p>
                                    <p class="text-muted text-sm mb-0">Pengantar resmi untuk riset dan pengumpulan data.</p>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-12 h-12 rounded-xl bg-primary.soft text-primary font-bold flex items-center justify-center">OB</span>
                                <div>
                                    <p class="font-semibold text-accent text-lg mb-1">Surat Permohonan Observasi</p>
                                    <p class="text-muted text-sm mb-0">Dokumen observasi lapangan atau studi pendahuluan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-12 h-12 rounded-xl bg-primary.soft text-primary font-bold flex items-center justify-center">PKL</span>
                                <div>
                                    <p class="font-semibold text-accent text-lg mb-1">Surat Permohonan PKL</p>
                                    <p class="text-muted text-sm mb-0">Pengantar resmi praktik kerja lapangan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-12 h-12 rounded-xl bg-primary.soft text-primary font-bold flex items-center justify-center">RK</span>
                                <div>
                                    <p class="font-semibold text-accent text-lg mb-1">Surat Rekomendasi</p>
                                    <p class="text-muted text-sm mb-0">Rekomendasi resmi untuk kebutuhan akademik/profesional.</p>
                                </div>
                            </div>
                        </div>
                        <div class="glass-card rounded-xl p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="w-12 h-12 rounded-xl bg-primary.soft text-primary font-bold flex items-center justify-center">SKL</span>
                                <div>
                                    <p class="font-semibold text-accent text-lg mb-1">Surat Keterangan Lulus</p>
                                    <p class="text-muted text-sm mb-0">Untuk kerja, CPNS/P3K, beasiswa, atau studi lanjut.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- TIMELINE -->
            <section id="alur" class="py-12">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
                        <div>
                            <p class="text-primary font-semibold text-sm uppercase tracking-wide">Alur Pengajuan</p>
                            <h2 class="text-2xl lg:text-3xl font-bold text-accent">Pilih Surat -> BAAK -> Dekan -> Cetak & Email</h2>
                            <p class="text-muted mt-2">Setiap langkah transparan, dapat dilacak, dan tanpa unggah berkas.</p>
                        </div>
                        <a href="https://sso.unuja.ac.id" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-primary text-white font-semibold shadow-soft hover:shadow-lift transition">Masuk untuk memulai</a>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="glass-card rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-semibold">1</div>
                                <p class="font-semibold text-accent text-lg mb-0">Pilih Surat</p>
                            </div>
                            <p class="text-muted text-sm">Pilih jenis surat dan, jika perlu, varian (Umum/PNS/P3K untuk Surat Aktif).</p>
                        </div>
                        <div class="glass-card rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-semibold">2</div>
                                <p class="font-semibold text-accent text-lg mb-0">Verifikasi BAAK</p>
                            </div>
                            <p class="text-muted text-sm">Data mahasiswa dicek otomatis tanpa upload; catatan disposisi tercatat.</p>
                        </div>
                        <div class="glass-card rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-semibold">3</div>
                                <p class="font-semibold text-accent text-lg mb-0">Persetujuan Dekan</p>
                            </div>
                            <p class="text-muted text-sm">Dekan menyetujui secara digital, mudah dilacak statusnya.</p>
                        </div>
                        <div class="glass-card rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-semibold">4</div>
                                <p class="font-semibold text-accent text-lg mb-0">Cetak & Email</p>
                            </div>
                            <p class="text-muted text-sm">Surat final siap diunduh/cetak dan otomatis terkirim ke email dengan QR verifikasi.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ / INFO -->
            <section id="faq" class="py-12 bg-white">
                <div class="container mx-auto px-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
                        <div>
                            <p class="text-primary font-semibold text-sm uppercase tracking-wide">Info cepat</p>
                            <h2 class="text-2xl lg:text-3xl font-bold text-accent">Syarat & waktu proses</h2>
                            <p class="text-muted mt-2">Ringkasan persyaratan utama untuk memperlancar pengajuan Anda.</p>
                        </div>
                        <a href="https://sso.unuja.ac.id" class="inline-flex items-center justify-center px-5 py-3 rounded-lg border border-primary text-primary font-semibold hover:bg-primary.soft transition">Masuk dashboard</a>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="glass-card rounded-xl p-5">
                            <h3 class="text-lg font-semibold mb-2">Surat Aktif</h3>
                            <ul class="text-muted text-sm space-y-2">
                                <li>Varian: Umum, PNS, P3K</li>
                                <li>Data mahasiswa diambil otomatis (NIM, Prodi, Semester)</li>
                                <li>Estimasi: 1-2 hari kerja setelah verifikasi</li>
                            </ul>
                        </div>
                        <div class="glass-card rounded-xl p-5">
                            <h3 class="text-lg font-semibold mb-2">Surat Keterangan Lulus</h3>
                            <ul class="text-muted text-sm space-y-2">
                                <li>QR verifikasi & template resmi UNUJA</li>
                                <li>Peruntukan: kerja, CPNS/P3K, beasiswa, studi lanjut</li>
                                <li>Dikirim otomatis ke email pemohon</li>
                            </ul>
                        </div>
                        <div class="glass-card rounded-xl p-5">
                            <h3 class="text-lg font-semibold mb-2">Bantuan cepat</h3>
                            <ul class="text-muted text-sm space-y-2">
                                <li>BAAK siap memandu pengajuan Anda</li>
                                <li>Catat tujuan instansi dan kebutuhan dokumen</li>
                                <li>Kunjungi dashboard untuk memulai</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA -->
            <section class="py-12">
                <div class="container mx-auto px-6">
                    <div class="glass-card rounded-2xl p-6 lg:p-8 bg-gradient-to-br from-primary.soft via-white to-primary.soft border border-primary.soft">
                        <div class="grid lg:grid-cols-3 gap-6 items-center">
                            <div class="lg:col-span-2 space-y-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('assets/media/logos/sipermata-dark.png') }}" class="h-10" alt="Logo">
                                </div>
                                <h3 class="text-2xl lg:text-3xl font-bold text-accent">Mulai ajukan surat hari ini.</h3>
                                <p class="text-muted">Alur terpantau, tanpa unggah berkas, diverifikasi BAAK dan Dekan, lalu otomatis dikirim ke email dengan QR verifikasi.</p>
                            </div>
                            <div class="flex flex-col gap-3">
                                <a href="https://sso.unuja.ac.id" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-primary text-white font-semibold shadow-soft hover:shadow-lift transition">Masuk / Dashboard</a>
                                <a href="#layanan" class="inline-flex items-center justify-center px-6 py-3 rounded-lg border border-primary text-primary font-semibold hover:bg-primary.soft transition">Lihat layanan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
