@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 font-weight-bold">Cek Status TTE User</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body text-center">

                    <button id="btn-cek-status"
                            class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i>
                        Cek Status TTE Saya
                    </button>

                    <div class="mt-4">
                        <pre id="result-status"
                             style="text-align:left; display:none; background:#f4f6f9; padding:15px; border-radius:6px;"></pre>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {

    $('#btn-cek-status').on('click', function () {

        $('#result-status').hide().html('');

        $.ajax({
            url: "{{ route('loop-status-user') }}",
            type: "GET",
            beforeSend: function () {
                $('#btn-cek-status')
                    .prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Mengecek...');
            },
            success: function (res) {
                $('#result-status')
                    .html(JSON.stringify(res, null, 2))
                    .fadeIn();
            },
            error: function (xhr) {
                $('#result-status')
                    .html(JSON.stringify(xhr.responseJSON, null, 2))
                    .fadeIn();
            },
            complete: function () {
                $('#btn-cek-status')
                    .prop('disabled', false)
                    .html('<i class="fas fa-search"></i> Cek Status TTE Saya');
            }
        });

    });

});
</script>
@endsection
