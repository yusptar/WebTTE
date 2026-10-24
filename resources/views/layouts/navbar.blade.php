<!-- Navbar -->
@php
    $pegawaiLogin = Auth::user()->pegawai;
    $path = $pegawaiLogin->photo ?? null;
    if (!$path || $path === 'pages/pegawai/photo/') {
        $photoUrl = asset('img/bsre.png');
    } else {
        $photoUrl = config('app.url_image') . $path;
    }
    $roleLabel = [
        'admin' => 'Administrator',
        'ppa' => 'PPA',
        'perawat' => 'Perawat',
        'petugas' => 'Petugas BPJS',
    ][Auth::user()->role] ?? ucfirst((string) Auth::user()->role);
@endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Buka/tutup menu">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a href="#" class="tte-user-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ $photoUrl }}" alt="Foto {{ $pegawaiLogin->nama ?? '' }}">
                <span class="tte-user-meta">
                    <span>{{ $pegawaiLogin->nama ?? Auth::user()->username }}</span>
                    <small>{{ $roleLabel }}</small>
                </span>
                <i class="fas fa-chevron-down" style="font-size:.7rem; color:#5f6b7a;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('profil') }}" class="dropdown-item">
                    <i class="fas fa-user-circle"></i> Profil
                </a>
                <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
