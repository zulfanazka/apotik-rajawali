<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>

    @include('include.style')
</head>

<body class="sb-nav-fixed">
    @include('include.navbar')
    <div id="layoutSidenav">
        @include('include.sidebar')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Wellcome</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body" id="total_transaksi">
                                    <i class="fas fa-money-bill" id="icon-uang"></i>
                                    <h3><strong>Total Transaksi</strong></h3>
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
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">lihat inventaris</a>
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
                                            <h4><strong>100</strong></h4>
                                        </a>
                                        <a class="small text-white stretched-link" id="barang-keluar">
                                            Barang Keluar<br />
                                            <h4><strong>100</strong></h4>
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
                                    <canvas id="myAreaChart"></canvas>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><img src="{{ asset('template/assets/images/tolak angin.jpg') }}" alt="Tolak Angin" width="50"></td>
                                                <td>Tolak Angin</td>
                                                <td>Rp. 10.000</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><img src="{{ asset('template/assets/images/tolak angin.jpg') }}" alt="Tolak Angin" width="50"></td>
                                                <td>Tolak Angin</td>
                                                <td>Rp. 10.000</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><img src="{{ asset('template/assets/images/tolak angin.jpg') }}" alt="Tolak Angin" width="50"></td>
                                                <td>Tolak Angin</td>
                                                <td>Rp. 10.000</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td><img src="{{ asset('template/assets/images/tolak angin.jpg') }}" alt="Tolak Angin" width="50"></td>
                                                <td>Tolak Angin</td>
                                                <td>Rp. 10.000</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td><img src="{{ asset('template/assets/images/tolak angin.jpg') }}" alt="Tolak Angin" width="50"></td>
                                                <td>Tolak Angin</td>
                                                <td>Rp. 10.000</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
            </main>
            @include('include.footer')
        </div>
    </div>

    @include('include.script')

</body>

</html>
