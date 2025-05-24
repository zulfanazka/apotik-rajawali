@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4 mt-40">{{ $welcomeMessage ?? 'Selamat datang kembali!' }}</h1>
        {{-- ... (Kartu-kartu informasi lainnya tetap sama) ... --}}
        <div class="row mb-4">
             <div class="col-xl-3 col-md-6">
                <div class="card bg-light-blue text-black mb-4">
                    <div class="card-body">
                        <h5>Total Keuntungan</h5>
                        @if (isset($userRole) && $userRole === 'admin')
                            <h4>Rp. {{ number_format($totalTransaksi ?? 0, 0, ',', '.') }}</h4>
                        @else
                            <span>-</span>
                        @endif
                        <p>Total Pendapatan Bersih</p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-black stretched-link" href="{{ route('laporan') }}">View Detailed Report</a>
                        <div class="small text-black"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-light-blue text-black mb-4">
                    <div class="card-body">
                        <h5>Stok Barang Saat Ini</h5>
                        <h4>{{ number_format($totalStokSaatIni ?? 0, 0, ',', '.') }} Unit</h4>
                        <p>Total Stok di Gudang</p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-black stretched-link" href="{{ route('stokbarang') }}">Visit Inventory</a>
                        <div class="small text-black"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-light-green text-black mb-4">
                    <div class="card-body">
                        <h5>Ringkasan Inventory</h5>
                        <p>Barang Masuk: {{ number_format($totalMasuk ?? 0, 0, ',', '.') }}</p>
                        <p>Barang Keluar: {{ number_format($totalKeluar ?? 0, 0, ',', '.') }}</p>
                    </div>
                     <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-black stretched-link" href="{{ route('barangmasuk') }}">Lihat Barang Masuk</a>
                        <div class="small text-black"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Kartu Chart Keuntungan Harian --}}
            <div class="col-xl-6">
                <div class="card h-100">
                    <div class="card-header bg-light-gray text-dark">
                        Laporan Keuntungan Harian (7 Hari Terakhir) {{-- PERUBAHAN JUDUL CHART --}}
                    </div>
                    <div class="card-body">
                        @if (isset($userRole) && $userRole === 'admin')
                            <canvas id="myDailyProfitChartCanvas"></canvas>
                        @else
                            <span>Data ditampilkan hanya untuk admin</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kartu Top 10 Produk (jika masih ingin ditampilkan) --}}
            <div class="col-xl-6">
                {{-- ... (kode tabel top produk tetap sama) ... --}}
                 <div class="card h-100">
                    <div class="card-header bg-light-gray text-dark">Top 10 Produk Terlaris</div>
                    <div class="card-body">
                        @if (isset($userRole) && $userRole === 'admin')
                            @if (isset($topProduk) && $topProduk->count() > 0)
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Produk</th>
                                            <th>Total Terjual</th>
                                            <th>Harga Jual (Contoh)</th>
                                            <th>Total Keuntungan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topProduk as $produk)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $produk->nama_barang }}</td>
                                                <td>{{ number_format($produk->total_terjual, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                                <td>Rp. {{ number_format($produk->keuntungan, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>Tidak ada data produk terlaris untuk ditampilkan.</p>
                            @endif
                        @else
                            <span>Data ditampilkan hanya untuk admin</span>
                        @endif
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