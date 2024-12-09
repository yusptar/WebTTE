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
            <div class="row">
                <!-- <div class="col-lg-6 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $jumlah_tte->sudah }}</h3>
                            <h6>Jumlah TTE <strong>{{ Auth::user()->pegawai->nama }}</strong> berstatus (SUDAH)</h6>
                        </div>
                        <div class="icon">
                          <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="col-lg-6 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $jumlah_tte->belum }}</h3>
                            <h6>Jumlah TTE <strong>{{ Auth::user()->pegawai->nama }}</strong> berstatus (BELUM)</h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-minus"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                          
                        </a>
                    </div>
                </div> -->
            </div>
            <div class="col-md-12 mr-10 text-center">
                <img src="{{ asset('img/alur-tte-new.png') }}" alt="" class="" width="70%" height="30%">
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <br>
    <!-- /.content -->
</div>
@endsection