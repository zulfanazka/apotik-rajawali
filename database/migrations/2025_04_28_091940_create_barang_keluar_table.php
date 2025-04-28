<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang'); // Nama Barang
            $table->string('id_barang'); // ID Barang, relasi dari barang masuk (tidak perlu unique di sini)
            $table->string('kategori'); // Kategori
            $table->integer('kuantitas'); // Kuantitas keluar
            $table->text('detail_obat')->nullable(); // Detail barang (opsional)
            $table->integer('harga_jual'); // Harga jual per unit saat keluar
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->date('tanggal_keluar'); // Tanggal barang keluar
            $table->timestamps(); // Kolom created_at dan updated_at
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
