@extends('layouts.main')

@section('content')
    <style>
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 0px 20px;
        }
    </style>

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('barangkeluar') }}" class="fw-bold text-dark">Barang Keluar</a>
                </li>
                <li class="breadcrumb-item active text-primary" aria-current="page">
                    <strong>Tambah Barang Keluar</strong>
                </li>
            </ol>
        </nav>

        <form action="{{ route('simpanbarangkeluar') }}" method="POST">
            @csrf

            <div class="row mt-3">
                <div class="col-md-6 mb-3">
                    <label for="id_barang" class="form-label">Pilih Barang</label>
                    <select class="form-select" name="id_barang" id="id_barang" required>
                        <option hidden>- Pilih Barang -</option>
                        @foreach ($barangMasuk as $b)
                            <option value="{{ $b->id_barang }}" data-nama="{{ $b->nama_barang }}"
                                data-satuan="{{ $b->satuan }}" data-stok="{{ $b->stok }}"
                                data-harga_beli="{{ $b->harga_beli }}" data-harga_jual="{{ $b->harga_jual }}"
                                data-tanggal_masuk="{{ $b->tanggal_masuk }}" data-kategori="{{ $b->kategori }}"
                                {{ old('id_barang') == $b->id_barang ? 'selected' : '' }}>
                                {{ $b->nama_barang }} ({{ $b->id_barang }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_barang')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar" class="form-control"
                        value="{{ old('tanggal_keluar', now()->format('Y-m-d')) }}" required>
                    @error('tanggal_keluar')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stok" class="form-label">Stok Tersedia</label>
                    <input type="number" name="stok" id="stok" class="form-control" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" name="satuan" id="satuan" class="form-control" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                    <input type="number" name="jumlah_keluar" class="form-control" id="jumlah_keluar"
                        value="{{ old('jumlah_keluar') }}" required>
                    @error('jumlah_keluar')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="harga_beli" class="form-label">Harga Beli</label>
                    <input type="number" name="harga_beli" id="harga_beli" class="form-control" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="harga_jual" class="form-label">Harga Jual</label>
                    <input type="number" name="harga_jual" id="harga_jual" class="form-control" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" readonly>
                </div>
            </div>

            <div class="mb-4">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('barangkeluar') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('id_barang').addEventListener('change', function() {
            let selected = this.options[this.selectedIndex];

            document.getElementById('nama_barang').value = selected.dataset.nama || '';
            document.getElementById('stok').value = selected.dataset.stok || '';
            document.getElementById('satuan').value = selected.dataset.satuan || '';
            document.getElementById('harga_beli').value = selected.dataset.harga_beli || '';
            document.getElementById('harga_jual').value = selected.dataset.harga_jual || '';
            document.getElementById('tanggal_masuk').value = selected.dataset.tanggal_masuk || '';
        });

        document.getElementById('jumlah_keluar').addEventListener('input', function() {
            let stok = parseInt(document.getElementById('stok').value) || 0;
            let jumlah = parseInt(this.value);

            if (jumlah > stok) {
                alert('Jumlah keluar tidak boleh melebihi stok tersedia!');
                this.value = stok;
            }
        });
    </script>
@endsection
