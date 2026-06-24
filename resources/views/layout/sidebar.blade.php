<style>
    /* Clean Sidebar Enhancements */
    #kt_app_sidebar_user .user-card {
        background: rgba(255, 255, 255, 0.03);
        border: none;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
    }

    #kt_app_sidebar_user .user-card:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    body[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_user .sidebar-minimize-hide,
    html[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_user .sidebar-minimize-hide,
    body.app-sidebar-minimize #kt_app_sidebar_user .sidebar-minimize-hide,
    html.app-sidebar-minimize #kt_app_sidebar_user .sidebar-minimize-hide {
        display: none !important
    }

    body[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_user,
    html[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_user,
    body.app-sidebar-minimize #kt_app_sidebar_user,
    html.app-sidebar-minimize #kt_app_sidebar_user {
        padding-left: .5rem !important;
        padding-right: .5rem !important
    }

    body[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_user .user-card,
    html[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_user .user-card,
    body.app-sidebar-minimize #kt_app_sidebar_user .user-card,
    html.app-sidebar-minimize #kt_app_sidebar_user .user-card {
        padding: .5rem !important
    }

    body[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_footer .sidebar-minimize-hide,
    html[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_footer .sidebar-minimize-hide,
    body.app-sidebar-minimize #kt_app_sidebar_footer .sidebar-minimize-hide,
    html.app-sidebar-minimize #kt_app_sidebar_footer .sidebar-minimize-hide {
        display: none !important
    }

    body[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_footer .btn,
    html[data-kt-app-sidebar-minimize="on"] #kt_app_sidebar_footer .btn,
    body.app-sidebar-minimize #kt_app_sidebar_footer .btn,
    html.app-sidebar-minimize #kt_app_sidebar_footer .btn {
        padding-left: .75rem !important;
        padding-right: .75rem !important;
        justify-content: center !important
    }

    /* Clean Logout Button */
    #kt_app_sidebar_footer .btn {
        background: rgba(255, 255, 255, 0.04) !important;
        border: none !important;
        color: rgba(255, 255, 255, 0.8) !important;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        font-weight: 600;
        padding: 0.75rem 1rem;
    }

    #kt_app_sidebar_footer .btn i {
        color: rgba(255, 255, 255, 0.8) !important;
        transition: all 0.3s ease;
    }

    #kt_app_sidebar_footer .btn:hover {
        background: rgba(220, 53, 69, 0.85) !important;
        /* Soft crimson hover */
        color: #ffffff !important;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.25);
        transform: translateY(-1px);
    }

    #kt_app_sidebar_footer .btn:hover i {
        color: #ffffff !important;
        transform: translateX(3px);
        /* Subtle slide effect */
    }

    /* Thin Custom Scrollbar */
    #kt_app_sidebar_menu_scroll::-webkit-scrollbar {
        width: 4px;
    }

    #kt_app_sidebar_menu_scroll::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
    }

    #kt_app_sidebar_menu_scroll:hover::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.25);
    }

    #kt_app_sidebar_logo .app-sidebar-logo-default {
        height: 45px !important;
        width: auto !important;
        margin-top: 0px;
        margin-left: 0;
        max-width: 100%;
        object-fit: contain;
        object-position: left center;
    }

    #kt_app_sidebar_logo .app-sidebar-logo-minimize {
        height: 28px !important;
        width: auto !important;
        object-fit: contain;
        object-position: center;
    }

    @media (min-width: 992px) {
        #kt_app_sidebar_logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #kt_app_sidebar_logo>a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 991.98px) {
        #kt_app_sidebar_logo .app-sidebar-logo-default {
            height: 38px !important;
            max-width: 100%;
        }

        #kt_app_sidebar_logo .app-sidebar-logo-minimize {
            height: 24px !important;
        }
    }
    /* Custom Menu Arrow + and - */
    #kt_app_sidebar_menu .menu-item .menu-arrow:after {
        content: "+" !important;
        background: none !important;
        -webkit-mask-image: none !important;
        mask-image: none !important;
        transform: none !important;
        font-size: 1.25rem !important;
        font-weight: 400 !important;
        color: inherit !important;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    #kt_app_sidebar_menu .menu-item.show > .menu-link .menu-arrow:after,
    #kt_app_sidebar_menu .menu-item.here > .menu-link .menu-arrow:after {
        content: "-" !important;
    }
