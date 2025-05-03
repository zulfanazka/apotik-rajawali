<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $totalMasuk = Inventory::sum('stok');
        $totalKeluar = BarangKeluar::sum('stok');
        $totalStokSaatIni = $totalMasuk - $totalKeluar;
        $totalTransaksi = DB::table('barang_keluar')->sum(DB::raw('harga_jual * stok'));

        // Ambil top 5 produk terlaris berdasarkan jumlah stok keluar
        $topProduk = DB::table('barang_keluar')
            ->select('nama_barang', 'harga_jual', DB::raw('SUM(stok) as total_terjual'))
            ->groupBy('nama_barang', 'harga_jual')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalMasuk',
            'totalKeluar',
            'totalStokSaatIni',
            'totalTransaksi',
            'topProduk'
        ));
    }
}
