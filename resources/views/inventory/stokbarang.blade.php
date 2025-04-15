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
    </style>
    
    <div class="container-fluid">
        <!-- Judul Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mt-4">Stok Barang</h1>
        </div>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Rajawali</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penyimpanan > Stok Barang</li>
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
                <a href="{{ route('editbarang') }}" class="btn btn-success">Tambah Barang</a>
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
                                <th>Nama Barang</th>
                                <th>Kode</th>
                                <th>Kategori</th>
                                <th>Kuantitas</th>
                                <th style="width: 120px;">Aksi</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->id_barang }}</td>
                                    <td>{{ $item->kategori }}</td>
                                    <td>{{ $item->kuantitas }}</td>
                                    <td class="d-flex justify-content-end">
                                        <a href="{{ route('editbarang') }}" class="btn btn-success btn-sm p-1">Edit</a>
                                        <button class="btn btn-danger btn-sm p-1 mx-2" onclick="openDeleteModal('{{ $item->id_barang }}')">Hapus</button>
                                        <a href="#" class="btn btn-info btn-sm p-1">Detail</a>
                                    </td>
                                    
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Konfirmasi Hapus -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Anda yakin ingin menghapus barang?</p>
                                <label for="verifyId">Masukkan Kode Barang:</label>
                                <input type="text" id="verifyId" class="form-control" placeholder="Kode Barang" oninput="validateDelete()">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="button" id="deleteButton" class="btn btn-danger" disabled onclick="confirmDelete()">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->
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
