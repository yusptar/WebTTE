@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Users</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-3">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight:600">Tambah User</h3>
                        </div>
                        <form id="form-users">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <div class="form-group">
                                    <label>Pilih Role <span style="color:red;">*</span></label>
                                    <select name="role" id="role" class="form-control">
                                        <option disabled selected>-- Pilih Role --</option>
                                        <option value="perawat">Perawat</option>
                                        <option value="ppa">PPA</option>
                                        <option value="petugas">Petugas BPJS</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>NIP/NRP <span style="color:red;">*</span></label>
                                    <input id="username" type="text" class="form-control" name="username" required placeholder="Masukkan NIP/NRP">
                                </div>
                                <div class="form-group">
                                    <label>Password <span style="color:red;">*</span></label>
                                    <input id="password-confirm" type="password" class="form-control" name="password" required placeholder="Masukkan Password">
                                </div>
                                <div class="form-group">
                                    <label>Konfirmasi Password <span style="color:red;">*</span></label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password" placeholder="Masukkan Konfirmasi Password">
                                </div>
                            </div>
                            <div class=" card-footer">
                                <button type="button" id="btn-submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-9">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight:600">Tabel Users</h3>
                        </div>
                        <div class="card-body">
                            <table id="users_table" class="table table-striped jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th class="column-title">NIP/NRP</th>
                                        <th class="column-title">Role</th>
                                        <th class="column-title">Nama</th>
                                        <th class="column-title">NIK</th>
                                        <th class="column-title">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            <nav class="d-inline-block">
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loading-spinner" style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); display:none;">
            <img src="{{ asset('img/spinner.gif') }}" alt="" width="120" height="120">
        </div>
    </section>
    <!-- End Main content -->
</div>

<!-- The modal -->
<div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel">Ubah Password</h4>
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
                            name="password">
                    </div>
                    <div class="col-md-12  form-group has-feedback">
                        <label>Konfirmasi Password Baru</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                    </div>
                    <div id="loading-spinner" style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); display:none;">
                        <img src="{{ asset('img/spinner.gif') }}" alt="" width="120" height="120">
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
@endsection

@section('script')
<script>
$('#btn-submit').click(function() {
    if ($('#form-users')[0].checkValidity()) {
        $('#loading-spinner').show();
        var formData = new FormData();
        formData.append('role', $('select[name=role]').val());
        formData.append('username', $('input[name=username]').val());
        formData.append('password', $('input[name=password]').val());
        formData.append('password_confirmation', $('input[name=password_confirmation]').val());
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            url: "{{ route('user-store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#loading-spinner').hide();
                Swal.fire({
                    title: "Berhasil!",
                    text: data.success,
                    icon: "success",
                    buttons: false,
                    timer: 3000,
                }).then(function() {
                    window.location.href = "{{ route('users') }}"
                });
            },
            error: function(data) {
                console.log(data);
                $('#loading-spinner').hide();
                Swal.fire({
                    title: "Gagal!",
                    text: data.responseJSON.error,
                    icon: "error",
                    buttons: false,
                    timer: 3000,
                })
            }
        });
    } else {
        $('#form-users')[0].reportValidity();
    }
});

$('#users_table').DataTable({
    processing: true,
    info: true,
    ajax: "{{ route('user-list') }}",
    columns: [{
            data: "username",
            name: "username"
        },
        {
            data: "role",
            name: "role"
        },
        {
            data: "nama",
            name: "nama"
        },
        {
            data: "no_ktp",
            name: "no_ktp"
        },
        {
            data: "actions",
            name: "actions"
        },
    ]
});


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
            $(this).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                $('#loading-spinner').hide();
                $.each(data.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                });
            } else {
                $(form)[0].reset();
                $('#users_table').DataTable().ajax.reload(null, false);
                $('.user_modal').modal('hide');
                $('#loading-spinner').hide();
                Swal.fire(
                    'Updated!',
                    'Password telah diperbarui!',
                    'success'
                )
            }
        }
    });
});


$(document).on('click', '#delete_user_btn', function(e) {
    e.preventDefault();
    let id = $(this).data('id');
    Swal.fire({
        title: 'Apakah anda yakin untuk menghapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Tidak',
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "{{ route('user-delete') }}",
                method: "POST",
                data: {
                    id: id,
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.code == 0) {
                        Swal.fire(
                            'Oops!',
                            'Something went wrong!.',
                            'error'
                        )
                    } else {
                        $('#users_table').DataTable().ajax.reload(null,
                            false);
                        Swal.fire(
                            'Deleted!',
                            'Data berhasil dihapus!.',
                            'success'
                        )
                    }
                }
            });
        }
    })
});
</script>
@endsection