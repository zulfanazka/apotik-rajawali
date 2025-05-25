@extends('layouts.main')

@section('content')
    <div class="container my-4 p-4 bg-white rounded shadow">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('barangkeluar') }}" class="text-decoration-none fw-bold text-dark">Barang Keluar</a>
                </li>
                <li class="breadcrumb-item active text-primary" aria-current="page">
                    <strong>{{ isset($barangKeluar) ? 'Edit Barang Keluar' : 'Tambah Barang Keluar' }}</strong>
                </li>
            </ol>
        </nav>
        <hr>

        <form action="{{ route('simpanbarangkeluar') }}" method="POST">
            @csrf
            @if (isset($barangKeluar))
                {{-- Hidden input untuk ID primary key dari barang_keluar saat edit --}}
                <input type="hidden" name="edit_id_barang_keluar" value="{{ $barangKeluar->id }}">
            @endif

            {{-- Pilih barang --}}
            <div class="mb-3">
                <label for="id_barang" class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                <select name="id_barang" id="id_barang" class="form-select @error('id_barang') is-invalid @enderror" required {{ isset($barangKeluar) ? 'disabled' : '' }}>
                    <option value="" hidden>-- Pilih Barang dari Stok --</option>
                    @foreach ($barangMasuk as $b)
                        <option value="{{ $b->id_barang }}"
                                data-nama="{{ $b->nama_barang }}"
                                data-stok="{{ $b->stok }}"
                                data-satuan="{{ $b->satuan }}"
                                data-kategori="{{ $b->kategori }}"
                                data-harga_beli="{{ $b->harga_beli }}"
                                data-harga_jual="{{ $b->harga_jual }}"
                                data-tanggal_masuk="{{ $b->tanggal_masuk ? \Carbon\Carbon::parse($b->tanggal_masuk)->format('d-m-Y') : '' }}"
                                data-nama_supplier="{{ $b->nama_supplier ?? '' }}"
                                {{ old('id_barang', $barangKeluar->id_barang ?? '') == $b->id_barang ? 'selected' : '' }}>
                            {{ $b->nama_barang }} (ID: {{ $b->id_barang }}) - Stok: {{ $b->stok }}
                        </option>
                    @endforeach
                </select>
                @if(isset($barangKeluar))
                     <input type="hidden" name="id_barang" value="{{ $barangKeluar->id_barang }}">
                     <small class="form-text text-muted">Barang tidak dapat diubah saat mode edit. Hapus dan buat baru jika ingin mengganti barang.</small>
                @endif
                @error('id_barang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" id="nama_barang_display" class="form-control" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" id="kategori_display" class="form-control" readonly>
                </div>
            </div>
             <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Stok Tersedia</label>
                    <input type="number" id="stok_display" class="form-control" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" id="satuan_display" class="form-control" readonly>
                </div>
                 <div class="col-md-4 mb-3">
                    <label class="form-label">Nama Supplier</label>
                    <input type="text" id="nama_supplier_display" class="form-control" readonly>
                </div>
            </div>


            {{-- Tanggal keluar --}}
            <div class="mb-3">
                <label for="tanggal_keluar" class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_keluar" id="tanggal_keluar" class="form-control @error('tanggal_keluar') is-invalid @enderror"
                       value="{{ old('tanggal_keluar', isset($barangKeluar->tanggal_keluar) ? \Carbon\Carbon::parse($barangKeluar->tanggal_keluar)->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                @error('tanggal_keluar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Jumlah Keluar --}}
            <div class="mb-3">
                <label for="jumlah_keluar" class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                <input type="number" name="jumlah_keluar" id="jumlah_keluar" class="form-control @error('jumlah_keluar') is-invalid @enderror"
                       value="{{ old('jumlah_keluar', $barangKeluar->jumlah_keluar ?? '') }}" required min="1">
                @error('jumlah_keluar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                 <small id="stokWarning" class="text-danger d-none">Jumlah keluar melebihi stok yang tersedia!</small>
            </div>

            {{-- Detail (terjual/exp/retur) --}}
            <div class="mb-3">
                <label for="detail_obat" class="form-label">Detail Status <span class="text-danger">*</span></label>
                <select name="detail_obat" id="detail_obat" class="form-select @error('detail_obat') is-invalid @enderror" required>
                    <option value="terjual" {{ old('detail_obat', $barangKeluar->detail_obat ?? '') == 'terjual' ? 'selected' : '' }}>Terjual</option>
                    <option value="exp" {{ old('detail_obat', $barangKeluar->detail_obat ?? '') == 'exp' ? 'selected' : '' }}>Expired</option>
                    <option value="retur" {{ old('detail_obat', $barangKeluar->detail_obat ?? '') == 'retur' ? 'selected' : '' }}>Retur Supplier</option>
                </select>
                @error('detail_obat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Keterangan --}}
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                <textarea name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror"
                          rows="3">{{ old('keterangan', $barangKeluar->keterangan ?? '') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('barangkeluar') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success">
                     {{ isset($barangKeluar) ? 'Update Data Keluar' : 'Simpan Data Keluar' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectBarang = document.getElementById('id_barang');
            const namaBarangDisplay = document.getElementById('nama_barang_display');
            const stokDisplay = document.getElementById('stok_display');
            const satuanDisplay = document.getElementById('satuan_display');
            const kategoriDisplay = document.getElementById('kategori_display');
            const namaSupplierDisplay = document.getElementById('nama_supplier_display');
            const jumlahKeluarInput = document.getElementById('jumlah_keluar');
            const stokWarning = document.getElementById('stokWarning');

            function updateDisplayFields() {
                let selectedOption = selectBarang.options[selectBarang.selectedIndex];
                if (selectedOption && selectedOption.value !== "") {
                    namaBarangDisplay.value = selectedOption.dataset.nama || '';
                    stokDisplay.value = selectedOption.dataset.stok || '0';
                    satuanDisplay.value = selectedOption.dataset.satuan || '';
                    kategoriDisplay.value = selectedOption.dataset.kategori || '';
                    namaSupplierDisplay.value = selectedOption.dataset.nama_supplier || '-';
                    jumlahKeluarInput.max = selectedOption.dataset.stok || '0'; // Set max for input
                } else {
                    namaBarangDisplay.value = '';
                    stokDisplay.value = '';
                    satuanDisplay.value = '';
                    kategoriDisplay.value = '';
                    namaSupplierDisplay.value = '';
                    jumlahKeluarInput.max = '';
                }
                validateJumlahKeluar(); // Validasi saat field berubah
            }

            function validateJumlahKeluar() {
                const stokTersedia = parseInt(stokDisplay.value) || 0;
                const jumlahDiminta = parseInt(jumlahKeluarInput.value) || 0;

                if (jumlahDiminta > stokTersedia) {
                    stokWarning.classList.remove('d-none');
                    jumlahKeluarInput.classList.add('is-invalid');
                } else {
                    stokWarning.classList.add('d-none');
                    jumlahKeluarInput.classList.remove('is-invalid');
                }
            }

            if (selectBarang) {
                selectBarang.addEventListener('change', updateDisplayFields);
                // Panggil sekali saat load jika ada value terpilih (untuk mode edit)
                if (selectBarang.value) {
                    updateDisplayFields();
                }
            }

            if(jumlahKeluarInput) {
                jumlahKeluarInput.addEventListener('input', validateJumlahKeluar);
            }
        });
    </script>
@endsection
