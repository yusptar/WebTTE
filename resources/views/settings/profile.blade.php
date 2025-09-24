@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col text-center">
                    <h2 class="font-weight-bold">{{ Auth::user()->pegawai->nama }}'s Profile</h2>
                    <p class="text-muted mb-0">Kelola informasi akun dan ubah password anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg rounded-lg">
                        <div class="card-body text-center">
                            <div class="mb-3"> 
                                @php
                                    $path = Auth::user()->pegawai->photo ?? null;

                                    // cek apakah path kosong atau hanya "pages/pegawai/photo/"
                                    if (!$path || $path === 'pages/pegawai/photo/') {
                                        $url_photo = asset('img/bsre.png');
                                    } else {
                                        $url_photo = env('URL_IMAGE') . $path;
                                    }
                                @endphp
                                <img src="{{ $url_photo }}"  class="rounded-circle border" width="300" height="300" alt="User Avatar">
                            </div>
                            
                            <h4 class="font-weight-bold mb-1">{{ Auth::user()->pegawai->nama }}</h4>
                            <p class="text-muted mb-3">{{ Auth::user()->username }}</p>

                            <ul class="list-group list-group-flush text-left mb-3">
                                <li class="list-group-item"><strong>Nama : </strong> {{ Auth::user()->pegawai->nama }}</li>
                                <!-- <li class="list-group-item"><strong>Jabatan : </strong> {{ Auth::user()->pegawai->jbtn }}</li> -->
                                <li class="list-group-item"><strong>Username : </strong> {{ Auth::user()->username ?? '-' }}</li>
                                <!-- <li class="list-group-item"><strong>NIK : </strong> {{ Auth::user()->pegawai->no_ktp ?? '-' }}</li> -->
                            </ul>
                            <button class="btn btn-info btn-m mr-2" id="edit_user_btn" data-id="{{ Auth::id() }}">
                                <i class="fa fa-key"></i> Ubah Password
                            </button>
                            <a href="#" class="btn btn-danger btn-m" id="btn-logout">
                                <i class='fas fa-sign-out-alt'></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="loading-spinner" style="position:fixed; top:50%; left:50%; transform: translate(-50%, -50%); display:none; z-index:1055;">
                <img src="{{ asset('img/spinner.gif') }}" alt="Loading..." width="120" height="120">
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="ubahPasswordModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="update_user" action="{{ route('user-update') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="ubahPasswordModal">Ubah Password</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id">

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" class="form-control" name="password" required>
                        <span class="text-danger error-text password_error"></span>
                    </div>

                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                        <span class="text-danger error-text password_confirmation_error"></span>
                    </div>

                    <small class="text-muted">
                        <i class="fa fa-info-circle"></i> Minimal 8 karakter, kombinasi huruf besar, huruf kecil, angka, dan simbol.
                    </small>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
$(document).on('click', '#edit_user_btn', function(e) {
    e.preventDefault();
    const id = $(this).data('id');
    const url = "{{ route('user.detail') }}";
    $('.user_modal form')[0].reset();
    $('.user_modal').modal('show');

    $.ajax({
        type: 'POST',
        url: url,
        data: { id: id },
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        dataType: 'json',
        success: function(data) {
            $('.user_modal').find('input[name="id"]').val(data.details.id);
        },
        error: function(xhr, status, error) {
            console.log('Error:', error);
        }
    });
});

$('#update_user').on('submit', function(e) {
    e.preventDefault();
    $('#loading-spinner').show();
    var form = this;

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: new FormData(form),
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function() {
            $(form).find('span.error-text').text('');
        },
        success: function(data) {
            $('#loading-spinner').hide();
            if (data.code === 0) {
                $.each(data.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                });
            } else {
                $(form)[0].reset();
                $('.user_modal').modal('hide');
                Swal.fire('Berhasil!', 'Password telah diperbarui!', 'success');
            }
        },
        error: function() {
            $('#loading-spinner').hide();
            Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
        }
    });
});

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
</script>
@endsection
