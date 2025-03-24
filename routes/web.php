<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

Route::get("/", [LoginController::class, 'login'])->name('login');
Route::post('loginaction', [LoginController::class, 'loginaction'])->name('loginaction');
Route::get("home", [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::post('logoutaction', [LoginController::class, 'logoutaction'])->name('logoutaction')->middleware('auth');

// Route::get('/dashboard', [DashboardController::class, 'index']);

// Route::get('/dashboard', function () {
//     return view('main.dashboard');
// })->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

