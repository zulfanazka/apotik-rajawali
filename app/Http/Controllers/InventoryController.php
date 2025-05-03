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

    public function updateBarang(Request $request)
    {
        $validated = $request->validate([
            'id_barang' => 'required',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            // 'jumlah_keluar' => 'required|numeric',
            'detail_obat' => 'nullable|string|max:255',
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

    // Menyimpan data barang baru atau mengupdate data barang yang sudah ada
    public function simpanBarang(Request $request)
    {
        // Validasi hanya kolom yang diperlukan
        $rules = [
            'id_barang' => 'required',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'detail_obat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ];

        // Jika tidak ada edit, maka id_barang harus unik
        if (!$request->has('edit')) {
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
            Inventory::create($request->only([
                'id_barang',
                'nama_barang',
                'kategori',
                'satuan',
                'tanggal_masuk',
                'harga_beli',
                'harga_jual',
                'stok',
                'detail_obat',
                'keterangan'
            ]));
            return redirect()->route('barangmasuk')->with('success', 'Data barang berhasil ditambahkan!');
        }
    }



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

    // Menampilkan halaman barangkeluar
    public function barangKeluar()
    {
        // Ambil data barang keluar dengan urutan terbaru dan relasi dengan inventory
        $barangKeluar = BarangKeluar::with('inventory') // Eager load relasi 'inventory'
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('inventory.barangkeluar', compact('barangKeluar'));
    }



    // Menampilkan form tambah barang keluar
    public function tambahBarangKeluar()
    {
        $barangMasuk = Inventory::all(); // Ambil semua barang dari inventory
        return view('inventory.tambahbarangkeluar', compact('barangMasuk'));
    }

    public function simpanBarangKeluar(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_barang' => 'required|exists:inventory,id_barang',
            'tanggal_keluar' => 'required|date',
            'jumlah_keluar' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Ambil data barang dari Inventory
        $barang = Inventory::where('id_barang', $request->id_barang)->first();

        // Validasi stok cukup
        if ($barang->stok < $request->jumlah_keluar) {
            return redirect()->back()->withErrors(['jumlah_keluar' => 'Jumlah keluar melebihi stok saat ini.'])->withInput();
        }

        // Simpan ke tabel BarangKeluar
        BarangKeluar::create([
            'id_barang' => $barang->id_barang,
            'nama_barang' => $barang->nama_barang,
            'kategori' => $barang->kategori,
            'tanggal_masuk' => $barang->tanggal_masuk,
            'tanggal_keluar' => $request->tanggal_keluar,
            'jumlah_keluar' => $request->jumlah_keluar,
            'stok' => $barang->stok, // stok sebelum dikurangi
            'satuan' => $barang->satuan,
            'harga_beli' => $barang->harga_beli,
            'harga_jual' => $barang->harga_jual,
            'keterangan' => $request->keterangan,
        ]);

        // Kurangi stok di inventory
        $barang->stok -= $request->jumlah_keluar;
        $barang->save();

        return redirect()->route('barangkeluar')->with('success', 'Data barang keluar berhasil disimpan!');
    }














    public function updateBarangKeluar(Request $request)
    {
        // Validasi input dari form
        $validated = $request->validate([
            'id_barang' => 'required',
            'nama_barang' => 'required',
            'kategori' => 'required',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'required|date',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|numeric',
            'jumlah_keluar' => 'required|numeric',
            'detail_obat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cari barang keluar berdasarkan ID
        $barang = BarangKeluar::find($request->id_barang);

        // Jika barang keluar ditemukan, lakukan update
        if ($barang) {
            $barang->update([
                'id_barang' => $validated['id_barang'],
                'nama_barang' => $validated['nama_barang'],
                'kategori' => $validated['kategori'],
                'satuan' => $validated['satuan'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'tanggal_keluar' => $validated['tanggal_keluar'],
                'harga_beli' => $validated['harga_beli'],
                'harga_jual' => $validated['harga_jual'],
                'stok' => $validated['stok'],
                'jumlah_keluar' => $validated['jumlah_keluar'],
                'detail_obat' => $validated['detail_obat'],
                'keterangan' => $validated['keterangan'],
            ]);

            return redirect()->route('barangkeluar')->with('success', 'Barang keluar berhasil diperbarui.');
        } else {
            return redirect()->route('barangkeluar')->with('error', 'Barang keluar tidak ditemukan.');
        }
    }

    public function deleteBarangKeluar($id)
    {
        try {
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
}
