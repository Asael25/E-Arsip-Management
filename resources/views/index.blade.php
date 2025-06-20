{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Zoogler - Responsive Bootstrap 4 Admin Dashboard</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

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
        <div class="topbar-main">
            <div class="container-fluid">

                <!-- Logo container-->
                <div class="logo">
                    <a href="index.html" class="logo">
                        <i class="mdi mdi-bowling text-success mr-1"></i> <span class="hide-phone">DPU BINA MARGA</span>
                    </a>

                </div>
                <!-- End Logo container-->


                <div class="menu-extras topbar-custom">

                    <ul class="list-unstyled float-right mb-0">

                        <!-- User-->
                        <li class="dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <img src="assets/images/users/avatar-1.jpg" alt="user" class="rounded-circle">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right profile-dropdown  border-0">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5>Welcome</h5>
                                </div>
                                <a class="dropdown-item" href="#"><i
                                        class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ url('logout') }}"><i class="mdi mdi-logout m-r-5 text-muted"></i>
                                    Logout</a>
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
        <div class="container-fluid">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Dashboard Admin</h4>
                        <div class="btn-group mt-2">
                            <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title end breadcrumb -->

            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <a href="#">
                            <div class="card">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                                <div class="col-2 align-self-center">
                                                    <i class="fas fa-tasks mdi mdi-bike"></i>
                                                </div>
                                                <div class="col-10 text-right">
                                                    <h5 class="mt-0 mb-1">190</h5>
                                                    <p class="mb-0 font-12 text-muted">Kendaraan</p>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="{{ url('admin/dashboard/tampil_user') }}">
                            <div class="card">
                                <div class="card-body justify-content-center">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-2 align-self-center">
                                                <i class="far fa-gem mdi mdi-account-multiple-plus"></i>
                                            </div>
                                            <div class="col-10 text-right">
                                                <h5 class="mt-0 mb-1">62</h5>
                                                <p class="mb-0 font-12 text-muted">User</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-2 align-self-center">
                                                <i class="fas fa-users mdi mdi-border-all"></i>
                                            </div>
                                            <div class="col-10 text-right">
                                                <h5 class="mt-0 mb-1">14</h5>
                                                <p class="mb-0 font-12 text-muted">Ruang Parkir</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                        <div class="col-lg-6">
                            <a href="">
                            <div class="card ">
                                <div class="card-body">
                                    <div class="icon-contain">
                                        <div class="row">
                                            <div class="col-2 align-self-center">
                                                <i class="fas fa-database text-gradient-primary"></i>
                                            </div>
                                            <div class="col-10 text-right">
                                                <h5 class="mt-0 mb-1">$15562</h5>
                                                <p class="mb-0 font-12 text-muted">Laporan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- end container -->
    </div>
    <!-- end wrapper -->


    <!-- Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    © 2024 Parkiran by YPA.
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->


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

</html> --}}
