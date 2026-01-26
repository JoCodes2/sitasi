
@extends('Layouts.Base')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Dashboard Pengajuan Judul</h1>
        <div id="gelombang-badge">
            </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Dosen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-total-dosen">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Mahasiswa (Gel. Aktif)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-total-mhs">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Kesiapan Hungarian</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800" id="stat-readiness-percent">0%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div id="progress-bar-readiness" class="progress-bar bg-info" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-microchip fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sisa Kuota Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="stat-sisa-kuota">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sebaran Topik Diminati</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function() {
        loadDashboardData();
    });
async function loadDashboardData() {
        try {
            const response = await fetch('/sitasi/dashboard');
            const res = await response.json();

            // Pastikan kita mengambil object 'data' dari response
            const data = res.data;

            // 1. Update Statistik (Mengacu pada key: summary)
            $('#stat-total-dosen').text(data.summary.total_dosen);
            $('#stat-total-mhs').text(data.summary.total_mhs);

            // 2. Update Badge Gelombang
            const infoGelombang = data.summary.gelombang !== " - " ? data.summary.gelombang : "Tidak Ada Gelombang Aktif";
            $('#gelombang-badge').html(`<span class="badge badge-primary p-2">${infoGelombang}</span>`);

            // 3. Kesiapan Hungarian (Mengacu pada key: readiness)
            // Kita buat logika persentase: (dosen_input / total_dosen) * 100
            let persentase = 0;
            if(data.summary.total_dosen > 0) {
                persentase = Math.round((data.readiness.dosen_input_kepakaran / data.summary.total_dosen) * 100);
            }

            $('#stat-readiness-percent').text(persentase + '%');
            $('#progress-bar-readiness').css('width', persentase + '%');

            // 4. Sisa Kuota (Contoh: Menampilkan status ratio)
            $('#stat-sisa-kuota').text(data.readiness.ratio_status);

            // 5. Render Chart (Mengacu pada key: topik_stats)
            // Transformasi data API ke format Chart
            const chartData = data.topik_stats.map(item => ({
                topik: item.nama_topik,
                jumlah: item.detail_pengajuan_count
            }));

            renderChart(chartData);

        } catch (error) {
            console.error("Gagal memuat data dashboard", error);
        }
    }

    function renderChart(topikData) {
        const ctx = document.getElementById("myPieChart");

        // Hancurkan chart lama jika ada (mencegah tumpang tindih saat refresh)
        if (window.myDoughnutChart) {
            window.myDoughnutChart.destroy();
        }

        window.myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: topikData.map(t => t.topik),
                datasets: [{
                    data: topikData.map(t => t.jumlah),
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12 }
                    }
                },
                cutout: '70%',
            },
        });
    }

</script>

@endsection

