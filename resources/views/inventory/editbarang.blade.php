@extends('layouts.main')

@section('content')
    <style>
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 0px 20px;
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
                <li class="breadcrumb-item"><a href="{{ route('editbarang') }}" class="fw-bold text-dark">Stock Barang</a></li>
                <li class="breadcrumb-item active text-primary" aria-current="page"><strong>Edit Barang</strong></li>
            </ol>
        </nav>

        <p class="text-muted">*Semua Field wajib diisi kecuali ada keterangan</p>

        <!-- Form Edit Barang -->
        <form action="{{ route('simpanbarang') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="namaBarang" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang') }}" required>
                    @error('nama_barang')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="idBarang" class="form-label">ID Barang</label>
                    <input type="text" class="form-control" name="id_barang" value="{{ old('id_barang') }}" required>
                    @error('id_barang')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" name="kategori">
                        <option hidden>- Select Group -</option>
                        <option value="Obat" {{ old('kategori') == 'Obat' ? 'selected' : '' }}>Obat</option>
                        <option value="Vitamin" {{ old('kategori') == 'Vitamin' ? 'selected' : '' }}>Vitamin</option>
                        <option value="Antibiotik" {{ old('kategori') == 'Antibiotik' ? 'selected' : '' }}>Antibiotik</option>
                    </select>
                    @error('kategori')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="kuantitas" class="form-label">Kuantitas</label>
                    <input type="text" class="form-control" name="kuantitas" value="{{ old('kuantitas') }}" required>
                    @error('kuantitas')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="mt-3">
                <label for="penggunaan" class="form-label">Cara Penggunaan (Optional)</label>
                <textarea class="form-control" rows="3" name="penggunaan">{{ old('penggunaan') }}</textarea>
            </div>

            <div class="mt-3">
                <label for="efekSamping" class="form-label">Efek Samping (Optional)</label>
                <textarea class="form-control" rows="3" name="efek_samping">{{ old('efek_samping') }}</textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-danger">Save Details</button>
            </div>
        </form>
    </div>
@endsection
