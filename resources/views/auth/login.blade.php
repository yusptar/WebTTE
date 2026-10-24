@extends('layouts.app')
@section('content')
<main class="auth">
    <div class="login-card">
        <div class="card-head">
            <img class="shield" src="{{ asset('img/logo-rs.png') }}" alt="Logo Rumah Sakit Tk. II dr. Soepraoen">
            <h4>Website</h4>
            <h1>Tanda Tangan Elektronik (TTE)</h1>
            <p>Rumah Sakit Tk. II dr. Soepraoen</p>
        </div>

        @if (session('error'))
        <div class="alert-lock" id="error-message" role="alert">
            <i class="fas fa-lock" style="margin-top:.15rem;"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <div class="form-group">
                <label for="username">NIP / Username</label>
                <div class="input-wrap">
                    <i class="fas fa-id-badge"></i>
                    <input id="username" type="text"
                        class="form-control @error('username') is-invalid @enderror"
                        name="username" value="{{ old('username') }}" required autofocus autocomplete="username"
                        placeholder="Contoh: 19800101">
                </div>
                @error('username')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-field">Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input id="password-field" type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="Password">
                    <button type="button" class="toggle-pass" id="toggle-pass" aria-label="Tampilkan password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-login" id="login-button">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>

        <div class="card-foot">
            <img src="{{ asset('img/logo-bsre.png') }}" alt="Balai Sertifikasi Elektronik">
            <span>Sertifikat elektronik oleh Balai Sertifikasi Elektronik, BSSN</span>
        </div>
    </div>

    <p class="auth-copy">&copy; {{ date('Y') }} IT Rumah Sakit Tk. II dr. Soepraoen</p>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tampilkan / sembunyikan password
        var passField = document.getElementById('password-field');
        var toggle = document.getElementById('toggle-pass');
        toggle.addEventListener('click', function () {
            var show = passField.type === 'password';
            passField.type = show ? 'text' : 'password';
            toggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            toggle.innerHTML = show ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        // Saat terkena batas percobaan login, form dikunci (perilaku lama).
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            var loginForm = document.getElementById('login-form');
            document.getElementById('login-button').disabled = true;
            toggle.disabled = true;
            loginForm.querySelectorAll('input').forEach(function (input) {
                input.disabled = true;
            });
        }

        // Cegah klik ganda saat proses masuk
        document.getElementById('login-form').addEventListener('submit', function () {
            var btn = document.getElementById('login-button');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
        });
    });
</script>
@endsection
