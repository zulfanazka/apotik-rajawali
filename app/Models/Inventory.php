<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';  // pastikan sesuai nama tabel di database
    protected $primaryKey = 'id_barang'; // INI PENTING

    public $timestamps = false; // kalau kamu gak pakai created_at dan updated_at

    protected $fillable = [
        'kategori', 'tanggal', 'nama_barang',
        'harga_barang', 'harga_jual',
        'id_barang', 'kuantitas',
        'detail_obat', 'keterangan'
    ];


}
