@extends('layouts.main')

@section('content')
    <main>
        <style>
            /* Styling DataTables agar tampilan lebih rapi */
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
            <!-- Judul -->
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
            <div class="col-md-12 mb-3">
                <div class="d-flex justify-content-between">
                    <div class="dataTables_length">
                        <label>
                            Kategori
                            <select id="kategoriFilter" class="custom-select form-control">
                                <option value="">Semua</option>
                                <option value="Obat">Obat</option>
                                <option value="Vitamin">Vitamin</option>
                                <option value="Antibiotik">Antibiotik</option>
                                <option value="Alkes">Alkes</option>
                                <option value="Suplemen">Suplemen</option>
                            </select>
                        </label>
                    </div>
                    <div>
                        <a href="{{ route('tambahbarang') }}" class="btn btn-success">Tambah Barang</a>
                        <button type="button" class="btn btn-danger ml-2" onclick="window.print()">Cetak</button>
                    </div>
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
                                    <th>Kode</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->tanggal_masuk }}</td>
                                        <td>{{ $item->tanggal_keluar }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>{{ $item->kuantitas }}</td>
                                        <td>{{ $item->keterangan }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                            
                    </table>
                </div>

                                                            <!-- Modal Konfirmasi Hapus -->
                                                            <div class="modal fade" id="deleteModal-{{ $item->id_barang }}"
                                                                tabindex="-1" aria-labelledby="deleteModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">Konfirmasi Hapus Stok
                                                                                Barang</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span>&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Apakah kamu yakin ingin menghapus
                                                                            <strong>{{ $item->nama_barang }}</strong> dari
                                                                            stok?
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <form
                                                                                action="{{ route('deletestokbarang', ['id_barang' => $item->id_barang]) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-danger">Hapus</button>
                                                                            </form>
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">Batal</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                language: {
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ barang"
                }
            });

            $('#kategoriFilter').on('change', function() {
                var selected = $(this).val();
                table.column(2).search(selected).draw();
            });
        });
    </script>
@endpush
