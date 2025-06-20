<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DPU BINA MARGA</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assets/images/logo_dpu_jatim.png') }}">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

</head>

<body>

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Navigation Bar-->
    <header id="topnav">
        <div class="topbar-main" style="background-color: #120456;">
            <div class="container-fluid">

                <!-- Logo container -->
                <div class="logo">
                    <a href="/admin/dashboard">
                        <img src="{{ asset('assets/images/logo_dpu_jatim.png') }}" alt="Logo DPU Bina Marga" style="height: 40px;"> 
                        <span class="hide-phone" style="color: #ffffff; font-weight: bold;">DINAS PEKERJAAN UMUM BINA MARGA JAWA TIMUR</span>
                    </a>
                </div>
                <!-- End Logo container -->


                <div class="menu-extras topbar-custom">

                    <ul class="list-unstyled float-right mb-0">

                        <!-- User-->
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="{{ asset('assets/images/users/logo_admin.png') }}" alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown  border-0">
                                <div class="dropdown-item noti-title" style="text-align: center">
                                    <h5>WELCOME</h5>
                                </div>
                                <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5 text-muted"></i>
                                    {{ Auth::check() ? Auth::user()->name : 'Guest' }}</a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
        
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();" class="dropdown-item; mdi mdi-logout m-r-5">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- end menu-extras -->

                <div class="clearfix"></div>

            </div> <!-- end container -->
        </div>
        <!-- end topbar-main -->
    </header>
    <!-- End Navigation Bar-->


    <div class="wrapper">
        @yield('konten')
    </div>
    <!-- end wrapper -->


    {{-- <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    © 2024 | Lazward
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer --> --}}


    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.scrollTo.min.js') }}"></script>

    <!-- KNOB JS -->
    <script src="{{ asset('assets/plugins/jquery-knob/excanvas.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
