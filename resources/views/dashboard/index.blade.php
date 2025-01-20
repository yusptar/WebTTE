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
                            <strong>{{ Auth::user()->pegawai->nama }}</strong>
                            @if(!empty($jumlah_tte->sudah))
                            <h3>{{ $jumlah_tte->sudah }} Dokumen</h3>
                            @else
                            <h3>0 Dokumen</h3>
                            @endif
                            <h6>Jumlah Keseluruhan Dokumen TTE berstatus <strong style="color:white; font-weight:bold">(SUDAH)</strong></h6>
                        </div>
                        <div class="icon">
                          <i class="fas fa-check"></i>
                        </div>
                        <button id="toggle-sudah" class="btn btn-success btn-block" style="margin-top: 5px;">Detail Dokumen</button>
                    </div>
                    <div id="list-sudah" style="display: none; margin-bottom: 20px">
                        <ul class="list-group">
                            @foreach($jumlah_dokumen_sudah->dokumen_count as $dokumen => $count)
                            <li class="list-group-item">Dokumen {{ $dokumen }} = {{ $count }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <strong>{{ Auth::user()->pegawai->nama }}</strong>
                            @if(!empty($jumlah_tte->belum))
                            <h3>{{ $jumlah_tte->belum }} Dokumen</h3>
                            @else
                            <h3>0 Dokumen</h3>
                            @endif
                            <h6>Jumlah Keselurahan Dokumen TTE berstatus <strong style="color:white; font-weight:bold">(BELUM)</strong></h6>
                        </div>
                        <div class="icon">
                            <i class="fas fa-minus"></i>
                        </div>
                        <button id="toggle-belum" class="btn btn-info btn-block" style="margin-top: 5px;">Detail Dokumen</button>
                    </div>
                    <div id="list-belum" style="display: none; margin-bottom: 20px;">
                        <ul class="list-group">
                            @foreach($jumlah_dokumen_belum->dokumen_count as $dokumen => $count)
                            <li class="list-group-item">Dokumen {{ $dokumen }} = {{ $count }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- <div class="card col-lg-6">
                    <div class="card-header">
                        <h3 class="card-title"><strong>{{ Auth::user()->pegawai->nama }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <canvas id="tte-all" width="100" height="50"></canvas>
                    </div>
                </div> -->
                <!-- <div class="card col-lg-6">
                    <div class="card-header">
                        <h3 class="card-title"><strong>{{ Auth::user()->pegawai->nama }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <canvas id="tte-all-pie" width="100" height="50"></canvas>
                    </div>
                </div> -->
                <!-- <div class="card col-lg-6">
                    <div class="card-header">
                        <h3 class="card-title"><strong>{{ Auth::user()->pegawai->nama }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <canvas id="tte-belum" width="100" height="50"></canvas>
                    </div>
                </div>
                <div class="card col-lg-6 col-6">
                    <div class="card-header">
                        <h3 class="card-title"><strong>{{ Auth::user()->pegawai->nama }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <canvas id="tte-all" width="100" height="50"></canvas>
                    </div>
                </div> -->
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center text-center" style="height: 100%;">
                <div class="card col-lg-6">
                    <div class="card-header">
                        <h3 class="card-title"><strong>{{ Auth::user()->pegawai->nama }}</strong></h3>
                    </div>
                    <div class="card-body">
                        <select id="yearFilter">
                            <option value="2025">2025</option>
                            <option value="2024">2024</option>
                        </select>
                        <canvas id="tte-all" width="100" height="50"></canvas>
                    </div>
                </div>
                <button id="toggleImageBtn" class="btn btn-success mb-3" style="border-radius:25px;"><strong>ALUR PENGGUNAAN TTE</strong></button>
                <img id="alurTteImage" src="{{ asset('img/alur-tte-new.png') }}" alt="Alur TTE" class="img-fluid" style="max-width: 100%; height: auto; display: none;">
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
    const tteData = @json($tte_data);
    
    function updateChart(selectedYear) {
        const data = tteData[selectedYear];
        if (!data) {
            console.error('Data for the selected year is not available.');
            return;
        }

        chart.data.labels = data.months;
        chart.data.datasets[0].data = data.belum;
        chart.data.datasets[1].data = data.sudah;
        chart.update();
    }

    const ctall = document.getElementById('tte-all').getContext('2d');
    const chart = new Chart(ctall, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Dokumen BELUM TTE',
                    data: [],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                },
                {
                    label: 'Dokumen SUDAH TTE',
                    data: [],
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
                    text: 'Perbandingan Jumlah Dokumen TTE Per Bulan',
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

    const years = Object.keys(tteData); 
    const latestYear = years.sort().reverse()[0];

    document.getElementById('yearFilter').value = latestYear; 
    updateChart(latestYear);

    document.getElementById('yearFilter').addEventListener('change', function () {
        const selectedYear = this.value;
        updateChart(selectedYear);
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
    document.getElementById('toggle-sudah').addEventListener('click', function () {
        const list = document.getElementById('list-sudah');
        if (list.style.display === 'none') {
            list.style.display = 'block';
            this.textContent = 'Sembunyikan Detail Dokumen';
        } else {
            list.style.display = 'none';
            this.textContent = 'Tampilkan Detail Dokumen';
        }
    });

    document.getElementById('toggle-belum').addEventListener('click', function () {
        const list = document.getElementById('list-belum');
        if (list.style.display === 'none') {
            list.style.display = 'block';
            this.textContent = 'Sembunyikan Detail Dokumen';
        } else {
            list.style.display = 'none';
            this.textContent = 'Tampilkan Detail Dokumen';
        }
    });
</script>
@endsection