<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TTE | Rumah Sakit Tk.II dr.Soepraoen</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('img/bsre.png') }}">

    <!-- Font: Public Sans (lokal) -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/public-sans/public-sans.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Tema TTE (paling akhir agar menimpa AdminLTE) -->
    <link rel="stylesheet" href="{{ asset('css/tte-theme.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div id="tte-ajax-bar" aria-hidden="true"></div>
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img src="{{ asset('img/logo-panjang-rst.png') }}" alt="RST dr. Soepraoen">
        </div>
        <!-- include navbar -->
        @include('layouts.navbar')
        <!-- include sidebar -->
        @include('layouts.sidebar')
        <!-- Main Content -->
        @yield('content')
        <!-- include footer -->
        @include('layouts.footer')
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- daterangepicker (dipakai di halaman List Dokumen) -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        // Teks DataTables dalam Bahasa Indonesia untuk semua tabel.
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                processing: 'Memuat data...',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(disaring dari _MAX_ data)',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data',
                paginate: { first: 'Awal', last: 'Akhir', next: '›', previous: '‹' }
            }
        });

        // Bar tipis di atas layar selama permintaan AJAX berjalan,
        // agar pengguna tahu sistem sedang memproses.
        (function () {
            var bar = document.getElementById('tte-ajax-bar');
            $(document).ajaxStart(function () {
                bar.classList.remove('is-done');
                void bar.offsetWidth;
                bar.classList.add('is-loading');
            }).ajaxStop(function () {
                bar.classList.remove('is-loading');
                bar.classList.add('is-done');
            });
        })();
    </script>
    @yield('script')
    @include('sweetalert::alert')
</body>

</html>
