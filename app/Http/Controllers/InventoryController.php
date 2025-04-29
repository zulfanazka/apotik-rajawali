<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\BarangKeluar;

class InventoryController extends Controller
{
    // Menampilkan halaman stokbarang
    public function stokBarang(Request $request)
    {
        $query = Inventory::query();

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('id_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }


        $items = $query->orderBy('nama_barang', 'asc')->paginate(10);
        return view("inventory.stokbarang", compact('items'));
    }

    // Menampilkan halaman barangmasuk dan mengambil data barang
    public function barangMasuk()
    {
        $barang = Inventory::all(); // Mengambil semua data dari tabel Inventory
        return view('inventory.barangmasuk', compact('barang'));
    }

    // Menyimpan data barang baru atau mengupdate data barang yang sudah ada
    public function simpanBarang(Request $request)
    {
        $rules = [
            'kategori' => 'required',
            'tanggal' => 'required|date',
            'nama_barang' => 'required',
            'harga_barang' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'id_barang' => 'required',
            'kuantitas' => 'required|numeric',
        ];

        if (!$request->has('edit')) {
            // Tambah data: pastikan id_barang unik
            $rules['id_barang'] .= '|unique:inventory,id_barang';
        }

        $request->validate($rules);

        if ($request->has('edit')) {
            // Update data barang yang sudah ada
            $barang = Inventory::findOrFail($request->edit);
            $barang->update($request->all());
            return redirect()->route('barangmasuk')->with('success', 'Data barang berhasil diubah!');
        } else {
            // Tambah data barang baru
            Inventory::create($request->all());
            return redirect()->route('barangmasuk')->with('success', 'Data barang berhasil ditambahkan!');
        }
    }

    public function simpanBarangKeluar(Request $request)
    {
        // Validasi input data
        $rules = [
            'kategori' => 'required',
            'tanggal_keluar' => 'required|date',
            'nama_barang' => 'required',
            'harga_jual' => 'required|numeric',
            'id_barang' => 'required',
            'kuantitas' => 'required|numeric',
        ];

        // Jika sedang menambah data (bukan edit), pastikan ID barang unik di tabel barangkeluar
        if (!$request->has('edit')) {
            $rules['id_barang'] .= '|unique:barangkeluar,id_barang';
        }

        // Validasi request
        $validated = $request->validate($rules);

        if ($request->has('edit')) {
            // MODE EDIT: Update barang keluar berdasarkan id_barang
            $barang = BarangKeluar::where('id_barang', $request->edit)->firstOrFail();

            $barang->update([
                'kategori' => $request->kategori,
                'tanggal_keluar' => $request->tanggal_keluar,
                'nama_barang' => $request->nama_barang,
                'harga_jual' => $request->harga_jual,
                'id_barang' => $request->id_barang,
                'kuantitas' => $request->kuantitas,
                'detail_obat' => $request->detail_obat,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('barangkeluar')->with('success', 'Data barang keluar berhasil diubah!');
        } else {
            // MODE TAMBAH: Tambahkan data baru ke tabel barangkeluar
            BarangKeluar::create([
                'kategori' => $request->kategori,
                'tanggal_keluar' => $request->tanggal_keluar,
                'nama_barang' => $request->nama_barang,
                'harga_jual' => $request->harga_jual,
                'id_barang' => $request->id_barang,
                'kuantitas' => $request->kuantitas,
                'detail_obat' => $request->detail_obat,
                'keterangan' => $request->keterangan,
            ]);

            return redirect()->route('barangkeluar')->with('success', 'Data barang keluar berhasil ditambahkan!');
        }
    }




    // Menampilkan halaman barangkeluar
    public function barangKeluar()
    {
        $barang = BarangKeluar::all();
        return view('inventory.barangkeluar', compact('barang'));
    }

    // Menampilkan form edit barang
    public function editBarang($id_barang)
    {
        // Pastikan mengambil satu data barang berdasarkan id_barang
        $barang = Inventory::find($id_barang);  // Mengambil satu barang berdasarkan id_barang

        if (!$barang) {
            return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan.');
        }

        // Mengirimkan data barang ke view
        return view("inventory.tambahbarang", compact('barang'));
    }


    // Menampilkan halaman tambah barang (form untuk tambah dan update)
    public function tambahBarang($id_barang = null)
    {
        $barang = null;

        if ($id_barang) {
            // Jika ada ID, kita ambil data barang untuk diupdate
            $barang = Inventory::find($id_barang);
            if (!$barang) {
                return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan.');
            }
        }

        return view('inventory.tambahbarang', compact('barang'));
    }

    public function tambahBarangKeluar()
    {
        return view('inventory.tambahbarangkeluar');
    }


    // Menghapus barang berdasarkan ID
    public function delete($id)
    {
        try {
            $barang = Inventory::find($id);
            if ($barang) {
                $barang->delete();
                return redirect()->route('barangmasuk')->with('success', 'Barang berhasil dihapus.');
            } else {
                return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect()->route('barangmasuk')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editBarangKeluar($id_barang)
    {
        // Pastikan mengambil satu data barang berdasarkan id_barang
        $barang = Inventory::find($id_barang);  // Mengambil satu barang berdasarkan id_barang

        if (!$barang) {
            return redirect()->route('barangkeluar')->with('error', 'Barang tidak ditemukan.');
        }

        // Mengirimkan data barang ke view
        return view("inventory.tambahbarangkeluar", compact('barang'));
    }

    // Menghapus barang keluar berdasarkan ID

    public function deleteBarangKeluar($id)
    {
        try {
            // Cari dari model BarangKeluar
            $barang = BarangKeluar::where('id_barang', $id)->first();

            if ($barang) {
                $barang->delete();
                return redirect()->route('barangkeluar')->with('success', 'Barang keluar berhasil dihapus.');
            } else {
                return redirect()->route('barangkeluar')->with('error', 'Barang keluar tidak ditemukan.');
            }
        } catch (\Exception $e) {
            return redirect()->route('barangkeluar')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }





    // Update data barang
    public function updateBarang(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'id_barang' => 'required|string|max:50',
            'kategori' => 'required|string|max:50',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'satuan' => 'required|string|max:50',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barang = Inventory::find($request->id_barang);

        if ($barang) {
            $barang->update($validated);
            return redirect()->route('stokBarang')->with('success', 'Barang berhasil diperbarui');
        } else {
            return redirect()->route('stokBarang')->with('error', 'Barang tidak ditemukan');
        }
    }

    public function updateBarangKeluar(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'id_barang' => 'required|string|max:50',
            'kategori' => 'required|string|max:50',
            'harga_jual' => 'required|numeric',
            'kuantitas' => 'required|numeric',
            'detail_obat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'tanggal_keluar' => 'required|date',
        ]);

        // Cari barang keluar berdasarkan ID
        $barang = BarangKeluar::find($request->id_barang);

        // Jika barang keluar ditemukan, lakukan update
        if ($barang) {
            $barang->update([
                'nama_barang' => $validated['nama_barang'],
                'id_barang' => $validated['id_barang'],
                'kategori' => $validated['kategori'],
                'harga_jual' => $validated['harga_jual'],
                'kuantitas' => $validated['kuantitas'],
                'detail_obat' => $validated['detail_obat'],
                'keterangan' => $validated['keterangan'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
            ]);

            return redirect()->route('barangkeluar')->with('success', 'Barang keluar berhasil diperbarui.');
        } else {
            return redirect()->route('barangkeluar')->with('error', 'Barang keluar tidak ditemukan.');
        }
    }


}
