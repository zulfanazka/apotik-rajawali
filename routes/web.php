<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;

Route::get("/", [LoginController::class, 'login'])->name('login');
Route::post('loginaction', [LoginController::class, 'loginaction'])->name('loginaction');
Route::get('main', [MainController::class, 'index'])->name('main')->middleware('auth');
Route::post('logoutaction', [LoginController::class, 'logoutaction'])->name('logoutaction')->middleware('auth');


Route::get('dashboard/index', [DashboardController::class, 'index'])->name('dashboard');

Route::get('inventory/stokbarang', [InventoryController::class, 'stokbarang'])->name('stokbarang');
Route::get('inventory/barangmasuk', [InventoryController::class, 'barangmasuk'])->name('barangmasuk');
Route::get('inventory/barangkeluar', [InventoryController::class, 'barangkeluar'])->name('barangkeluar');
Route::get('inventory/editbarang', [InventoryController::class, 'editbarang'])->name('editbarang');

