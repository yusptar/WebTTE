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
                                        <th>No Rawat</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Tgl Registrasi</th>
                                        <th>Poliklinik</th>
                                        <th>No SEP</th>
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


<!-- The modal -->
<div class="modal fade user_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalLabel"><span id="namaPasien"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nomor Rawat</label>
                        </div>
                        <div class="col-md-8">
                            <span id="nomorRawat"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nomor RM</label>
                        </div>
                        <div class="col-md-8">
                            <span id="nomorRM"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nomor SEP</label>
                        </div>
                        <div class="col-md-8">
                            <span id="nomorSEP"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label>Status Akhir</label>
                        </div>
                        <div class="col-md-8">
                            <span id="statusAkhir"></span>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Awal Medis IGD</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketAwalMedisIGD"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-awal-medis-igd" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Awal Keperawatan IGD</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketAwalKepIGD"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-awal-kep-igd" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-5">
                            <label>Awal Medis</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketAwalMedis"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-awal-medis" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <label>Awal Keperawatan</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketAwalKep"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-awal-kep" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-5">
                            <label>Resume Medis</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketResumeMedis"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-resume-medis" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-5">
                            <label>Laporan Operasi</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketLaporanOperasi"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-laporan-operasi" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-5">
                            <label>Hasil Lab</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketHasilLab"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-hasil-lab" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-5">
                            <label>Hasil Radiologi</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketHasilRad"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-hasil-rad" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-5">
                            <label>CPPT</label>
                        </div>
                        <div class="col-md-4">
                            <span id="ketCCPPT"></span>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn-cppt" class="btn btn-primary btn-sm download">Download</button>
                        </div>
                    </div> 
                </div>
                <!-- <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                </div> -->
            </div>
        </div>
    </div>
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
                url: "{{ (request()->routeIs('list-dokumen-rm-ri')) ? route('list-dokumen-rm-ri') : route('list-dokumen-rm-rj') }}",
                data:function (d) {
                    d.from_date = $('input[name="daterange"]').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    d.to_date = $('input[name="daterange"]').data('daterangepicker').endDate.format('YYYY-MM-DD');
                }
            },
            columns: [
                {data: 'no_rawat', name: 'no_rawat'},
                {data: 'no_rkm_medis', name: 'no_rkm_medis'},
                {data: 'nm_pasien', name: 'nm_pasien'},
                {data: 'tanggal', name: 'tanggal'},
                {data: 'nm_ruang', name: 'nm_ruang'},
                {data: 'no_sep', name: 'no_sep'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $(".filter").click(function(){
            table.draw();
        });
    });

    $(document).ready(function() {
        $(document).on('click', ".download", function() {
            $(this).addClass(
                'download-trigger-clicked'
            ); 
            var el = $(".download-trigger-clicked"); 
            // var row = el.closest("tr");

            // get the data
            // var namaFile = row.children(".nama_file").text();
            // var namaFile = row.find("td:eq(6)").text();
            var noRawat = $('#nomorRawat').text();
            var jenisRM = el.attr('id');
            $.ajax({
                url: "{{ route('downloadberkas') }}",
                type: "POST",
                data: {
                    _token : "{{ csrf_token() }}",
                    noRawat : noRawat,
                    jenisRM : jenisRM
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var a = document.createElement('a');
                    var url = window.URL.createObjectURL(data);
                    a.href = url;
                    a.download = noRawat+"-"+jenisRM.substring(4);
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

    

    $(document).on('click', '#detail', function() {
        const id = $(this).data('id');
        const url = "{{ route('rm.detail') }}";
        var statusAkhir = "";
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
                if(data.details==null){
                    Swal.fire({
                        title: "Gagal!",
                        text: "Oops, belum ada berkas RM yang diupload..",
                        icon: "question",
                        buttons: false,
                        timer: 3000,
                    })
                }else{
                    statusAkhir = (data.details.status_akhir=="1")?"Lengkap":(data.details.status_akhir==null)?"Belum Verifikasi":"Tidak Lengkap";
                    if(data.details.btn_awal_medis_igd=="-"){
                        $("#btn-awal-medis-igd").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_awal_medis_igd=="BELUM"){
                            $("#btn-awal-medis-igd").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-awal-medis-igd").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_awal_kep_igd=="-"){
                        $("#btn-awal-kep-igd").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_awal_kep_igd=="BELUM"){
                            $("#btn-awal-kep-igd").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-awal-kep-igd").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_awal_medis=="-"){
                        $("#btn-awal-medis").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_awal_medis=="BELUM"){
                            $("#btn-awal-medis").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-awal-medis").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_awal_kep=="-"){
                        $("#btn-awal-kep").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_awal_kep=="BELUM"){
                            $("#btn-awal-kep").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-awal-kep").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_resume_medis=="-"){
                        $("#btn-resume-medis").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_resume_medis=="BELUM"){
                            $("#btn-aresume-medis").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-resume-medis").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_laporan_operasi=="-"){
                        $("#btn-laporan-operasi").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_laporan_operasi=="BELUM"){
                            $("#btn-laporan-operasi").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-laporan-operasi").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_hasil_lab=="-"){
                        $("#btn-hasil-lab").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_hasil_lab=="BELUM"){
                            $("#btn-hasil-lab").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-hasil-lab").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_hasil_rad=="-"){
                        $("#btn-hasil-rad").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_hasil_rad=="BELUM"){
                            $("#btn-hasil-rad").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-hasil-rad").css({ visibility: 'visible' });
                    }
                    if(data.details.btn_cppt=="-"){
                        $("#btn-cppt").css({ visibility: 'hidden' });
                    }else{
                        if(data.details.btn_cppt=="BELUM"){
                            $("#btn-cppt").removeClass("btn-primary").addClass("btn-warning");
                        }
                        $("#btn-cppt").css({ visibility: 'visible' });
                    }
                    $('#namaPasien').text( data.details.nm_pasien );
                    $('#nomorRawat').text( data.details.no_rawat );
                    $('#nomorRM').text( data.details.no_rkm_medis );
                    $('#nomorSEP').text( data.details.no_sep );
                    $('#statusAkhir').html( statusAkhir );
                    $('#ketAwalMedisIGD').text( data.details.ket_awal_medis_igd );
                    $('#ketAwalKepIGD').text( data.details.ket_awal_kep_igd );
                    $('#ketAwalMedis').text( data.details.ket_awal_medis );
                    $('#ketAwalKep').text( data.details.ket_awal_kep );
                    $('#ketResumeMedis').text( data.details.ket_resume_medis );
                    $('#ketLaporanOperasi').text( data.details.ket_laporan_operasi );
                    $('#ketHasilLab').text( data.details.ket_hasil_lab );
                    $('#ketHasilRad').text( data.details.ket_hasil_rad );
                    $('#ketCPPT').text( data.details.ket_ccppt );
                    $('.user_modal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error: ' + error);
            }
        });
    });
</script>
@endsection