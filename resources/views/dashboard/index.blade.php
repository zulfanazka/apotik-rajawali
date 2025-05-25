@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">Welcome</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body" id="total_transaksi">
                        <i class="fas fa-money-bill" id="icon-uang"></i>
                        <h3><strong>Total Transaksi</strong></h3>
                        <h4>Rp {{ number_format($totalTransaksi, 0, ',', '.') }}</h4>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">Lihat Detail Laporan</a>
                        <div class="small text-white">
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body" id="stok_barang">
                        <i class="fa fa-medkit" id="icon-stok-barang"></i>
                        <h3><strong>Stok Barang</strong></h3>
                        <h4><strong>{{ $totalStokSaatIni }}</strong></h4> <!-- Tambahan ini -->
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('stokbarang') }}">lihat inventaris</a>
                        <div class="small text-white">
                            <i class="fas fa-angle-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card inventaris -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body" id="inventaris">
                        <div class="atas-inventaris-card">
                            <span>Inventaris</span>
                            <span>January 2022</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <div class="card-footer-kanan-home">
                            <a class="small text-white stretched-link">
                                Barang Masuk<br />
                                <h4><strong>{{ $totalMasuk }}</strong></h4>
                            </a>
                            <a class="small text-white stretched-link" id="barang-keluar">
                                Barang Keluar<br />
                                <h4><strong>{{ $totalKeluar }}</strong></h4>
                            </a>
                        </div>
                        <div class="small text-white">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row equal-height">
            <!-- Grafik Keuntungan -->
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header">Grafik Keuntungan</div>
                    <div class="card-body">
                        @if (isset($userRole) && $userRole === 'admin')
                            <canvas id="myDailyProfitChartCanvas"></canvas>
                        @else
                            <span>Data ditampilkan hanya untuk admin</span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Top Produk -->
            <div class="col-xl-6">
                <div class="card top-produk-card h-100">
                    <div class="card-header">
                        <h5 class="card-title">Top Produk</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Keuntungan</th> <!-- Kolom Keuntungan -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topProduk as $index => $produk)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><img src="{{ asset('template/assets/images/' . strtolower(str_replace(' ', '_', $produk->nama_barang)) . '.jpg') }}"
                                                alt="{{ $produk->nama_barang }}" width="50" style="object-fit: cover;">
                                        </td>
                                        <td>{{ $produk->nama_barang }}</td>
                                        <td>Rp. {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($produk->keuntungan, 0, ',', '.') }}</td>
                                        <!-- Keuntungan per Produk -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Pastikan Chart.js sudah ter-include --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (isset($userRole) && $userRole === 'admin')
                var dailyProfitChartCanvas = document.getElementById("myDailyProfitChartCanvas");
                if (dailyProfitChartCanvas) {
                    var ctxDailyProfit = dailyProfitChartCanvas.getContext('2d');
                    @if (isset($dailyProfitsData) && !empty($dailyProfitsData['labels']) && !empty($dailyProfitsData['data']))
                        const chartDataForDailyProfit = @json($dailyProfitsData);

                        new Chart(ctxDailyProfit, {
                            type: 'line',
                            data: {
                                labels: chartDataForDailyProfit.labels,
                                datasets: [{
                                    label: "Keuntungan Bersih Harian",
                                    data: chartDataForDailyProfit.data,
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                    fill: true,
                                    tension: 0.1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            callback: function(value, index, values) {
                                                return 'Rp. ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }],
                                    xAxes: [{}]
                                },
                                tooltips: {
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                            if (label) { label += ': '; }
                                            label += 'Rp. ' + Number(tooltipItem.yLabel).toLocaleString('id-ID');
                                            return label;
                                        }
                                    }
                                }
                            }
                        });
                    @else
                        ctxDailyProfit.font = "16px Arial";
                        ctxDailyProfit.fillStyle = "#888";
                        ctxDailyProfit.textAlign = "center";
                        ctxDailyProfit.fillText("Data keuntungan harian tidak tersedia.", dailyProfitChartCanvas.width / 2, dailyProfitChartCanvas.height / 2);
                    @endif
                }
            @endif
        });
    </script>
@endpush