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
            $table->string('id_barang'); // ID Barang
            $table->string('nama_barang'); // Nama Barang
            $table->string('kategori'); // Kategori
            $table->string('satuan'); // Satuan barang
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable()->default(null);
            $table->integer('harga_beli'); // Harga beli barang
            $table->integer('harga_jual'); // Harga jual barang
            $table->integer('stok'); // Stok tersisa
            $table->integer('jumlah_keluar')->nullable(); // Jumlah keluar
            $table->text('detail_obat')->nullable(); // Keterangan terjual / exp
            $table->text('keterangan')->nullable(); // Keterangan tambahan
            $table->integer('keuntungan')->default(0); // Keuntungan jika terjual
            $table->integer('kerugian')->default(0); // Kerugian jika exp
            $table->timestamps(); // created_at & updated_at
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
