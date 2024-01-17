@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Dashboard</h1>
                    <h6 class="m-0" style="font-weight:bold">Alur Penggunaan Aplikasi TTE</h6>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12 mr-10 text-center">
                <img src="{{ asset('img/alur-tte-new.png') }}" alt="" class="" width="80%" height="50%">
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <br>
    <!-- /.content -->
</div>
@endsection