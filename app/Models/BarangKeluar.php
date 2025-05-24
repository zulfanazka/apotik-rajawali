<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';
    // Primary key dari tabel barang_keluar adalah 'id' (auto-increment) sesuai migrasi
    protected $primaryKey = 'id';

    // Karena 'id' adalah auto-incrementing integer, incrementing harus true
    public $incrementing = true;
    // Tipe key adalah integer (default untuk incrementing PK, bisa dihilangkan)
    protected $keyType = 'int';

    // Timestamps diatur false jika tidak ada kolom created_at & updated_at di migrasi barang_keluar
    // Namun, migrasi Anda untuk barang_keluar memiliki $table->timestamps(); jadi ini harusnya true.
    public $timestamps = true;

    protected $fillable = [
        'id_barang', // Ini adalah foreign key ke tabel inventory
        'nama_barang',
        'kategori',
        'satuan',
        'tanggal_masuk',
        'tanggal_keluar',
        'harga_beli',
        'harga_jual',
        'stok', // Stok sisa di inventory setelah transaksi ini
        'jumlah_keluar',
        'detail_obat', // Status: terjual, exp, retur
        'keterangan',
        'keuntungan',
        'kerugian'
    ];

    // Relasi ke model Inventory
    public function inventory()
    {
        // Foreign key di tabel barang_keluar adalah 'id_barang'
        // Owner key di tabel inventory adalah 'id_barang' (primary key inventory)
        return $this->belongsTo(Inventory::class, 'id_barang', 'id_barang');
    }
}
