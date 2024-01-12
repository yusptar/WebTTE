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
                            </div>
                            <table class="table table-bordered table-hover" id="table-rm">
                                <thead>
                                    <tr>
                                        <th>No Surat</th>
                                        <th>No RM</th>
                                        <th>Nama Pasien</th>
                                        <th>Jenis Bayar</th>
                                        <th>Nama File</th>
                                        <th>Status TTE</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($manj_tte as $mt)
                                    <tr class="data-row">
                                        <td class="no_rawat">{{ $mt->no_rawat}}</td>
                                        <td class="nama_file">{{ $mt->path }}</td>
                                        <td class="no_rawat">{{ $mt->no_rawat}}</td>
                                        <td class="nama_file">{{ $mt->path }}</td>
                                        <td class="no_rawat">{{ $mt->no_rawat}}</td>
                                        <td class="nama_file">{{ $mt->path }}</td>
                                        <td class="signed_status">
                                            <span class="badge rounded-pill {{ $mt->signed_status == 'BELUM' ? "bg-secondary" : "bg-success" }}" >{{ $mt->signed_status}}</span>
                                        </td>
                                        <td>
                                            @if($mt->signed_status == 'BELUM')
                                                <div>
                                                    <button class="btn btn-primary btn-sm cetak-btn" id="open-modal" type="button">Sign Now..!!</button>
                                                </div>
                                            @else
                                                No Action
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach --}}
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
                                        <div class="modal-body">
                                            <form id="form-send-tte">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="hidden" name="_token" value="Wm0qbXXO6oIkYEbFWl4as7auxZdxYa06" />
                                                    <input type="text" class="form-control" name="modal_no_rawat" id="modal_no_rawat" hidden>
                                                    <input type="text" class="form-control" name="modal_nama_file" id="modal_nama_file" hidden>
                                                    <input type="text" class="form-control" name="passphrase" id="passphrase" autocomplete="off" required>
                                                </div>
                                            </form>
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
                {data: 'no_rkm_medis', name: 'no_rkm_medis'},
                {data: 'nm_pasien', name: 'nm_pasien'},
                {data: 'png_jawab', name: 'png_jawab'},
                {data: 'path', name: 'path'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $("#toggleSwitch").click(function(){
            table.draw();
        });
    });

    $('#btn-send').click(function() {
        if ($('#form-send-tte')[0].checkValidity()) {
            var formData = new FormData();
            formData.append('no_rawat', $('input[name=modal_no_rawat]').val());
            formData.append('nama_file', $('input[name=modal_nama_file]').val());
            formData.append('passphrase', $('input[name=passphrase]').val());
            formData.append('_token', $('input[name=_token]').val());
            $.ajax({
                url: "{{ route('signInvisibleTTE') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    Swal.fire({
                        title: "Berhasil!",
                        text: data.msg,
                        icon: "success",
                        buttons: false,
                        // timer: 3000,
                    }).then(function() {
                        window.location.href = "{{ route('pembubuhan-tte') }}"
                    });
                },
                error: function(data) {
                    Swal.fire({
                        title: "Gagal!",
                        text: data.responseJSON.msg,
                        icon: "error",
                        buttons: false,
                        // timer: 3000,
                    }).then(function() {
                        window.location.href = "{{ route('pembubuhan-tte') }}"
                    });
                }
            });
        } else {
            $('#form-send-tte')[0].reportValidity();
        }
    });

    $(document).ready(function() {

        $(document).on('click', "#open-modal", function() {
            $(this).addClass(
                'open-modal-trigger-clicked'
            ); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

            var options = {
                'backdrop': 'static'
            };
            $('#demoModal').modal(options)
        })

        // on modal show
        $('#demoModal').on('show.bs.modal', function() {
            var el = $(".open-modal-trigger-clicked"); // See how its usefull right here? 
            var row = el.closest("tr");

            // get the data
            // var no_rawat = row.children(".no_rawat").text();
            // var tanggal_upload = row.children(".tanggal_upload").text();
            // // var tanggal_signed = row.children(".tanggal_signed").text();
            // var nama_file = row.children(".nama_file").text();
            var no_rawat =  row.find("td:eq(0)").text();
            var nama_file = row.find("td:eq(4)").text();

            // fill the data in the input fields
            $("#modal_no_rawat").val(no_rawat);
            $("#modal_nama_file").val(nama_file);

        })

        // on modal hide
        $('#demoModal').on('hide.bs.modal', function() {
            $("#passphrase").val('');
            $('.open-modal-trigger-clicked').removeClass('open-modal-trigger-clicked')
            $("#demoModal").trigger("reset");
        })
    });
</script>
@endsection