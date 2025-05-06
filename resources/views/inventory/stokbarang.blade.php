@extends('layouts.main')

@section('content')
<main>
    <style>
        /* Atur bagian atas: Show entries & Search */
        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-bottom: 10px;
        }
    
        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-bottom: 10px;
        }
    
        /* Atur bagian bawah: Info & Pagination */
        .dataTables_wrapper .dataTables_info {
            float: left;
            margin-top: 10px;
        }
    
        .dataTables_wrapper .dataTables_paginate {
            float: right;
            margin-top: 10px;
        }
    
        /* Biar layout lebih clean saat responsive */
        .dataTables_wrapper .row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
        }
    
        .dataTables_wrapper .col-sm-12,
        .dataTables_wrapper .col-sm-6 {
            flex: 1 1 auto;
            padding: 0 !important;
        }
        /* Menambahkan border-radius pada tabel */
.table-bordered {
    border-radius: 8px; /* Sesuaikan nilai sesuai dengan kebutuhan */
    overflow: hidden;   /* Agar sudutnya tetap rapi */
}

/* Jika ingin menambahkan border-radius pada kontainer wrapper */
.dataTables_wrapper {
    border-radius: 8px; /* Sesuaikan nilai sesuai dengan kebutuhan */
    overflow: hidden;   /* Menjaga agar kontainer tetap terkelola dengan baik */
}

    </style>
    
    <div class="container-fluid">
        <!-- Judul Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mt-4">Stok Barang</h1>
        </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Rajawali</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory > Stok Barang</li>
                </ol>
            </nav>

        <!-- Filter & Tombol -->
        <div class="d-flex justify-content-between mb-3">
            <div>
                <select class="form-control" id="filterKategori">
                    <option value="">-- Semua Kategori --</option>
                    <option value="Obat">Obat</option>
                    <option value="Vitamin">Vitamin</option>
                    <option value="Antibiotik">Antibiotik</option>
                    <option value="Alkes">Alkes</option>
                    <option value="Suplemen">Suplemen</option>
                </select>
            </div>
            <div>
                <a href="{{ route('tambahbarang') }}" class="btn btn-success">Tambah Barang</a>
                <button type="button" class="btn btn-danger ml-2" onclick="window.print()">Cetak</button>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-pills mr-1"></i>
                Data Stok Barang Apotik Rajawali
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>ID Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Satuan</th>
                                <th>Keterangan</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->id_barang }}</td>
                                    <td>{{ $item->kategori }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td>{{ $item->satuan }}</td>
                                    <td>{{ $item->keterangan }}</td>
                                    <td>
                                        <!-- Tombol untuk membuka modal dengan data item -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal-{{ $item->id_barang }}">
                                            Detail
                                        </button>
                                    
                                        <!-- Modal untuk setiap barang -->
                                        <div class="modal fade" id="exampleModal-{{ $item->id_barang }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Detail Barang: {{ $item->nama_barang }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Tabel di dalam Modal untuk menampilkan detail item -->
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Tanggal Masuk</th>
                                                                    <td>{{ $item->tanggal_masuk }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tanggal Keluar</th>
                                                                    <td>{{ $item->tanggal_keluar }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Harga Beli</th>
                                                                    <td>{{ number_format($item->harga_beli, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Harga Jual</th>
                                                                    <td>{{ number_format($item->harga_jual, 0, ',', '.') }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Stok</th>
                                                                    <td>{{ $item->stok }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Jumlah Keluar</th>
                                                                    <td>{{ $item->jumlah_keluar ?? 0 }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Satuan</th>
                                                                    <td>{{ $item->satuan }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Detail Obat</th>
                                                                    <td>{{ $item->detail_obat }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <!-- Tombol di dalam Modal -->
                                                        <a href="{{ route('editbarang', ['id_barang' => $item->id_barang]) }}" class="btn btn-success">Edit</a>
                                                        <!-- Tombol trigger modal -->
<button class="btn btn-sm btn-danger" data-toggle="modal"
data-target="#deleteModal-{{ $item->id_barang }}">
Hapus
</button>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="deleteModal-{{ $item->id_barang }}" tabindex="-1"
aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus Stok Barang</h5>
        <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
            <span>&times;</span>
        </button>
    </div>
    <div class="modal-body">
        Apakah kamu yakin ingin menghapus <strong>{{ $item->nama_barang }}</strong> dari stok?
    </div>
    <div class="modal-footer">
        <form action="{{ route('deletestokbarang', ['id_barang' => $item->id_barang]) }}"
              method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
    </div>
</div>
</div>
</div>

                                                      
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                        
                            
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script>
    let selectedId = '';

    $(document).ready(function () {
        let table = $('#dataTable').DataTable({
            language: {
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ barang"
            }
        });

        $('#filterKategori').on('change', function () {
            let value = $(this).val();
            table.column(2).search(value).draw();
        });
    });

    function openDeleteModal(id) {
        selectedId = id;
        $("#verifyId").val('');
        $("#deleteButton").prop('disabled', true);
        $("#deleteModal").modal("show");
    }

    function validateDelete() {
        let inputId = document.getElementById("verifyId").value.trim();
        document.getElementById("deleteButton").disabled = (inputId !== selectedId);
    }

    function confirmDelete() {
        alert("Barang dengan Kode " + selectedId + " berhasil dihapus.");
        $("#deleteModal").modal("hide");
        // Implement AJAX delete logic if needed
    }
</script>
@endpush
