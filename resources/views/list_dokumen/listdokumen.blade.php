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
                            <div style="margin: 20px 0px;">
                                <strong>Date Filter:</strong>
                                <input type="text" name="daterange" value="" />
                                <button class="btn btn-success filter">Filter</button>
                            </div>
                            <table class="table table-bordered table-hover" id="table-rm">
                                <thead>
                                    <tr>
                                        <th>No Surat</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Tgl Registrasi</th>
                                        <th>Poliklinik</th>
                                        <th>Jenis Bayar</th>
                                        <th>Nama File</th>
                                        <th>Status TTE</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
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

    $(function () {
    
        $('input[name="daterange"]').daterangepicker({
            startDate: moment(),//.subtract(1, 'M'),
            endDate: moment()
        });
            
        var table = $('#table-rm').DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ (request()->routeIs('list-dokumen-ri')) ? route('list-dokumen-ri') : route('list-dokumen-rj') }}",
                data:function (d) {
                    d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
            },
            columns: [
                {data: 'no_rawat', name: 'no_rawat'},
                {data: 'no_rkm_medis', name: 'no_rkm_medis'},
                {data: 'nm_pasien', name: 'nm_pasien'},
                {data: 'tgl_registrasi', name: 'tgl_registrasi'},
                {data: 'nm_ruang', name: 'nm_ruang'},
                {data: 'png_jawab', name: 'png_jawab'},
                {data: 'path', name: 'path'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $(".filter").click(function(){
            table.draw();
        });
    });

    $(document).ready(function() {
        $(document).on('click', "#download", function() {
            $(this).addClass(
                'download-trigger-clicked'
            ); 
            var el = $(".download-trigger-clicked"); 
            var row = el.closest("tr");

            // get the data
            // var namaFile = row.children(".nama_file").text();
            var namaFile = row.find("td:eq(6)").text();
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
                    })
                },
                error: function(data) {
                    Swal.fire({
                        title: "Gagal!",
                        text: "Oops, terjadi kesalahan. Silahkan Hubungi Administrator..!",
                        icon: "error",
                        buttons: false,
                        timer: 3000,
                    })
                }
            });
            
            $('.download-trigger-clicked').removeClass('download-trigger-clicked')
        });
    });
</script>
@endsection