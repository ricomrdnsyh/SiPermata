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
                            DEFAULT: '#1e40af',
                            dark: '#1e3a5f',
                            soft: '#eff6ff'
                        },
                        accent: '#0f2744',
                        muted: '#64748b',
                    },
                    boxShadow: {
                        soft: '0 14px 40px rgba(15,39,68,0.08)',
                        lift: '0 20px 60px rgba(15,39,68,0.12)',
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
            background: linear-gradient(135deg, rgba(30, 64, 175, .3), rgba(59, 130, 246, .2), rgba(6, 182, 212, .2));
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

        .faq-item.open {
            border-color: #3b82f6 !important;
        }

        .faq-item.open .faq-trigger span {
            color: #2563eb !important;
        }

        .faq-item.open .faq-answer {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        .faq-item.open .faq-chevron {
            transform: rotate(180deg);
            color: #2563eb !important;
        }

        .faq-chevron {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slide-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            animation: slide-up 0.6s ease-out forwards;
        }

        .animate-slide-up-delay-1 {
            animation: slide-up 0.6s ease-out 0.15s forwards;
            opacity: 0;
        }

        .animate-slide-up-delay-2 {
            animation: slide-up 0.6s ease-out 0.3s forwards;
            opacity: 0;
        }

        .animate-slide-up-delay-3 {
            animation: slide-up 0.6s ease-out 0.45s forwards;
            opacity: 0;
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

        .hero-glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
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

        <section id="beranda" class="relative overflow-hidden bg-slate-50">
            <div class="absolute inset-0 z-0">
                <div
                    class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-100/60 via-indigo-50/40 to-white opacity-80">
                </div>
                <div
                    class="absolute top-[20%] left-[-10%] w-[500px] h-[500px] bg-blue-400/20 rounded-full mix-blend-multiply filter blur-[100px] animate-[pulse_8s_ease-in-out_infinite]">
                </div>
                <div
                    class="absolute bottom-[-10%] right-[-5%] w-[600px] h-[600px] bg-cyan-300/20 rounded-full mix-blend-multiply filter blur-[100px] animate-[pulse_10s_ease-in-out_infinite]">
                </div>
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-indigo-300/10 rounded-full mix-blend-multiply filter blur-[120px] animate-[pulse_12s_ease-in-out_infinite]">
                </div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSg1OSwgMTMwLCAyNDYsIDAuMDUpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-60"
                    style="-webkit-mask-image:linear-gradient(to bottom,transparent,black,transparent);mask-image:linear-gradient(to bottom,transparent,black,transparent);">
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 w-full h-48 bg-gradient-to-t from-slate-50 via-slate-50/80 to-transparent z-0 pointer-events-none">
            </div>

            <div class="relative mx-auto max-w-7xl px-6 md:px-14 lg:px-20 pt-16 md:pt-24 pb-12 md:pb-32 z-10">
                <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">

                    <div class="lg:col-span-7 space-y-8">
                        <div
                            class="inline-flex items-center gap-2.5 px-5 py-2 rounded-full bg-white/60 backdrop-blur-md border border-white/80 shadow-sm animate-slide-up hover:shadow-md hover:bg-white/80 transition-all cursor-default">
                            <span class="relative flex h-2.5 w-2.5">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                            </span>
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-widest">Portal Surat
                                Mahasiswa UNUJA</span>
                        </div>

                        <div class="space-y-5">
                            <h1
                                class="text-4xl md:text-5xl lg:text-[3.25rem] font-extrabold leading-[1.15] tracking-tight animate-slide-up-delay-1 text-slate-900">
                                Ajukan Surat Resmi
                                <br class="hidden lg:block">
                                <span class="relative inline-block mt-2">
                                    <span
                                        class="absolute inset-0 bg-blue-200/40 transform -skew-x-12 -rotate-2 rounded-lg"></span>
                                    <span
                                        class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500 bg-clip-text text-transparent">Lebih
                                        Cepat &amp; Mudah</span>
                                </span>
                            </h1>
                            <p
                                class="text-base md:text-lg text-slate-600 max-w-lg leading-relaxed animate-slide-up-delay-2">
                                Platform digital terintegrasi untuk pengajuan surat akademik. Bebas antre, lacak status
                                secara real-time, dan unduh surat resmi ber-QR code di mana saja.
                            </p>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row items-center sm:items-start gap-4 animate-slide-up-delay-3 pt-2">
                            <a href="https://sso.unuja.ac.id"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl font-bold text-white text-sm
                                bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-[0_10px_20px_-10px_rgba(37,99,235,0.5)] hover:shadow-[0_15px_25px_-10px_rgba(37,99,235,0.6)] hover:-translate-y-0.5 transition-all duration-300">
                                Mulai Ajukan Surat
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                            <a href="{{ asset('panduan.pdf') }}" target="_blank" rel="noopener noreferrer"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-2xl font-bold text-sm text-slate-700
                                    bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 hover:text-blue-600 hover:-translate-y-0.5 transition-all duration-300 group">
                                <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition-colors"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Lihat Panduan
                            </a>
                        </div>

                        <div class="grid sm:grid-cols-3 gap-4 pt-4 animate-slide-up-delay-3">
                            <div
                                class="group rounded-2xl bg-white/60 backdrop-blur-md border border-white/80 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 hover:bg-white transition-all duration-300 cursor-default">
                                <div
                                    class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-blue-100 transition-all duration-300">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm mb-1">Cepat &amp; Instan</h3>
                                <p class="text-[11px] font-medium text-slate-500 leading-relaxed">Proses digital tanpa
                                    perlu datang ke kampus.</p>
                            </div>
                            <div
                                class="group rounded-2xl bg-white/60 backdrop-blur-md border border-white/80 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 hover:bg-white transition-all duration-300 cursor-default">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-indigo-100 transition-all duration-300">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                        stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm mb-1">Resmi ber-QR</h3>
                                <p class="text-[11px] font-medium text-slate-500 leading-relaxed">Dilengkapi TTE dan
                                    QRCode verifikasi sah.</p>
                            </div>
                            <div
                                class="group rounded-2xl bg-white/60 backdrop-blur-md border border-white/80 p-4 shadow-sm hover:shadow-md hover:-translate-y-1 hover:bg-white transition-all duration-300 cursor-default">
                                <div
                                    class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-cyan-100 transition-all duration-300">
                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor"
                                        stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm mb-1">Real-time</h3>
                                <p class="text-[11px] font-medium text-slate-500 leading-relaxed">Pantau status
                                    pengajuan langsung di dasbor.</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 hidden lg:block animate-slide-up relative z-20">
                        <div
                            class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-pulse">
                        </div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-[pulse_4s_ease-in-out_infinite]"
                            style="animation-delay: 1s;"></div>

                        <div
                            class="relative bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white p-7 shadow-[0_20px_60px_-15px_rgba(30,64,175,0.15)] transition-all duration-500 hover:shadow-[0_30px_70px_-15px_rgba(30,64,175,0.2)] hover:-translate-y-2 group/card">
                            <div class="flex items-center justify-between mb-8 border-b border-slate-100 pb-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white shadow-inner">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-800 text-base tracking-tight">SiPermata</p>
                                        <p class="text-xs font-medium text-slate-500">Pengajuan Surat
                                            Mahasiswa Terpadu
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 mb-6">
                                <div
                                    class="rounded-xl bg-slate-50/80 border border-slate-100 p-3 text-center transition-colors group-hover/card:bg-blue-50/50">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Surat
                                    </p>
                                    <p class="text-xl font-black text-slate-800">6</p>
                                </div>
                                <div
                                    class="rounded-xl bg-slate-50/80 border border-slate-100 p-3 text-center transition-colors group-hover/card:bg-indigo-50/50">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                        Proses</p>
                                    <p class="text-xl font-black text-slate-800">1-2<span
                                            class="text-sm font-bold text-slate-400">hr</span></p>
                                </div>
                                <div
                                    class="rounded-xl bg-slate-50/80 border border-slate-100 p-3 text-center transition-colors group-hover/card:bg-cyan-50/50">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">
                                        Status</p>
                                    <p class="text-xl font-black text-slate-800">100%</p>
                                </div>
                            </div>
                            <div class="space-y-3 mb-6">
                                <div
                                    class="animate-float delay-1 flex items-center gap-4 rounded-xl bg-white border border-slate-100 p-3 shadow-sm hover:border-blue-200 hover:shadow-md transition-all cursor-default">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-800">Autentikasi SSO</p>
                                        <p class="text-[11px] font-medium text-slate-500">Otomatis sinkron NIM &amp;
                                            Prodi</p>
                                    </div>
                                    <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                            stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>

                                <div
                                    class="animate-float delay-2 flex items-center gap-4 rounded-xl bg-white border border-slate-100 p-3 shadow-sm hover:border-indigo-200 hover:shadow-md transition-all cursor-default">
                                    <div
                                        class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                            stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-800">Tanda Tangan Elektronik</p>
                                        <p class="text-[11px] font-medium text-slate-500">Disertai QRCode verifikasi
                                        </p>
                                    </div>
                                    <div class="w-6 h-6 rounded-full bg-slate-50 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                            stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100/50 p-4 flex items-center justify-between gap-3 shadow-inner">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-white flex items-center justify-center shadow-sm">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-slate-700">Surat Resmi Tersedia 24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section id="layanan" class="py-12 md:py-20 bg-slate-50 relative overflow-hidden">
            <div
                class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSg1OSwgMTMwLCAyNDYsIDAuMDQpIiBzdHJva2Utd2lkdGg9IjEiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjZ3JpZCkiLz48L3N2Zz4=')] opacity-60 z-0">
            </div>

            <div class="relative mx-auto max-w-7xl px-6 lg:px-8 z-10">

                <div class="text-center max-w-2xl mx-auto mb-14">
                    <h3
                        class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50/80 border border-blue-100 text-blue-600 font-bold text-[11px] uppercase tracking-widest mb-4 shadow-sm cursor-default">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                        Layanan
                    </h3>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Enam Surat Utama <br class="hidden sm:block"> Dalam Satu Portal Terpadu
                    </h2>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                    <div
                        class="group bg-white/70 backdrop-blur-md rounded-[1.5rem] p-6 border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 hover:border-blue-200/60 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100/50 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-md transition-all duration-300 border border-blue-100/50">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-slate-800 text-base mb-1.5 group-hover:text-blue-600 transition-colors">
                                    Surat Keterangan Aktif</h3>
                                <p class="text-slate-500 text-[13px] leading-relaxed mb-4">Untuk keperluan beasiswa,
                                    persyaratan bank, dan instansi lainnya.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Umum</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">PNS</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">P3K</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="group bg-white/70 backdrop-blur-md rounded-[1.5rem] p-6 border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 hover:border-violet-200/60 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-50 to-violet-100/50 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-md transition-all duration-300 border border-violet-100/50">
                                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-slate-800 text-base mb-1.5 group-hover:text-violet-600 transition-colors">
                                    Surat Izin Penelitian</h3>
                                <p class="text-slate-500 text-[13px] leading-relaxed mb-4">Pengantar resmi untuk riset
                                    akademik tingkat lanjut di instansi.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Skripsi</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Tesis</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Disertasi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="group bg-white/70 backdrop-blur-md rounded-[1.5rem] p-6 border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 hover:border-emerald-200/60 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:rotate-3 group-hover:shadow-md transition-all duration-300 border border-emerald-100/50">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-slate-800 text-base mb-1.5 group-hover:text-emerald-600 transition-colors">
                                    Permohonan Observasi</h3>
                                <p class="text-slate-500 text-[13px] leading-relaxed mb-4">Perizinan observasi lapangan
                                    untuk pemenuhan tugas mata kuliah.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Lapangan</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Instansi</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Sekolah</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="group bg-white/70 backdrop-blur-md rounded-[1.5rem] p-6 border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 hover:border-amber-200/60 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100/50 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:-rotate-3 group-hover:shadow-md transition-all duration-300 border border-amber-100/50">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-slate-800 text-base mb-1.5 group-hover:text-amber-600 transition-colors">
                                    Surat Permohonan PKL</h3>
                                <p class="text-slate-500 text-[13px] leading-relaxed mb-4">Pengantar khusus untuk
                                    praktik kerja lapangan, magang, atau KKN.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Magang</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Industri</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Pemda</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="group bg-white/70 backdrop-blur-md rounded-[1.5rem] p-6 border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 hover:border-rose-200/60 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-50 to-rose-100/50 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:-rotate-3 group-hover:shadow-md transition-all duration-300 border border-rose-100/50">
                                <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-slate-800 text-base mb-1.5 group-hover:text-rose-600 transition-colors">
                                    Surat Rekomendasi</h3>
                                <p class="text-slate-500 text-[13px] leading-relaxed mb-4">Dukungan pimpinan/dekan
                                    untuk beasiswa atau studi lanjut.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Beasiswa</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Studi</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Kerja</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="group bg-white/70 backdrop-blur-md rounded-[1.5rem] p-6 border border-white/80 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_40px_rgb(0,0,0,0.08)] hover:-translate-y-1 hover:border-cyan-200/60 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div
                                class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-50 to-cyan-100/50 flex items-center justify-center shrink-0 group-hover:scale-110 group-hover:-rotate-3 group-hover:shadow-md transition-all duration-300 border border-cyan-100/50">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14l9-5-9-5-9 5 9 5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                </svg>
                            </div>
                            <div>
                                <h3
                                    class="font-bold text-slate-800 text-base mb-1.5 group-hover:text-cyan-600 transition-colors">
                                    Surat Keterangan Lulus</h3>
                                <p class="text-slate-500 text-[13px] leading-relaxed mb-4">Pengganti ijazah sementara
                                    untuk lamaran kerja &amp; pendaftaran.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">CPNS</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">P3K</span>
                                    <span
                                        class="text-[10px] font-bold px-2.5 py-1 rounded-md bg-white border border-slate-200 text-slate-600 shadow-sm">Beasiswa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="alur" class="py-16 lg:py-24 relative overflow-hidden"
            style="background: linear-gradient(160deg, #0f2744, #1e3a5f);">
            <div class="absolute inset-0 opacity-[0.04]"
                style="background-image: radial-gradient(circle at 2px 2px, white 2px, transparent 0); background-size: 32px 32px;">
            </div>
            <div
                class="absolute w-[600px] h-[600px] -top-40 -left-40 bg-blue-500/30 rounded-full blur-[100px] opacity-40 pointer-events-none">
            </div>
            <div
                class="absolute w-[500px] h-[500px] -bottom-40 -right-20 bg-cyan-400/20 rounded-full blur-[100px] opacity-30 pointer-events-none">
            </div>

            <div class="max-w-7xl mx-auto px-6 md:px-14 lg:px-20 relative z-10">
                <div class="text-center mb-16 relative">
                    <h2 class="font-black text-white text-2xl lg:text-3xl">Alur Pengajuan Surat</h2>
                </div>

                <div class="relative w-full mx-auto">
                    <div
                        class="hidden lg:block absolute top-[2.5rem] left-[12.5%] right-[12.5%] h-[3px] bg-white/10 z-0">
                    </div>
                    <div
                        class="hidden lg:block absolute top-[2.5rem] left-[12.5%] w-[25%] h-[3px] bg-gradient-to-r from-blue-500 to-white/10 z-0">
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10 lg:gap-4 relative z-10">
                        <div class="flex flex-col items-center text-center relative group">
                            <div
                                class="w-20 h-20 rounded-full bg-blue-500 text-white shadow-xl shadow-blue-500/40 ring-4 ring-blue-500/30 flex items-center justify-center mb-6 shrink-0 z-10 transition-transform duration-300 group-hover:-translate-y-1 group-active:-translate-y-1 group-hover:scale-105 group-active:scale-105">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-white text-lg mb-3 transition-colors text-blue-400">
                                Pilih surat
                            </h4>
                            <p class="text-white/60 text-sm leading-relaxed px-2 max-w-xs">Pilih jenis surat dan varian
                                yang mau diajukan dari
                                dasbor mahasiswa.</p>
                        </div>

                        <div class="flex flex-col items-center text-center relative group">
                            <div
                                class="w-20 h-20 rounded-full bg-white/5 text-white/50 border-[3px] border-white/20 backdrop-blur-sm flex items-center justify-center mb-6 shrink-0 z-10 transition-transform duration-300 group-hover:-translate-y-1 group-active:-translate-y-1 group-hover:scale-105 group-active:scale-105 group-hover:text-blue-300 group-hover:border-blue-400/50">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-white text-lg mb-3 transition-colors group-hover:text-blue-300">
                                Verifikasi BAAK
                            </h4>
                            <p class="text-white/60 text-sm leading-relaxed px-2 max-w-xs">Mengecek data pengajuan,
                                kelengkapan dan memvalidasi pengajuan surat</p>
                        </div>

                        <div class="flex flex-col items-center text-center relative group">
                            <div
                                class="w-20 h-20 rounded-full bg-white/5 text-white/50 border-[3px] border-white/20 backdrop-blur-sm flex items-center justify-center mb-6 shrink-0 z-10 transition-transform duration-300 group-hover:-translate-y-1 group-active:-translate-y-1 group-hover:scale-105 group-active:scale-105 group-hover:text-blue-300 group-hover:border-blue-400/50">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-white text-lg mb-3 transition-colors group-hover:text-blue-300">
                                Persetujuan Dekan
                            </h4>
                            <p class="text-white/60 text-sm leading-relaxed px-2 max-w-xs">Melakukan tinjauan dan
                                persetujuan akhir pada pengajuan surat</p>
                        </div>

                        <div class="flex flex-col items-center text-center relative group">
                            <div
                                class="w-20 h-20 rounded-full bg-white/5 text-white/50 border-[3px] border-white/20 backdrop-blur-sm flex items-center justify-center mb-6 shrink-0 z-10 transition-transform duration-300 group-hover:-translate-y-1 group-active:-translate-y-1 group-hover:scale-105 group-active:scale-105 group-hover:text-blue-300 group-hover:border-blue-400/50">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="font-bold text-white text-lg mb-3 transition-colors group-hover:text-blue-300">
                                Cetak &amp; email
                            </h4>
                            <p class="text-white/60 text-sm leading-relaxed px-2 max-w-xs">Surat final siap unduh/cetak
                                mandiri + terkirim
                                otomatis ke email.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="py-20 lg:py-32 bg-slate-50/60 relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div
                    class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-200/60 to-transparent">
                </div>
                <div
                    class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-blue-200/60 to-transparent">
                </div>
                <div
                    class="absolute -top-20 -right-20 w-[500px] h-[500px] bg-gradient-to-br from-blue-100/70 to-indigo-100/70 rounded-full blur-[100px]">
                </div>
                <div
                    class="absolute -bottom-20 -left-20 w-[400px] h-[400px] bg-gradient-to-tr from-violet-100/60 to-blue-100/60 rounded-full blur-[80px]">
                </div>
            </div>

            <div class="mx-auto max-w-7xl px-6 md:px-14 lg:px-20 relative z-10">
                <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 items-start">
                    <div class="lg:col-span-4 lg:sticky lg:top-28 text-center lg:text-left">
                        <p
                            class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-primary-surface border border-primary-mist text-primary text-xs font-bold uppercase tracking-widest mb-4">
                            FAQ</p>
                        <h2
                            class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                            Pertanyaan Umum
                        </h2>
                        <p class="text-slate-500 text-sm leading-relaxed mb-8">
                            Punya pertanyaan seputar layanan SiPermata? Temukan jawaban untuk pertanyaan yang paling
                            sering diajukan oleh pengguna di bawah ini.
                        </p>
                        <div class="hidden lg:block bg-slate-50 border border-slate-200 rounded-2xl p-5">
                            <div
                                class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center mb-3 shadow-sm">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <p class="text-slate-800 font-bold text-[15px] mb-1">Masih butuh bantuan?</p>
                            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                Jika jawaban yang Anda cari tidak ada, silakan hubungi tim kami untuk bantuan lebih
                                lanjut.
                            </p>
                            <a href="#"
                                class="inline-flex items-center gap-2 bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-slate-700 transition-colors no-underline">
                                Hubungi BAAK
                            </a>
                        </div>

                    </div>
                    <div class="lg:col-span-8 space-y-3">
                        <div
                            class="faq-item group/item bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 relative">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-600 opacity-0 group-hover/item:opacity-100 transition-opacity duration-300 rounded-l-2xl pointer-events-none">
                            </div>
                            <h3 class="m-0">
                                <button
                                    class="faq-trigger cursor-pointer w-full flex items-center justify-between gap-4 px-6 py-5 text-left focus:outline-none group collapsed">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <span
                                            class="shrink-0 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="font-semibold text-slate-800 text-[15px] leading-snug group-hover:text-blue-700 transition-colors">Apakah
                                            saya perlu mengunggah dokumen fisik?</span>
                                    </div>
                                    <span
                                        class="shrink-0 w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:border-blue-100 group-hover:text-blue-500 transition-all duration-200">
                                        <svg class="faq-chevron w-4 h-4 transition-transform duration-300"
                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <div class="faq-answer">
                                <div
                                    class="px-6 pb-5 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 ml-11">
                                    Tidak perlu. Semua data mahasiswa tersinkron otomatis dari SIAKAD. Anda cukup
                                    memilih jenis surat dan mengisi informasi tambahan yang diperlukan tanpa melampirkan
                                    berkas fisik apapun.
                                </div>
                            </div>
                        </div>
                        <div
                            class="faq-item group/item bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 relative">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-600 opacity-0 group-hover/item:opacity-100 transition-opacity duration-300 rounded-l-2xl pointer-events-none">
                            </div>
                            <h3 class="m-0">
                                <button
                                    class="faq-trigger cursor-pointer w-full flex items-center justify-between gap-4 px-6 py-5 text-left focus:outline-none group collapsed">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <span
                                            class="shrink-0 w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="font-semibold text-slate-800 text-[15px] leading-snug group-hover:text-blue-700 transition-colors">Berapa
                                            lama proses penerbitan surat?</span>
                                    </div>
                                    <span
                                        class="shrink-0 w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:border-blue-100 group-hover:text-blue-500 transition-all duration-200">
                                        <svg class="faq-chevron w-4 h-4 transition-transform duration-300"
                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <div class="faq-answer">
                                <div
                                    class="px-6 pb-5 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 ml-11">
                                    Rata-rata 1–2 hari kerja setelah verifikasi BAAK selesai. Anda akan mendapat
                                    notifikasi email di setiap perubahan status pengajuan secara otomatis.
                                </div>
                            </div>
                        </div>
                        <div
                            class="faq-item group/item bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 relative">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-600 opacity-0 group-hover/item:opacity-100 transition-opacity duration-300 rounded-l-2xl pointer-events-none">
                            </div>
                            <h3 class="m-0">
                                <button
                                    class="faq-trigger cursor-pointer w-full flex items-center justify-between gap-4 px-6 py-5 text-left focus:outline-none group collapsed">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <span
                                            class="shrink-0 w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </span>
                                        <span
                                            class="font-semibold text-slate-800 text-[15px] leading-snug group-hover:text-blue-700 transition-colors">Bisakah
                                            saya mengajukan lebih dari satu surat sekaligus?</span>
                                    </div>
                                    <span
                                        class="shrink-0 w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:border-blue-100 group-hover:text-blue-500 transition-all duration-200">
                                        <svg class="faq-chevron w-4 h-4 transition-transform duration-300"
                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <div class="faq-answer">
                                <div
                                    class="px-6 pb-5 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 ml-11">
                                    Bisa. Anda dapat mengajukan beberapa jenis surat secara bersamaan melalui dasbor
                                    mahasiswa. Setiap pengajuan akan diproses secara independen.
                                </div>
                            </div>
                        </div>
                        <div
                            class="faq-item group/item bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 relative">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-600 opacity-0 group-hover/item:opacity-100 transition-opacity duration-300 rounded-l-2xl pointer-events-none">
                            </div>
                            <h3 class="m-0">
                                <button
                                    class="faq-trigger cursor-pointer w-full flex items-center justify-between gap-4 px-6 py-5 text-left focus:outline-none group collapsed">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <span
                                            class="shrink-0 w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="font-semibold text-slate-800 text-[15px] leading-snug group-hover:text-blue-700 transition-colors">Bagaimana
                                            cara login ke SiPermata?</span>
                                    </div>
                                    <span
                                        class="shrink-0 w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:border-blue-100 group-hover:text-blue-500 transition-all duration-200">
                                        <svg class="faq-chevron w-4 h-4 transition-transform duration-300"
                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <div class="faq-answer">
                                <div
                                    class="px-6 pb-5 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 ml-11">
                                    Login menggunakan akun SSO UNUJA (NIM dan password) melalui portal <span
                                        class="font-medium text-slate-700">sso.unuja.ac.id</span>. Akun SIAKAD Anda
                                    langsung bisa digunakan.
                                </div>
                            </div>
                        </div>
                        <div
                            class="faq-item group/item bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-200 transition-all duration-300 relative">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 to-indigo-600 opacity-0 group-hover/item:opacity-100 transition-opacity duration-300 rounded-l-2xl pointer-events-none">
                            </div>
                            <h3 class="m-0">
                                <button
                                    class="faq-trigger cursor-pointer w-full flex items-center justify-between gap-4 px-6 py-5 text-left focus:outline-none group collapsed">
                                    <div class="flex items-center gap-3.5 min-w-0">
                                        <span
                                            class="shrink-0 w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </span>
                                        <span
                                            class="font-semibold text-slate-800 text-[15px] leading-snug group-hover:text-blue-700 transition-colors">Apakah
                                            surat yang diterbitkan resmi dan sah?</span>
                                    </div>
                                    <span
                                        class="shrink-0 w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:border-blue-100 group-hover:text-blue-500 transition-all duration-200">
                                        <svg class="faq-chevron w-4 h-4 transition-transform duration-300"
                                            fill="none" stroke="currentColor" stroke-width="2.5"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </button>
                            </h3>
                            <div class="faq-answer">
                                <div
                                    class="px-6 pb-5 text-slate-500 text-sm leading-relaxed border-t border-slate-50 pt-4 ml-11">
                                    Ya. Setiap surat dilengkapi QR Code verifikasi resmi dan ditandatangani secara
                                    digital oleh pejabat berwenang sesuai jenis suratnya.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="relative mt-auto" style="background: linear-gradient(160deg, #0f2744, #1e3a5f 50%, #1e40af);">
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent">
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-16 pb-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8">
                <div class="sm:col-span-2 lg:col-span-4">
                    <div class="mb-6">
                        <img src="{{ asset('assets/media/logos/sipermata.png') }}" alt="Logo SiPermata"
                            class="h-10 lg:h-[45px] w-auto object-contain hover:opacity-90 active:opacity-90 transition-opacity active:scale-[0.98]">
                    </div>
                    <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                        Portal layanan surat menyurat mahasiswa Universitas Nurul Jadid yang terintegrasi secara digital
                        untuk kemudahan layanan akademik.
                    </p>
                    <div class="flex gap-2.5 mt-6">
                        <a href="https://www.facebook.com/universitasnuruljadid/" target="_blank"
                            class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 active:bg-white/10 hover:text-yellow-400 active:text-yellow-400 transition-all no-underline active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/unujaofficial/" target="_blank"
                            class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 active:bg-white/10 hover:text-yellow-400 active:text-yellow-400 transition-all no-underline active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                            </svg>
                        </a>
                        <a href="https://x.com/unujaofficial" target="_blank"
                            class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 active:bg-white/10 hover:text-yellow-400 active:text-yellow-400 transition-all no-underline active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="https://www.tiktok.com/@unujaofficial" target="_blank"
                            class="w-9 h-9 rounded-xl bg-white/5 flex items-center justify-center text-white/40 hover:bg-white/10 active:bg-white/10 hover:text-yellow-400 active:text-yellow-400 transition-all no-underline active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.54V6.78a4.85 4.85 0 01-1.02-.09z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-3 lg:col-start-5">
                    <h4 class="font-bold text-white/80 text-xs uppercase tracking-[0.15em] mb-4">Kontak</h4>
                    <div class="w-8 h-0.5 rounded-full mb-5 bg-yellow-400"></div>
                    <ul class="flex flex-col gap-3.5 text-sm text-white/50 list-none p-0 m-0">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 mt-0.5 shrink-0 text-white/50" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span
                                class="leading-relaxed hover:text-yellow-400 active:text-yellow-400 transition-colors cursor-default active:scale-[0.98]">
                                JL. PP Nurul Jadid, Dusun Tj. Lor, Karanganyar, Kec. Paiton, Kabupaten Probolinggo, Jawa
                                Timur 67291
                            </span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 text-white/50" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:+628883077077"
                                class="text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 transition-colors active:scale-[0.98]">0888
                                30 77077</a>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 text-white/50" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="6 9 6 2 18 2 18 9" />
                                <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                                <rect x="6" y="14" width="12" height="8" />
                            </svg>
                            <span
                                class="hover:text-yellow-400 active:text-yellow-400 transition-colors cursor-default active:scale-[0.98]">Fax
                                0888 30 77077</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 shrink-0 text-white/50" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:unuja@unuja.ac.id"
                                class="text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 transition-colors active:scale-[0.98]">unuja@unuja.ac.id</a>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-3">
                    <h4 class="font-bold text-white/80 text-xs uppercase tracking-[0.15em] mb-4">Internal</h4>
                    <div class="w-8 h-0.5 rounded-full mb-5 bg-yellow-400"></div>
                    <ul class="flex flex-col gap-2.5 text-sm list-none p-0 m-0">
                        <li>
                            <a href="https://unuja.ac.id" target="_blank"
                                class="inline-flex items-center gap-2 text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 hover:translate-x-1.5 active:translate-x-1.5 transition-all duration-300 group active:scale-[0.98]">
                                <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-yellow-400 group-active:text-yellow-400 transition-colors shrink-0 active:scale-[0.98]"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Universitas Nurul Jadid
                            </a>
                        </li>
                        <li>
                            <a href="https://pmb.unuja.ac.id" target="_blank"
                                class="inline-flex items-center gap-2 text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 hover:translate-x-1.5 active:translate-x-1.5 transition-all duration-300 group active:scale-[0.98]">
                                <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-yellow-400 group-active:text-yellow-400 transition-colors shrink-0 active:scale-[0.98]"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                PMB Universitas Nurul Jadid
                            </a>
                        </li>
                        <li>
                            <a href="https://sso.unuja.ac.id" target="_blank"
                                class="inline-flex items-center gap-2 text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 hover:translate-x-1.5 active:translate-x-1.5 transition-all duration-300 group active:scale-[0.98]">
                                <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-yellow-400 group-active:text-yellow-400 transition-colors shrink-0 active:scale-[0.98]"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Portal SSO Universitas Nurul Jadid
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-2">
                    <h4 class="font-bold text-white/80 text-xs uppercase tracking-[0.15em] mb-4">Navigasi</h4>
                    <div class="w-8 h-0.5 rounded-full mb-5 bg-yellow-400"></div>
                    <ul class="flex flex-col gap-2.5 text-sm list-none p-0 m-0">
                        <li>
                            <a href="#beranda"
                                class="inline-flex items-center gap-2 text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 hover:translate-x-1.5 active:translate-x-1.5 transition-all duration-300 group active:scale-[0.98]">
                                <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-yellow-400 group-active:text-yellow-400 transition-colors shrink-0 active:scale-[0.98]"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Beranda
                            </a>
                        </li>
                        <li>
                            <a href="#layanan"
                                class="inline-flex items-center gap-2 text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 hover:translate-x-1.5 active:translate-x-1.5 transition-all duration-300 group active:scale-[0.98]">
                                <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-yellow-400 group-active:text-yellow-400 transition-colors shrink-0 active:scale-[0.98]"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Layanan
                            </a>
                        </li>
                        <li>
                            <a href="#alur"
                                class="inline-flex items-center gap-2 text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 hover:translate-x-1.5 active:translate-x-1.5 transition-all duration-300 group active:scale-[0.98]">
                                <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-yellow-400 group-active:text-yellow-400 transition-colors shrink-0 active:scale-[0.98]"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                Alur
                            </a>
                        </li>
                        <li>
                            <a href="#faq"
                                class="inline-flex items-center gap-2 text-white/50 no-underline hover:text-yellow-400 active:text-yellow-400 hover:translate-x-1.5 active:translate-x-1.5 transition-all duration-300 group active:scale-[0.98]">
                                <svg class="w-3.5 h-3.5 text-white/50 group-hover:text-yellow-400 group-active:text-yellow-400 transition-colors shrink-0 active:scale-[0.98]"
                                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                                FAQ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-white/5">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-3">
                <p class="text-sm text-white/30 m-0 font-medium">&copy; 2026 PDSI Universitas Nurul Jadid. Hak Cipta
                    Dilindungi.</p>
                <div class="flex items-center gap-4 text-sm text-white/30">
                    <a href="#"
                        class="text-white/30 no-underline hover:text-yellow-400 active:text-yellow-400 transition-colors active:scale-[0.98]">Kebijakan
                        Privasi</a>
                    <span class="w-1 h-1 rounded-full bg-white/10"></span>
                    <a href="#"
                        class="text-white/30 no-underline hover:text-yellow-400 active:text-yellow-400 transition-colors active:scale-[0.98]">Syarat
                        &amp; Ketentuan</a>
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
