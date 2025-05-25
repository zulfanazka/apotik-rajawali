@extends('layouts.main')

@push('styles')
    {{-- DataTables CSS untuk Bootstrap 4 (sesuai dengan main.blade.php) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
    <style>
        /* Gaya tambahan jika diperlukan */
        .filter-form .form-control,
        .filter-form .custom-select {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .dt-buttons .btn {
            margin-left: 0.5rem;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .action-buttons .btn {
            margin-bottom: 5px;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .action-buttons {
                display: none !important;
            }

            th.no-print-col,
            td.no-print-col {
                display: none !important;
            }
        }

        /* Styling untuk area import */
        .import-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 0.25rem;
            border: 1px solid #dee2e6;
        }

        #excelPreviewTable th,
        #excelPreviewTable td {
            font-size: 0.85rem;
            padding: 0.4rem;
        }
    </style>
@endpush

@section('content')
    <main>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mt-4">Barang Masuk</h1>
            </div>

            <nav aria-label="breadcrumb" class="no-print">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Rajawali</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory > Barang Masuk</li>
                </ol>
            </nav>

            {{-- Filter Form --}}
            <div class="card mb-3 no-print">
                <div class="card-body">
                    <form method="GET" action="{{ route('barangmasuk') }}" class="filter-form" id="filterFormBarangMasuk">
                        <div class="form-row align-items-end">
                            <div class="col-md-4 mb-2">
                                <label for="search_query_masuk" class="sr-only">Cari ID/Nama Barang</label>
                                <input type="text" class="form-control form-control-sm" id="search_query_masuk"
                                    name="search_query_masuk" placeholder="Cari ID/Nama Barang..."
                                    value="{{ request('search_query_masuk') }}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="kategori_filter_masuk" class="sr-only">Kategori</label>
                                <select id="kategori_filter_masuk" name="kategori_filter_masuk"
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
                                            {{ request('kategori_filter_masuk') == $kategoriOption ? 'selected' : '' }}>
                                            {{ $kategoriOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="supplier_filter_masuk" class="sr-only">Supplier</label>
                                <select id="supplier_filter_masuk" name="supplier_filter_masuk"
                                    class="custom-select custom-select-sm">
                                    <option value="">Semua Supplier</option>
                                    @foreach ($suppliers ?? [] as $supplier)
                                        <option value="{{ $supplier }}"
                                            {{ request('supplier_filter_masuk') == $supplier ? 'selected' : '' }}>
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
                        <i class="fas fa-pills mr-1"></i> Data Barang Masuk Apotik Rajawali
                    </div>
                    <a href="{{ route('tambahbarang') }}" class="btn btn-success btn-md">Tambah Barang Masuk</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success no-print">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger no-print">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableBarangMasuk" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Nama Supplier</th>
                                    <th>Stok Awal</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Satuan</th>
                                    <th>Catatan</th>
                                    <th class="no-print no-print-col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($barang as $item)
                                    <tr>
                                        <td>{{ $item->id_barang }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') }}</td>
                                        <td>{{ $item->nama_supplier ?? '-' }}</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                        <td class="no-print no-print-col action-buttons">
                                            <a href="{{ route('editbarang', ['id_barang' => $item->id_barang]) }}"
                                                class="btn btn-sm btn-warning mr-1"><i class="fas fa-edit"></i> Edit</a>
                                            <button class="btn btn-sm btn-danger delete-btn"
                                                data-id="{{ $item->id_barang }}" data-nama="{{ $item->nama_barang }}"
                                                data-toggle="modal" data-target="#deleteModal-{{ $item->id_barang }}"><i
                                                    class="fas fa-trash"></i> Hapus</button>
                                            {{-- Modal Delete --}}
                                            <div class="modal fade no-print" id="deleteModal-{{ $item->id_barang }}"
                                                tabindex="-1" aria-labelledby="deleteModalLabel-{{ $item->id_barang }}"
                                                aria-hidden="true">
                                                {{-- Konten Modal --}}
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteModalLabel-{{ $item->id_barang }}">Konfirmasi
                                                                Hapus Barang</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Anda yakin ingin menghapus barang
                                                                <strong>{{ $item->nama_barang }}</strong> (ID:
                                                                {{ $item->id_barang }})?
                                                            </p>
                                                            <p class="text-danger small">Perhatian: Menghapus barang ini
                                                                dari daftar barang masuk akan menghapusnya secara permanen
                                                                jika belum ada transaksi keluar terkait.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('deletebarang', ['id_barang' => $item->id_barang]) }}"
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
                                        <td colspan="11" class="text-center">Tidak ada data barang masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center no-print">
                        {{ $barang->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {{-- jQuery, Bootstrap, DataTables --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

    {{-- Library SheetJS (xlsx.js) untuk membaca file Excel --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#dataTableBarangMasuk').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Indonesian.json"
                },
                "searching": false,
                "paging": false,
                "info": false,
                "ordering": true,
                "columnDefs": [{
                    "orderable": false,
                    "targets": 10
                }]
            });

            $('body').on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                $('#deleteModal-' + id).modal('show');
            });

            // --- Script untuk Import Excel Client-Side ---
    //         const excelFileEl = document.getElementById('excelFile');
    //         const processExcelBtnEl = document.getElementById('processExcelBtn');
    //         const previewContainerEl = document.getElementById('excelPreviewContainer');
    //         const previewTableHeadEl = document.querySelector('#excelPreviewTable thead');
    //         const previewTableBodyEl = document.querySelector('#excelPreviewTable tbody');

    //         if (processExcelBtnEl) {
    //             processExcelBtnEl.addEventListener('click', function() {
    //                 if (excelFileEl.files.length === 0) {
    //                     alert('Silakan pilih file Excel terlebih dahulu.');
    //                     return;
    //                 }
    //                 const file = excelFileEl.files[0];
    //                 const reader = new FileReader();

    //                 reader.onload = function(e) {
    //                     const data = new Uint8Array(e.target.result);
    //                     const workbook = XLSX.read(data, {
    //                         type: 'array',
    //                         cellDates: true
    //                     }); // cellDates: true untuk parse tanggal

    //                     // Asumsi data ada di sheet pertama
    //                     const firstSheetName = workbook.SheetNames[0];
    //                     const worksheet = workbook.Sheets[firstSheetName];

    //                     // Konversi sheet ke array of objects (JSON)
    //                     // header: 1 -> baris pertama adalah header
    //                     const jsonData = XLSX.utils.sheet_to_json(worksheet, {
    //                         header: 1
    //                     });

    //                     if (jsonData.length === 0) {
    //                         alert('File Excel kosong atau format tidak sesuai.');
    //                         return;
    //                     }

    //                     displayPreview(jsonData);
    //                 };

    //                 reader.onerror = function(ex) {
    //                     console.error(ex);
    //                     alert('Gagal membaca file: ' + ex.message);
    //                 };

    //                 reader.readAsArrayBuffer(file);
    //             });
    //         }

    //         function displayPreview(data) {
    //             previewTableHeadEl.innerHTML = ''; // Kosongkan header lama
    //             previewTableBodyEl.innerHTML = ''; // Kosongkan body lama

    //             if (data.length > 0) {
    //                 // Buat header tabel dari baris pertama data (asumsi header)
    //                 const headerRow = document.createElement('tr');
    //                 data[0].forEach(headerText => {
    //                     const th = document.createElement('th');
    //                     th.textContent = headerText;
    //                     headerRow.appendChild(th);
    //                 });
    //                 previewTableHeadEl.appendChild(headerRow);

    //                 // Tampilkan maks 10 baris data (tidak termasuk header)
    //                 const maxRowsToShow = 10;
    //                 for (let i = 1; i < data.length && i <= maxRowsToShow; i++) {
    //                     const dataRow = document.createElement('tr');
    //                     data[i].forEach(cellData => {
    //                         const td = document.createElement('td');
    //                         // Format tanggal jika merupakan instance Date
    //                         if (cellData instanceof Date) {
    //                             td.textContent = cellData.toLocaleDateString('id-ID'); // Format dd/mm/yyyy
    //                         } else {
    //                             td.textContent = cellData !== null && cellData !== undefined ? cellData :
    //                                 '';
    //                         }
    //                         dataRow.appendChild(td);
    //                     });
    //                     previewTableBodyEl.appendChild(dataRow);
    //                 }
    //                 previewContainerEl.style.display = 'block';
    //             } else {
    //                 previewContainerEl.style.display = 'none';
    //                 alert('Tidak ada data untuk ditampilkan dari file Excel.');
    //             }
    //         }


    //         // --- Script untuk Export Server-Side (jika masih digunakan) ---
    //         function redirectToExport(format) {
    //             const form = document.getElementById('filterFormBarangMasuk');
    //             const formData = new FormData(form);
    //             const params = new URLSearchParams(formData).toString();


    //             if (exportUrl) {
    //                 window.open(exportUrl + (params ? '?' + params : ''), '_blank');
    //             }
    //         }

    //         $('#exportExcelBtn').on('click', function() {
    //             redirectToExport('excel');
    //         });

    //         $('#exportPdfBtn').on('click', function() {
    //             redirectToExport('pdf');
    //         });
        });
    </script>
@endpush
