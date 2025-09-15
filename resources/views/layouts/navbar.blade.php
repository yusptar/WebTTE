<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li> -->
        <!-- <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li> -->
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
        <li class="nav-item">
            <a href="#" class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown" style="color:black; font-weight:bold; font-size:16px">
                @php
                    $photoPath = Auth::user()->pegawai->photo ?? null;
                    $photoUrl = $photoPath 
                        ? env('URL_IMAGE') . $photoPath 
                        : asset('img/bsre.png');
                @endphp
                <img src="{{ $photoUrl }}" 
                    alt="fotomu" 
                    class="rounded-circle mr-2" 
                    width="32" 
                    height="32"
                    style="object-fit: cover; border: 2px solid #ddd;">
                <span>{{ Auth::user()->pegawai->nama }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li class="nav-item">
                    <a href="{{ route('profil') }}"  class="nav-link"> 
                        <i class='fas fa-user-circle'></i>
                        <strong>&nbsp;Profil</strong>
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" class="nav-link">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                        
                        <i class='fas fa-sign-out-alt'></i>
                        <strong>&nbsp;Log Out</strong>
                    </a>
                    <!-- <a href="#" id="btn-logout" class="nav-link">
                        <i class='fas fa-sign-out-alt'></i> 
                        <strong>&nbsp;Log Out</strong>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form> -->
                </li>
            </ul>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
@section('script')
<!-- <script>
    $(document).on('click', '#btn-logout', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Yakin ingin logout?',
        text: "Anda akan keluar dari sesi ini.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#logout-form').submit();
        }
    });
});
</script> -->
@endsection