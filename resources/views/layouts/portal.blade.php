<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('/') }}">
    <link rel="shortcut icon" href="{{ asset('img/logo-no-background.svg') }}" type="image/x-icon">
    <title>@yield('title') &mdash; {{ config('app.name', 'Laravel') }}</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.css">

    @stack('style')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>

<body class="layout-3">
    <div id="app">
        <div class="main-wrapper container">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <a href="#" class="navbar-brand sidebar-gone-hide"><img src="{{ asset('img/logo-black.png') }}"
                        alt="logo" width="50" class="shadow-light rounded-circle"></a>
                <a href="#" class="nav-link sidebar-gone-show mt-4" data-toggle="sidebar"><i
                        class="fas fa-bars"></i></a>
                <ul class="navbar-nav ">
                    <li class="nav-item active"><a href="#" class="nav-link">Menu</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Check Status</a></li>
                </ul>
            </nav>

            <!-- Main Content -->
            @yield('main')

            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2024 <div class="bullet"></div>{{ config('app.name', 'Laravel') }}
                </div>
                <div class="footer-right">
                </div>
            </footer>

        </div>
    </div>

    @if (session()->has('success'))
        <!-- Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="toast-success" class="toast align-items-center text-white border-0 bg-success" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session()->get('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
        <!-- Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="toast-danger" class="toast align-items-center text-white border-0 bg-danger" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session()->get('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    {{-- error validation --}}
    @if ($errors->all())
        <!-- Toast -->
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="toast-danger-validation" class="toast align-items-center text-white border-0 bg-danger"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        Please Check Your Field
                    </div>
                    <button type="button" class="btn-close btn-close-white m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9.17.2/dist/sweetalert2.min.js"></script>

    @stack('scripts')

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            var base_url = $('meta[name=base_url]').attr('content');
            var token = $('meta[name=csrf-token]').attr('content');
            $.ajaxSetup({
                'headers': {
                    'X-CSRF-TOKEN': token
                }
            })
        });
    </script>
    @if (session()->has('success'))
        <script>
            let toastSuccess = new bootstrap.Toast($("#toast-success"))
            toastSuccess.show()
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            let toastDanger = new bootstrap.Toast($("#toast-danger"))
            toastDanger.show()
        </script>
    @endif

    @if ($errors->all())
        <script>
            let toastDanger = new bootstrap.Toast($("#toast-danger-validation"))
            toastDanger.show()
        </script>
    @endif
</body>

</html>
