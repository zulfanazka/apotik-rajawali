@extends('layouts.main')

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<style>
    #laporanExport table {
        border-collapse: collapse;
        width: 100%;
        font-size: 12px;
        color: #000;
    }

    #laporanExport th,
    #laporanExport td {
        border: none;
        padding: 2px 4px;
        text-align: left;
    }

    .judul-cetak {
        text-align: center;
        font-size: 18px;
        margin-bottom: 10px;
        display: none;
    }

    #dataTable th,
    #dataTable td {
        border: 1px solid #ddd !important;
        padding: 18px 22px;
        font-size: 16px;
    }

    #dataTable {
        border-collapse: collapse !important;
        width: 100%;
        border: 1px solid #ddd;
    }


    @media print {
        body * {
            visibility: hidden;

        }

        .judul-cetak {
            display: block !important;
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
                    <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                </ol>
            </nav>

            <div class="d-flex flex-wrap gap-2 mb-3 align-items-end justify-content-between">
                <div>
                    <label>Kategori:
                        <select id="kategoriFilter" class="form-control">
                            <option value="">Semua</option>
                            <option value="Obat">Obat</option>
                            <option value="Vitamin">Vitamin</option>
                            <option value="Antibiotik">Antibiotik</option>
                        </select>
                    </label>
                    <label>Tanggal Masuk:
                        <input type="date" id="tanggalMasukFilter" class="form-control">
                    </label>
                    <label>Tanggal Keluar:
                        <input type="date" id="tanggalKeluarFilter" class="form-control">
                    </label>
                </div>
                <div>
                    <button type="button" class="btn btn-danger ml-2" onclick="printLaporan()">Cetak</button>
                    <button onclick="exportTableToExcel('laporanExport')" class="btn btn-success ml-2">Export Excel</button>
                    <button onclick="exportTableToPDF('laporanExport')" class="btn btn-danger ml-2">Export PDF</button>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-pills mr-1"></i>
                    Laporan Apotik Rajawali
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Stok</th>
                                    <th>Jumlah Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangKeluar as $item)
                                    @php
                                        $hargaJual = $item->inventory->harga_jual ?? 0;
                                        $hargaBeli = $item->inventory->harga_beli ?? 0;
                                        $jumlahKeluar = $item->jumlah_keluar ?? 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->id_barang }}</td>
                                        <td>{{ $item->inventory->nama_barang ?? '-' }}</td>
                                        <td>{{ $item->inventory->kategori ?? '-' }}</td>
                                        <td>{{ $item->inventory->satuan ?? '-' }}</td>
                                        <td>{{ $item->inventory->tanggal_masuk ?? '-' }}</td>
                                        <td>{{ $item->tanggal_keluar }}</td>
                                        <td>{{ number_format($hargaBeli, 0, ',', '.') }}</td>
                                        <td>{{ number_format($hargaJual, 0, ',', '.') }}</td>
                                        <td>{{ $item->inventory->stok ?? 0 }}</td>
                                        <td>{{ $item->jumlah_keluar }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="9" style="text-align: right;">Total Keuntungan:</th>
                                    <td id="totalKeuntunganCell">Rp. 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div id="laporanExport" style="display: none;">
                <h1 class="judul-cetak">LAPORAN APOTIK RAJAWALI</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Keluar</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Jumlah Keluar</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th colspan="9" style="text-align: right;">Total Keuntungan:</th>
                            <td>Rp. 0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        let dataTableInstance;

        function syncFilteredDataToExportTable() {
            const table = dataTableInstance;
            const exportBody = document.querySelector('#laporanExport tbody');
            exportBody.innerHTML = '';

            const exportFooter = document.querySelector('#laporanExport tfoot td');
            let totalKeuntungan = 0;

            table.rows({
                search: 'applied'
            }).every(function() {
                const data = this.data();
                const row = document.createElement('tr');

                data.forEach((cell) => {
                    const td = document.createElement('td');
                    td.innerHTML = cell;
                    row.appendChild(td);
                });

                const hargaBeli = parseInt(data[6].replace(/[^\d]/g, '')) || 0;
                const hargaJual = parseInt(data[7].replace(/[^\d]/g, '')) || 0;
                const jumlahKeluar = parseInt(data[9].replace(/[^\d]/g, '')) || 0;
                const keuntungan = (hargaJual - hargaBeli) * jumlahKeluar;
                totalKeuntungan += keuntungan;

                exportBody.appendChild(row);
            });

            if (exportFooter) {
                exportFooter.innerText = 'Rp. ' + totalKeuntungan.toLocaleString('id-ID');
            }
        }

        function updateTotalKeuntungan() {
            const table = dataTableInstance;
            let totalKeuntungan = 0;

            table.rows({
                search: 'applied'
            }).every(function() {
                const data = this.data();
                const hargaBeli = parseInt(data[6].replace(/[^\d]/g, '')) || 0;
                const hargaJual = parseInt(data[7].replace(/[^\d]/g, '')) || 0;
                const jumlahKeluar = parseInt(data[9].replace(/[^\d]/g, '')) || 0;
                const keuntungan = (hargaJual - hargaBeli) * jumlahKeluar;
                totalKeuntungan += keuntungan;
            });

            const keuntunganCell = document.querySelector('#dataTable tfoot td:last-child');
            if (keuntunganCell) {
                keuntunganCell.innerText = 'Rp. ' + totalKeuntungan.toLocaleString('id-ID');
            }
        }

        function printLaporan() {
            syncFilteredDataToExportTable();
            const laporanElement = document.getElementById('laporanExport');
            laporanElement.style.display = 'block';

            const originalContent = document.body.innerHTML;
            const printContent = laporanElement.outerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

        function exportTableToExcel(exportId) {
            syncFilteredDataToExportTable();
            const exportTable = document.getElementById(exportId).querySelector('table');
            const wb = XLSX.utils.table_to_book(exportTable, {
                sheet: "Laporan"
            });

            // Auto width fix
            const ws = wb.Sheets["Laporan"];
            const range = XLSX.utils.decode_range(ws['!ref']);
            const colWidths = [];

            for (let C = range.s.c; C <= range.e.c; ++C) {
                let maxWidth = 10;
                for (let R = range.s.r; R <= range.e.r; ++R) {
                    const cellAddress = {
                        c: C,
                        r: R
                    };
                    const cellRef = XLSX.utils.encode_cell(cellAddress);
                    const cell = ws[cellRef];
                    if (cell && cell.v) {
                        const cellLength = String(cell.v).length;
                        if (cellLength > maxWidth) maxWidth = cellLength;
                    }
                }
                colWidths.push({
                    wch: maxWidth + 2
                });
            }

            ws['!cols'] = colWidths;

            XLSX.writeFile(wb, "laporan-barang-keluar.xlsx");
        }


        async function exportTableToPDF(exportId) {
            syncFilteredDataToExportTable();
            const {
                jsPDF
            } = window.jspdf;
            const element = document.getElementById(exportId);

            element.style.display = 'block';
            const canvas = await html2canvas(element, {
                scale: 2,
                useCORS: true
            });
            element.style.display = 'none';

            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            if (pdfHeight <= pdf.internal.pageSize.getHeight()) {
                pdf.addImage(imgData, 'PNG', 10, 10, pdfWidth - 20, pdfHeight);
            } else {
                let renderedHeight = 0;
                const pageHeight = pdf.internal.pageSize.getHeight() - 20;
                const pageCanvas = document.createElement('canvas');
                const pageCtx = pageCanvas.getContext('2d');
                const pageHeightInPx = (canvas.width * pageHeight) / (pdfWidth - 20);

                while (renderedHeight < canvas.height) {
                    pageCanvas.width = canvas.width;
                    pageCanvas.height = Math.min(pageHeightInPx, canvas.height - renderedHeight);

                    pageCtx.clearRect(0, 0, pageCanvas.width, pageCanvas.height);
                    pageCtx.drawImage(
                        canvas,
                        0,
                        renderedHeight,
                        canvas.width,
                        pageCanvas.height,
                        0,
                        0,
                        canvas.width,
                        pageCanvas.height
                    );

                    const imgPart = pageCanvas.toDataURL('image/png');
                    if (renderedHeight > 0) pdf.addPage();
                    pdf.addImage(imgPart, 'PNG', 10, 10, pdfWidth - 20, (pageCanvas.height * (pdfWidth - 20)) / canvas
                        .width);

                    renderedHeight += pageHeightInPx;
                }
            }

            pdf.save('laporan-barang-keluar.pdf');
        }

        $(document).ready(function() {
            dataTableInstance = $('#dataTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                responsive: true
            });

            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const kategoriFilter = $('#kategoriFilter').val().toLowerCase();
                const tanggalMasukFilter = $('#tanggalMasukFilter').val();
                const tanggalKeluarFilter = $('#tanggalKeluarFilter').val();

                const kategoriData = data[2].toLowerCase();
                const tanggalMasukData = data[4];
                const tanggalKeluarData = data[5];

                const kategoriMatch = kategoriFilter === "" || kategoriData === kategoriFilter;

                const tanggalMasuk = tanggalMasukData ? new Date(tanggalMasukData) : null;
                const tanggalKeluar = tanggalKeluarData ? new Date(tanggalKeluarData) : null;

                let tanggalMasukMatch = true;
                if (tanggalMasukFilter && tanggalMasuk) {
                    tanggalMasukMatch = tanggalMasuk >= new Date(tanggalMasukFilter);
                }

                let tanggalKeluarMatch = true;
                if (tanggalKeluarFilter && tanggalKeluar) {
                    tanggalKeluarMatch = tanggalKeluar <= new Date(tanggalKeluarFilter);
                }

                return kategoriMatch && tanggalMasukMatch && tanggalKeluarMatch;
            });

            $('#kategoriFilter, #tanggalMasukFilter, #tanggalKeluarFilter').on('change', function() {
                dataTableInstance.draw();
                updateTotalKeuntungan(); // Update keuntungan saat filter berubah
            });

            // Inisialisasi awal
            updateTotalKeuntungan();
        });
    </script>
@endsection
