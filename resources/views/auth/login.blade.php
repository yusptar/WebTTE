@extends('layouts.app')
@section('content')
<!-- <img src="{{ asset('img/logo-bsre.png') }}" class="" alt="" height="9%" width="12%"> -->
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center mb-5">
                <img src="{{ asset('img/logorst-panjang.png') }}" class="mr-4" alt="" height="69%" width="70%">
                <h3 class="mb-4 text-center" style="font-weight:bold;">Tanda Tangan Elektronik (TTE)</h3>
                <p class="mb-4 text-center" style="font-weight:bold;color:black;">Rumah Sakit Tk.II dr.Soepraoen</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-wrap p-0">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input id="username" type="text"
                                class="form-control @error('username') is-invalid @enderror" name="username" required
                                autofocus placeholder="Username">
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input id="password-field" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                placeholder="Password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
                        </div>
                        <div class="form-group d-md-flex">
                            <div class="w-50">
                                <label class="checkbox-wrap checkbox-primary">Remember Me
                                    <input type="checkbox" checked>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="w-50 text-md-right">
                                <a href="#" style="color: #fff">Forgot Password</a>
                            </div>
                            <!-- <div class="w-50 text-md-right">
                                <a href="{{ route('register') }}" style="color: #fff">Register</a>
                            </div> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection