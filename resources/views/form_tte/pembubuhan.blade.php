@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Pembubuhan TTE RM</h1>
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
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggleSwitch" id="toggleSwitch" name="toggleSwitch" checked>
                                <label class="custom-control-label" for="toggleSwitch">BELUM TTE</label>
                                &nbsp;
                                <button class="btn btn-danger btn-sm" id="tte-semua" type="button" data-id="asdasd">TTE Semua</button>
                            </div>
                            <table class="table table-bordered table-hover" id="table-rm">
                                <thead>
                                    <tr>
                                        <th>No Rawat</th>
                                        <th>Nama File</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Jenis RM</th>
                                        <th>Asal</th>
                                        <th>Status TTE</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <!-- Modal Example Start-->
                            <div class="modal fade" id="demoModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="demoModalLabel">Masukkan passphrase..!!</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="align-items:center; justify-content:center;">
                                            <form id="form-send-tte">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="hidden" name="_token" value="{{ env('TOKEN') }}" />
                                                    <input type="text" class="form-control" name="modal_type" id="modal_type" hidden> 
                                                    <input type="text" class="form-control" name="modal_no_rawat" id="modal_no_rawat" hidden> 
                                                    <input type="text" class="form-control" name="modal_nama_file" id="modal_nama_file" hidden>
                                                    <input type="text" class="form-control" name="modal_jenis_rm" id="modal_jenis_rm" hidden>
                                                    <input type="text" class="form-control" name="passphrase" id="passphrase" autocomplete="off" required>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="loading-spinner" style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%); display:none;">
                                            <img src="{{ asset('img/spinner.gif') }}" alt="" width="120" height="120">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" id="btn-send" class="btn btn-primary input_passphrase">Sign Now</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal Example End-->
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
    $(function () {
        var table = $('#table-rm').DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('pembubuhan-tte') }}",
                data:function (d) {
                    d.status = ($('#toggleSwitch').is(':checked'))?'BELUM':'SUDAH';
                }
            },
            columns: [
                {data: 'no_rawat', name: 'no_rawat'},
                {data: 'path', name: 'path'},
                {data: 'no_rkm_medis', name: 'no_rkm_medis'},
                {data: 'nm_pasien', name: 'nm_pasien'},
                {data: 'jenis_rm', name: 'jenis_rm'},
                {data: 'nm_ruang', name: 'nm_ruang'},
                {data: 'signed_status', name: 'signed_status', searchable: true, visible: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $("#toggleSwitch").click(function(){
            table.draw();
        });
    });

    $('#btn-send').click(function() {
        if ($('#form-send-tte')[0].checkValidity()) {
            $('#loading-spinner').show();
            if($('input[name=modal_type]').val()=="bulk"){
                console.log("bulk");
                var dataArr = $('#table-rm').DataTable().rows().data().toArray();
                var no_rawat = "";
                var nama_file = "";
                var jenis_rm = "";
                var passphrase = $('input[name=passphrase]').val();
                var errorMsg = "";
                // console.log(dataArr);
                dataArr.forEach((data) => {
                    no_rawat = data['no_rawat'];
                    nama_file = data['path'];
                    jenis_rm = nama_file.substring(2, 5);
                    // console.log(no_rawat + " | " + nama_file + " | " + jenis_rm + " | "+passphrase);
                    var formData = new FormData();
                    formData.append('no_rawat', no_rawat);
                    formData.append('nama_file', nama_file);
                    formData.append('jenis_rm', jenis_rm);
                    formData.append('passphrase', passphrase);
                    formData.append('_token', $('input[name=_token]').val());
                    $.ajax({
                        url: "{{ route('signInvisibleTTE') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            
                        },
                        error: function(data) {
                            if(data.status == 400){
                                // console.log(data.responseJSON.msg);
                                errorMsg += no_rawat + "gagal, " + data.responseJSON.msg + ".\n";
                            }
                        }
                    });
                });

                //fungsi menunggu semua ajax dalam loop selesai
                $(document).ajaxStop(function () {
                    console.log("selesai");
                        
                    if(errorMsg==""){
                        $('#loading-spinner').hide();
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Bulk TTE berhasil..!!",
                            icon: "success",
                            buttons: false,
                            timer: 3000,
                        }).then(function() {
                            window.location.href = "{{ route('view-pemb-rm') }}"
                        });
                    }else{
                        console.log(errorMsg);
                        $('#loading-spinner').hide();
                        Swal.fire({
                            title: "Gagal!",
                            text: errorMsg,
                            icon: "error",
                            buttons: false,
                            timer: 5000,
                        }).then(function() {
                            window.location.href = "{{ route('view-pemb-rm') }}"
                        });
                    }
                    $(this).unbind('ajaxStop'); // to stop this event repeating further
                });
            }else{
                var formData = new FormData();
                formData.append('no_rawat', $('input[name=modal_no_rawat]').val());
                formData.append('nama_file', $('input[name=modal_nama_file]').val());
                formData.append('jenis_rm', $('input[name=modal_jenis_rm]').val());
                formData.append('passphrase', $('input[name=passphrase]').val());
                formData.append('_token', $('input[name=_token]').val());
                $.ajax({
                    url: "{{ route('signInvisibleTTE') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#loading-spinner').hide();
                        Swal.fire({
                            title: "Berhasil!",
                            text: data.msg,
                            icon: "success",
                            buttons: false,
                            timer: 3000,
                        }).then(function() {
                            window.location.href = "{{ route('view-pemb-rm') }}"
                        });
                    },
                    error: function(data) {
                        $('#loading-spinner').hide();
                        Swal.fire({
                            title: "Gagal!",
                            text: data.responseJSON.msg,
                            icon: "error",
                            buttons: false,
                            timer: 3000,
                        }).then(function() {
                            window.location.href = "{{ route('view-pemb-rm') }}"
                        });
                    }
                });
            }
            
        } else {
            $('#form-send-tte')[0].reportValidity();
        }
    });

    $(document).ready(function() {
        var type = "";
        $(document).on('click', "#open-modal", function() {
            $(this).addClass(
                'open-modal-trigger-clicked'
            ); 

            type = "single";

            var options = {
                'backdrop': 'static'
            };
            $('#demoModal').modal(options)
        })

        $(document).on('click', "#tte-semua", function() {
            $(this).addClass(
                'open-modal-trigger-clicked'
            ); 

            type = "bulk";

            var options = {
                'backdrop': 'static'
            };
            $('#demoModal').modal(options)
        })

        $('#demoModal').on('show.bs.modal', function() {
            var el = $(".open-modal-trigger-clicked"); // See how its usefull right here? 
            var row = el.closest("tr");

            // get the data
            // var no_rawat = row.children(".no_rawat").text();
            // var tanggal_upload = row.children(".tanggal_upload").text();
            // // var tanggal_signed = row.children(".tanggal_signed").text();
            // var nama_file = row.children(".nama_file").text();
            var no_rawat =  row.find("td:eq(0)").text();
            var nama_file = row.find("td:eq(1)").text();
            var jenis_rm = nama_file.substring(2, 5);

            // fill the data in the input fields
            $("#modal_no_rawat").val(no_rawat);
            $("#modal_nama_file").val(nama_file);
            $("#modal_jenis_rm").val(jenis_rm);
            $("#modal_type").val(type);
            

        })

        $('#demoModal').on('hide.bs.modal', function() {
            $("#passphrase").val('');
            $('.open-modal-trigger-clicked').removeClass('open-modal-trigger-clicked')
            $("#demoModal").trigger("reset");
        })
    });
</script>
@endsection