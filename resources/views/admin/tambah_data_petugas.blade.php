@extends('layout/aplikasi')
@section('konten')
<div class="container-fluid">

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard Admin</h4>
                <div class="btn-group mt-2 d-flex justify-content-between">
                    <div>
                        <ol class="breadcrumb hide-phone p-0 m-0">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard/tampil_user') }}">Tampil Data Petugas</a></li>
                            <li class="breadcrumb-item active">Tambah Data Petugas</li>
                        </ol>
                    </div>
                    <div>
                        <button onclick="history.back()" class="btn btn-secondary">Back</button>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <div class="text-center m-b-15">
                        <h1>Tambah Data Petugas</h1>
                    </div>

                    <div class="p-3">
                        <form method="GET" action="/create_petugas">
                            @csrf

                            <!-- Name -->
                            <div class="form-group row">
                                <div class="col-12">
                                    <x-input-label for="name" :value="__('Name')" />
                                    <x-text-input id="name" class="block mt-1 w-full form-control" type="text"
                                        name="name" :value="old('name')" required autofocus autocomplete="name" />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="form-group row">
                                <div class="col-12">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full form-control" type="email"
                                        name="email" :value="old('email')" required autocomplete="username" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <h6 class="input-title mt-0">Sebagai Apa </h6>
                                    <select class="select2 form-control mb-3 custom-select"
                                        style="width: 100%; height:36px;" name="usertype">
                                        <option value="admin">Admin</option>
                                        <option value="p_masuk">Petugas Masuk</option>
                                        <option value="p_keluar">Petugas Keluar</option>
                                        <option value="p_ruangan">Petugas Ruangan</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="form-group row">
                                <div class="col-12">
                                    <x-input-label for="password" :value="__('Password')" />

                                    <x-text-input id="password" class="block mt-1 w-full form-control" type="password"
                                        name="password" required autocomplete="new-password" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="form-group row">
                                <div class="col-12">
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                    <x-text-input id="password_confirmation" class="block mt-1 w-full form-control"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" />

                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>



                            <div class="form-group text-center row m-t-20">
                                <div class="col-3"></div>
                                <div class="col-6">
                                    <button class="btn btn-danger btn-block waves-effect waves-light"
                                        type="submit">Tambah</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- end row -->
</div> <!-- end container -->

@endsection
