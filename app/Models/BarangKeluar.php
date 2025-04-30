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
