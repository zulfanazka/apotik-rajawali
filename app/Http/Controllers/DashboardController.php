<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\BarangKeluar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total stok saat ini adalah jumlah stok semua barang
        $totalStokSaatIni = Inventory::sum('stok');

        // Total barang keluar dan keuntungan/kerugian
        $totalKeluar = BarangKeluar::sum('jumlah_keluar');
        $totalKeuntunganDariKeluar = BarangKeluar::sum('keuntungan');
        $totalKerugianDariKeluar = BarangKeluar::sum('kerugian');
        $totalTransaksi = $totalKeuntunganDariKeluar - $totalKerugianDariKeluar;

        // Top 10 produk berdasarkan jumlah keluar
        $topProduk = DB::table('barang_keluar')
            ->select(
                'nama_barang',
                DB::raw('SUM(jumlah_keluar) as total_terjual_produk'),
                DB::raw('SUM(keuntungan) as total_keuntungan_produk')
            )
            ->groupBy('nama_barang')
            ->orderByDesc('total_terjual_produk')
            ->limit(10)
            ->get()
            ->map(function ($produk) {
                $produk->total_terjual = $produk->total_terjual_produk;
                $produk->keuntungan = $produk->total_keuntungan_produk;

                $latestTransaction = BarangKeluar::where('nama_barang', $produk->nama_barang)
                    ->orderBy('tanggal_keluar', 'desc')
                    ->first();

                $produk->harga_jual = $latestTransaction ? $latestTransaction->harga_jual : 0;
                return $produk;
            });

        // Data untuk chart keuntungan harian 7 hari terakhir
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(6);

        $dailyProfitsQuery = BarangKeluar::select(
            DB::raw('DATE(tanggal_keluar) as date'),
            DB::raw('SUM(keuntungan) as total_daily_keuntungan'),
            DB::raw('SUM(kerugian) as total_daily_kerugian')
        )
            ->whereBetween('tanggal_keluar', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->keyBy('date');

        $chartDailyLabels = [];
        $chartDailyData = [];
        $currentDateIterator = $startDate->copy();

        while ($currentDateIterator <= $endDate) {
            $dateString = $currentDateIterator->toDateString();
            $chartDailyLabels[] = $currentDateIterator->translatedFormat('d M');

            if (isset($dailyProfitsQuery[$dateString])) {
                $profitEntry = $dailyProfitsQuery[$dateString];
                $netProfit = $profitEntry->total_daily_keuntungan - $profitEntry->total_daily_kerugian;
                $chartDailyData[] = $netProfit;
            } else {
                $chartDailyData[] = 0;
            }
            $currentDateIterator->addDay();
        }

        $dailyProfitsData = [
            'labels' => $chartDailyLabels,
            'data' => $chartDailyData,
        ];

        // User info & welcome message
        $user = Auth::user();
        $userRole = $user ? $user->role : null;
        $welcomeMessage = $user ? 'Welcome ' . ucfirst($user->role) : 'Welcome';

        return view('dashboard.index', compact(
            'totalStokSaatIni',
            'totalKeluar',
            'totalTransaksi',
            'topProduk',
            'dailyProfitsData',
            'welcomeMessage',
            'userRole'
        ));
    }
}
