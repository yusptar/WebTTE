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
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title" style="font-weight:600">Input Form</h3>
                </div>
                <form id="form-users">
                    @csrf
                    <div class="card-body">
                        {{-- <input type="hidden" name="_token" value="Wm0qbXXO6oIkYEbFWl4as7auxZdxYa06" /> --}}
                        <div class="form-group">
                            <label>NIP/NRP</label>
                            <input id="username" type="text"
                                class="form-control @error('username') is-invalid @enderror" name="username"
                                value="{{ old('username') }}" required autocomplete="username" autofocus>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                        </div>
                    </div>
                    <div class=" card-footer">
                        <button type="button" id="btn-submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- End Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight:600">Tabel Users</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="table-users">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user as $usr)
                                    <tr class="data-row">
                                        <td class="username">{{ $usr->username}}</td>
                                        <td>
                                            Tes
                                        </td>
                                    </tr>
                                    @endforeach
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
    </section>
</div>


@endsection

@section('script')
<script>
$(document).ready(function() {
    $("#table-users").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
    })
});

$('#btn-submit').click(function() {
    if ($('#form-users')[0].checkValidity()) {
        var formData = new FormData();
        formData.append('username', $('input[name=username]').val());
        formData.append('password', $('input[name=password]').val());
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            url: "{{ route('users-store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                Swal.fire({
                    title: "Berhasil!",
                    text: "Data Berhasil ditambahkan",
                    icon: "success",
                    buttons: false,
                    timer: 3000,
                }).then(function() {
                    window.location.href = "{{ route('users') }}"
                });
            },
            error: function(data) {
                Swal.fire({
                    title: "Gagal!",
                    text: "Data gagal ditambahkan",
                    icon: "error",
                    buttons: false,
                    timer: 3000,
                }).then(function() {
                    window.location.href = "{{ route('users') }}"
                });
            }
        });
    } else {
        $('#form-users')[0].reportValidity();
    }
});
</script>
@endsection