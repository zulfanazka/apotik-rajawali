@extends('layouts.main')

@section('content')
    <style>
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); */
            margin: 0px 20px; /* Bisa menyebabkan geser, coba hapus */
        }

        .breadcrumb a {
            text-decoration: none;
        }
    </style>

    <div class="container">
        <!-- Breadcrumb (Navigasi Halaman) -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Penyimpanan</a></li>
                <li class="breadcrumb-item"><a href="stok-barang.html" class="fw-bold text-dark">Stock Barang</a></li>
                <li class="breadcrumb-item active text-primary" aria-current="page"><strong>Edit Barang</strong></li>
            </ol>
        </nav>

        <p class="text-muted">*Semua Field wajib diisi kecuali ada keterangan</p>

        <!-- Form Edit Barang -->
        <form>
            <div class="row">
                <div class="col-md-6">
                    <label for="namaBarang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" id="namaBarang">
                </div>
                <div class="col-md-6">
                    <label for="idBarang" class="form-label">ID</label>
                    <input type="text" class="form-control" id="idBarang">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select">
                        <option hidden>- Select Group -</option>
                        <option>Obat</option>
                        <option>Vitamin</option>
                        <option>Antibiotik</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="kuantitas" class="form-label">Kuantitas</label>
                    <input type="text" class="form-control" id="kuantitas">
                </div>
            </div>

            <div class="mt-3">
                <label for="penggunaan" class="form-label">Cara Penggunaan (Optional)</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>

            <div class="mt-3">
                <label for="efekSamping" class="form-label">Efek Samping (Optional)</label>
                <textarea class="form-control" rows="3"></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-danger">Save Details</button>
            </div>
        </form>
    </div>

@endsection
