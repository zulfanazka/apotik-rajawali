@extends('layouts.main')

@section('content')
    <style>
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 900px;
        }
        .breadcrumb a {
            text-decoration: none;
        }
    </style>

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('barangmasuk') }}" class="fw-bold text-dark">Barang Masuk</a>
                </li>
                <li class="breadcrumb-item active text-primary" aria-current="page">
                    <strong>{{ isset($barang) ? 'Edit Barang' : 'Tambah Barang Baru' }}</strong>
                </li>
            </ol>
        </nav>

        <p class="text-muted">*Semua field wajib diisi kecuali ada keterangan (opsional).</p>

        <form action="{{ isset($barang) ? route('updateBarang', ['id_barang_untuk_routing_jika_perlu' => $barang->id_barang]) : route('simpanbarang') }}" method="POST">
            @csrf
            @if (isset($barang))
                @method('POST')
                <input type="hidden" name="id_barang_hidden_for_update" value="{{ $barang->id_barang }}">
            @endif

            @if (isset($barang))
                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <label for="id_barang_display" class="form-label">ID Barang</label>
                        <input type="text" class="form-control" id="id_barang_display"
                               value="{{ $barang->id_barang }}" readonly
                               title="ID Barang tidak dapat diubah">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" name="nama_barang" id="nama_barang"
                               value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" required>
                        @error('nama_barang')
                            <small class="text-danger">{{ $messages }}</small>
                        @enderror
                    </div>
                </div>
            @else
                 <div class="row mt-3">
                    <div class="col-md-12 mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" name="nama_barang" id="nama_barang"
                               value="{{ old('nama_barang') }}" required>
                        @error('nama_barang')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            @endif


            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-select @error('kategori') is-invalid @enderror" name="kategori" id="kategori" required>
                        <option value="" hidden>- Pilih Kategori -</option>
                        @foreach (['Obat', 'Vitamin', 'Antibiotik', 'Alkes', 'Suplemen'] as $kategoriOption)
                            <option value="{{ $kategoriOption }}"
                                    {{ old('kategori', $barang->kategori ?? '') === $kategoriOption ? 'selected' : '' }}>
                                {{ $kategoriOption }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                 <div class="col-md-6 mb-3">
                    <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                    <select class="form-select @error('satuan') is-invalid @enderror" name="satuan" id="satuan" required>
                        <option value="" hidden>- Pilih Satuan -</option>
                        @php
                            $satuanOptions = ['Botol', 'Kapsul', 'Tablet', 'Sachet', 'Strip', 'Box', 'Tube', 'Pcs', 'Pack'];
                        @endphp
                        @foreach ($satuanOptions as $satuanOption)
                            <option value="{{ $satuanOption }}"
                                    {{ old('satuan', $barang->satuan ?? '') === $satuanOption ? 'selected' : '' }}>
                                {{ $satuanOption }}
                            </option>
                        @endforeach
                    </select>
                    @error('satuan')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal_masuk">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" name="tanggal_masuk" id="tanggal_masuk"
                           value="{{ old('tanggal_masuk', isset($barang->tanggal_masuk) ? \Carbon\Carbon::parse($barang->tanggal_masuk)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                    @error('tanggal_masuk')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nama_supplier" class="form-label">Nama Supplier (Opsional)</label>
                    <input type="text" class="form-control @error('nama_supplier') is-invalid @enderror" name="nama_supplier" id="nama_supplier"
                           value="{{ old('nama_supplier', $barang->nama_supplier ?? '') }}">
                    @error('nama_supplier')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="harga_beli" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" name="harga_beli" id="harga_beli"
                           value="{{ old('harga_beli', $barang->harga_beli ?? '') }}" required min="0" step="any">
                    @error('harga_beli')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" name="harga_jual" id="harga_jual"
                           value="{{ old('harga_jual', $barang->harga_jual ?? '') }}" required min="0" step="any">
                    @error('harga_jual')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                 <div class="col-md-4 mb-3">
                    <label for="stok" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('stok') is-invalid @enderror" name="stok" id="stok"
                           value="{{ old('stok', $barang->stok ?? 0) }}" required min="0">
                    @error('stok')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label for="keterangan" class="form-label">Catatan (Opsional)</label> 
                {{-- Label diubah dari Keterangan menjadi Catatan --}}
                <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" id="keterangan"
                          rows="3">{{ old('keterangan', $barang->keterangan ?? '') }}</textarea>
                @error('keterangan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('barangmasuk') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success">
                    {{ isset($barang) ? 'Update Barang' : 'Simpan Barang Baru' }}
                </button>
            </div>
        </form>
    </div>
@endsection
