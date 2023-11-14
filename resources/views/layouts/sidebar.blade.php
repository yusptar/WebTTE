<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <div class="text-center">
            {{-- <img src="{{ asset('auth/images/logo-nagara.png') }}" alt="" width="60%" height="60%"> --}}
            <img src="{{ asset('img/logorst.png') }}" alt="" height="50%">
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

                <!-- ADMIN ACCESS -->
                @can('admin')
                <li class="nav-header">Pra Integrasi TTE</li>
                <li
                    class="nav-item {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Form TTE
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('upload-rm') }}"
                                class="nav-link {{ (request()->routeIs('upload-rm')) ? 'active' : '' }}">
                                <p>Upload Dokumen RM</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pembubuhan-tte') }}"
                                class="nav-link {{ (request()->routeIs('pembubuhan-tte')) ? 'active' : '' }}">
                                <p>Pembubuhan TTE PDF</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">Lainnya</li>
                <li class="nav-item {{ (request()->routeIs('users')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ (request()->routeIs('users'))  ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users') }}"
                                class="nav-link {{ (request()->routeIs('users')) ? 'active' : '' }}">
                                <p>Users</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                
                <!-- PETUGAS ACCESS -->
                @can('petugas')
                <li class="nav-header">Pra Integrasi TTE</li>
                <li
                    class="nav-item {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Form TTE
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('pembubuhan-tte') }}"
                                class="nav-link {{ (request()->routeIs('pembubuhan-tte')) ? 'active' : '' }}">
                                <p>Pembubuhan TTE PDF</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                
                <!-- PPA ACCESS -->
                @can('ppa')
                <li class="nav-header">Pra Integrasi TTE</li>
                <li
                    class="nav-item {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Form TTE
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('upload-rm') }}"
                                class="nav-link {{ (request()->routeIs('upload-rm')) ? 'active' : '' }}">
                                <p>Upload Dokumen RM</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pembubuhan-tte') }}"
                                class="nav-link {{ (request()->routeIs('pembubuhan-tte')) ? 'active' : '' }}">
                                <p>Pembubuhan TTE PDF</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <!-- PERAWAT ACCESS -->
                @can('perawat')
                <li class="nav-header">Pra Integrasi TTE</li>
                <li
                    class="nav-item {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>
                            Form TTE
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('upload-rm') }}"
                                class="nav-link {{ (request()->routeIs('upload-rm')) ? 'active' : '' }}">
                                <p>Upload Dokumen RM</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                <li class="nav-header">LOG OUT</li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();" class="nav-link">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Log Out</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

 <!-- The modal -->
 <div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="image">
                        <img src="{{ asset('img/avatar/avatar-1.png') }}" class="img-circle elevation-2" alt="User Image" height=50% width=50% style="margin-left:28%">
                        <br><br>
                        <h5 style="text-align:center; font-weight:bold; margin-left:20px">{{Auth::user()->pegawai->nama}}</h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="update_user" action="{{ route('user-update') }}" method="POST"
                        class="form-label-left input_mask">
                        @csrf
                        <input type="hidden" name="id">
                        <!-- <div class="col-md-12  form-group has-feedback">
                            <label for="username">NIP/NRP</label>
                            <input type="text" name="username" class="form-control has-feedback-left" placeholder="NIP/NRP"
                                disabled>
                        </div> -->
                        <div class="col-md-12  form-group has-feedback">
                            <label for="name">Password Baru</label>
                            <input id="password-confirm" type="password" class="form-control has-feedback-left"
                                name="password" placeholder="Masukkan password baru">
                        </div>
                        <div class="col-md-12  form-group has-feedback">
                            <label>Konfirmasi Password Baru</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Masukkan konfirmasi password baru">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@section('script')
<script>
$(document).on('click', '#edit_user_btn', function() {
    const id = $(this).data('id');
    const url = "{{ route('user.detail') }}";
    $('.user_modal').find('form')[0].reset();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            id: id,
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken,
        },
        dataType: 'json',
        success: function(data) {
            $('.user_modal').find('input[name="id"]').val(data.details.id);
            // $('.user_modal').find('input[name="username"]').val(data.details.username);
            $('.user_modal').find('input[name="password"]').val(data.details.password);
            $('.user_modal').find('input[name="password_confirmation"]').val(data.details.password);
            $('.user_modal').modal('show');
        },
        error: function(xhr, status, error) {
            console.log('Error: ' + error);
        }
    });
});

$('#update_user').on('submit', function(e) {
    e.preventDefault();
    var form = this;
    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: new FormData(form),
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function() {
            $(this).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                $.each(data.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                });
            } else {
                $(form)[0].reset();
                $('#users_table').DataTable().ajax.reload(null, false);
                $('.user_modal').modal('hide');
                Swal.fire(
                    'Updated!',
                    'Password telah diperbarui!',
                    'success'
                )
            }
        }
    });
});
</script>
@endsection