</style>

@php
    $currentUser = Auth::user();
    $roleName = 'Pengguna';
    if ($currentUser) {
        if ($currentUser->role == 'admin') {
            $roleName = 'Administrator';
        } elseif ($currentUser->role == 'DEKAN') {
            $roleName = 'Dekan';
        } elseif ($currentUser->role == 'BAK') {
            $roleName = 'BAK';
        } elseif ($currentUser->role == 'mahasiswa') {
            $roleName = 'Mahasiswa';
        }
    }
@endphp

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="#">
            <img alt="Logo" src="{{ asset('assets/media/logos/sipermata.png') }}"
                class="app-sidebar-logo-default" />
            <img alt="Logo" src="{{ asset('assets/media/logos/unuja.png') }}" class="app-sidebar-logo-minimize" />
        </a>

        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span><span class="path2"></span>
            </i>
        </div>
    </div>

    <div class="px-4 pt-4 pb-3" id="kt_app_sidebar_user">
        <a href="#"
            class="user-card d-flex flex-column align-items-center text-center w-100 rounded-3 p-3 text-decoration-none">
            <div class="symbol symbol-42px symbol-circle position-relative">
                <img src="{{ asset('assets/media/avatars/profile.png') }}" alt="avatar"
                    class="w-30 h-30 object-fit-cover" />
                <span
                    class="position-absolute translate-middle bottom-0 start-100 bg-success rounded-circle border-2 border-white"
                    style="width:10px;height:10px;"></span>
            </div>

            <div class="sidebar-minimize-hide mt-2 w-100">
                <div class="text-white fw-semibold text-truncate">{{ $currentUser?->nama ?? 'User' }}</div>
                <div class="text-gray-400 fs-8 text-truncate">
                    {{ $roleName }}
                </div>
            </div>
        </a>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_user, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">

                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">
                    <div class="menu-item">
                        <div class="menu-content pb-2">
                            <span class="menu-section text-muted text-uppercase fs-8 ls-1">Main</span>
                        </div>
                    </div>
                    @if (Auth::user()->role == 'admin')
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <rect x="2" y="2" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('admin/users*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z"
                                                fill="currentColor" />
                                            <path
                                                d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Pengguna</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content pb-2">
                                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Master</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::is('admin/penduduk*', 'admin/jabatan*', 'admin/fakultas*', 'admin/prodi*', 'admin/akademik*', 'admin/mitra*', 'admin/template*', 'admin/ttdSurat*', 'admin/eligible-lulus*') ? 'here show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Data Master</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/penduduk*') ? 'active' : '' }}"
                                        href="{{ route('admin.penduduk.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Penduduk</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/jabatan*') ? 'active' : '' }}"
                                        href="{{ route('admin.jabatan.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Jabatan</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/fakultas*') ? 'active' : '' }}"
                                        href="{{ route('admin.fakultas.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Fakultas</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/prodi*') ? 'active' : '' }}"
                                        href="{{ route('admin.prodi.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Prodi</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/akademik*') ? 'active' : '' }}"
                                        href="{{ route('admin.akademik.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Tahun Akademik</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/mitra*') ? 'active' : '' }}"
                                        href="{{ route('admin.mitra.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Mitra</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/template*') ? 'active' : '' }}"
                                        href="{{ route('admin.template.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Template</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/ttdSurat*') ? 'active' : '' }}"
                                        href="{{ route('admin.ttdSurat.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">TTD Surat</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/eligible-lulus*') ? 'active' : '' }}"
                                        href="{{ route('admin.eligible-lulus.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Data Mahasiswa Lulusan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content pb-2">
                                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Surat</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::is('admin/surat-aktif*', 'admin/surat-izin-penelitian*', 'admin/surat-observasi*', 'admin/surat-rekomendasi*', 'admin/surat-pkl*', 'admin/surat-keterangan-lulus*') ? 'here show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M13 5.91517C15.8 6.41517 18 8.81519 18 11.8152C18 12.5152 17.9 13.2152 17.6 13.9152L20.1 15.3152C20.6 15.6152 21.4 15.4152 21.6 14.8152C21.9 13.9152 22.1 12.9152 22.1 11.8152C22.1 7.01519 18.8 3.11521 14.3 2.01521C13.7 1.91521 13.1 2.31521 13.1 3.01521V5.91517H13Z"
                                                fill="currentColor"></path>
                                            <path opacity="0.3"
                                                d="M19.1 17.0152C19.7 17.3152 19.8 18.1152 19.3 18.5152C17.5 20.5152 14.9 21.7152 12 21.7152C9.1 21.7152 6.50001 20.5152 4.70001 18.5152C4.30001 18.0152 4.39999 17.3152 4.89999 17.0152L7.39999 15.6152C8.49999 16.9152 10.2 17.8152 12 17.8152C13.8 17.8152 15.5 17.0152 16.6 15.6152L19.1 17.0152ZM6.39999 13.9151C6.19999 13.2151 6 12.5152 6 11.8152C6 8.81517 8.2 6.41515 11 5.91515V3.01519C11 2.41519 10.4 1.91519 9.79999 2.01519C5.29999 3.01519 2 7.01517 2 11.8152C2 12.8152 2.2 13.8152 2.5 14.8152C2.7 15.4152 3.4 15.7152 4 15.3152L6.39999 13.9151Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Pengajuan Surat</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/surat-aktif*') ? 'active' : '' }}"
                                        href="{{ route('admin.surat-aktif.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Keterangan Aktif</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/surat-izin-penelitian*') ? 'active' : '' }}"
                                        href="{{ route('admin.surat-izin-penelitian.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Izin Penelitian</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/surat-observasi*') ? 'active' : '' }}"
                                        href="{{ route('admin.surat-observasi.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Permohonan Observasi</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/surat-rekomendasi*') ? 'active' : '' }}"
                                        href="{{ route('admin.surat-rekomendasi.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Rekomendasi</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/surat-pkl*') ? 'active' : '' }}"
                                        href="{{ route('admin.surat-pkl.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Permohonan PKL</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('admin/surat-keterangan-lulus*') ? 'active' : '' }}"
                                        href="{{ route('admin.surat-keterangan-lulus.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Keterangan Lulus</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('admin/history-pengajuan*') ? 'active' : '' }}"
                                href="{{ route('admin.history-pengajuan.index') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                fill="currentColor"></path>
                                            <path opacity="0.3"
                                                d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Pengajuan Mahasiswa</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('admin/rekapitulasi-surat*') ? 'active' : '' }}"
                                href="{{ route('admin.rekapitulasi.index') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                fill="currentColor"></path>
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"></path>
                                            <path
                                                d="M7 13.5C7 13.2 7.2 13 7.5 13H16.5C16.8 13 17 13.2 17 13.5C17 13.8 16.8 14 16.5 14H7.5C7.2 14 7 13.8 7 13.5ZM7.5 16H11.5C11.8 16 12 15.8 12 15.5C12 15.2 11.8 15 11.5 15H7.5C7.2 15 7 15.2 7 15.5C7 15.8 7.2 16 7.5 16ZM16.5 11H7.5C7.2 11 7 10.8 7 10.5C7 10.2 7.2 10 7.5 10H16.5C16.8 10 17 10.2 17 10.5C17 10.8 16.8 11 16.5 11Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Rekapitulasi Surat</span>
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->role == 'DEKAN')
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('dekan/dashboard*') ? 'active' : '' }}"
                                href="{{ route('dekan.dashboard') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <rect x="2" y="2" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('dekan/history-pengajuan*') ? 'active' : '' }}"
                                href="{{ route('dekan.history.index') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M13 5.91517C15.8 6.41517 18 8.81519 18 11.8152C18 12.5152 17.9 13.2152 17.6 13.9152L20.1 15.3152C20.6 15.6152 21.4 15.4152 21.6 14.8152C21.9 13.9152 22.1 12.9152 22.1 11.8152C22.1 7.01519 18.8 3.11521 14.3 2.01521C13.7 1.91521 13.1 2.31521 13.1 3.01521V5.91517H13Z"
                                                fill="currentColor"></path>
                                            <path opacity="0.3"
                                                d="M19.1 17.0152C19.7 17.3152 19.8 18.1152 19.3 18.5152C17.5 20.5152 14.9 21.7152 12 21.7152C9.1 21.7152 6.50001 20.5152 4.70001 18.5152C4.30001 18.0152 4.39999 17.3152 4.89999 17.0152L7.39999 15.6152C8.49999 16.9152 10.2 17.8152 12 17.8152C13.8 17.8152 15.5 17.0152 16.6 15.6152L19.1 17.0152ZM6.39999 13.9151C6.19999 13.2151 6 12.5152 6 11.8152C6 8.81517 8.2 6.41515 11 5.91515V3.01519C11 2.41519 10.4 1.91519 9.79999 2.01519C5.29999 3.01519 2 7.01517 2 11.8152C2 12.8152 2.2 13.8152 2.5 14.8152C2.7 15.4152 3.4 15.7152 4 15.3152L6.39999 13.9151Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Pengajuan Mahasiswa</span>
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->role == 'BAK')
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('bak/dashboard*') ? 'active' : '' }}"
                                href="{{ route('bak.dashboard') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <rect x="2" y="2" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content pb-2">
                                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Master</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::is('bak/mitra*', 'bak/ttdSurat*', 'bak/eligible-lulus*') ? 'here show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M20 15H4C2.9 15 2 14.1 2 13V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V13C22 14.1 21.1 15 20 15ZM13 12H11C10.5 12 10 12.4 10 13V16C10 16.5 10.4 17 11 17H13C13.6 17 14 16.6 14 16V13C14 12.4 13.6 12 13 12Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M14 6V5H10V6H8V5C8 3.9 8.9 3 10 3H14C15.1 3 16 3.9 16 5V6H14ZM20 15H14V16C14 16.6 13.5 17 13 17H11C10.5 17 10 16.6 10 16V15H4C3.6 15 3.3 14.9 3 14.7V18C3 19.1 3.9 20 5 20H19C20.1 20 21 19.1 21 18V14.7C20.7 14.9 20.4 15 20 15Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Data Master</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/mitra*') ? 'active' : '' }}"
                                        href="{{ route('bak.mitra.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Mitra</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/ttdSurat*') ? 'active' : '' }}"
                                        href="{{ route('bak.ttdSurat.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">TTD Surat</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/eligible-lulus*') ? 'active' : '' }}"
                                        href="{{ route('bak.eligible-lulus.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Data Mahasiswa Lulusan</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item">
                            <div class="menu-content pb-2">
                                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Surat</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::is('bak/surat-aktif*', 'bak/surat-izin-penelitian*', 'bak/surat-observasi*', 'bak/surat-rekomendasi*', 'bak/surat-pkl*', 'bak/surat-keterangan-lulus*') ? 'here show' : '' }}">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M13 5.91517C15.8 6.41517 18 8.81519 18 11.8152C18 12.5152 17.9 13.2152 17.6 13.9152L20.1 15.3152C20.6 15.6152 21.4 15.4152 21.6 14.8152C21.9 13.9152 22.1 12.9152 22.1 11.8152C22.1 7.01519 18.8 3.11521 14.3 2.01521C13.7 1.91521 13.1 2.31521 13.1 3.01521V5.91517H13Z"
                                                fill="currentColor"></path>
                                            <path opacity="0.3"
                                                d="M19.1 17.0152C19.7 17.3152 19.8 18.1152 19.3 18.5152C17.5 20.5152 14.9 21.7152 12 21.7152C9.1 21.7152 6.50001 20.5152 4.70001 18.5152C4.30001 18.0152 4.39999 17.3152 4.89999 17.0152L7.39999 15.6152C8.49999 16.9152 10.2 17.8152 12 17.8152C13.8 17.8152 15.5 17.0152 16.6 15.6152L19.1 17.0152ZM6.39999 13.9151C6.19999 13.2151 6 12.5152 6 11.8152C6 8.81517 8.2 6.41515 11 5.91515V3.01519C11 2.41519 10.4 1.91519 9.79999 2.01519C5.29999 3.01519 2 7.01517 2 11.8152C2 12.8152 2.2 13.8152 2.5 14.8152C2.7 15.4152 3.4 15.7152 4 15.3152L6.39999 13.9151Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Pengajuan Surat</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/surat-aktif*') ? 'active' : '' }}"
                                        href="{{ route('bak.surat-aktif.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Keterangan Aktif</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/surat-izin-penelitian*') ? 'active' : '' }}"
                                        href="{{ route('bak.surat-izin-penelitian.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Izin Penelitian</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/surat-observasi*') ? 'active' : '' }}"
                                        href="{{ route('bak.surat-observasi.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Permohonan Observasi</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/surat-rekomendasi*') ? 'active' : '' }}"
                                        href="{{ route('bak.surat-rekomendasi.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Rekomendasi</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/surat-pkl*') ? 'active' : '' }}"
                                        href="{{ route('bak.surat-pkl.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Permohonan PKL</span>
                                    </a>
                                </div>
                            </div>
                            <div class="menu-sub menu-sub-accordion menu-active-bg">
                                <div class="menu-item">
                                    <a class="menu-link {{ Request::is('bak/surat-keterangan-lulus*') ? 'active' : '' }}"
                                        href="{{ route('bak.surat-keterangan-lulus.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Surat Keterangan Lulus</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('bak/history-pengajuan*') ? 'active' : '' }}"
                                href="{{ route('bak.history.index') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                                                fill="currentColor"></path>
                                            <path opacity="0.3"
                                                d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Pengajuan Mahasiswa</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('bak/rekapitulasi-surat*') ? 'active' : '' }}"
                                href="{{ route('bak.rekapitulasi.index') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22Z"
                                                fill="currentColor"></path>
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor"></path>
                                            <path
                                                d="M7 13.5C7 13.2 7.2 13 7.5 13H16.5C16.8 13 17 13.2 17 13.5C17 13.8 16.8 14 16.5 14H7.5C7.2 14 7 13.8 7 13.5ZM7.5 16H11.5C11.8 16 12 15.8 12 15.5C12 15.2 11.8 15 11.5 15H7.5C7.2 15 7 15.2 7 15.5C7 15.8 7.2 16 7.5 16ZM16.5 11H7.5C7.2 11 7 10.8 7 10.5C7 10.2 7.2 10 7.5 10H16.5C16.8 10 17 10.2 17 10.5C17 10.8 16.8 11 16.5 11Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Rekapitulasi Surat</span>
                            </a>
                        </div>
                    @endif
                    @if (Auth::user()->role == 'mahasiswa')
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/dashboard*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.dashboard') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <rect x="2" y="2" width="9" height="9" rx="2"
                                                fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="2" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="13" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                            <rect opacity="0.3" x="2" y="13" width="9" height="9"
                                                rx="2" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Dashboard</span>
                            </a>
                        </div>
                        <div class="menu-item pt-5">
                            <div class="menu-content pb-2">
                                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Surat</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/surat-aktif*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.surat-aktif.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-file-alt fs-4"></i>
                                </span>
                                <span class="menu-title">Surat Keterangan Aktif</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/surat-izin-penelitian*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.surat-izin-penelitian.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-flask fs-4"></i>
                                </span>
                                <span class="menu-title">Surat Izin Penelitian</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/surat-observasi*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.surat-observasi.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-eye fs-4"></i>
                                </span>
                                <span class="menu-title">Surat Permohonan Observasi</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/surat-rekomendasi*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.surat-rekomendasi.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-thumbs-up fs-4"></i>
                                </span>
                                <span class="menu-title">Surat Rekomendasi</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/surat-pkl*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.surat-pkl.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-briefcase fs-4"></i>
                                </span>
                                <span class="menu-title">Surat Permohonan PKL</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/surat-keterangan-lulus*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.surat-keterangan-lulus.index') }}">
                                <span class="menu-icon">
                                    <i class="fas fa-graduation-cap fs-4"></i>
                                </span>
                                <span class="menu-title">Surat Keterangan Lulus</span>
                            </a>
                        </div>
                        <div class="menu-item pt-5">
                            <div class="menu-content pb-2">
                                <span class="menu-section text-muted text-uppercase fs-8 ls-1">Riwayat</span>
                            </div>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ Request::is('mahasiswa/history-pengajuan*') ? 'active' : '' }}"
                                href="{{ route('mahasiswa.history.index') }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3"
                                                d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">Riwayat Pengajuan</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="app-sidebar-footer px-4 pb-4 mt-auto" id="kt_app_sidebar_footer">
        <a href="{{ route('logout') }}"
            class="btn btn-sm btn-light w-100 d-flex align-items-center justify-content-center gap-2">
            <i class="ki-duotone ki-exit-right fs-4">
                <span class="path1"></span><span class="path2"></span>
            </i>
            <span class="sidebar-minimize-hide fw-semibold">Logout</span>
        </a>
    </div>
</div>
