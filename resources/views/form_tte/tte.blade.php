@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Pembubuhan TTE PDF</h1>
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
                <form id="form-pembubuhan-tte-pdf">
                    @csrf
                    <div class="card-body">
                        <input type="text" class="form-control" name="tanggal_upload" hidden>
                        <input type="text" class="form-control" name="jam_upload" id="jam_upload" hidden>
                        <input type="text" class="form-control" name="signed_status" hidden>
                        <input type="hidden" name="_token" value="Wm0qbXXO6oIkYEbFWl4as7auxZdxYa06" />
                        <div class="form-group">
                            <label>No RM (Rekam Medis) <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" name="no_rawat"
                                placeholder="Masukkan Nomer Rekam Medis" required>
                        </div>
                        <div class="form-group">
                            <label>Jenis RM (Rekam Medis) <span style="color:red;">*</span></label>
                            <select class="form-control" aria-label="Default select example">
                                <option disabled selected>-- Pilih Jenis RM --</option>
                                @foreach ($mstr_berkas as $mb)
                                <option value="{{ $mb->kode }}">{{ $mb->kode }} - {{ $mb->nama }}</option>
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
    </section>
    <!-- End Main content -->

    <!-- List Data -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="font-weight:600">Tabel RM</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover" id="table-rm">
                                <thead>
                                    <tr>
                                        <th>No Rawat</th>
                                        <th>Jenis RM</th>
                                        <th>Tgl Upload</th>
                                        <th>Tgl Signed</th>
                                        <th>Path</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manj_tte as $mt)
                                    <tr>
                                        <td>{{ $mt->no_rawat}}</td>
                                        <td>{{ $mt->jenis_rm}}</td>
                                        <td>{{ $mt->tanggal_upload}}</td>
                                        <td>{{ $mt->tanggal_signed}}</td>
                                        <td><a href="https://rssoepraoen.com/webapps/berkasrawat/{{ $mt->path }}">{{ $mt->path }}
                                        </td>
                                        @if($mt->signed_status == 'BELUM')
                                        <td>
                                            <span class="badge rounded-pill bg-secondary">Belum TTE</span>
                                        </td>
                                        @elseif($mt->signed_status == 'SUDAH')
                                        <td>
                                            <span class="badge rounded-pill bg-success">Sudah TTE</span>
                                        </td>
                                        @endif
                                        <td>
                                            @if($mt->signed_status == 'BELUM')
                                            <form id="">
                                                @csrf
                                                <input type="text" class="form-control" name="tanggal_signed"
                                                    value="{{ $mt->tanggal_signed }}" hidden>
                                                <input type="text" class="form-control" value="{{ $mt->jam_signed }}"
                                                    name="jam_signed" id="jam_upload" value="{{ $mt->signed_status }}"
                                                    hidden>
                                                <input type="hidden" name="signed_status"
                                                    value="{{ $mt->signed_status }}">
                                                <div>
                                                    <button class="btn btn-primary btn-sm cetak-btn"
                                                        type="button">Kirim</button>
                                                </div>
                                            </form>
                                            @else
                                            No Action
                                            @endif
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
    $("#table-rm").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
    })
});

$('#btn-submit').click(function() {
    if ($('#form-dokter-spesialis')[0].checkValidity()) {
        var formData = new FormData();
        formData.append('tgl_transaksi', $('input[name=tgl_transaksi]').val());
        formData.append('alos', $('input[name=alos]').val());
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            url: "{{ route('store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data.data);
                Swal.fire({
                    title: "Berhasil!",
                    text: "Data Berhasil ditambahkan",
                    icon: "success",
                    buttons: false,
                    timer: 2000,
                }).then(function() {
                    window.location.href = "{{ route('tte') }}"
                });
            },
            error: function(data) {
                console.log(data);
                Swal.fire({
                    title: "Gagal!",
                    text: "Data gagal ditambahkan",
                    icon: "error",
                    buttons: false,
                    timer: 2000,
                })
            }
        });
    } else {
        $('#form-pembubuhan-tte-pdf')[0].reportValidity();
    }
});
</script>
@endsection