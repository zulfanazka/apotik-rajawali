<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;

Route::get("/", [LoginController::class, 'login'])->name('login');
Route::post('loginaction', [LoginController::class,'loginaction'])->name('loginaction');
Route::get("home", [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::post('logoutaction', [LoginController::class,'logoutaction'])->name('logoutaction')->middleware('auth');


