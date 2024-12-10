@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0" style="font-weight:bold">Dashboard</h1>
                    <h6 class="m-0" style="font-weight:bold">Detail Penggunaan Aplikasi TTE</h6>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $jumlah_tte->sudah }} Dokumen</h3>
                            <h6>Jumlah Keseluruhan Dokumen TTE berstatus <strong style="color:white; font-weight:bold">(SUDAH)</strong></h6>
                        </div>
                        <div class="icon">
                          <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $jumlah_tte->belum }} Dokumen</h3>
                            <h6>Jumlah Keselurahan Dokumen TTE berstatus <strong style="color:white; font-weight:bold">(BELUM)</strong></h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-minus"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card col-lg-6 col-6">
                    <!-- <div class="card-header">
                        <h3 class="card-title"><strong>{{ Auth::user()->pegawai->nama }}</strong></h3>
                    </div> -->
                    <div class="card-body">
                        <canvas id="tte-sudah" width="200" height="100"></canvas>
                    </div>
                </div>
                <div class="card col-lg-6 col-6">
                    <!-- <div class="card-header">
                        <h3 class="card-title"><strong>{{ Auth::user()->pegawai->nama }}</strong></h3>
                    </div> -->
                    <div class="card-body">
                        <canvas id="tte-belum" width="200" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center text-center" style="height: 100%;">
                <button id="toggleImageBtn" class="btn btn-success mb-3" style="border-radius:25px;"><strong>ALUR PENGGUNAAN TTE</strong></button>
                <img id="alurTteImage" src="{{ asset('img/alur-tte-new.png') }}" alt="Alur TTE" style="width: auto; height: auto; display: none;">
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <br>
    <!-- /.content -->
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = @json($months);
    const dataBelum = @json($belum);
    const dataSudah = @json($sudah);

    const ctx = document.getElementById('tte-sudah').getContext('2d');
    const cty = document.getElementById('tte-belum').getContext('2d');
    new Chart(ctx, {
        type: 'line', 
        data: {
            labels: months,
            datasets: [
                // {
                //     label: 'BELUM',
                //     data: dataBelum,
                //     borderColor: 'rgba(54, 162, 235, 1)', 
                //     backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                //     borderWidth: 2, 
                //     fill: true, 
                //     tension: 0.3, 
                // },
                {
                    label: 'SUDAH',
                    data: dataSudah,
                    borderColor: 'rgba(75, 192, 192, 1)', 
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', 
                    borderWidth: 2, 
                    fill: true, 
                    tension: 0.3, 
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', 
                },
                title: {
                    display: true,
                    text: 'Grafik Jumlah Dokumen TTE Per Bulan (SUDAH)', 
                },
            },
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
    new Chart(cty, {
        type: 'line', 
        data: {
            labels: months,
            datasets: [
                {
                    label: 'BELUM',
                    data: dataBelum,
                    borderColor: 'rgba(54, 162, 235, 1)', 
                    backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                    borderWidth: 2, 
                    fill: true, 
                    tension: 0.3, 
                },
                // {
                //     label: 'SUDAH',
                //     data: dataSudah,
                //     borderColor: 'rgba(75, 192, 192, 1)', 
                //     backgroundColor: 'rgba(75, 192, 192, 0.2)', 
                //     borderWidth: 2, 
                //     fill: true, 
                //     tension: 0.3, 
                // },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', 
                },
                title: {
                    display: true,
                    text: 'Grafik Jumlah Dokumen TTE Per Bulan (BELUM)', 
                },
            },
            scales: {
                x: {
                    beginAtZero: true,
                },
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
</script>
<script>
    document.getElementById('toggleImageBtn').addEventListener('click', function () {
        const image = document.getElementById('alurTteImage');
        const button = this;

        if (image.style.display === 'none') {
            image.style.display = 'block';
            button.textContent = 'Sembunyikan Gambar';
        } else {
            image.style.display = 'none';
            button.textContent = 'Tampilkan Gambar';
        }
    });
</script>
@endsection