@extends('layouts.main')

@push('styles')
    {{-- DataTables CSS untuk Bootstrap 4 (sesuai dengan main.blade.php) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <style>
        /* Gaya tambahan jika diperlukan */
        .filter-form .form-control,
        .filter-form .custom-select {
            margin-right: 10px;
            /* Jarak antar filter */
            margin-bottom: 10px;
            /* Jarak bawah untuk tampilan mobile */
        }

        .dt-buttons .btn {
            /* Jika Anda menambahkan DataTables Buttons nanti */
            margin-left: 0.5rem;
        }

        .card-header i {
            margin-right: 0.25rem;
            /* Konsistensi margin ikon header */
        }

        /* Pastikan tabel bisa scroll jika scrollX true dan kontennya lebar */
        #dataTableBarangKeluar_wrapper .table-responsive {
            overflow-x: auto !important;
            /* Penting untuk scrollX DataTables */
        }
    </style>
@endpush

@section('content')
    <main>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mt-4">Barang Keluar</h1>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Rajawali</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory > Barang Keluar</li>
                </ol>
            </nav>

            {{-- Filter Form --}}
            <div class="card mb-3 no-print">
                <div class="card-body">
                    <form method="GET" action="{{ route('barangkeluar') }}" class="filter-form">
                        <div class="form-row align-items-end">
                            <div class="col-md-4 mb-2">
                                <label for="search_query_keluar" class="sr-only">Cari ID/Nama Barang</label>
                                <input type="text" class="form-control form-control-sm" id="search_query_keluar"
                                    name="search_query_keluar" placeholder="Cari ID/Nama Barang..."
                                    value="{{ request('search_query_keluar') }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="kategori_filter_keluar" class="sr-only">Kategori</label>
                                <select id="kategori_filter_keluar" name="kategori_filter_keluar"
                                    class="custom-select custom-select-sm">
                                    <option value="">Semua Kategori</option>
                                    @php
                                        $kategoriList = $kategoriOptions ?? [
                                            'Obat',
                                            'Vitamin',
                                            'Antibiotik',
                                            'Alkes',
                                            'Suplemen',
                                        ];
                                    @endphp
                                    @foreach ($kategoriList as $kategoriOption)
                                        <option value="{{ $kategoriOption }}"
                                            {{ request('kategori_filter_keluar') == $kategoriOption ? 'selected' : '' }}>
                                            {{ $kategoriOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="supplier_filter_keluar" class="sr-only">Supplier</label>
                                <select id="supplier_filter_keluar" name="supplier_filter_keluar"
                                    class="custom-select custom-select-sm">
                                    <option value="">Semua Supplier</option>
                                    @foreach ($suppliers ?? [] as $supplier)
                                        <option value="{{ $supplier }}"
                                            {{ request('supplier_filter_keluar') == $supplier ? 'selected' : '' }}>
                                            {{ $supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center no-print">
                    <div>
                        <i class="fas fa-table mr-1"></i> Data Barang Keluar Apotik Rajawali
                    </div>
                    <div>
                        <a href="{{ route('tambahbarangkeluar') }}" class="btn btn-success btn-md">Tambah Barang
                            Keluar</a>
                    </div>
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
                    <div class="table-responsive"> {{-- Wrapper ini penting untuk scrollX DataTables --}}
                        <table class="table table-bordered" id="dataTableBarangKeluar" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Jumlah Keluar</th>
                                    <th>Satuan</th>
                                    <th>Detail Status</th>
                                    <th>Stok Sisa</th>
                                    <th>Catatan</th>
                                    <th class="no-print">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barangKeluar as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->id_barang }}</td>
                                        <td>{{ $item->nama_barang ?? ($item->inventory->nama_barang ?? '-') }}</td>
                                        <td>{{ $item->kategori ?? ($item->inventory->kategori ?? '-') }}</td>
                                        <td>{{ $item->inventory->nama_supplier ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') }}</td>
                                        <td>{{ $item->jumlah_keluar }}</td>
                                        <td>{{ $item->satuan ?? ($item->inventory->satuan ?? '-') }}</td>
                                        <td>
                                            @if ($item->detail_obat == 'terjual')
                                                <span class="badge badge-success">Terjual</span>
                                            @elseif($item->detail_obat == 'exp')
                                                <span class="badge badge-danger">Expired</span>
                                            @elseif($item->detail_obat == 'retur')
                                                <span class="badge badge-warning">Retur</span>
                                            @else
                                                {{ $item->detail_obat }}
                                            @endif
                                        </td>
                                        <td>{{ $item->stok }}</td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <td class="no-print">
                                                <a href="{{ route('editbarangkeluar', ['id_barang_keluar_pk' => $item->id]) }}"
                                                    class="btn btn-sm btn-warning mr-1"><i class="fas fa-edit"></i> Edit</a>
                                                <button class="btn btn-sm btn-danger delete-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-nama="{{ $item->nama_barang ?? ($item->inventory->nama_barang ?? $item->id_barang) }}"
                                                    data-toggle="modal" data-target="#deleteModal-{{ $item->id }}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>

                                            <div class="modal fade" id="deleteModal-{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="deleteModalLabel-{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteModalLabel-{{ $item->id }}">Konfirmasi Hapus
                                                                Barang Keluar</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menghapus transaksi barang keluar untuk
                                                            <strong>{{ $item->nama_barang ?? ($item->inventory->nama_barang ?? $item->id_barang) }}</strong>
                                                            (ID Transaksi: {{ $item->id }})
                                                            ? Stok akan dikembalikan.
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('deletebarangkeluar', ['id_barang_keluar_pk' => $item->id]) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Ya,
                                                                    Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">Tidak ada data barang keluar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="d-flex justify-content-center no-print">
                        @if (
                            $barangKeluar instanceof \Illuminate\Pagination\LengthAwarePaginator ||
                                $barangKeluar instanceof \Illuminate\Pagination\Paginator)
                            {{ $barangKeluar->appends(request()->query())->links() }}
                        @endif
                    </div> --}}
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTableBarangKeluar').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
                "searching": false,
                "paging": false,
                "info": false,
                "ordering": true,
                "scrollX": true, // Tambahkan ini untuk mengaktifkan scroll horizontal
                "columnDefs": [{
                    "orderable": false,
                    "targets": 11
                }]
            });

            $('body').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                $('#deleteModal-' + id).modal('show');
            });
        });
    </script>
@endpush
