@extends('layouts.main')

@section('content')
    <main>
        <div class="container-fluid">
            <h1 class="mt-4 mb-4">Barang Masuk</h1>

            <!-- Tombol dengan Jarak -->
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <!-- Filter di Kiri -->
                    <div class="dataTables_length" id="dataTable_length">
                        <label>
                            Kategori
                            <select id="kategoriFilter" class="custom-select form-control">
                                <option value="">Semua</option>
                                <option value="Obat">Obat</option>
                                <option value="Vitamin">Vitamin</option>
                                <option value="Antibiotik">Antibiotik</option>
                            </select>
                        </label>
                    </div>

                    <!-- Button di Kanan -->
                    <div>
                        <a href="{{ route('tambahbarang') }}" class="btn btn-success">Tambah barang</a>
                        <button type="button" class="btn btn-danger mx-2">Cetak</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-3">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    {{-- <th>Detail Barang</th> --}}
                                    {{-- <th>Detail Obat</th> --}}
                                    <th>Tanggal Masuk</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>stok</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang as $item)
                                    <tr>
                                        <td>{{ $item->id_barang }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td>{{ $item->satuan }}</td>
                                        <td>{{ $item->tanggal_masuk }}</td>
                                        <td>{{ $item->harga_beli }}</td>
                                        <td>{{ $item->harga_jual }}</td>
                                        <td>{{ $item->stok }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('editbarang', ['id_barang' => $item->id_barang]) }}"
                                                class="btn btn-success">Edit</a>


                                            <!-- Tombol Hapus -->
                                            <button class="btn btn-danger delete-btn" data-id="{{ $item->id_barang }}"
                                                data-toggle="modal"
                                                data-target="#deleteModal-{{ $item->id_barang }}">Hapus</button>

                                            <!-- Modal Konfirmasi Hapus -->
                                            <div class="modal fade" id="deleteModal-{{ $item->id_barang }}" tabindex="-1"
                                                aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus
                                                                Barang</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Anda yakin ingin menghapus barang ini?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('deletebarang', ['id' => $item->id_barang]) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menggunakan delegasi event untuk menangani klik tombol hapus
            document.querySelector('table').addEventListener('click', function(e) {
                if (e.target && e.target.matches('.delete-btn')) {
                    const id = e.target.getAttribute('data-id');
                    // Ganti ID modal dengan ID barang yang tepat
                    $('#deleteModal-' + id).modal('show');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // 1) Inisialisasi DataTable
            var table = $('#dataTable').DataTable({
                // opsi DataTables kamu...
            });

            // 2) Pasang listener ke dropdown kategori
            $('#kategoriFilter').on('change', function() {
                var val = $(this).val(); // ambil nilai dropdown
                // kolom kategori di tabelmu berada di index ke-2 (0-based: 0=Nama,1=ID,2=Kategori)
                table
                    .column(2) // pilih kolom Kategori
                    .search(val) // filter berdasarkan nilai val
                    .draw(); // redraw tabel
            });
        });
    </script>
@endsection
