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
    public $incrementing = false; // ← ini penting
    protected $keyType = 'string'; // ← sesuaikan jika id_barang berupa string

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'tanggal_masuk',
        'tanggal_keluar',
        'harga_beli',
        'harga_jual',
        'stok',
        'jumlah_keluar',
        'keterangan'
    ];

    // Menambahkan relasi ke model BarangKeluar
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_barang', 'id_barang');
    }



}
