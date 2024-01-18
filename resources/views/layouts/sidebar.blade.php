<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <div class="text-center">
            <img src="{{ asset('img/logorst.png') }}" alt="" width="60%" height="50%">
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
                <li class="nav-header">Pembubuhan TTE</li>
                <li
                    class="nav-item {{ (request()->routeIs('view-pemb-rm') || request()->routeIs('upload-rm') || request()->routeIs('view-pemb-sur')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('view-pemb-rm') || request()->routeIs('upload-rm') || request()->routeIs('view-pemb-sur')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-signature"></i>
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
                            <a href="{{ route('view-pemb-rm') }}"
                                class="nav-link {{ (request()->routeIs('view-pemb-rm')) ? 'active' : '' }}">
                                <p>Pembubuhan TTE RM</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view-pemb-sur') }}"
                                class="nav-link {{ (request()->routeIs('view-pemb-sur')) ? 'active' : '' }}">
                                <p>Pembubuhan TTE Surat</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ (request()->routeIs('list-dokumen-ri') || request()->routeIs('list-dokumen-rj') || request()->routeIs('list-dokumen-surat')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('list-dokumen-ri') || request()->routeIs('list-dokumen-rj') || request()->routeIs('list-dokumen-surat')) ? 'active' : '' }}"> 
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p> List Dokumen RM <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-rj') }}"
                                class="nav-link {{ (request()->routeIs('list-dokumen-rj')) ? 'active' : '' }}">
                                <p>Rawat Jalan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-ri') }}"
                                class="nav-link {{ (request()->routeIs('list-dokumen-ri')) ? 'active' : '' }}">
                                <p>Rawat Inap</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('view-dok-surat') }}"
                                class="nav-link {{ (request()->routeIs('view-dok-surat')) ? 'active' : '' }}">
                                <p>Surat</p>
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
                <li class="nav-item {{ (request()->routeIs('upload-rm')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('pembubuhan-tte')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-signature"></i>
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
                <li class="nav-item {{ (request()->routeIs('list-dokumen-ri') || request()->routeIs('list-dokumen-rj')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('list-dokumen-ri') || request()->routeIs('list-dokumen-rj')) ? 'active' : '' }}"> 
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p> List Dokumen RM <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-rj') }}"
                                class="nav-link {{ (request()->routeIs('list-dokumen-rj')) ? 'active' : '' }}">
                                <p>Rawat Jalan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-ri') }}"
                                class="nav-link {{ (request()->routeIs('list-dokumen-ri')) ? 'active' : '' }}">
                                <p>Rawat Inap</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                
                <!-- PPA ACCESS -->
                @can('ppa')
                <li class="nav-header">Pra Integrasi TTE</li>
                <li class="nav-item {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('pembubuhan-tte') || request()->routeIs('upload-rm')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-signature"></i>
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
                <li class="nav-item {{ (request()->routeIs('list-dokumen-ri') || request()->routeIs('list-dokumen-rj')) ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ (request()->routeIs('list-dokumen-ri') || request()->routeIs('list-dokumen-rj')) ? 'active' : '' }}"> 
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p> List Dokumen RM <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-rj') }}"
                                class="nav-link {{ (request()->routeIs('list-dokumen-rj')) ? 'active' : '' }}">
                                <p>Rawat Jalan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('list-dokumen-ri') }}"
                                class="nav-link {{ (request()->routeIs('list-dokumen-ri')) ? 'active' : '' }}">
                                <p>Rawat Inap</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

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