@extends('layouts.app')
@section('content')
<!-- <img src="{{ asset('img/logo-bsre.png') }}" class="" alt="" height="9%" width="12%"> -->
<section class="ftco-section">
    <div class="login-card">
        <img src="{{ asset('img/logo-panjang-rst.png') }}" alt="" width="40%" height="40%">
        <img src="{{ asset('img/bsre.png') }}" alt="" width="7%" height="7%">
        <div class="text-center mb-3 mt-3">
            <img src="{{ asset('img/logo-rs.png') }}" alt="" width="60%" height="50%">
            <h6 class="fw-bold text-dark" style="font-weight:bold; font-size:22px; margin-top: 10px;">Tanda Tangan Elektronik - TTE</h6>
            <h7 class="fw-bold text-dark" style="font-weight:bold; font-size:14px">Rumah Sakit Tk.II dr. Soepraoen</h7>
        </div>

        @if (session('error'))
        <div class="alert alert-danger" id="error-message">
            {{ session('error') }}
        </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <div class="form-group">
            <i class="fa fa-user input-icon"></i>
            <input id="username" type="text"
            class="form-control @error('username') is-invalid @enderror"
            name="username" required autofocus placeholder="Username">
            @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <i class="fa fa-lock input-icon"></i>
            <input id="password-field" type="password"
            class="form-control @error('password') is-invalid @enderror"
            name="password" required placeholder="Password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Check if error message exists
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            // Disable form inputs and button
            var loginForm = document.getElementById('login-form');
            var loginButton = document.getElementById('login-button');
            var inputs = loginForm.querySelectorAll('input');

            loginButton.disabled = true;
            inputs.forEach(function (input) {
                input.disabled = true;
            });
        }
    });
</script>
@endsection
