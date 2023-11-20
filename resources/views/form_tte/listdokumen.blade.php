@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Daftar Dokumen Rekam Medis</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

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
                                        <th>Tgl Upload</th>
                                        <th>Tgl Signed</th>
                                        <th>Nama File</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manj_tte as $mt)
                                    <tr class="data-row">
                                        <td class="no_rawat">{{ $mt->no_rawat}}</td>
                                        <td class="tanggal_upload">{{ $mt->tanggal_upload}}</td>
                                        <td class="tanggal_signed">{{ $mt->tanggal_signed}}</td>
                                        <td class="nama_file">{{ $mt->path }}</td>
                                        <td class="signed_status">
                                            <span class="badge rounded-pill {{ $mt->signed_status == 'BELUM' ? "bg-secondary" : "bg-success" }}" >{{ $mt->signed_status}}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <button class="btn btn-primary btn-sm cetak-btn" id="download" type="button">Download</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    });

    $(document).on('click', "#download", function() {
    
        $(this).addClass(
            'download-trigger-clicked'
        ); 

        var el = $(".download-trigger-clicked"); // See how its usefull right here? 
        var row = el.closest(".data-row");

        // get the data
        var namaFile = row.children(".nama_file").text();
        $.ajax({
            url: "{{ route('downloadRM') }}",
            type: "POST",
            data: {
                _token : "{{ csrf_token() }}",
                namaFile : namaFile
            },
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = namaFile;
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
                Swal.fire({
                    title: "Berhasil!",
                    text: "Dokumen berhasil didownload..!",
                    icon: "success",
                    buttons: false,
                    timer: 3000,
                }).then(function() {
                    // window.location.href = "{{ route('list-dokumen-rm') }}"
                });
            },
            error: function(data) {
                Swal.fire({
                    title: "Gagal!",
                    text: "Oops, terjadi kesalahan. Silahkan Hubungi Administrator..!",
                    icon: "error",
                    buttons: false,
                    timer: 3000,
                }).then(function() {
                    // window.location.href = "{{ route('list-dokumen-rm') }}"
                });
            }
        });
        
        $('.download-trigger-clicked').removeClass('download-trigger-clicked')
    });
});
</script>
@endsection