@extends('layouts.main')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0">Dashboard</h1>
            <h6 class="m-0">Ringkasan dokumen TTE atas nama {{ Auth::user()->pegawai->nama }}</h6>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="tte-stat tte-stat--sudah">
                        <p class="tte-stat-label">Sudah ditandatangani</p>
                        <p class="tte-stat-value">{{ !empty($jumlah_tte->sudah) ? $jumlah_tte->sudah : 0 }}<small>dokumen</small></p>
                        <span class="badge rounded-pill bg-success">SUDAH</span>
                        <div>
                            <button id="toggle-sudah" class="btn btn-sm" type="button" aria-expanded="false" aria-controls="list-sudah">
                                <i class="fas fa-list-ul mr-1"></i> Lihat rincian per jenis dokumen
                            </button>
                        </div>
                        <div id="list-sudah" style="display: none;">
                            <ul class="list-group">
                                @forelse($jumlah_dokumen_sudah->dokumen_count as $dokumen => $count)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $dokumen }} <span class="tte-doc-count">{{ $count }}</span>
                                </li>
                                @empty
                                <li class="list-group-item text-muted">Belum ada dokumen yang ditandatangani.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="tte-stat tte-stat--belum">
                        <p class="tte-stat-label">Menunggu tanda tangan Anda</p>
                        <p class="tte-stat-value">{{ !empty($jumlah_tte->belum) ? $jumlah_tte->belum : 0 }}<small>dokumen</small></p>
                        <span class="badge rounded-pill bg-secondary">BELUM</span>
                        <div>
                            <button id="toggle-belum" class="btn btn-sm" type="button" aria-expanded="false" aria-controls="list-belum">
                                <i class="fas fa-list-ul mr-1"></i> Lihat rincian per jenis dokumen
                            </button>
                        </div>
                        <div id="list-belum" style="display: none;">
                            <ul class="list-group">
                                @forelse($jumlah_dokumen_belum->dokumen_count as $dokumen => $count)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $dokumen }} <span class="tte-doc-count">{{ $count }}</span>
                                </li>
                                @empty
                                <li class="list-group-item text-muted">Tidak ada dokumen yang menunggu.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header tte-chart-head">
                            <h3 class="card-title">Dokumen TTE per bulan</h3>
                            <label class="mb-0 d-flex align-items-center" style="gap:.5rem; font-weight:600; font-size:.875rem;">
                                Tahun
                                <select id="yearFilter" class="form-control form-control-sm">
                                    <option value="2026">2026</option>
                                    <option value="2025">2025</option>
                                    <option value="2024">2024</option>
                                </select>
                            </label>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 300px;">
                                <canvas id="tte-all"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Alur penggunaan TTE</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted" style="font-size:.875rem;">Langkah dari upload dokumen sampai dokumen bertanda tangan elektronik.</p>
                            <button id="toggleImageBtn" class="btn btn-success btn-block" type="button">Tampilkan alur</button>
                        </div>
                    </div>
                </div>
            </div>
            <img id="alurTteImage" src="{{ asset('img/alur-tte-new.png') }}" alt="Alur penggunaan TTE" class="img-fluid card p-3" style="max-width: 100%; height: auto; display: none;">
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
<script>
    Chart.defaults.font.family = '"Public Sans", "Segoe UI", system-ui, sans-serif';
    Chart.defaults.color = '#5f6b7a';
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
                    borderColor: '#a8690f',
                    backgroundColor: 'rgba(168, 105, 15, 0.75)',
                    borderRadius: 4,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                },
                {
                    label: 'Dokumen SUDAH TTE',
                    data: [],
                    borderColor: '#1f7a5c',
                    backgroundColor: 'rgba(31, 122, 92, 0.85)',
                    borderRadius: 4,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false,
                },
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { display: false },
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: '#eef1f5' },
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
        const show = image.style.display === 'none';
        image.style.display = show ? 'block' : 'none';
        this.textContent = show ? 'Sembunyikan alur' : 'Tampilkan alur';
        if (show) image.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    function bindToggle(buttonId, listId) {
        const button = document.getElementById(buttonId);
        button.addEventListener('click', function () {
            const list = document.getElementById(listId);
            const show = list.style.display === 'none';
            $(list).stop(true, true)[show ? 'slideDown' : 'slideUp'](160);
            button.setAttribute('aria-expanded', show ? 'true' : 'false');
            button.innerHTML = show
                ? '<i class="fas fa-chevron-up mr-1"></i> Sembunyikan rincian'
                : '<i class="fas fa-list-ul mr-1"></i> Lihat rincian per jenis dokumen';
        });
    }
    bindToggle('toggle-sudah', 'list-sudah');
    bindToggle('toggle-belum', 'list-belum');
</script>
@endsection