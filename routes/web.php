<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController; // Asumsi Anda memiliki controller ini
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;

// Rute yang tidak memerlukan autentikasi
Route::get("/", [LoginController::class, 'login'])->name('login');
Route::post('loginaction', [LoginController::class, 'loginaction'])->name('loginaction');

// Grup rute yang memerlukan autentikasi (pengguna harus login)
Route::middleware(['auth'])->group(function () {
    // Rute yang bisa diakses oleh semua peran setelah login (admin & staff)
    Route::get('main', [MainController::class, 'index'])->name('main'); // Jika ada
    Route::post('logoutaction', [LoginController::class, 'logoutaction'])->name('logoutaction');
    Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard');

    // Fungsionalitas inti (melihat daftar) yang Anda minta tidak diubah kodenya
    // Ini tetap bisa diakses oleh staff dan admin karena hanya memerlukan 'auth'
    Route::get('inventory/stokbarang', [InventoryController::class, 'stokBarang'])->name('stokbarang');
    Route::get('inventory/barangmasuk', [InventoryController::class, 'barangMasuk'])->name('barangmasuk');
    Route::get('inventory/barangkeluar', [InventoryController::class, 'barangKeluar'])->name('barangkeluar');
    Route::get('/inventory/laporan', [InventoryController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export/{format}', [InventoryController::class, 'export'])->name('laporan.export'); // Pastikan controller memiliki method ini jika digunakan

    // Grup rute yang hanya bisa diakses oleh ADMIN
    Route::middleware(['role:admin,staff'])->group(function () {
        // Form tambah atau edit barang
        Route::get('inventory/tambahbarang/{id_barang_param?}', [InventoryController::class, 'tambahBarang'])->name('tambahbarang');
        Route::get('inventory/tambahbarangkeluar', [InventoryController::class, 'tambahBarangKeluar'])->name('tambahbarangkeluar');
        Route::get('inventory/editbarang/{id_barang}', [InventoryController::class, 'editBarang'])->name('editbarang');
        Route::get('inventory/editbarangkeluar/{id_barang_keluar_pk}', [InventoryController::class, 'editBarangKeluar'])->name('editbarangkeluar');

        // Simpan data barang (tambah atau update)
        Route::post('inventory/simpanbarang', [InventoryController::class, 'simpanBarang'])->name('simpanbarang');
        Route::post('inventory/simpanbarangkeluar', [InventoryController::class, 'simpanBarangKeluar'])->name('simpanbarangkeluar');
        Route::post('/update-barang', [InventoryController::class, 'updateBarang'])->name('updateBarang');

        // Hapus barang
        Route::delete('inventory/delete/{id_barang}', [InventoryController::class, 'delete'])->name('deletebarang');
        Route::delete('inventory/barangkeluar/delete/{id_barang_keluar_pk}', [InventoryController::class, 'deleteBarangKeluar'])->name('deletebarangkeluar');
        Route::delete('inventory/stokbarang/delete/{id_barang}', [InventoryController::class, 'deleteStokBarang'])->name('deletestokbarang');

        // Contoh jika ada rute manajemen pengguna khusus admin
        // Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    });

    // Jika ada fitur yang bisa diakses Admin DAN Staff secara spesifik (bukan semua yang terautentikasi)
    // Route::middleware(['role:admin,staff'])->group(function () {
    // Route::get('/shared-feature', [SomeController::class, 'index'])->name('shared.feature');
    // });
});