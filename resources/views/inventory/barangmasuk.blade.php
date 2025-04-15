@extends('layouts.main')

@section('content')
<main>
    <div class="container-fluid">
        <h1 class="mt-4 mb-4">Stok Barang</h1>

        <!-- Tombol dengan Jarak -->
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <!-- Filter di Kiri -->
                <div class="dataTables_length" id="dataTable_length">
                    <select name="dataTable_length" aria-controls="dataTable"
                        class="custom-select custom-select-sm form-control form-control-sm">
                        <option value="merkbarang" hidden>Merk barang</option>
                        <option value="Samsung">Paracetamol</option>
                        <option value="Apple">OBH Combi</option>
                        <option value="Xiaomi">Bufadol</option>
                        <option value="Vivo">Imboost</option>
                    </select>
                </div>

                <!-- Button di Kanan -->
                <div>
                    <a href="{{ route('editbarang') }}" class="btn btn-success">Tambah barang</a>
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
                                <th>Nama Barang</th>
                                <th>ID Barang</th>
                                <th>Merk Barang</th>
                                <th>Stok Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $item)  <!-- Menampilkan data inventory -->
                                <tr>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->id_barang }}</td>
                                    <td>{{ $item->merk_barang }}</td>
                                    <td>{{ $item->stok_barang }}</td>
                                    <td>
                                        <a href="editbarang.html" class="btn btn-success">Edit</a>
                                        <button class="btn btn-danger" onclick="openDeleteModal()">Hapus</button>
                                        <!-- Modal Konfirmasi -->
                                        <div class="modal fade" id="deleteModal" tabindex="-1"
                                            aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Anda yakin ingin menghapus barang?</p>
                                                        <label for="verifyId">Masukkan ID Barang:</label>
                                                        <input type="text" id="verifyId" class="form-control"
                                                            placeholder="ID Barang" oninput="validateDelete()">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="button" id="deleteButton" class="btn btn-danger"
                                                            disabled onclick="confirmDelete()">Hapus</button>
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

<script>
    function openDeleteModal() {
        $("#deleteModal").modal("show");
    }

    function validateDelete() {
        let inputId = document.getElementById("verifyId").value;
        document.getElementById("deleteButton").disabled = inputId.trim() === "";
    }

    function confirmDelete() {
        let inputId = document.getElementById("verifyId").value;
        alert("Barang dengan ID " + inputId + " berhasil dihapus");
        $("#deleteModal").modal("hide");
    }
</script>
@endsection
