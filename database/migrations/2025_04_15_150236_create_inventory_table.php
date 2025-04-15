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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang'); // Nama Barang
            $table->string('id_barang')->unique(); // ID Barang, pastikan unik
            $table->string('kategori'); // Kategori
            $table->integer('kuantitas'); // Kuantitas sebagai integer
            $table->text('penggunaan')->nullable(); // Penggunaan barang
            $table->text('efek_samping')->nullable(); // Efek samping barang
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
