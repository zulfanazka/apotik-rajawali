<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function stokbarang()
    {
        return view("inventory.stokbarang");
    }
    // public function barangmasuk()
    // {
    //     return view("inventory.barangmasuk");
    // }

    public function barangmasuk()
{
    // Ambil data dari tabel Inventory
    $barang = Inventory::all(); // Anda bisa mengganti dengan query sesuai kebutuhan jika perlu filter

    // Kirim data ke view barangmasuk
    return view('inventory.barangmasuk', compact('barang'));
}


    public function simpanbarang(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|min:3|max:50',
            'id_barang' => 'required|unique:inventory,id_barang',
            'kategori' => 'required',
            'kuantitas' => 'required|numeric|min:1',
            'penggunaan' => 'nullable|max:255',
            'efek_samping' => 'nullable|max:255',
        ], [
            'nama_barang.required' => 'Nama Barang wajib diisi',
            'id_barang.required' => 'ID Barang wajib diisi',
            'id_barang.unique' => 'ID Barang sudah ada',
            'kategori.required' => 'Kategori wajib dipilih',
            'kuantitas.required' => 'Kuantitas wajib diisi',
            'kuantitas.numeric' => 'Kuantitas harus berupa angka',
        ]);

        // Simpan data ke dalam tabel Inventory
        $data = [
            'nama_barang' => $request->input('nama_barang'),
            'id_barang' => $request->input('id_barang'),
            'kategori' => $request->input('kategori'),
            'kuantitas' => $request->input('kuantitas'),
            'penggunaan' => $request->input('penggunaan'),
            'efek_samping' => $request->input('efek_samping'),
        ];

        Inventory::create($data); // Simpan data ke model Inventory

        return redirect()->route('barangmasuk')->with('success', 'Barang berhasil disimpan');
    }

    public function barangkeluar()
    {
        return view("inventory.barangkeluar");
    }
    public function editbarang()
    {
        return view("inventory.editbarang");
    }
}
