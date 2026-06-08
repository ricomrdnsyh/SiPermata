<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="robots" content="index, follow">
    <meta name="description"
        content="SiPermata merupakan Sistem Informasi Pengajuan Surat Mahasiswa Terpadu di Universitas Nurul Jadid.">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/unuja.png') }}" />
    <title>SiPermata | Universitas Nurul Jadid</title>
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
                        soft: '0 14px 40px rgba(11,18,43,0.10)',
                        lift: '0 20px 60px rgba(11,18,43,0.15)',
                    }
                }
            }
        }
    </script>

    <style>
        .glass {
            background: rgba(255, 255, 255, .85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, .65);
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
            background: linear-gradient(135deg, rgba(11, 59, 122, .22), rgba(139, 92, 246, .15), rgba(14, 165, 233, .15));
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        footer a:hover {
            color: #facc15 !important;
            transition: color 0.2s;
        }

        .faq-answer {
            display: none;
            overflow: hidden;
        }

        .faq-item.open .faq-answer {
            display: block;
        }

        .faq-item.open .faq-chevron {
            transform: rotate(180deg);
        }

        .faq-chevron {
            transition: transform 0.25s ease;
        }

        @keyframes float-up {
            0% {
                opacity: 0;
                transform: translateY(8px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-float {
            animation: float-up 0.5s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
            opacity: 0;
        }

        .delay-2 {
            animation-delay: 0.2s;
            opacity: 0;
        }

        .delay-3 {
            animation-delay: 0.3s;
            opacity: 0;
        }

        .delay-4 {
            animation-delay: 0.4s;
            opacity: 0;
        }

        .stat-divider:not(:last-child) {
            border-right: 1px solid rgba(11, 59, 122, .12);
        }
    </style>
</head>

<body class="text-accent font-sans">

    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-100 shadow-sm">
        <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20">
            <div class="flex items-center justify-between py-3.5">
                <a href="#beranda" class="flex items-center gap-3">
                    <img src="{{ asset('assets/media/logos/sipermata-dark.png') }}" class="h-9 md:h-10"
                        alt="Logo SiPermata">
                </a>

                <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-accent">
                    <a href="#beranda" class="hover:text-primary transition">Beranda</a>
                    <a href="#layanan" class="hover:text-primary transition">Layanan</a>
                    <a href="#alur" class="hover:text-primary transition">Alur</a>
                    <a href="#faq" class="hover:text-primary transition">FAQ</a>
                </nav>

                <div class="flex items-center gap-2">
                    <a href="https://sso.unuja.ac.id"
                        class="hidden sm:inline-flex items-center justify-center px-5 py-2.5 rounded-xl font-semibold text-sm text-white
                        bg-primary hover:bg-primary-dark shadow-soft hover:shadow-lift transition">
                        Login Mahasiswa
                    </a>
                    <button id="btnMobileMenu"
                        class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl
                         bg-white hover:bg-slate-50 transition border border-slate-200"
                        aria-label="Buka menu" aria-expanded="false">
                        <svg id="iconOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="iconClose" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="mobileMenu" class="md:hidden hidden border-t border-slate-100 pb-4">
                <div class="pt-3 flex flex-col gap-1 text-sm font-semibold">
                    <a href="#beranda" class="py-2.5 px-3 rounded-xl hover:bg-slate-50 transition">Beranda</a>
                    <a href="#layanan" class="py-2.5 px-3 rounded-xl hover:bg-slate-50 transition">Layanan</a>
                    <a href="#alur" class="py-2.5 px-3 rounded-xl hover:bg-slate-50 transition">Alur</a>
                    <a href="#faq" class="py-2.5 px-3 rounded-xl hover:bg-slate-50 transition">FAQ</a>
                    <a href="https://sso.unuja.ac.id"
                        class="mt-2 inline-flex items-center justify-center px-4 py-3 rounded-xl font-semibold text-white
                        bg-primary shadow-soft hover:shadow-lift transition">
                        Login Mahasiswa
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>

        <section id="beranda" style="background: linear-gradient(160deg, #ffffff 0%, #f0f5ff 60%, #eef2ff 100%);">
            <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20 pt-14 md:pt-20 pb-16 md:pb-24">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                    <div class="lg:col-span-7 space-y-7">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/10 border border-primary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary inline-block"></span>
                            <span class="text-xs font-semibold text-primary uppercase tracking-wide">Portal Surat
                                Mahasiswa UNUJA</span>
                        </div>

                        <div class="space-y-4">
                            <h1 class="text-3xl md:text-4xl lg:text-[2.65rem] font-bold leading-[1.2]">
                                Ajukan Surat Resmi
                                <span
                                    class="bg-gradient-to-r from-primary to-indigo-600 bg-clip-text text-transparent">Lebih
                                    Cepat</span>
                                Dengan Alur Digital yang Transparan.
                            </h1>
                            <p class="text-sm md:text-base text-muted max-w-lg leading-relaxed">
                                Verifikasi BAAK &rarr; Persetujuan Dekan &rarr; Surat siap cetak + email otomatis.
                                Data mahasiswa tersinkron (NIM, Fakultas, Prodi), lengkap dengan QR verifikasi.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="https://sso.unuja.ac.id"
                                class="inline-flex items-center justify-center px-6 py-3 rounded-2xl font-semibold text-white text-sm
                                bg-primary hover:bg-primary-dark shadow-soft hover:shadow-lift transition">
                                Mulai ajukan
                            </a>
                            <a href="{{ asset('panduan.pdf') }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl font-semibold text-sm
                                    border border-slate-300 bg-white hover:bg-slate-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2 4h6a4 4 0 0 1 4 4v12a3 3 0 0 0-3-3H2z" />
                                    <path d="M22 4h-6a4 4 0 0 0-4 4v12a3 3 0 0 1 3-3h7z" />
                                </svg>
                                Buku panduan
                            </a>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-3">
                            <div
                                class="rounded-2xl bg-white border border-slate-100 p-4 shadow-soft hover:shadow-lift transition">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <p class="text-sm font-semibold">Data otomatis</p>
                                </div>
                                <p class="text-xs text-muted">NIM, Fakultas, Prodi tersinkron.</p>
                            </div>
                            <div
                                class="rounded-2xl bg-white border border-slate-100 p-4 shadow-soft hover:shadow-lift transition">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-sm font-semibold">Tracking jelas</p>
                                </div>
                                <p class="text-xs text-muted">Jejak disposisi &amp; status realtime.</p>
                            </div>
                            <div
                                class="rounded-2xl bg-white border border-slate-100 p-4 shadow-soft hover:shadow-lift transition">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1" />
                                        <rect x="14" y="3" width="7" height="7" rx="1" />
                                        <rect x="3" y="14" width="7" height="7" rx="1" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14 14h2v2h-2zM18 14h3v3h-3zM14 18h3v3h-3zM20 20h1v1h-1z" />
                                    </svg>
                                    <p class="text-sm font-semibold">QR verifikasi</p>
                                </div>
                                <p class="text-xs text-muted">Surat ber-TTD QRCode resmi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="glass card-border rounded-3xl p-6 shadow-lift space-y-5">

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-base">Sistem Informasi Surat</p>
                                </div>
                                <span
                                    class="flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-100">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse inline-block"></span>
                                    Active
                                </span>
                            </div>

                            <div
                                class="grid grid-cols-3 divide-x divide-slate-100 rounded-2xl bg-white border border-slate-100 shadow-soft overflow-hidden">
                                <div class="stat-divider px-4 py-4 text-center">
                                    <p class="text-2xl font-bold text-primary">6</p>
                                    <p class="text-xs text-muted mt-0.5 leading-tight">Jenis<br>surat</p>
                                </div>
                                <div class="stat-divider px-4 py-4 text-center">
                                    <p class="text-2xl font-bold text-primary">1–2</p>
                                    <p class="text-xs text-muted mt-0.5 leading-tight">Hari<br>kerja</p>
                                </div>
                                <div class="px-4 py-4 text-center">
                                    <p class="text-2xl font-bold text-primary">100%</p>
                                    <p class="text-xs text-muted mt-0.5 leading-tight">Digital<br>terverifikasi</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div
                                    class="animate-float delay-1 flex items-center gap-3 rounded-2xl bg-white border border-slate-100 px-4 py-3 shadow-soft">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold">Login SSO UNUJA</p>
                                        <p class="text-xs text-muted">Gunakan akun SIAKAD Anda</p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>

                                <div
                                    class="animate-float delay-2 flex items-center gap-3 rounded-2xl bg-white border border-slate-100 px-4 py-3 shadow-soft">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-violet-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold">Pilih &amp; ajukan surat</p>
                                        <p class="text-xs text-muted">Data NIM tersinkron otomatis</p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>

                                <div
                                    class="animate-float delay-3 flex items-center gap-3 rounded-2xl bg-white border border-slate-100 px-4 py-3 shadow-soft">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold">Unduh surat ber-QR</p>
                                        <p class="text-xs text-muted">Terkirim otomatis ke email</p>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            <div
                                class="rounded-2xl bg-primary/5 border border-primary/10 px-4 py-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <p class="text-xs font-semibold text-primary">Semua surat berQR &amp; terverifikasi
                                        resmi</p>
                                </div>
                                <span class="shrink-0 w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="layanan" style="background: #f4f6fb;">
            <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20 py-16">
                <div class="mb-10 text-center">
                    <h3 class="text-primary font-semibold text-xs uppercase tracking-widest mb-2">Layanan</h3>
                    <h2 class="text-2xl md:text-3xl font-bold">Enam Surat Utama Dalam Satu Portal</h2>
                </div>
 
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Surat keterangan aktif</p>
                                <p class="text-muted text-xs mt-0.5">Untuk beasiswa, bank, instansi dan tersedia 3 jenis surat aktif.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">Umum</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">PNS</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">P3K</span>
                        </div>
                    </div>
 
                    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Surat izin penelitian</p>
                                <p class="text-muted text-xs mt-0.5">Pengantar resmi untuk riset akademik tingkat lanjut.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-violet-50 text-violet-600">Skripsi</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-violet-50 text-violet-600">Tesis</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-violet-50 text-violet-600">Disertasi</span>
                        </div>
                    </div>
 
                    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Surat permohonan observasi</p>
                                <p class="text-muted text-xs mt-0.5">Izin observasi lapangan atau studi awal mata kuliah.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">Lapangan</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">Instansi</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">Sekolah</span>
                        </div>
                    </div>
 
                    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Surat permohonan PKL</p>
                                <p class="text-muted text-xs mt-0.5">Pengantar resmi praktik kerja lapangan dan magang.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-50 text-amber-600">Magang</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-50 text-amber-600">Industri</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-amber-50 text-amber-600">Pemerintahan</span>
                        </div>
                    </div>
 
                    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Surat rekomendasi</p>
                                <p class="text-muted text-xs mt-0.5">Dukungan untuk kebutuhan akademik atau profesional.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-rose-50 text-rose-500">Beasiswa</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-rose-50 text-rose-500">Studi lanjut</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-rose-50 text-rose-500">Kerja</span>
                        </div>
                    </div>
 
                    <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-soft hover:shadow-lift transition">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="w-11 h-11 rounded-xl bg-cyan-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-sm">Surat keterangan lulus</p>
                                <p class="text-muted text-xs mt-0.5">Kebutuhan kerja, CPNS/P3K, dan daftar beasiswa.</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-cyan-50 text-cyan-600">CPNS</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-cyan-50 text-cyan-600">P3K</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-cyan-50 text-cyan-600">Beasiswa</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="alur" style="background: #ffffff;">
            <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20 py-16">
                <div class="text-center mb-12">
                    <h3 class="text-primary font-semibold text-xs uppercase tracking-widest mb-2">Alur pengajuan</h3>
                    <h2 class="text-2xl md:text-3xl font-bold">Mudah, Cepat &amp; Transparan</h2>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-5 w-full flex justify-center">
                            <div
                                class="w-12 h-12 rounded-full text-white font-bold text-lg flex items-center justify-center bg-gradient-to-br from-primary to-indigo-600 shadow-soft z-10 relative">
                                1</div>
                            <div
                                class="hidden lg:block absolute top-1/2 left-[calc(50%+24px)] right-0 h-0.5 bg-primary/20 -translate-y-1/2">
                            </div>
                        </div>
                        <p class="font-semibold text-sm mb-1.5">Pilih surat</p>
                        <p class="text-muted text-xs leading-relaxed">Pilih jenis surat dan varian jika diperlukan dari
                            dasbor mahasiswa.</p>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-5 w-full flex justify-center">
                            <div
                                class="w-12 h-12 rounded-full text-white font-bold text-lg flex items-center justify-center bg-gradient-to-br from-primary to-indigo-600 shadow-soft z-10 relative">
                                2</div>
                            <div
                                class="hidden lg:block absolute top-1/2 left-[calc(50%+24px)] right-0 h-0.5 bg-primary/20 -translate-y-1/2">
                            </div>
                        </div>
                        <p class="font-semibold text-sm mb-1.5">Verifikasi BAAK</p>
                        <p class="text-muted text-xs leading-relaxed">Data mahasiswa dicek otomatis secara sistem,
                            catatan tercatat rapi.</p>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-5 w-full flex justify-center">
                            <div
                                class="w-12 h-12 rounded-full text-white font-bold text-lg flex items-center justify-center bg-gradient-to-br from-primary to-indigo-600 shadow-soft z-10 relative">
                                3</div>
                            <div
                                class="hidden lg:block absolute top-1/2 left-[calc(50%+24px)] right-0 h-0.5 bg-primary/20 -translate-y-1/2">
                            </div>
                        </div>
                        <p class="font-semibold text-sm mb-1.5">Persetujuan Dekan</p>
                        <p class="text-muted text-xs leading-relaxed">Persetujuan digital diberikan langsung, status
                            mudah dipantau realtime.</p>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-5 w-full flex justify-center">
                            <div
                                class="w-12 h-12 rounded-full text-white font-bold text-lg flex items-center justify-center bg-gradient-to-br from-primary to-indigo-600 shadow-soft z-10 relative">
                                4</div>
                        </div>
                        <p class="font-semibold text-sm mb-1.5">Cetak &amp; email</p>
                        <p class="text-muted text-xs leading-relaxed">Surat final siap unduh/cetak mandiri + terkirim
                            otomatis ke email.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" style="background: #f4f6fb;">
            <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20 py-16">
                <div class="grid lg:grid-cols-12 gap-6">

                    <div class="lg:col-span-7 bg-white border border-slate-100 rounded-3xl p-6 md:p-8">
                        <h4 class="text-primary font-semibold text-xs uppercase tracking-widest mb-1">FAQ</h4>
                        <h2 class="text-xl md:text-2xl font-bold mt-1">Pertanyaan yang sering diajukan</h2>

                        <div class="mt-6 space-y-2">

                            <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                <button
                                    class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left">
                                    <span class="text-sm font-semibold text-accent">Apakah saya perlu mengunggah
                                        dokumen fisik?</span>
                                    <svg class="faq-chevron w-4 h-4 text-muted shrink-0" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="faq-answer px-5 pb-4">
                                    <p class="text-xs text-muted leading-relaxed">Tidak perlu. Semua data mahasiswa
                                        tersinkron otomatis dari SIAKAD. Anda cukup memilih jenis surat dan mengisi
                                        informasi tambahan yang diperlukan tanpa perlu melampirkan berkas fisik apapun.
                                    </p>
                                </div>
                            </div>

                            <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                <button
                                    class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left">
                                    <span class="text-sm font-semibold text-accent">Berapa lama proses penerbitan
                                        surat?</span>
                                    <svg class="faq-chevron w-4 h-4 text-muted shrink-0" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="faq-answer px-5 pb-4">
                                    <p class="text-xs text-muted leading-relaxed">Rata-rata 1–2 hari kerja setelah
                                        verifikasi BAAK selesai. Anda akan mendapat notifikasi email di setiap perubahan
                                        status pengajuan secara otomatis.</p>
                                </div>
                            </div>

                            <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                <button
                                    class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left">
                                    <span class="text-sm font-semibold text-accent">Bagaimana cara login ke
                                        SiPermata?</span>
                                    <svg class="faq-chevron w-4 h-4 text-muted shrink-0" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="faq-answer px-5 pb-4">
                                    <p class="text-xs text-muted leading-relaxed">Login menggunakan akun SSO UNUJA (NIM
                                        dan password) melalui portal <span
                                            class="text-primary font-medium">sso.unuja.ac.id</span>. Tidak perlu
                                        mendaftar akun baru — akun SIAKAD Anda langsung bisa digunakan.</p>
                                </div>
                            </div>

                            <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                <button
                                    class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left">
                                    <span class="text-sm font-semibold text-accent">Apakah surat yang diterbitkan resmi
                                        dan sah?</span>
                                    <svg class="faq-chevron w-4 h-4 text-muted shrink-0" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="faq-answer px-5 pb-4">
                                    <p class="text-xs text-muted leading-relaxed">Ya. Setiap surat dilengkapi QR Code
                                        verifikasi resmi dan ditandatangani secara digital oleh pejabat berwenang sesuai
                                        jenis suratnya. Pihak eksternal dapat memverifikasi keaslian surat melalui QR
                                        tersebut.</p>
                                </div>
                            </div>

                            <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                <button
                                    class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left">
                                    <span class="text-sm font-semibold text-accent">Bisakah saya mengajukan lebih dari
                                        satu surat sekaligus?</span>
                                    <svg class="faq-chevron w-4 h-4 text-muted shrink-0" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="faq-answer px-5 pb-4">
                                    <p class="text-xs text-muted leading-relaxed">Bisa. Anda dapat mengajukan beberapa
                                        jenis surat secara bersamaan melalui dasbor mahasiswa. Setiap pengajuan akan
                                        diproses secara independen dan bisa dipantau statusnya masing-masing.</p>
                                </div>
                            </div>

                            <div class="faq-item rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden">
                                <button
                                    class="faq-trigger w-full flex items-center justify-between gap-3 px-5 py-4 text-left">
                                    <span class="text-sm font-semibold text-accent">Ke mana saya bisa menghubungi jika
                                        ada kendala?</span>
                                    <svg class="faq-chevron w-4 h-4 text-muted shrink-0" fill="none"
                                        stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="faq-answer px-5 pb-4">
                                    <p class="text-xs text-muted leading-relaxed">Anda dapat menghubungi BAAK Fakultas
                                        Universitas Nurul Jadid pada jam kerja.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="lg:col-span-5 flex flex-col">
                        <div class="rounded-3xl p-6 md:p-8 shadow-lift flex-1 flex flex-col justify-between"
                            style="background: linear-gradient(160deg, #0b3b7a 0%, #1a3a8f 60%, #2d3aae 100%);">
                            <div>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/15 text-white mb-5">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
                                    Pengajuan 24 jam
                                </span>
                                <h3 class="text-xl md:text-2xl font-bold text-white leading-snug">
                                    Pantau status surat kamu kapan saja &amp; di mana saja.
                                </h3>
                                <p class="text-white/65 mt-3 text-sm leading-relaxed">
                                    Setiap perubahan status — mulai verifikasi BAAK hingga persetujuan Dekan — langsung
                                    dikirim ke email kamu secara otomatis.
                                </p>
                            </div>

                            <div class="mt-7 space-y-3">
                                <div class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-white/90 font-medium">Notifikasi email otomatis tiap tahap
                                    </p>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-white/90 font-medium">Riwayat pengajuan tersimpan rapi</p>
                                </div>
                                <div class="flex items-center gap-3 rounded-2xl bg-white/10 px-4 py-3">
                                    <div
                                        class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-white/90 font-medium">Unduh surat kapan saja setelah terbit
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="https://sso.unuja.ac.id"
                                    class="inline-flex items-center justify-center w-full px-6 py-3 rounded-2xl font-semibold text-primary text-sm
                                    bg-white hover:bg-slate-100 shadow-soft transition">
                                    Masuk ke Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer style="background: #081f4d; color: #fff;">
        <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20 py-12">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="lg:col-span-1">
                    <img src="{{ asset('assets/media/logos/sipermata.png') }}" class="h-9 mb-4" alt="Logo SiPermata">
                    <p class="text-white/55 text-sm leading-relaxed">
                        Portal layanan surat menyurat mahasiswa Universitas Nurul Jadid yang terintegrasi secara digital untuk kemudahan layanan akademik.
                    </p>
                    <div class="flex gap-3 mt-5">
                        <a href="https://www.facebook.com/universitasnuruljadid/" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/unujaofficial/" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="https://x.com/unujaofficial" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://www.tiktok.com/@unujaofficial" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.54V6.78a4.85 4.85 0 01-1.02-.09z"/></svg>
                        </a>
                    </div>
                </div>
 
                <div>
                    <h4 class="font-semibold text-xs mb-3 text-white/80 uppercase tracking-widest">Kontak kami</h4>
                    <div class="w-8 h-0.5 bg-yellow-400 mb-4"></div>
                    <ul class="space-y-3 text-sm text-white/55">
                        <li class="flex items-start gap-2 group">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-white/35 group-hover:text-yellow-400 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="group-hover:text-yellow-400 transition-colors duration-300">JL. PP Nurul Jadid, Dusun Tj. Lor, Karanganyar, Kec. Paiton, Kabupaten Probolinggo, Jawa Timur 67291</span>
                        </li>
                        <li class="flex items-center gap-2 group">
                            <svg class="w-4 h-4 shrink-0 text-white/35 group-hover:text-yellow-400 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:+628883077077" class="group-hover:text-yellow-400 transition-colors duration-300">0888 30 77077</a>
                        </li>
                        <li class="flex items-center gap-2 group">
                            <svg class="w-4 h-4 shrink-0 text-white/35 group-hover:text-yellow-400 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            <span class="group-hover:text-yellow-400 transition-colors duration-300">Fax 0888 30 77077</span>
                        </li>
                        <li class="flex items-center gap-2 group">
                            <svg class="w-4 h-4 shrink-0 text-white/35 group-hover:text-yellow-400 transition-colors duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:unuja@unuja.ac.id" class="group-hover:text-yellow-400 transition-colors duration-300">unuja@unuja.ac.id</a>
                        </li>
                    </ul>
                </div>
 
                <div>
                    <h4 class="font-semibold text-xs mb-3 text-white/80 uppercase tracking-widest">Internal link</h4>
                    <div class="w-8 h-0.5 bg-yellow-400 mb-4"></div>
                    <ul class="space-y-2.5 text-sm text-white/55">
                        <li>
                            <a href="https://unuja.ac.id" target="_blank" class="inline-flex items-center gap-1.5 hover:text-yellow-400 hover:translate-x-1.5 transition-all duration-300">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                Universitas Nurul Jadid
                            </a>
                        </li>
                        <li>
                            <a href="https://pmb.unuja.ac.id" target="_blank" class="inline-flex items-center gap-1.5 hover:text-yellow-400 hover:translate-x-1.5 transition-all duration-300">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                PMB Universitas Nurul Jadid
                            </a>
                        </li>
                        <li>
                            <a href="https://sso.unuja.ac.id" target="_blank" class="inline-flex items-center gap-1.5 hover:text-yellow-400 hover:translate-x-1.5 transition-all duration-300">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                Portal SSO
                            </a>
                        </li>
                    </ul>
                </div>
 
                <div>
                    <h4 class="font-semibold text-xs mb-3 text-white/80 uppercase tracking-widest">Navigasi cepat</h4>
                    <div class="w-8 h-0.5 bg-yellow-400 mb-4"></div>
                    <ul class="space-y-2.5 text-sm text-white/55">
                        <li>
                            <a href="#beranda" class="inline-flex items-center gap-1.5 hover:text-yellow-400 hover:translate-x-1.5 transition-all duration-300">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="#layanan" class="inline-flex items-center gap-1.5 hover:text-yellow-400 hover:translate-x-1.5 transition-all duration-300">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                Layanan
                            </a>
                        </li>
                        <li>
                            <a href="#alur" class="inline-flex items-center gap-1.5 hover:text-yellow-400 hover:translate-x-1.5 transition-all duration-300">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                Alur
                            </a>
                        </li>
                        <li>
                            <a href="#faq" class="inline-flex items-center gap-1.5 hover:text-yellow-400 hover:translate-x-1.5 transition-all duration-300">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                FAQ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
 
        <div style="border-top: 1px solid rgba(255,255,255,0.08);">
            <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-white/35">&copy; 2025 PDSI Universitas Nurul Jadid. Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4 text-xs text-white/35">
                    <a href="#" class="hover:text-yellow-400 transition-colors duration-200">Kebijakan Privasi</a>
                    <span class="text-white/20">·</span>
                    <a href="#" class="hover:text-yellow-400 transition-colors duration-200">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

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

        document.querySelectorAll('.faq-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const item = trigger.closest('.faq-item');
                const isOpen = item.classList.contains('open');
                document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
                if (!isOpen) item.classList.add('open');
            });
        });
    </script>
</body>

</html>
