{{--
    Sidebar TTE
    Hak akses per role sama seperti sebelumnya:
    - admin   : Dashboard, Upload RM, Pembubuhan TTE, List Dokumen RM, Settings
    - ppa     : Dashboard, Upload RM, Pembubuhan TTE, List Dokumen RM
                (List Dokumen RM disembunyikan untuk username 11111111)
    - petugas : Dokumen Pasien
    Menu Upload, Pembubuhan dan List Dokumen ditulis sekali lalu dipakai
    oleh admin dan ppa, supaya tidak ada dua salinan yang bisa berbeda.
--}}
@php
    $isAdmin = Gate::allows('admin');
    $isPpa = Gate::allows('ppa');
    $isPetugas = Gate::allows('petugas');

    $showDokumenRM = $isAdmin || ($isPpa && Auth::user()->username != '11111111');

    $uploadActive = request()->routeIs('upload-rm', 'upload-surat');
    $pembubuhanActive = request()->routeIs('view-pemb-rm', 'view-pemb-sur');
    $dokumenRMActive = request()->routeIs('list-dokumen-rj', 'list-dokumen-ri', 'list-dokumen-surat', 'view-dok-surat');
    $settingsActive = request()->routeIs('users', 'status-tte', 'view-berkas-tte');
    $dokumenPasienActive = request()->routeIs('list-dokumen-rm-rj', 'list-dokumen-rm-ri');
@endphp

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link" title="Ke Dashboard">
        <span class="brand-mark">
            <img src="{{ asset('img/logo-rs.png') }}" alt="">
        </span>
        <span class="brand-text">
            <strong>TTE</strong>
            <small>RS Tk. II dr. Soepraoen</small>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2" aria-label="Menu utama">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                @if ($isAdmin || $isPpa)
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">Rekam Medis</li>

                <li class="nav-item {{ $uploadActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $uploadActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-upload"></i>
                        <p>Upload RM <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('upload-rm') }}" class="nav-link {{ request()->routeIs('upload-rm') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-medical"></i>
                                <p>Upload Dokumen RM</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('upload-surat') }}" class="nav-link {{ request()->routeIs('upload-surat') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope-open-text"></i>
                                <p>Upload Dokumen Surat</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ $pembubuhanActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $pembubuhanActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-signature"></i>
                        <p>Pembubuhan TTE <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('view-pemb-rm') }}" class="nav-link {{ request()->routeIs('view-pemb-rm') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-file-signature"></i>
                                <p>Pembubuhan TTE RM</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view-pemb-sur') }}" class="nav-link {{ request()->routeIs('view-pemb-sur') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-pen-nib"></i>
                                <p>Pembubuhan TTE Surat</p>
                            </a>
                        </li>
                    </ul>
                </li>

                @if ($showDokumenRM)
                <li class="nav-item {{ $dokumenRMActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $dokumenRMActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p>List Dokumen RM <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-rj') }}" class="nav-link {{ request()->routeIs('list-dokumen-rj') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-walking"></i>
                                <p>Rawat Jalan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-ri') }}" class="nav-link {{ request()->routeIs('list-dokumen-ri') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-procedures"></i>
                                <p>Rawat Inap</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view-dok-surat') }}" class="nav-link {{ request()->routeIs('view-dok-surat', 'list-dokumen-surat') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Surat</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @endif

                @if ($isAdmin)
                <li class="nav-header">Lainnya</li>

                <li class="nav-item {{ $settingsActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $settingsActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users') }}" class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('status-tte') }}" class="nav-link {{ request()->routeIs('status-tte') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-check"></i>
                                <p>Cek Status TTE Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view-berkas-tte') }}" class="nav-link {{ request()->routeIs('view-berkas-tte') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-trash-alt"></i>
                                <p>Hapus Data Corrupted</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if ($isPetugas)
                <li class="nav-header">Rekam Medis</li>

                <li class="nav-item {{ $dokumenPasienActive ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $dokumenPasienActive ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder-open"></i>
                        <p>Dokumen Pasien <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-rm-rj') }}" class="nav-link {{ request()->routeIs('list-dokumen-rm-rj') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-walking"></i>
                                <p>Rawat Jalan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-rm-ri') }}" class="nav-link {{ request()->routeIs('list-dokumen-rm-ri') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-procedures"></i>
                                <p>Rawat Inap</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
</aside>
