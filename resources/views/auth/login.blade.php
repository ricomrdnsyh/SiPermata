<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../">
    <title>Halaman Login</title>
    <meta name="description"
        content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords"
        content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title"
        content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="bg-body">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-in -->
        <div style="position: relative; height: 100vh; overflow: hidden;">
            <img src="assets/media/illustrations/sketchy-1/bg2.jpg"
                style="position:absolute; width:100%; height:100%; object-fit:cover; opacity:0.1;">
            <div style="position:relative; z-index:1;">
                <!--begin::Content-->
                <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                    <!--begin::Logo-->
                    <a href="{{ route('login') }}" class="mb-12">
                        <img alt="Logo" src="assets/media/logos/logo-1.svg" class="h-40px" />
                    </a>
                    <!--end::Logo-->
                    <!--begin::Wrapper-->
                    <div class="w-lg-500px bg-body rounded shadow-lg p-10 p-lg-15 mx-auto">
                        <!--begin::Form-->
                        <form id="login_form" class="form w-100" action="{{ route('login-proses') }}" method="POST">
                            @csrf
                            <!--begin::Heading-->
                            <div class="text-center mb-10">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3">Login</h1>
                                <!--end::Title-->
                            </div>
                            <!--begin::Heading-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label fs-6 fw-bolder text-dark">Username</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-lg" type="text" name="identifier"
                                    value="{{ old('identifier') }}" required autofocus />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <div class="d-flex flex-stack mb-2">
                                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                                </div>
                                <div class="input-group">
                                    <input class="form-control form-control-lg" type="password" id="password"
                                        name="password" autocomplete="off" required />
                                    <span class="input-group-text bg-transparent" id="togglePassword"
                                        style="cursor: pointer;">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="text-center">
                                <!--begin::Submit button-->
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    Login
                                </button>
                                <!--end::Submit button-->
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Content-->
            </div>
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Main-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
    <!--end::Page Custom Javascript-->
    <!--end::Javascript-->

    <script>
        const form = document.getElementById('login_form');
        form.addEventListener('submit', function(event) {
            Swal.fire({
                icon: 'info',
                title: 'Mohon tunggu...',
                text: 'Permintaan anda sedang diproses',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    </script>

    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        </script>
    @endif
    @if ($message = Session::get('failed'))
        <script>
            Swal.fire({
                text: "{{ $message }}",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-danger"
                }
            });
        </script>
    @endif

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', function() {
            // toggle type
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // toggle icon
            this.innerHTML = type === 'password' ?
                '<i class="fas fa-eye"></i>' :
                '<i class="fas fa-eye-slash"></i>';
        });
    </script>

</body>
<!--end::Body-->

</html>
