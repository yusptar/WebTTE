<!doctype html>
<html lang="en">
<head>
    <title>TTE | Rumah Sakit Tk.II dr.Soepraoen</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bsre.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('auth/css/style.css') }}">
</head>
 <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: url('{{ asset('img/bg-gear.png') }}') no-repeat center center fixed;
      background-size: cover;
      font-family: "Lato", sans-serif;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.1); /* lebih transparan */
        backdrop-filter: blur(6px);
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        width: 100%;
        max-width: 400px;
    }

    .login-card h3 {
      font-weight: bold;
      color: #2c3e50;
      margin-bottom: 1rem;
    }

    .form-control {
      border-radius: 50px;
      padding-left: 2.5rem;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: black;
    }

    .form-group {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .btn-primary {
      border-radius: 50px;
      font-weight: bold;
    }

    .forgot-link {
      font-size: 0.9rem;
      color: #007bff;
      text-decoration: none;
    }

    .forgot-link:hover {
      text-decoration: underline;
    }
  </style>

<body class="img js-fullheight" style="background-image: url(auth/images/background.jpg); background-size: 100% auto;">
    @yield('content')
    <script src="{{ asset('auth/js/jquery.min.js') }}"></script>
    <script src="{{ asset('auth/js/popper.js') }}"></script>
    <script src="{{ asset('auth/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('auth/js/main.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @include('sweetalert::alert')
</body>

</html>