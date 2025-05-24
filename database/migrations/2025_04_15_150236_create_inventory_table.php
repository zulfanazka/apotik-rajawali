<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis dari Laravel
            $table->string('id_barang')->unique(); // ID Barang, pastikan unik. Saya tambahkan unique constraint.
            $table->string('nama_barang'); // Nama Barang
            $table->string('kategori'); // Kategori
            $table->string('satuan'); // Satuan barang
            $table->date('tanggal_masuk');
            $table->string('nama_supplier')->nullable(); // Kolom baru: Nama Supplier
            $table->date('tanggal_keluar')->nullable()->default(null);
            $table->integer('harga_beli'); // Harga beli barang
            $table->integer('harga_jual');
            $table->integer('stok'); // stok sebagai integer
            // $table->integer('jumlah_keluar'); // Jumlah barang yang keluar (sudah ada di barang_keluar)
            $table->text('detail_obat')->nullable(); // Penggunaan barang (di model fillable, tapi tidak ada di migrasi awal, saya biarkan sesuai file terakhir)
            $table->text('keterangan')->nullable();
            $table->timestamps(); // Kolom waktu pembuatan dan update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
