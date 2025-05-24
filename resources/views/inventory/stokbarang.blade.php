@extends('layouts.main')

@section('content')
    <main>
        <style>
            /* Styling DataTables agar tampilan lebih rapi */
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                /* Akan kita sembunyikan default filter DataTables */
                margin-bottom: 10px;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                margin-top: 10px;
            }

            .dataTables_length select {
            width: auto !important;
            min-width: 50px; /* Atau ubah sesuai kebutuhan */
            padding-right: 25px; /* Memberi ruang untuk panah dropdown */
            }

            .table-bordered {
                border-radius: 8px;
                overflow: hidden;
            }

            .dataTables_wrapper {
                border-radius: 8px;
                overflow: hidden;
            }

            .modal-body .table th {
                width: 35%;
                /* Atur lebar kolom header di modal */
            }

            .filter-form .form-control,
            .filter-form .custom-select {
                margin-right: 10px;
                /* Jarak antar filter */
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

            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('stokbarang') }}" class="filter-form form-inline">
                        <div class="form-row align-items-end">
                            <div class="form-group mb-2 mr-sm-2">
                                <label for="search_query" class="sr-only">Cari...</label>
                                <input type="text" class="form-control form-control-sm" id="search_query"
                                    name="search_query" placeholder="Cari ID/Nama Barang..."
                                    value="{{ request('search_query') }}">
                            </div>
                            <div class="form-group mb-2 mr-sm-2">
                                <label for="kategori_filter" class="sr-only">Kategori</label>
                                <select id="kategori_filter" name="kategori_filter" class="custom-select custom-select-sm">
                                    <option value="">Semua Kategori</option>
                                    {{-- Asumsi $kategoriOptions dikirim dari controller --}}
                                    @foreach ($kategoriOptions ?? [] as $kategoriOption)
                                        <option value="{{ $kategoriOption }}"
                                            {{ request('kategori_filter') == $kategoriOption ? 'selected' : '' }}>
                                            {{ $kategoriOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2 mr-sm-2">
                                <label for="supplier_filter" class="sr-only">Supplier</label>
                                <input type="text" class="form-control form-control-sm" id="supplier_filter"
                                    name="supplier_filter" placeholder="Nama Supplier..."
                                    value="{{ request('supplier_filter') }}">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mb-2">Filter</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-pills mr-1"></i> Data Stok Barang Apotik Rajawali
                    </div>
                    <a href="{{ route('tambahbarang') }}" class="btn btn-success btn-md">Tambah Barang Baru</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Nama Supplier</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Satuan</th>
                                    <th>Harga Jual</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td>{{ $item->id_barang }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>{{ $item->nama_supplier ?? '-' }}</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info mr-1" data-toggle="modal"
                                                data-target="#detailModal-{{ $item->id_barang }}">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                            <a href="{{ route('editbarang', ['id_barang' => $item->id_barang]) }}"
                                                class="btn btn-sm btn-warning mr-1"><i class="fas fa-edit"></i> Edit</a>
                                            <button class="btn btn-sm btn-danger delete-stok-btn"
                                                data-id="{{ $item->id_barang }}" data-nama="{{ $item->nama_barang }}"
                                                data-toggle="modal" data-target="#deleteStokModal-{{ $item->id_barang }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>

                                            {{-- Modal Detail Barang --}}
                                            <div class="modal fade" id="detailModal-{{ $item->id_barang }}" tabindex="-1"
                                                aria-labelledby="detailModalLabel-{{ $item->id_barang }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="detailModalLabel-{{ $item->id_barang }}">Detail Barang:
                                                                {{ $item->nama_barang }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <th>ID Barang</th>
                                                                        <td>{{ $item->id_barang }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Nama Barang</th>
                                                                        <td>{{ $item->nama_barang }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Nama Supplier</th>
                                                                        <td>{{ $item->nama_supplier ?? '-' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Kategori</th>
                                                                        <td>{{ $item->kategori }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Tanggal Masuk</th>
                                                                        <td>{{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : '-' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Tanggal Keluar Terakhir</th>
                                                                        <td>{{ $item->tanggal_keluar ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') : 'Belum ada' }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Harga Beli</th>
                                                                        <td>Rp
                                                                            {{ number_format($item->harga_beli, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Harga Jual</th>
                                                                        <td>Rp
                                                                            {{ number_format($item->harga_jual, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Stok Saat Ini</th>
                                                                        <td>{{ $item->stok }} {{ $item->satuan }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Satuan</th>
                                                                        <td>{{ $item->satuan }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Catatan Tambahan</th>
                                                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Konfirmasi Hapus Stok --}}
                                            <div class="modal fade" id="deleteStokModal-{{ $item->id_barang }}"
                                                tabindex="-1"
                                                aria-labelledby="deleteStokModalLabel-{{ $item->id_barang }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteStokModalLabel-{{ $item->id_barang }}">
                                                                Konfirmasi Hapus Stok Barang</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus stok barang
                                                                <strong>{{ $item->nama_barang }}</strong> (ID:
                                                                {{ $item->id_barang }})?
                                                            </p>
                                                            <p class="text-danger small">Menghapus stok barang akan
                                                                memperbarui catatan pada barang keluar terkait dan menghapus
                                                                item ini dari inventaris.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('deletestokbarang', ['id_barang' => $item->id_barang]) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Ya, Hapus
                                                                    Stok</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data stok barang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{-- Pagination Links dari Laravel --}}
                        <div class="d-flex justify-content-center">
                            {{ $items->appends(request()->query())->links() }} {{-- Mempertahankan query string filter pada link paginasi --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {{-- Pastikan jQuery dimuat sebelum DataTables dan Bootstrap --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // PERIKSA KONSOL BROWSER (F12) UNTUK MELIHAT ERROR JAVASCRIPT JIKA ADA.

            // Inisialisasi DataTables hanya untuk styling, tanpa fitur searching/paging client-side
            $('#dataTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
                "searching": false, // Matikan pencarian default DataTables
                "paging": false, // Matikan paginasi DataTables
                "info": false, // Matikan info DataTables
                "ordering": true, // Anda bisa membiarkan ordering client-side jika mau, atau handle server-side
                "columnDefs": [
                    // Jika ingin kolom aksi tidak bisa diurutkan
                    {
                        "orderable": false,
                        "targets": 7
                    }
                ]
            });

            // JavaScript untuk filter client-side DataTables sebelumnya telah dihapus.
            // Filter sekarang ditangani oleh form GET dan controller di sisi server.

            // Event delegation untuk tombol hapus stok (menggunakan Bootstrap 4 jQuery)
            $('body').on('click', '.delete-stok-btn', function() {
                const id = $(this).data('id');
                $('#deleteStokModal-' + id).modal('show');
            });

            // Fungsi Cetak: Tombol cetak menggunakan onclick="window.print()" standar browser.
            // Jika tidak berfungsi, periksa error JavaScript di konsol browser.
        });
    </script>
@endpush
