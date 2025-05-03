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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('id_barang')->unique(); // ID Barang, pastikan unik
            $table->string('nama_barang'); // Nama Barang
            $table->string('kategori'); // Kategori
            $table->string('satuan'); // Satuan barang
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable()->default(null);
            $table->integer('harga_beli'); // Efek samping barang
            $table->integer('harga_jual');
            $table->integer('stok'); // stok sebagai integer
            $table->integer('jumlah_keluar')->nullable(); // Menjadikan jumlah_keluar nullable;
            $table->text('detail_obat')->nullable(); // Penggunaan barang
            $table->text('keterangan')->nullable();
            $table->timestamps(); // Kolom waktu pembuatan dan update
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
