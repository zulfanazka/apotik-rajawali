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
        // Menghitung total stok yang masuk dan keluar
        $totalMasuk = Inventory::sum('stok'); // Total stok masuk
        $totalKeluar = BarangKeluar::sum('jumlah_keluar'); // Total stok keluar
        $totalStokSaatIni = $totalMasuk - $totalKeluar; // Total stok saat ini

        // Menghitung total transaksi dengan mempertimbangkan keuntungan/kerugian
        $totalTransaksi = DB::table('barang_keluar')
            ->select(DB::raw('SUM(
            CASE
                WHEN detail_obat = "terjual" THEN harga_jual * stok
                WHEN detail_obat = "exp" THEN - (harga_beli * stok)
                ELSE 0
            END
        ) as total_transaksi'))
            ->value('total_transaksi');

        // Ambil top 5 produk terlaris berdasarkan jumlah stok keluar dan menghitung keuntungan
        $topProduk = DB::table('barang_keluar')
            ->select('nama_barang', 'harga_jual', 'harga_beli', DB::raw('SUM(stok) as total_terjual'))
            ->groupBy('nama_barang', 'harga_jual', 'harga_beli')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get()
            ->map(function ($produk) {
                // Hitung keuntungan untuk setiap produk
                $produk->keuntungan = ($produk->harga_jual - $produk->harga_beli) * $produk->total_terjual;
                return $produk;
            });

        // Ambil semua barang dari inventory
        $barangMasuk = Inventory::all(); // atau gunakan select('id_barang', 'nama_barang', 'stok')

        return view('dashboard.index', compact(
            'totalMasuk',
            'totalKeluar',
            'totalStokSaatIni',
            'totalTransaksi',
            'topProduk',
            'barangMasuk' // Kirimkan data barang masuk
        ));
    }



}
