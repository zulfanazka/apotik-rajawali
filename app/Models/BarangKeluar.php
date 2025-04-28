<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar'; // Nama tabel
    protected $fillable = [
        'nama_barang',
        'id_barang',
        'kategori',
        'kuantitas',
        'detail_obat',
        'harga_jual',
        'keterangan',
        'tanggal_keluar',
    ];
}
