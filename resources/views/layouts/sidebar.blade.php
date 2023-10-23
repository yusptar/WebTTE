<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <div class="text-center">
            <img src="{{ asset('auth/images/logo-nagara.png') }}" alt="" width="60%" height="60%">
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li class="nav-header">Pra Integrasi TTE</li>
                <li class="nav-item {{ (request()->routeIs('tte')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->routeIs('tte')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Form TTE
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('tte') }}"
                                class="nav-link {{ (request()->routeIs('tte')) ? 'active' : '' }}">
                                <p>Pembubuhan TTE PDF</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-header">LOG OUT</li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" class="nav-link">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li> -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
// Mendapatkan semua elemen nav-link
var navLinks = document.querySelectorAll('.nav-link');

// Menambahkan event listener untuk setiap nav-link
navLinks.forEach(function(link) {
    link.addEventListener('click', function() {
        // Menghapus kelas "active" dari semua nav-link
        navLinks.forEach(function(navLink) {
            navLink.classList.remove('active');
        });

        // Menandai nav-link yang sedang diklik sebagai "active"
        link.classList.add('active');
    });
});
</script>