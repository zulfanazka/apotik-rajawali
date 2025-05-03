<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';
    protected $primaryKey = 'id_barang'; // ← wajib kalau tidak pakai id default

    public $incrementing = false; // ← penting jika id_barang bukan auto increment
    protected $keyType = 'string'; // ← sesuaikan jika id_barang pakai format seperti "BRG001"
    public $timestamps = false; // ← jika tidak pakai created_at dan updated_at

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
        'detail',
        'keterangan'
    ];

    // Menambahkan relasi ke model Inventory
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'id_barang', 'id_barang');
    }
}

