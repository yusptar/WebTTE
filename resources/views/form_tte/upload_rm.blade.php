@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Upload Dokumen RM</h1>
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
                <form id="form-upload-rm" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <div class="form-group">
                            <label>No Rawat <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="no_rawat"
                                placeholder="Masukkan Nomer Rawat">
                        </div>
                        <div class="form-group">
                            <label>File PDF <span style="color:red;">*</span></label>
                            <br>
                            <input name="path" type="file" accept="application/pdf" required>
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
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $("#table-rm").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
    })
});

$('#btn-submit').click(function() {
    if ($('#form-upload-rm')[0].checkValidity()) {
        var formData = new FormData();
        formData.append('no_rawat', $('input[name=no_rawat]').val());
        formData.append('path', $('input[name=path]')[0].files[0]);
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            url: "{{ route('store-rm') }}",
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
                })
            },
            error: function(data) {
                console.log(data);
                Swal.fire({
                    title: "Gagal!",
                    text: "Data gagal ditambahkan",
                    icon: "error",
                    buttons: false,
                    timer: 3000,
                })
                // }).then(function() {
                //     // window.location.href = "{{ route('upload-rm') }}"
                // });
            }
        });
    } else {
        $('#form-upload-rm')[0].reportValidity();
    }
});
</script>
@endsection