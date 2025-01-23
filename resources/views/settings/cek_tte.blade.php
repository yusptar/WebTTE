@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Berkas TTE</h1>
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
                            <!-- <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggleSwitch" id="toggleSwitch" name="toggleSwitch" checked>
                                <label class="custom-control-label" for="toggleSwitch">BELUM TTE</label>
                                &nbsp;
                                <button class="btn btn-outline-secondary btn-sm" id="all-in-one" type="button" data-id="asdasd">ALL IN ONE (HONDA)</button>
                            </div> -->
                            <div class="btn-group mb-3">
                                <button class="btn btn-outline-primary btn-sm" id="all-in-one" type="button" data-id="asdasd">ALL IN ONE (HONDA)</button>
                               
                            </div>
                            <div class="btn-group mb-3">
                                <button class="btn btn-outline-danger btn-sm" id="hapus-semua" type="button" data-id="asdasd"><i class="fas fa-trash"></i> DESTROY ALL OF THIS</button>
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
                                        <!-- <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <!-- Modal Example Start-->
                            <div class="modal fade" id="demoModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <!-- <div class="modal-header">
                                            <h5 class="modal-title" id="demoModalLabel">Masukkan passphrase..!!</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div> -->
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
        $(function () {
            var table = $('#table-rm').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('cek-tte') }}",
                    data: function (d) {}
                },
                columns: [
                    { data: 'no_rawat', name: 'no_rawat', searchable: true},
                    { data: 'path', name: 'path',searchable: true },
                    { data: 'no_rkm_medis', name: 'no_rkm_medis', searchable: true},
                    { data: 'nm_pasien', name: 'nm_pasien', searchable: true},
                    { data: 'jenis_rm', name: 'jenis_rm', searchable: true },
                    { data: 'nm_ruang', name: 'nm_ruang',searchable: true },
                    { data: 'signed_status', name: 'signed_status', searchable: true },
                    // { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
        });
    });

    

    $(document).ready(function() {
        $(document).on('click', "#all-in-one", function() {
            var table = $('#table-rm').DataTable();
            table.ajax.url("{{ route('cek-tte') }}").load();
            
        })

        $(document).on('click', '#hapus_semua', function(e) {
            e.preventDefault();
            let id = $(this).data('id');
            Swal.fire({
                title: 'Apakah anda yakin untuk menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak',
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('user-delete') }}",
                        method: "POST",
                        data: {
                            id: id,
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.code == 0) {
                                Swal.fire(
                                    'Oops!',
                                    'Something went wrong!.',
                                    'error'
                                )
                            } else {
                                $('#users_table').DataTable().ajax.reload(null,
                                    false);
                                Swal.fire(
                                    'Deleted!',
                                    'Data berhasil dihapus!.',
                                    'success'
                                )
                            }
                        }
                    });
                }
            })
        });

        $(document).on('click', '#hapus-semua', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'WADUH BAHAYA IKI?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'YES, HAPUS KABEH!',
                cancelButtonText: 'SIK MIKIR-MIKIR',
            }).then((result) => {
                if (result.isConfirmed) {
                    const csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var formData = new FormData();
                    formData.append('type', 'hapus_semua'); // Indikasi penghapusan semua
                    formData.append('_token', csrfToken);

                    $.ajax({
                        url: "{{ route('delete-berkas') }}",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: data.msg,
                                icon: "success",
                                buttons: false,
                            }).then(function () {
                                location.reload(); // Memuat ulang halaman setelah data dihapus
                            });
                        },
                        error: function (data) {
                            Swal.fire({
                                title: "Gagal!",
                                text: data.responseJSON.msg,
                                icon: "error",
                                buttons: false,
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endsection