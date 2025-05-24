<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Support\Str; // Tidak lagi menggunakan Str::uuid() untuk ini

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $primaryKey = 'id_barang'; // Primary key adalah id_barang
    public $incrementing = false;       // Karena id_barang bukan auto-increment integer
    protected $keyType = 'string';      // Tipe data primary key adalah string

    // Timestamps diatur true karena migrasi Anda menggunakan $table->timestamps()
    public $timestamps = true;

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($inventory) {
            // Hasilkan id_barang secara otomatis jika belum ada
            if (empty($inventory->{$inventory->getKeyName()})) {
                // Menghasilkan angka acak unik 4 atau 5 digit
                // Pastikan ini cukup unik untuk data Anda. Jika tidak, pertimbangkan prefix atau mekanisme lain.
                do {
                    // Menentukan apakah akan 4 atau 5 digit (50% chance masing-masing)
                    // Atau Anda bisa selalu 5 digit untuk lebih banyak kemungkinan: mt_rand(10000, 99999)
                    $length = mt_rand(4, 9);
                    if ($length == 4) {
                        $id = (string) mt_rand(1000, 9999);
                    } else {
                        $id = (string) mt_rand(10000, 99999);
                    }
                } while (static::where('id_barang', $id)->exists()); // Pastikan ID unik

                $inventory->{$inventory->getKeyName()} = $id;
            }
        });
    }

    protected $fillable = [
        // 'id_barang', // Dihapus dari fillable karena akan di-generate otomatis
        'nama_barang',
        'kategori',
        'satuan',
        'tanggal_masuk',
        'nama_supplier',
        'tanggal_keluar',
        'harga_beli',
        'harga_jual',
        'stok',
        'detail_obat',
        'keterangan'
    ];

    // Relasi ke model BarangKeluar
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_barang', 'id_barang');
    }
}
