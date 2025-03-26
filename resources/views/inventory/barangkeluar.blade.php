@extends('layouts.main')

@section('content')
    <main>
        <div class="container-fluid">
            <h1 class="mt-4 mb-4">Barang keluar</h1>

            <div class="row">
                <div class="col-md-4">
                    <label for="startMonth">Pilih Bulan</label>
                    <input type="month" class="form-control" id="startMonth">
                </div>
                <div class="col-md-4">
                    <label for="startDate">Tanggal Awal</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="col-md-4">
                    <label for="endDate">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
            </div>


            <!-- Tombol dengan Jarak -->
            <!-- <div class="col-md-12">
                        <div class="d-flex justify-content-between"> -->
            <!-- Filter di Kiri -->
            <!-- <div class="dataTables_length" id="dataTable_length">
                                <select name="dataTable_length" aria-controls="dataTable" class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="merkbarang" hidden>Merk barang</option>
                                    <option value="Samsung">Paracetamol</option>
                                    <option value="Apple">OBH Combi</option>
                                    <option value="Xiaomi">Bufadol</option>
                                    <option value="Vivo">Imboost</option>
                                </select>
                            </div> -->

            <!-- Button di Kanan -->
            <!-- <div>
                                <a href="tambahbarang.html"  class="btn btn-success">Tambah barang</a>
                                <button type="button" class="btn btn-danger mx-2">Cetak</button>
                            </div>
                        </div>
                    </div> -->

        </div>

        <!-- <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Static Navigation</li>
                    </ol> -->
        <div class="col-md-12 mt-3">
            <div class="card mb-4">
                <!-- <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            DataTable Example
                        </div> -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Tanggal Keluar </th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <!-- <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Position</th>
                                                <th>Office</th>
                                                <th>Age</th>
                                                <th>Start date</th>
                                                <th>Salary</th>
                                            </tr>
                                        </tfoot> -->
                            <tbody>
                                <tr>
                                    <td>Barang A</td>
                                    <td>
                                        <input type="date" class="form-control" id="tanggal-1"
                                            onchange="updateTanggal(1)">
                                    </td>
                                    <td>
                                        <select class="form-control" id="keterangan-1" onchange="updateKeterangan(1)">
                                            <option value="">Pilih Keterangan</option>
                                            <option value="Exp">Exp</option>
                                            <option value="Terjual">Terjual</option>
                                        </select>
                                        <span id="status-1" class="badge"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Barang B</td>
                                    <td>
                                        <input type="date" class="form-control" id="tanggal-2"
                                            onchange="updateTanggal(2)">
                                    </td>
                                    <td>
                                        <select class="form-control" id="keterangan-2" onchange="updateKeterangan(2)">
                                            <option value="">Pilih Keterangan</option>
                                            <option value="Exp">Exp</option>
                                            <option value="Terjual">Terjual</option>
                                        </select>
                                        <span id="status-2" class="badge"></span>
                                    </td>
                                </tr>
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
