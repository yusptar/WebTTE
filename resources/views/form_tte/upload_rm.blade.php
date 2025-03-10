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
                            <label>Pilih Pegawai <span style="color:red;">*</span></label>
                            <input list="pegawai" class="form-control col-sm-4" required/>
                            <datalist id="pegawai">
                                @foreach($pegawai as $p)
                                <option value="{{ $p->nik }}">{{ $p->nama }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label>No Rawat <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="no_rawat"
                                placeholder="Masukkan Nomer Rawat" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis RM<span style="color:red;">*</span></label>
                            <select class="form-control col-sm-4" name="jenis_rm" required>>
                                <option selected disabled>--- Pilih Jenis RM ---</option>
                                @foreach($m_berkas as $mb)
                                <option value="{{ $mb->kode }}">{{ $mb->nama }}</option>
                                @endforeach
                            </select>
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
        formData.append('no_rawat', $('input[name=no_rawat]').val());
        formData.append('nip', $('input[list=pegawai]').val());
        formData.append('jenis_rm', $('select[name=jenis_rm]').val());
        formData.append('path', $('input[name=path]')[0].files[0]);
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            url: "{{ route('store-rm') }}",
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
                    window.location.href = "{{ route('upload-rm') }}"
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