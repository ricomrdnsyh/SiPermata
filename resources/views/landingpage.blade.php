<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#0b3b7a',
                            dark: '#081f4d',
                            soft: '#e6eefb'
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
        .aurora {
            background:
                radial-gradient(1200px 600px at 20% 10%, rgba(59, 130, 246, .22), transparent 60%),
                radial-gradient(900px 500px at 80% 20%, rgba(139, 92, 246, .18), transparent 55%),
                radial-gradient(800px 500px at 50% 90%, rgba(14, 165, 233, .15), transparent 60%),
                linear-gradient(180deg, #ffffff 0%, #f5f7fb 55%, #ffffff 100%);
        }

        .glass {
            background: rgba(255, 255, 255, .82);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, .6);
        }

        .card-border {
            position: relative;
        }

        .card-border:before {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: 1rem;
            padding: 1px;
            background: linear-gradient(135deg, rgba(11, 59, 122, .35), rgba(139, 92, 246, .25), rgba(14, 165, 233, .25));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }
    </style>
</head>

<body class="text-accent font-sans">
    <div class="min-h-screen aurora">
        <!-- NAVBAR -->
        <header class="sticky top-0 z-40">
            <div class="mx-auto max-w-7xl px-5 md:px-6 py-4">
                <div class="glass rounded-2xl shadow-soft">
                    <div class="flex items-center justify-between px-4 md:px-6 py-3">
                        <a href="#" class="flex items-center gap-3">
                            <img src="{{ asset('assets/media/logos/sipermata-dark.png') }}" class="h-9 md:h-10"
                                alt="Logo SiPermata">
                        </a>

                        <!-- desktop menu -->
                        <nav class="hidden md:flex items-center gap-7 text-sm font-semibold">
                            <a href="#layanan" class="hover:text-primary">Layanan</a>
                            <a href="#alur" class="hover:text-primary">Alur</a>
                            <a href="#faq" class="hover:text-primary">Info Penting</a>
                        </nav>

                        <div class="flex items-center gap-2">
                            <a href="https://sso.unuja.ac.id"
                                class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-xl font-semibold text-white
                        bg-gradient-to-r from-primary via-blue-600 to-indigo-600 shadow-soft hover:shadow-lift transition">
                                Masuk
                            </a>

                            <!-- hamburger -->
                            <button id="btnMobileMenu"
                                class="md:hidden inline-flex items-center justify-center w-11 h-11 rounded-xl
                             bg-white/70 hover:bg-white transition border border-white/60"
                                aria-label="Buka menu" aria-expanded="false">
                                <svg id="iconOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- mobile menu -->
                    <div id="mobileMenu" class="md:hidden hidden border-t border-white/60 px-4 pb-4">
                        <div class="pt-3 flex flex-col gap-2 text-sm font-semibold">
                            <a href="#layanan" class="py-2.5 px-3 rounded-xl hover:bg-primary.soft">Layanan</a>
                            <a href="#alur" class="py-2.5 px-3 rounded-xl hover:bg-primary.soft">Alur</a>
                            <a href="#faq" class="py-2.5 px-3 rounded-xl hover:bg-primary.soft">Info Penting</a>
                            <a href="https://sso.unuja.ac.id"
                                class="mt-2 inline-flex items-center justify-center px-4 py-3 rounded-xl font-semibold text-white
                        bg-gradient-to-r from-primary via-blue-600 to-indigo-600 shadow-soft hover:shadow-lift transition">
                                Masuk / Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- HERO -->
            <section class="mx-auto max-w-7xl px-5 md:px-6 pt-4 md:pt-8 pb-10 md:pb-14">
                <div class="grid lg:grid-cols-12 gap-10 items-center">
                    <div class="lg:col-span-7 space-y-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass shadow-soft">
                            <span class="text-sm font-semibold text-primary">Portal Surat Mahasiswa UNUJA</span>
                            <span class="w-1 h-1 rounded-full bg-primary"></span>
                            <span class="text-sm font-semibold text-muted">Tanpa unggah berkas</span>
                        </div>

                        <div class="space-y-3">
                            <h1 class="text-3xl md:text-5xl font-bold leading-tight">
                                Ajukan surat resmi <span
                                    class="bg-gradient-to-r from-primary to-indigo-600 bg-clip-text text-transparent">lebih
                                    cepat</span>
                                dengan alur digital yang transparan.
                            </h1>
                            <p class="text-base md:text-lg text-muted max-w-2xl">
                                Verifikasi BAAK -> Persetujuan Dekan -> Surat siap cetak + email otomatis.
                                Data mahasiswa tersinkron (NIM, Fakultas, Prodi), lengkap dengan QR verifikasi.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="https://sso.unuja.ac.id"
                                class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-semibold text-white
                        bg-gradient-to-r from-primary via-blue-600 to-indigo-600 shadow-soft hover:shadow-lift transition">
                                Mulai ajukan
                            </a>
                            <a href="#layanan"
                                class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-semibold
                        border border-white/70 bg-white/60 hover:bg-white transition">
                                Lihat layanan
                            </a>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-3">
                            <div class="glass card-border rounded-2xl p-4 shadow-soft hover:shadow-lift transition">
                                <p class="text-sm font-semibold">Data otomatis</p>
                                <p class="text-sm text-muted mt-1">NIM, Fakultas, Prodi tersinkron.</p>
                            </div>
                            <div class="glass card-border rounded-2xl p-4 shadow-soft hover:shadow-lift transition">
                                <p class="text-sm font-semibold">Tracking jelas</p>
                                <p class="text-sm text-muted mt-1">Jejak disposisi & status realtime.</p>
                            </div>
                            <div class="glass card-border rounded-2xl p-4 shadow-soft hover:shadow-lift transition">
                                <p class="text-sm font-semibold">QR Verifikasi</p>
                                <p class="text-sm text-muted mt-1">Surat mudah divalidasi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- PREVIEW -->
                    <div class="lg:col-span-5">
                        <div class="glass card-border rounded-3xl p-6 shadow-lift">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-muted">Status langsung</p>
                                    <p class="font-semibold">Contoh pengajuan</p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold text-white
                             bg-gradient-to-r from-emerald-500 to-cyan-500">
                                    Live
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="rounded-2xl bg-white/85 p-4 shadow-soft border border-white/60">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm text-muted">Surat Aktif Mahasiswa</p>
                                            <p class="font-semibold">Varian Umum</p>
                                        </div>
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-semibold text-primary bg-primary.soft">
                                            Sedang diverifikasi
                                        </span>
                                    </div>

                                    <div class="mt-3 space-y-2 text-sm text-muted">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Data tersinkron
                                            otomatis
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span> Menunggu
                                            persetujuan Dekan
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span> Cetak & email
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-white/85 p-4 shadow-soft border border-white/60">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-muted">Surat Keterangan Lulus</p>
                                            <p class="font-semibold">Otomatis QR</p>
                                        </div>
                                        <span class="text-xs text-muted">Siap cetak</span>
                                    </div>
                                    <p class="mt-2 text-sm text-muted">Dikirim ke email pemohon dan dapat diunduh kapan
                                        saja.</p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-3 gap-3">
                                <div class="rounded-2xl bg-white/70 p-3 border border-white/60">
                                    <p class="text-xs text-muted">Rata-rata selesai</p>
                                    <p class="font-bold text-lg">1-2 hari</p>
                                </div>
                                <div class="rounded-2xl bg-white/70 p-3 border border-white/60">
                                    <p class="text-xs text-muted">Notifikasi</p>
                                    <p class="font-bold text-lg">Email</p>
                                </div>
                                <div class="rounded-2xl bg-white/70 p-3 border border-white/60">
                                    <p class="text-xs text-muted">Validasi</p>
                                    <p class="font-bold text-lg">QR</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- LAYANAN -->
            <section id="layanan" class="mx-auto max-w-7xl px-5 md:px-6 py-12">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-8">
                    <div>
                        <p class="text-primary font-semibold text-sm uppercase tracking-wide">Layanan</p>
                        <h2 class="text-2xl md:text-3xl font-bold">Enam surat utama dalam satu portal</h2>
                        <p class="text-muted mt-2 max-w-2xl">
                            Semua surat mengikuti alur BAAK & Dekan. Surat Aktif tersedia untuk Umum, PNS, dan P3K.
                        </p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- cards -->
                    <div class="glass card-border rounded-2xl p-6 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="w-12 h-12 rounded-2xl bg-primary.soft text-primary font-bold flex items-center justify-center">SKAK</span>
                            <div>
                                <p class="font-semibold text-lg">Surat Keterangan Aktif</p>
                                <p class="text-muted text-sm">Untuk beasiswa, bank, instansi.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="text-xs font-semibold px-3 py-1 rounded-full bg-white/80 border border-white/60">Umum</span>
                            <span
                                class="text-xs font-semibold px-3 py-1 rounded-full bg-white/80 border border-white/60">PNS</span>
                            <span
                                class="text-xs font-semibold px-3 py-1 rounded-full bg-white/80 border border-white/60">P3K</span>
                        </div>
                    </div>

                    <div class="glass card-border rounded-2xl p-6 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="w-12 h-12 rounded-2xl bg-primary.soft text-primary font-bold flex items-center justify-center">SIP</span>
                            <div>
                                <p class="font-semibold text-lg">Surat Izin Penelitian</p>
                                <p class="text-muted text-sm">Pengantar resmi untuk riset.</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass card-border rounded-2xl p-6 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="w-12 h-12 rounded-2xl bg-primary.soft text-primary font-bold flex items-center justify-center">SO</span>
                            <div>
                                <p class="font-semibold text-lg">Surat Observasi</p>
                                <p class="text-muted text-sm">Observasi lapangan/studi awal.</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass card-border rounded-2xl p-6 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="w-12 h-12 rounded-2xl bg-primary.soft text-primary font-bold flex items-center justify-center">SPKL</span>
                            <div>
                                <p class="font-semibold text-lg">Surat Permohonan PKL</p>
                                <p class="text-muted text-sm">Pengantar praktik kerja lapangan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass card-border rounded-2xl p-6 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="w-12 h-12 rounded-2xl bg-primary.soft text-primary font-bold flex items-center justify-center">SR</span>
                            <div>
                                <p class="font-semibold text-lg">Surat Rekomendasi</p>
                                <p class="text-muted text-sm">Kebutuhan akademik/profesional.</p>
                            </div>
                        </div>
                    </div>

                    <div class="glass card-border rounded-2xl p-6 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="w-12 h-12 rounded-2xl bg-primary.soft text-primary font-bold flex items-center justify-center">SKL</span>
                            <div>
                                <p class="font-semibold text-lg">Surat Keterangan Lulus</p>
                                <p class="text-muted text-sm">Kerja, CPNS/P3K, beasiswa.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ALUR -->
            <section id="alur" class="mx-auto max-w-7xl px-5 md:px-6 pb-12">
                <div class="glass card-border rounded-3xl p-6 md:p-8 shadow-soft">
                    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-8">
                        <div>
                            <p class="text-primary font-semibold text-sm uppercase tracking-wide">Alur Pengajuan</p>
                            <h2 class="text-2xl md:text-3xl font-bold">Pilih Surat -> BAAK -> Dekan-> Cetak & Email
                            </h2>
                            <p class="text-muted mt-2">Setiap langkah transparan, bisa dilacak, tanpa unggah berkas.
                            </p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="rounded-2xl bg-white/70 border border-white/60 p-5 hover:shadow-soft transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="w-10 h-10 rounded-2xl text-white font-bold flex items-center justify-center
                            bg-gradient-to-r from-primary to-indigo-600">
                                    1</div>
                                <p class="font-semibold text-lg">Pilih Surat</p>
                            </div>
                            <p class="text-muted text-sm">Pilih jenis surat dan varian jika diperlukan.</p>
                        </div>

                        <div class="rounded-2xl bg-white/70 border border-white/60 p-5 hover:shadow-soft transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="w-10 h-10 rounded-2xl text-white font-bold flex items-center justify-center
                            bg-gradient-to-r from-primary to-indigo-600">
                                    2</div>
                                <p class="font-semibold text-lg">Verifikasi BAAK</p>
                            </div>
                            <p class="text-muted text-sm">Data mahasiswa dicek otomatis, catatan tercatat.</p>
                        </div>

                        <div class="rounded-2xl bg-white/70 border border-white/60 p-5 hover:shadow-soft transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="w-10 h-10 rounded-2xl text-white font-bold flex items-center justify-center
                            bg-gradient-to-r from-primary to-indigo-600">
                                    3</div>
                                <p class="font-semibold text-lg">Persetujuan Dekan</p>
                            </div>
                            <p class="text-muted text-sm">Persetujuan digital, status mudah dipantau.</p>
                        </div>

                        <div class="rounded-2xl bg-white/70 border border-white/60 p-5 hover:shadow-soft transition">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="w-10 h-10 rounded-2xl text-white font-bold flex items-center justify-center
                            bg-gradient-to-r from-primary to-indigo-600">
                                    4</div>
                                <p class="font-semibold text-lg">Cetak & Email</p>
                            </div>
                            <p class="text-muted text-sm">Surat final siap unduh/cetak + terkirim ke email.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ + CTA -->
            <section id="faq" class="mx-auto max-w-7xl px-5 md:px-6 pb-14">
                <div class="grid lg:grid-cols-12 gap-6">
                    <div class="lg:col-span-7 glass card-border rounded-3xl p-6 md:p-8 shadow-soft">
                        <p class="text-primary font-semibold text-sm uppercase tracking-wide">Info cepat</p>
                        <h2 class="text-2xl md:text-3xl font-bold mt-2">Syarat & waktu proses</h2>
                        <p class="text-muted mt-2">Ringkasan persyaratan utama untuk memperlancar pengajuan.</p>

                        <div class="mt-6 grid md:grid-cols-2 gap-4">
                            <div class="rounded-2xl bg-white/70 border border-white/60 p-5">
                                <h3 class="text-lg font-semibold mb-2">Surat Aktif</h3>
                                <ul class="text-muted text-sm space-y-2">
                                    <li>Varian: Umum, PNS, P3K</li>
                                    <li>Data otomatis (NIM, Fakultas, Prodi)</li>
                                    <li>Estimasi: 1-2 hari kerja setelah verifikasi</li>
                                </ul>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-white/60 p-5">
                                <h3 class="text-lg font-semibold mb-2">Surat Keterangan Lulus</h3>
                                <ul class="text-muted text-sm space-y-2">
                                    <li>QR verifikasi & template resmi</li>
                                    <li>Peruntukan: kerja/CPNS/beasiswa</li>
                                    <li>Dikirim otomatis ke email pemohon</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div
                            class="rounded-3xl p-[1px] bg-gradient-to-br from-primary via-blue-600 to-indigo-600 shadow-lift">
                            <div class="rounded-3xl p-6 md:p-8 bg-white">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('assets/media/logos/sipermata-dark.png') }}" class="h-9"
                                        alt="Logo">
                                </div>
                                <h3 class="text-2xl md:text-3xl font-bold mt-3">
                                    Siap ajukan surat hari ini?
                                </h3>
                                <p class="text-muted mt-2">
                                    Tanpa unggah berkas, alur terpantau, dan surat terkirim otomatis ke email dengan QR
                                    verifikasi.
                                </p>
                                <div class="mt-6 flex flex-col gap-3">
                                    <a href="https://sso.unuja.ac.id"
                                        class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-semibold text-white
                            bg-gradient-to-r from-primary via-blue-600 to-indigo-600 shadow-soft hover:shadow-lift transition">
                                        Masuk / Dashboard
                                    </a>
                                    <a href="#layanan"
                                        class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-semibold
                            border border-slate-200 hover:bg-slate-50 transition">
                                        Lihat layanan
                                    </a>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-muted mt-4 px-2">
                            &copy; PDSI Universitas Nurul Jadid
                        </p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        const btn = document.getElementById('btnMobileMenu');
        const menu = document.getElementById('mobileMenu');
        const iconOpen = document.getElementById('iconOpen');
        const iconClose = document.getElementById('iconClose');

        btn?.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', String(isHidden));
        });

        menu?.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                menu.classList.add('hidden');
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            });
        });
    </script>
</body>

</html>
