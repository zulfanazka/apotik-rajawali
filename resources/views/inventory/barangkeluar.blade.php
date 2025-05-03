@extends('layouts.main')

@section('content')
    <main>
        <div class="container-fluid">
            <h1 class="mt-4 mb-4">Barang Keluar</h1>

            <!-- Filter dan Tombol -->
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <!-- Filter Kategori -->
                    <div>
                        <label>
                            Kategori:
                            <select id="kategoriFilter" class="form-control form-control-sm">
                                <option value="">Semua</option>
                                <option value="Obat">Obat</option>
                                <option value="Vitamin">Vitamin</option>
                                <option value="Antibiotik">Antibiotik</option>
                            </select>
                        </label>
                    </div>

                    <!-- Tombol Tambah dan Cetak -->
                    <div>
                        <a href="{{ route('tambahbarangkeluar') }}" class="btn btn-success">Tambah Barang Keluar</a>
                        <button type="button" class="btn btn-danger mx-2">Cetak</button>
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <div class="col-md-12 mt-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Satuan</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Keluar</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Stok</th>
                                        <th>Jumlah Keluar</th>
                                        <th>Detail</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangKeluar as $item)
                                        <tr>
                                            <td>{{ $item->id_barang }}</td>
                                            <td>{{ $item->inventory->nama_barang ?? '-' }}</td>
                                            <!-- Menggunakan relasi untuk nama_barang -->
                                            <td>{{ $item->inventory->kategori ?? '-' }}</td>
                                            <!-- Menggunakan relasi untuk kategori -->
                                            <td>{{ $item->inventory->satuan ?? '-' }}</td>
                                            <!-- Menggunakan relasi untuk satuan -->
                                            <td>{{ $item->inventory->tanggal_masuk ?? '-' }}</td>
                                            <!-- Menggunakan relasi untuk tanggal masuk -->
                                            <td>{{ $item->tanggal_keluar }}</td>
                                            <td>{{ number_format($item->inventory->harga_beli ?? 0, 0, ',', '.') }}</td>
                                            <!-- Menggunakan relasi untuk harga beli -->
                                            <td>{{ number_format($item->inventory->harga_jual ?? 0, 0, ',', '.') }}</td>
                                            <!-- Menggunakan relasi untuk harga jual -->
                                            <td>{{ $item->inventory->stok ?? 0 }}</td>
                                            <!-- Menggunakan relasi untuk stok -->
                                            <td>{{ $item->jumlah_keluar }}</td>
                                            <td>{{ $item->detail }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>
                                                <a href="{{ route('editbarangkeluar', ['id_barang' => $item->id_barang]) }}"
                                                    class="btn btn-sm btn-success">Edit</a>
                                                <button class="btn btn-sm btn-danger delete-btn"
                                                    data-id="{{ $item->id_barang }}">Hapus</button>

                                                <!-- Modal Konfirmasi Hapus -->
                                                <div class="modal fade" id="deleteModal-{{ $item->id_barang }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Konfirmasi Hapus Barang</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal"><span>&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">Yakin ingin menghapus barang keluar ini?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form
                                                                    action="{{ route('deletebarangkeluar', ['id' => $item->id_barang]) }}"
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
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Script -->
    @push('scripts')
        <script>
            // Tampilkan modal konfirmasi hapus
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.delete-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        const id = this.dataset.id;
                        $('#deleteModal-' + id).modal('show');
                    });
                });

                // Filter kategori di DataTables
                $('#dataTable').DataTable();

                $('#kategoriFilter').on('change', function() {
                    var val = $(this).val();
                    var table = $('#dataTable').DataTable();
                    table.column(2).search(val).draw(); // kolom kategori
                });
            });
        </script>
    @endpush
@endsection
