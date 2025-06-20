<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>SISTEM ARSIP</title>
    <meta content="Admin Dashboard" name="description" />
    <meta content="Mannatthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="{{ asset('assets/images/logo_dpu_jatim.png') }}">

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
</head>


<body>

    <!-- Begin page -->
    <div class="accountbg" style="background-image: url('{{ asset('assets/images/dpu_binaMarga.jpeg') }}');
    background-size: cover; background-position: center; background-repeat: no-repeat;">
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">

                    <div class="text-center m-b-15">
                        <h2>DPU BINA MARGA</h2>
                    </div>

                    <div class="p-3">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                        <div class="form-group row">
                            <div class="col-12">
                                <x-input-label for="username" :value="__('Username')" />
                                <x-text-input id="username" class="block mt-1 w-full form-control" type="text"
                                    name="username" :value="old('username')" required autofocus autocomplete="username" />
                                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <x-input-label for="password" :value="__('Password')" />
                    
                                <x-text-input id="password" class="form-control"
                                                type="password"
                                                name="password"
                                                required autocomplete="current-password" />
                    
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                        </div>


                        <div class="form-group text-center row m-t-20">
                            <div class="col-12">
                                <button class="btn btn-danger btn-block waves-effect waves-light" type="submit">Log
                                    In</button>
                            </div>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>

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