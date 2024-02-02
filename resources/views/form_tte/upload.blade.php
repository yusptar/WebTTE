@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Upload Dokumen Surat</h1>
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
                            <label>Pilih Pegawai <span style="color:red;">*</span></label>
                            <input list="pegawai" class="form-control col-sm-4" required/>
                            <datalist id="pegawai">
                                @foreach($pegawai as $p)
                                <option value="{{ $p->nik }}">{{ $p->nama }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>No Surat <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="no_rawat"
                                placeholder="Masukkan Nomer Surat" required>
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
        <div id="loading-spinner" style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); display:none;">
            <img src="{{ asset('img/spinner.gif') }}" alt="" width="120" height="120">
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
        $('#loading-spinner').show();
        var formData = new FormData();
        formData.append('nip', $('input[list=pegawai]').val());
        formData.append('no_rawat', $('input[name=no_rawat]').val());
        formData.append('path', $('input[name=path]')[0].files[0]);
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            url: "{{ route('store-surat') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#loading-spinner').hide();
                Swal.fire({
                    title: "Berhasil!",
                    text: "Data berhasil ditambahkan!",
                    icon: "success",
                    buttons: false,
                    timer: 3000,
                }).then(function() {
                    window.location.href = "{{ route('upload-surat') }}"
                });
            },
            error: function(data) {
                $('#loading-spinner').hide();
                console.log(data);
                Swal.fire({
                    title: "Gagal!",
                    text: data.responseJSON.error,
                    icon: "error",
                    buttons: false,
                    timer: 3000,
                })
                // }).then(function() {
                //     // window.location.href = "{{ route('upload-surat') }}"
                // });
            }
        });
    } else {
        $('#form-upload-rm')[0].reportValidity();
    }
});
</script>
@endsection