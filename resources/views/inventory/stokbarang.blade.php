@extends('layouts.main')

@section('content')
    <main>
        <style>
            .dataTables_wrapper .dataTables_length {
                float: left;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_filter {
                float: right;
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_info {
                float: left;
                margin-top: 10px;
            }

            .dataTables_wrapper .dataTables_paginate {
                float: right;
                margin-top: 10px;
            }

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

            .table-bordered {
                border-radius: 8px;
                overflow: hidden;
            }

            .dataTables_wrapper {
                border-radius: 8px;
                overflow: hidden;
            }
        </style>

        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mt-4">Stok Barang</h1>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Rajawali</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory > Stok Barang</li>
                </ol>
            </nav>

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
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Satuan</th>
                                    <th>Detail Obat</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->id_barang }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>{{ $item->tanggal_masuk }}</td>
                                        <td>{{ $item->tanggal_keluar }}</td>
                                        <td>{{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td>{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>{{ $item->jumlah_keluar }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>{{ $item->detail_obat }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modalDetail-{{ $item->id_barang }}">
                                                Detail
                                            </button>

                                            <!-- Modal -->
                                            <div class="modal fade" id="modalDetail-{{ $item->id_barang }}" tabindex="-1"
                                                aria-labelledby="modalLabel-{{ $item->id_barang }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail Barang: {{ $item->nama_barang }}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <th>ID Barang</th>
                                                                    <td>{{ $item->id_barang }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nama Barang</th>
                                                                    <td>{{ $item->nama_barang }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Kategori</th>
                                                                    <td>{{ $item->kategori }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tanggal Masuk</th>
                                                                    <td>{{ $item->tanggal_masuk }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Tanggal Keluar</th>
                                                                    <td>{{ $item->tanggal_keluar ?? '-' }}</td>
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
                                                                <tr>
                                                                    <th>Keterangan</th>
                                                                    <td>{{ $item->keterangan }}</td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="{{ route('editbarang', $item->id_barang) }}"
                                                                class="btn btn-success">Edit</a>
                                                            <a href="{{ route('deletebarang', $item->id_barang) }}"
                                                                class="btn btn-danger"
                                                                onclick="return confirm('Yakin ingin menghapus barang ini?')">Hapus</a>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
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
        $(document).ready(function() {
            let table = $('#dataTable').DataTable({
                language: {
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ barang"
                }
            });

            $('#filterKategori').on('change', function() {
                let value = $(this).val();
                table.column(2).search(value).draw();
            });
        });
    </script>
@endpush
