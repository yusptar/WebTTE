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
                                    <tr class="data-row">
                                        <td class="no_rawat">{{ $mt->no_rawat}}</td>
                                        <td class="jenis_rm">{{ $mt->jenis_rm}}</td>
                                        <td class="tanggal_upload">{{ $mt->tanggal_upload}}</td>
                                        <td class="tanggal_signed">{{ $mt->tanggal_signed}}</td>
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
                                                    <button class="btn btn-primary btn-sm cetak-btn" id="open-modal"
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
                            <!-- Modal Example Start-->
                            <div class="modal fade" id="demoModal" tabindex="-1" role="dialog"
                                aria-labelledby="demoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="demoModalLabel">Masukkan passphrase..!!</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="form-send-tte" autocomplete="off">
                                                @csrf
                                                <div class="form-group">
                                                    <input type="hidden" name="_token"
                                                        value="Wm0qbXXO6oIkYEbFWl4as7auxZdxYa06" />
                                                    <input type="text" class="form-control" name="modal_no_rawat"
                                                        id="modal_no_rawat" hidden>
                                                    <input type="text" class="form-control" name="modal_jenis_rm"
                                                        id="modal_jenis_rm" hidden>
                                                    <input type="text" class="form-control" name="modal_tanggal_upload"
                                                        id="modal_tanggal_upload" hidden>
                                                    <input type="password" class="form-control" name="passphrase"
                                                        autocomplete="new-password" required>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Close</button>
                                            <button type="button" id="btn-send" class="btn btn-primary">Send</button>
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
$(document).ready(function() {
    $("#table-rm").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": true,
    })
});

$('#btn-send').click(function() {
    if ($('#form-send-tte')[0].checkValidity()) {
        var formData = new FormData();
        formData.append('no_rawat', $('input[name=modal_no_rawat]').val());
        formData.append('jenis_rm', $('input[name=modal_jenis_rm]').val());
        formData.append('tanggal_upload', $('input[name=modal_tanggal_upload]').val());
        formData.append('passphrase', $('input[name=passphrase]').val());
        formData.append('_token', $('input[name=_token]').val());
        $.ajax({
            url: "{{ route('kirimTTE') }}",
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
                    timer: 3000,
                }).then(function() {
                    window.location.href = "{{ route('pembubuhan-tte') }}"
                });
            },
            error: function(data) {
                // alert(data.responseJSON.msg);
                Swal.fire({
                    title: "Gagal!",
                    text: data.responseJSON.msg,
                    icon: "error",
                    buttons: false,
                    timer: 3000,
                }).then(function() {
                    // window.location.href = "{{ route('pembubuhan-tte') }}"
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
        var row = el.closest(".data-row");

        // get the data
        var no_rawat = row.children(".no_rawat").text();
        var jenis_rm = row.children(".jenis_rm").text();
        var tanggal_upload = row.children(".tanggal_upload").text();

        // fill the data in the input fields
        $("#modal_no_rawat").val(no_rawat);
        $("#modal_jenis_rm").val(jenis_rm);
        $("#modal_tanggal_upload").val(tanggal_upload);
    })

    // on modal hide
    $('#demoModal').on('hide.bs.modal', function() {
        $('.open-modal-trigger-clicked').removeClass('open-modal-trigger-clicked')
        $("#demoModal").trigger("reset");
    })
});
</script>
@endsection