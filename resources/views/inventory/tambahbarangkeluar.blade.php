@extends('layouts.main')

@section('content')
    <div class="container my-4 p-4 bg-white rounded">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('barangkeluar') }}">Barang Keluar</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ isset($barangKeluar) ? 'Edit Barang Keluar' : 'Tambah Barang Keluar' }}
                </li>
            </ol>
        </nav>

        <form action="{{ route('simpanbarangkeluar') }}" method="POST">
            @csrf
            @if (isset($barangKeluar))
                {{-- flag untuk update --}}
                <input type="hidden" name="edit" value="{{ $barangKeluar->id }}">
            @endif

            {{-- Pilih barang --}}
            <div class="mb-3">
                <label for="id_barang" class="form-label">Pilih Barang</label>
                <select name="id_barang" id="id_barang" class="form-select" required>
                    <option value="" hidden>-- Pilih --</option>
                    @foreach ($barangMasuk as $b)
                        <option value="{{ $b->id_barang }}" data-nama="{{ $b->nama_barang }}"
                            data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}"
                            data-kategori="{{ $b->kategori }}" data-harga_beli="{{ $b->harga_beli }}"
                            data-harga_jual="{{ $b->harga_jual }}" data-tanggal_masuk="{{ $b->tanggal_masuk }}"
                            {{ old('id_barang', $barangKeluar->id_barang ?? '') == $b->id_barang ? 'selected' : '' }}>
                            {{ $b->nama_barang }} ({{ $b->id_barang }})
                        </option>
                    @endforeach
                </select>
                @error('id_barang')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tanggal keluar --}}
            <div class="mb-3">
                <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control"
                    value="{{ old('tanggal_keluar', $barangKeluar->tanggal_keluar ?? now()->format('Y-m-d')) }}" required>
                @error('tanggal_keluar')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                {{-- Nama Barang --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" id="nama_barang" class="form-control" readonly>
                </div>
                {{-- Stok --}}
                <div class="col-md-2 mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" id="stok" class="form-control" readonly>
                </div>
                {{-- Satuan --}}
                <div class="col-md-2 mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" id="satuan" class="form-control" readonly>
                </div>
                {{-- Kategori --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" id="kategori" class="form-control" readonly>
                </div>
            </div>

            {{-- Jumlah Keluar --}}
            <div class="mb-3">
                <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control"
                    value="{{ old('jumlah_keluar', $barangKeluar->jumlah_keluar ?? '') }}" required>
                @error('jumlah_keluar')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Detail (terjual/exp) --}}
            <div class="mb-3">
                <label for="detail_obat" class="form-label">Detail</label>
                <select name="detail_obat" id="detail_obat" class="form-select" required>
                    <option value="terjual"
                        {{ old('detail_obat', $barangKeluar->detail_obat ?? '') == 'terjual' ? 'selected' : '' }}>
                        Terjual
                    </option>
                    <option value="exp"
                        {{ old('detail_obat', $barangKeluar->detail_obat ?? '') == 'exp' ? 'selected' : '' }}>
                        Exp
                    </option>
                </select>
                @error('detail_obat')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $barangKeluar->keterangan ?? '') }}</textarea>
                @error('keterangan')
                    <div class="text-danger">{{ $message }}</div>
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
            let opt = this.options[this.selectedIndex];
            document.getElementById('nama_barang').value = opt.dataset.nama || '';
            document.getElementById('stok').value = opt.dataset.stok || '';
            document.getElementById('satuan').value = opt.dataset.satuan || '';
            document.getElementById('kategori').value = opt.dataset.kategori || '';
        });

        document.getElementById('jumlah_keluar').addEventListener('input', function() {
            let s = parseInt(document.getElementById('stok').value) || 0,
                v = parseInt(this.value) || 0;
            if (v > s) {
                alert('Melebihi stok!');
                this.value = s;
            }
        });

        // Trigger sekali untuk populate saat edit
        window.addEventListener('DOMContentLoaded', function() {
            let sel = document.getElementById('id_barang');
            if (sel.value) sel.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
