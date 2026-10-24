<!doctype html>
<html lang="id">
<head>
    <title>Masuk | TTE Rumah Sakit Tk.II dr.Soepraoen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#14284b">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bsre.png') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fonts/public-sans/public-sans.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="preload" as="image" href="{{ asset('img/login-bg.jpg') }}">
    <style>
        :root {
            --ink: #14284b;
            --ink-soft: #1d3866;
            --seal: #1f7a5c;
            --seal-dark: #17614a;
            --danger: #b4232f;
            --paper: #f4f6f9;
            --line: #cfd6e0;
            --text: #1e2633;
            --muted: #5f6b7a;
        }

        * { box-sizing: border-box; }

        html, body { height: 100%; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Public Sans", "Segoe UI", system-ui, Arial, sans-serif;
            color: var(--text);
            background: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        /* Latar penuh: foto ikon medis + lapisan biru tinta agar kartu tetap terbaca */
        .auth {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            padding: 2.5rem 1.25rem;
            isolation: isolate;
        }

        .auth::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -2;
            background: url("{{ asset('img/login-bg.jpg') }}") center / cover no-repeat;
            transform: scale(1.04);
            animation: bg-settle 1.2s ease-out forwards;
        }

        .auth::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            background:
                radial-gradient(ellipse 60% 55% at 50% 45%, rgba(20, 40, 75, .25), rgba(20, 40, 75, .78)),
                linear-gradient(180deg, rgba(20, 40, 75, .35), rgba(20, 40, 75, .6));
        }

        @keyframes bg-settle {
            to { transform: scale(1); }
        }

        /* Kartu login di tengah */
        .login-card {
            width: 100%;
            max-width: 410px;
            background: rgba(255, 255, 255, .94);
            -webkit-backdrop-filter: blur(14px) saturate(140%);
            backdrop-filter: blur(14px) saturate(140%);
            border: 1px solid rgba(255, 255, 255, .6);
            border-radius: 20px;
            padding: 2.25rem 2rem 1.75rem;
            box-shadow:
                0 1px 0 rgba(255, 255, 255, .7) inset,
                0 30px 70px -20px rgba(8, 18, 38, .65);
            animation: card-in .5s cubic-bezier(.2, .8, .2, 1) both;
        }

        @keyframes card-in {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: none; }
        }

        .card-head {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .card-head .shield {
            width: 76px;
            height: 76px;
            object-fit: contain;
            display: block;
            margin: 0 auto 1rem;
        }

        .card-head h1 {
            font-size: 1.45rem;
            line-height: 1.2;
            font-weight: 700;
            letter-spacing: -.01em;
            color: var(--ink);
            margin: 0 0 .35rem;
        }

        .card-head p {
            margin: 0;
            color: var(--muted);
            font-size: .9rem;
            line-height: 1.5;
        }

        .form-group { margin-bottom: 1.1rem; }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: .85rem;
            margin-bottom: .4rem;
        }

        .input-wrap { position: relative; }

        .input-wrap > i {
            position: absolute;
            left: .95rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: .9rem;
            pointer-events: none;
            transition: color .15s ease;
        }

        .input-wrap:focus-within > i { color: var(--seal); }

        .form-control {
            width: 100%;
            height: 48px;
            border: 1px solid var(--line);
            border-radius: 11px;
            padding: 0 2.8rem 0 2.6rem;
            font: inherit;
            font-size: .9375rem;
            color: var(--text);
            background: #fff;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .form-control::placeholder { color: #98a2b0; }

        .form-control:focus {
            outline: none;
            border-color: var(--seal);
            box-shadow: 0 0 0 4px rgba(31, 122, 92, .16);
        }

        .form-control:disabled { background: #eef1f5; color: var(--muted); }

        .form-control.is-invalid { border-color: var(--danger); }

        .invalid-feedback {
            display: block;
            color: var(--danger);
            font-size: .8125rem;
            margin-top: .35rem;
        }

        .toggle-pass {
            position: absolute;
            right: .35rem;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--muted);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
        }

        .toggle-pass:hover { background: var(--paper); color: var(--ink); }
        .toggle-pass:focus-visible { outline: 2px solid var(--seal); outline-offset: 1px; }

        .btn-login {
            width: 100%;
            height: 50px;
            margin-top: .6rem;
            border: 0;
            border-radius: 11px;
            background: var(--seal);
            color: #fff;
            font: inherit;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            box-shadow: 0 10px 22px -10px rgba(31, 122, 92, .8);
            transition: background-color .15s ease, transform .1s ease, box-shadow .15s ease;
        }

        .btn-login:hover { background: var(--seal-dark); }
        .btn-login:active { transform: translateY(1px); }
        .btn-login:focus-visible { outline: 3px solid rgba(31, 122, 92, .35); outline-offset: 2px; }
        .btn-login:disabled { background: #8aa79c; box-shadow: none; cursor: not-allowed; }

        .alert-lock {
            display: flex;
            gap: .7rem;
            align-items: flex-start;
            background: #fdf1f2;
            border: 1px solid #f2c9cd;
            color: #7d1720;
            border-radius: 11px;
            padding: .85rem 1rem;
            font-size: .875rem;
            line-height: 1.45;
            margin-bottom: 1.25rem;
        }

        .card-foot {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            margin-top: 1.6rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e4e8ee;
            color: var(--muted);
            font-size: .78rem;
            text-align: left;
        }

        .card-foot img { height: 26px; width: auto; flex: none; }

        .auth-copy {
            color: rgba(255, 255, 255, .7);
            font-size: .8rem;
            margin: 0;
            text-align: center;
            animation: card-in .5s .15s cubic-bezier(.2, .8, .2, 1) both;
        }

        @media (max-width: 480px) {
            .auth { padding: 1.5rem 1rem; }
            .auth::before { background-image: url("{{ asset('img/login-bg-sm.jpg') }}"); }
            .login-card { padding: 1.75rem 1.35rem 1.4rem; border-radius: 16px; }
            .card-head .shield { width: 62px; height: 62px; }
            .card-head h1 { font-size: 1.3rem; }
        }

        /* Peramban tanpa backdrop-filter: kartu dibuat putih penuh */
        @supports not ((backdrop-filter: blur(1px)) or (-webkit-backdrop-filter: blur(1px))) {
            .login-card { background: #fff; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation: none !important; transition: none !important; }
            .auth::before { transform: none; }
        }
    </style>
</head>

<body>
    @yield('content')
    <script src="{{ asset('auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @include('sweetalert::alert')
</body>

</html>
