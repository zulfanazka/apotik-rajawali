@extends('layouts.main')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<style>
    #dataTable {
        font-size: 16px;
        border-collapse: collapse;
        width: 100%;
    }

    #dataTable th,
    #dataTable td {
        padding: 6px 10px;
        text-align: left;
        vertical-align: middle;
        border: 1px solid #dee2e6;
    }

    #dataTable th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    tfoot tr td {
        font-weight: bold;
        background-color: #f1f1f1;
    }

    .dataTables_length select {
        width: auto !important;
        min-width: 50px; /* Atau ubah sesuai kebutuhan */
        padding-right: 25px; /* Memberi ruang untuk panah dropdown */
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #laporanExport,
        #laporanExport * {
            visibility: visible;
        }

        #laporanExport {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .breadcrumb,
        .btn,
        .dataTables_wrapper,
        nav,
        header,
        footer {
            display: none !important;
        }
    }
</style>

@section('content')
    <main>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="mt-4">Laporan</h1>
            </div>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Rajawali</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inventory > Laporan</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap gap-2 mb-3 align-items-end justify-content-between">
                <div>
                    <label>Kategori:
                        <select id="kategoriFilter" class="form-control form-control-sm">
                            <option value="">Semua</option>
                            <option value="obat">Obat</option>
                            <option value="vitamin">Vitamin</option>
                            <option value="antibiotik">Antibiotik</option>
                            <option value="suplemen">Suplemen</option>
                        </select>
                    </label>
                    <label>Supplier:
                        <select id="supplierFilter" class="form-control form-control-sm">
                            <option value="">Semua</option>
                            @php
                                $suppliers = collect($barangKeluar)
                                    ->pluck('inventory.nama_supplier')
                                    ->unique()
                                    ->filter()
                                    ->sort();
                            @endphp
                            @foreach ($suppliers as $supplier)
                                <option value="{{ strtolower($supplier) }}">{{ $supplier }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>Tanggal Masuk:
                        <input type="date" id="tanggalMasukFilter" class="form-control form-control-sm">
                    </label>
                    <label>Tanggal Keluar:
                        <input type="date" id="tanggalKeluarFilter" class="form-control form-control-sm">
                    </label>
                </div>
                <div>
                    <button onclick="exportTableToExcel()" class="btn btn-success ml-2">Export Excel</button>
                    <button onclick="exportTableToPDF()" class="btn btn-danger ml-2">Export PDF</button>
                </div>
            </div>

            <div class="card mb-4" id="laporanExport">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="display nowrap table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Tgl Masuk Inv.</th>
                                    <th>Tgl Keluar</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Jml Keluar</th>
                                    <th>Satuan</th>
                                    <th>Status Keluar</th>
                                    <th>Keuntungan</th>
                                    <th>Kerugian</th>
                                </tr>
                            </thead>
                            @if (Auth::check() && Auth::user()->role === 'admin')
                                <tbody>
                                    @foreach ($barangKeluar as $item)
                                        <tr>
                                            <td>{{ $item->id_barang }}</td>
                                            <td>{{ $item->nama_barang ?? ($item->inventory->nama_barang ?? '-') }}</td>
                                            <td>{{ $item->kategori ?? ($item->inventory->kategori ?? '-') }}</td>
                                            <td>{{ $item->inventory->nama_supplier ?? '-' }}</td>
                                            <td>{{ $item->tanggal_masuk ? \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') : ($item->inventory->tanggal_masuk ? \Carbon\Carbon::parse($item->inventory->tanggal_masuk)->format('d-m-Y') : '-') }}
                                            </td>
                                            <td>{{ $item->tanggal_keluar ? \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') : '-' }}
                                            </td>
                                            <td>Rp {{ number_format($item->harga_beli ?? 0, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->harga_jual ?? 0, 0, ',', '.') }}</td>
                                            <td>{{ $item->jumlah_keluar }}</td>
                                            <td>{{ $item->satuan ?? ($item->inventory->satuan ?? '-') }}</td>
                                            <td>{{ ucfirst($item->detail_obat) }}</td>
                                            <td>Rp {{ number_format($item->keuntungan ?? 0, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->kerugian ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="11" style="text-align: right;">Total Keuntungan:</td>
                                        <td id="totalKeuntungan">Rp 0</td>
                                        <td id="totalKerugian">Rp 0</td>
                                    </tr>
                                    <tr>
                                        <td colspan="11" style="text-align: right;">Laba Bersih:</td>
                                        <td colspan="2" id="labaBersih">Rp 0</td>
                                    </tr>
                                </tfoot>
                            @else
                                <span>Data ditampilkan hanya untuk owner</span>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        let table;

        $(document).ready(function() {
            table = $('#dataTable').DataTable({
                responsive: true
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const kategori = $('#kategoriFilter').val().toLowerCase();
                const tanggalMasuk = $('#tanggalMasukFilter').val();
                const tanggalKeluar = $('#tanggalKeluarFilter').val();
                const supplier = $('#supplierFilter').val().toLowerCase();

                const dataKategori = data[2]?.toLowerCase() || '';
                const dataMasuk = parseTanggalIndo(data[4]);
                const dataKeluar = parseTanggalIndo(data[5]);
                const dataSupplier = data[3]?.toLowerCase() || '';

                let kategoriMatch = !kategori || dataKategori === kategori;
                let masukMatch = !tanggalMasuk || (dataMasuk && dataMasuk >= new Date(tanggalMasuk));
                let keluarMatch = !tanggalKeluar || (dataKeluar && dataKeluar <= new Date(tanggalKeluar));
                let supplierMatch = !supplier || dataSupplier === supplier;

                return kategoriMatch && masukMatch && keluarMatch && supplierMatch;
            });

            $('#kategoriFilter, #tanggalMasukFilter, #tanggalKeluarFilter, #supplierFilter').on('change',
                function() {
                    table.draw();
                    updateTotals();
                });

            updateTotals();
        });

        function parseTanggalIndo(str) {
            const parts = str.split("-");
            if (parts.length !== 3) return null;
            return new Date(`${parts[2]}-${parts[1]}-${parts[0]}`);
        }

        function updateTotals() {
            let totalKeuntungan = 0;
            let totalKerugian = 0;

            table.rows({
                search: 'applied'
            }).every(function() {
                const row = this.node();
                const keuntunganText = $(row).find('td').eq(11).text().replace(/[^\d]/g, '') || '0';
                const kerugianText = $(row).find('td').eq(12).text().replace(/[^\d]/g, '') || '0';
                totalKeuntungan += parseInt(keuntunganText);
                totalKerugian += parseInt(kerugianText);
            });

            const labaBersih = totalKeuntungan - totalKerugian;

            $('#totalKeuntungan').text(formatRupiah(totalKeuntungan));
            $('#totalKerugian').text(formatRupiah(totalKerugian));
            $('#labaBersih').text(formatRupiah(labaBersih));
        }

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function getFilteredTable() {
            const tableClone = document.createElement('table');
            tableClone.style.borderCollapse = 'collapse';
            tableClone.style.fontSize = '14px';
            tableClone.style.width = '100%';

            const thead = $('#dataTable thead').clone();
            const tfoot = $('#dataTable tfoot').clone();
            const tbody = $('<tbody></tbody>');

            table.rows({
                search: 'applied'
            }).every(function() {
                const rowNode = this.node().cloneNode(true);
                tbody.append(rowNode);
            });

            $(tableClone).append(thead);
            $(tableClone).append(tbody);
            $(tableClone).append(tfoot);
            return tableClone.outerHTML;
        }

        function printLaporan() {
            const printContents = getFilteredTable();
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function exportTableToExcel() {
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.table_to_sheet(document.querySelector('#dataTable'));
            XLSX.utils.book_append_sheet(wb, ws, "Laporan");
            XLSX.writeFile(wb, "laporan-inventory.xlsx");
        }

        async function exportTableToPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF('landscape');

            const exportContainer = document.createElement('div');
            exportContainer.style.padding = '20px';
            exportContainer.style.fontSize = '14px';
            exportContainer.style.width = '100%';

            const logoImg = new Image();
            logoImg.src = "{{ asset('storage/logo/logoraja.jpg') }}";
            logoImg.style.height = '50px';
            logoImg.style.marginBottom = '10px';
            exportContainer.appendChild(logoImg);

            const title = document.createElement('h3');
            title.textContent = "Laporan Data Barang";
            title.style.marginBottom = '10px';
            exportContainer.appendChild(title);

            const filteredTableHtml = getFilteredTable();
            exportContainer.innerHTML += filteredTableHtml;

            document.body.appendChild(exportContainer);

            await html2canvas(exportContainer).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('laporan-inventory.pdf');
            });

            document.body.removeChild(exportContainer);
        }
    </script>
@endsection
