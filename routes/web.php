<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Models\Inventory;

Route::get("/", [LoginController::class, 'login'])->name('login');
Route::post('loginaction', [LoginController::class, 'loginaction'])->name('loginaction');
Route::get('main', [MainController::class, 'index'])->name('main')->middleware('auth');
Route::post('logoutaction', [LoginController::class, 'logoutaction'])->name('logoutaction')->middleware('auth');

// Dashboard
Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard');

// Routes Inventory
Route::get('inventory/stokbarang', [InventoryController::class, 'stokBarang'])->name('stokbarang');
Route::get('inventory/barangmasuk', [InventoryController::class, 'barangMasuk'])->name('barangmasuk');
Route::get('inventory/barangkeluar', [InventoryController::class, 'barangKeluar'])->name('barangkeluar');

// Form untuk tambah atau edit barang
Route::get('inventory/tambahbarang', [InventoryController::class, 'tambahBarang'])->name('tambahbarang');
Route::get('inventory/editbarang/{id_barang}', [InventoryController::class, 'editBarang'])->name('editbarang');


// Simpan data barang (tambah atau update)
Route::post('inventory/simpanbarang', [InventoryController::class, 'simpanBarang'])->name('simpanbarang');

// Hapus barang
Route::delete('inventory/{id}', [InventoryController::class, 'delete'])->name('deletebarang');

Route::post('/update-barang', [InventoryController::class, 'updateBarang'])->name('updateBarang');