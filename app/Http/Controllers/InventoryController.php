<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangKeluar;
use App\Models\Inventory;
// use Illuminate\Support\Facades\Log; // Untuk debugging jika diperlukan

class InventoryController extends Controller
{
    // Menampilkan halaman stokbarang
    public function stokBarang(Request $request)
    {
        $query = Inventory::query();

        $searchQuery = $request->input('search_query'); // Menggunakan nama dari form filter stokbarang
        $kategoriFilter = $request->input('kategori_filter'); // Menggunakan nama dari form filter stokbarang
        $supplierFilter = $request->input('supplier_filter'); // Menggunakan nama dari form filter stokbarang

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('nama_barang', 'like', '%' . $searchQuery . '%')
                    ->orWhere('id_barang', 'like', '%' . $searchQuery . '%');
            });
        }
        if ($kategoriFilter) {
            $query->where('kategori', $kategoriFilter);
        }
        if ($supplierFilter) {
            $query->where('nama_supplier', 'like', '%' . $supplierFilter . '%');
        }

        $items = $query->orderBy('nama_barang', 'asc')->paginate(10)->withQueryString();
        $kategoriOptions = Inventory::select('kategori')->distinct()->whereNotNull('kategori')->where('kategori', '!=', '')->orderBy('kategori')->pluck('kategori');
        // $supplierOptions (jika diperlukan untuk stokBarang, bisa ditambahkan di sini)

        return view("inventory.stokbarang", compact(
            'items',
            'kategoriOptions'
            // 'searchQuery', 'kategoriFilter', 'supplierFilter' // Kirim kembali untuk mengisi nilai form
        ));
    }

    public function deleteStokBarang($id_barang)
    {
        try {
            $barang = Inventory::find($id_barang);

            if (!$barang) {
                return redirect()->route('stokbarang')->with('error', 'Barang tidak ditemukan.');
            }

            $timestamp = now()->format('d-m-Y H:i');
            $barang->barangKeluar()->update(['keterangan' => 'Item dihapus dari stok pada ' . $timestamp . '. Supplier asal: ' . ($barang->nama_supplier ?? 'N/A')]);
            $barang->delete();

            return redirect()->route('stokbarang')->with('success', 'Barang berhasil dihapus dan keterangan barang keluar telah diperbarui.');
        } catch (\Exception $e) {
            // Log::error("Error deleting stock: " . $e->getMessage());
            return redirect()->route('stokbarang')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function barangMasuk(Request $request) // Tambahkan Request $request
    {
        $query = Inventory::query();

        $searchQuery = $request->input('search_query_masuk');
        $kategoriFilter = $request->input('kategori_filter_masuk');
        $supplierFilter = $request->input('supplier_filter_masuk');
        // Filter tanggal sudah dihapus sesuai permintaan sebelumnya

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('nama_barang', 'like', '%' . $searchQuery . '%')
                    ->orWhere('id_barang', 'like', '%' . $searchQuery . '%');
            });
        }
        if ($kategoriFilter) {
            $query->where('kategori', $kategoriFilter);
        }
        if ($supplierFilter) {
            $query->where('nama_supplier', $supplierFilter); // Filter berdasarkan supplier yang dipilih
        }

        $barang = $query->orderBy('tanggal_masuk', 'desc')->paginate(10)->withQueryString();

        // Mengambil opsi untuk filter dropdown
        $kategoriOptions = Inventory::select('kategori')->distinct()->whereNotNull('kategori')->where('kategori', '!=', '')->orderBy('kategori')->pluck('kategori');
        $suppliers = Inventory::select('nama_supplier')->distinct()->whereNotNull('nama_supplier')->where('nama_supplier', '!=', '')->orderBy('nama_supplier')->pluck('nama_supplier'); // Mengambil daftar supplier

        return view('inventory.barangmasuk', compact(
            'barang',
            'kategoriOptions',
            'suppliers' // Kirim variabel $suppliers ke view
            // Anda mungkin juga ingin mengirim kembali nilai filter yang aktif:
            // 'searchQuery', 'kategoriFilter', 'supplierFilter' 
            // agar bisa di-set di value input form filter.
        ));
    }

    public function tambahBarang($id_barang_param = null)
    {
        $barang = null;
        if ($id_barang_param) {
            $barang = Inventory::find($id_barang_param);
            if (!$barang) {
                return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan.');
            }
        }
        return view('inventory.tambahbarang', compact('barang'));
    }

    public function editBarang($id_barang)
    {
        $barang = Inventory::find($id_barang);
        if (!$barang) {
            return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan.');
        }
        return view("inventory.tambahbarang", compact('barang'));
    }

    public function updateBarang(Request $request)
    {
        $idBarangFromRequest = $request->input('id_barang_hidden_for_update');

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'nama_supplier' => 'nullable|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barang = Inventory::find($idBarangFromRequest);

        if ($barang) {
            $barang->update($validated);
            return redirect()->route('barangmasuk')->with('success', 'Barang berhasil diperbarui');
        } else {
            return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan saat update.');
        }
    }

    public function simpanBarang(Request $request)
    {
        $isEditMode = $request->has('id_barang_hidden_for_update') && $request->input('id_barang_hidden_for_update') != null;
        $idBarangToUpdate = $request->input('id_barang_hidden_for_update');

        $rules = [
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'nama_supplier' => 'nullable|string|max:255',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:255',
        ];

        $validatedData = $request->validate($rules);

        if ($isEditMode) {
            $barang = Inventory::find($idBarangToUpdate);
            if (!$barang) {
                return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan untuk diubah.');
            }
            $barang->update($validatedData);
            return redirect()->route('barangmasuk')->with('success', 'Data barang berhasil diubah!');
        } else {
            Inventory::create($validatedData);
            return redirect()->route('barangmasuk')->with('success', 'Data barang berhasil ditambahkan!');
        }
    }

    public function delete($id_barang)
    {
        try {
            $barang = Inventory::find($id_barang);
            if ($barang) {
                if (BarangKeluar::where('id_barang', $id_barang)->exists()) {
                    return redirect()->route('barangmasuk')->with('error', 'Barang tidak bisa dihapus karena sudah ada transaksi di barang keluar. Update keterangan di stok barang jika item dihapus dari peredaran.');
                }
                $barang->delete();
                return redirect()->route('barangmasuk')->with('success', 'Barang berhasil dihapus.');
            } else {
                return redirect()->route('barangmasuk')->with('error', 'Barang tidak ditemukan.');
            }
        } catch (\Exception $e) {
            // Log::error("Error deleting item: " . $e->getMessage());
            return redirect()->route('barangmasuk')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function barangKeluar(Request $request)
    {
        $query = BarangKeluar::with('inventory') // Eager load relasi inventory
            ->orderBy('tanggal_keluar', 'desc'); // Urutkan default

        // Ambil nilai filter dari request
        $searchQuery = $request->input('search_query_keluar');
        $kategoriFilter = $request->input('kategori_filter_keluar');
        $supplierFilter = $request->input('supplier_filter_keluar');

        // Terapkan filter pencarian umum
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('barang_keluar.id_barang', 'like', '%' . $searchQuery . '%') // Cari di ID barang pada tabel barang_keluar
                    ->orWhere('barang_keluar.nama_barang', 'like', '%' . $searchQuery . '%') // Cari di nama barang pada tabel barang_keluar
                    ->orWhereHas('inventory', function ($invQuery) use ($searchQuery) { // Cari di nama barang pada tabel inventory terkait
                        $invQuery->where('nama_barang', 'like', '%' . $searchQuery . '%');
                    });
            });
        }

        // Terapkan filter kategori
        if ($kategoriFilter) {
            $query->whereHas('inventory', function ($q) use ($kategoriFilter) {
                $q->where('kategori', $kategoriFilter);
            });
        }

        // Terapkan filter supplier
        if ($supplierFilter) {
            $query->whereHas('inventory', function ($q) use ($supplierFilter) {
                $q->where('nama_supplier', $supplierFilter); // Untuk dropdown, biasanya pencocokan persis
            });
        }

        // Paginasi hasil query dan pastikan parameter filter dipertahankan
        $barangKeluar = $query->paginate(10)->withQueryString();

        // Mengambil opsi untuk filter dropdown (jika belum ada di view atau ingin dinamis)
        // Variabel $kategoriOptions digunakan di view Anda
        $kategoriOptions = Inventory::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->orderBy('kategori')
            ->pluck('kategori');

        // Variabel $suppliers digunakan di view Anda
        $suppliers = Inventory::select('nama_supplier')
            ->distinct()
            ->whereNotNull('nama_supplier')
            ->where('nama_supplier', '!=', '')
            ->orderBy('nama_supplier')
            ->pluck('nama_supplier');

        return view('inventory.barangkeluar', compact(
            'barangKeluar',
            'kategoriOptions', // Kirim ke view untuk dropdown kategori
            'suppliers'      // Kirim ke view untuk dropdown supplier
            // Anda juga bisa mengirim kembali nilai filter yang aktif untuk mengisi ulang form:
            // 'search_query_keluar' => $searchQuery,
            // 'kategori_filter_keluar' => $kategoriFilter,
            // 'supplier_filter_keluar' => $supplierFilter
        ));
    }

    public function tambahBarangKeluar()
    {
        $barangMasuk = Inventory::where('stok', '>', 0)
            ->select('id_barang', 'nama_barang', 'kategori', 'stok', 'satuan', 'harga_beli', 'harga_jual', 'tanggal_masuk', 'nama_supplier')
            ->orderBy('nama_barang')
            ->get();
        return view('inventory.tambahbarangkeluar', compact('barangMasuk'));
    }

    public function simpanBarangKeluar(Request $request)
    {
        $rules = [
            'id_barang' => 'required|exists:inventory,id_barang',
            'tanggal_keluar' => 'required|date',
            'jumlah_keluar' => 'required|integer|min:1',
            'detail_obat' => 'required|in:terjual,exp,retur',
            'keterangan' => 'nullable|string|max:255',
            'edit_id_barang_keluar' => 'nullable|exists:barang_keluar,id',
        ];

        $data = $request->validate($rules);
        $qty = $data['jumlah_keluar'];
        $barangBaru = Inventory::findOrFail($data['id_barang']);

        $keuntungan = 0;
        $kerugian = 0;

        if ($data['detail_obat'] === 'terjual') {
            $keuntungan = ($barangBaru->harga_jual - $barangBaru->harga_beli) * $qty;
        } elseif ($data['detail_obat'] === 'exp') {
            $kerugian = $barangBaru->harga_beli * $qty;
        }

        $data['keuntungan'] = $keuntungan;
        $data['kerugian'] = $kerugian;

        // Jika proses EDIT
        if ($request->filled('edit_id_barang_keluar')) {
            $barangKeluarLama = BarangKeluar::findOrFail($request->edit_id_barang_keluar);
            $barangLama = Inventory::find($barangKeluarLama->id_barang);

            // Kembalikan stok lama
            if ($barangLama) {
                $barangLama->stok += $barangKeluarLama->jumlah_keluar;
                $barangLama->save();
            }

            // Validasi stok baru
            if ($qty > $barangBaru->stok) {
                return back()->withErrors(['jumlah_keluar' => 'Jumlah keluar melebihi stok tersedia (Stok: ' . $barangBaru->stok . ').'])->withInput();
            }

            // Kurangi stok
            $barangBaru->stok -= $qty;
            $barangBaru->tanggal_keluar = $data['tanggal_keluar'];
            $barangBaru->save();

            // Update data barang keluar
            $barangKeluarLama->update([
                'id_barang' => $barangBaru->id_barang,
                'nama_barang' => $barangBaru->nama_barang,
                'kategori' => $barangBaru->kategori,
                'satuan' => $barangBaru->satuan,
                'tanggal_masuk' => $barangBaru->tanggal_masuk,
                'tanggal_keluar' => $data['tanggal_keluar'],
                'jumlah_keluar' => $qty,
                'stok' => $barangBaru->stok,
                'harga_beli' => $barangBaru->harga_beli,
                'harga_jual' => $barangBaru->harga_jual,
                'detail_obat' => $data['detail_obat'],
                'keterangan' => $data['keterangan'],
                'keuntungan' => $keuntungan,
                'kerugian' => $kerugian,
            ]);

            return redirect()->route('barangkeluar')->with('success', 'Data barang keluar berhasil diubah!');
        }

        // Jika proses INSERT
        if ($qty > $barangBaru->stok) {
            return back()->withErrors(['jumlah_keluar' => 'Jumlah keluar tidak boleh melebihi stok yang ada (Stok: ' . $barangBaru->stok . ').'])->withInput();
        }

        $barangBaru->stok -= $qty;
        $barangBaru->tanggal_keluar = $data['tanggal_keluar'];
        $barangBaru->save();

        BarangKeluar::create([
            'id_barang' => $barangBaru->id_barang,
            'nama_barang' => $barangBaru->nama_barang,
            'kategori' => $barangBaru->kategori,
            'satuan' => $barangBaru->satuan,
            'tanggal_masuk' => $barangBaru->tanggal_masuk,
            'tanggal_keluar' => $data['tanggal_keluar'],
            'jumlah_keluar' => $qty,
            'stok' => $barangBaru->stok,
            'harga_beli' => $barangBaru->harga_beli,
            'harga_jual' => $barangBaru->harga_jual,
            'detail_obat' => $data['detail_obat'],
            'keterangan' => $data['keterangan'],
            'keuntungan' => $keuntungan,
            'kerugian' => $kerugian,
        ]);

        return redirect()->route('barangkeluar')->with('success', 'Data barang keluar berhasil disimpan!');
    }


    public function updateBarangKeluar(Request $request) // Sebaiknya disatukan dengan simpanBarangKeluar
    {
        $idBarangKeluarPK = $request->input('id_barang_keluar_primary_key');
        if (!$idBarangKeluarPK) {
            return redirect()->route('barangkeluar')->with('error', 'ID Transaksi Barang Keluar tidak ditemukan untuk update.');
        }

        $rules = [
            'id_barang' => 'required|exists:inventory,id_barang',
            'tanggal_keluar' => 'required|date',
            'jumlah_keluar' => 'required|integer|min:1',
            'detail_obat' => 'required|in:terjual,exp,retur',
            'keterangan' => 'nullable|string|max:255',
        ];
        $validated = $request->validate($rules);

        $barangKeluar = BarangKeluar::findOrFail($idBarangKeluarPK);
        $barangInventoryTarget = Inventory::findOrFail($validated['id_barang']);
        $barangInventoryLama = Inventory::find($barangKeluar->id_barang);

        if ($barangInventoryLama) {
            $barangInventoryLama->stok += $barangKeluar->jumlah_keluar;
            $barangInventoryLama->save();
        }

        if ($validated['jumlah_keluar'] > $barangInventoryTarget->stok) {
            if ($barangInventoryLama) {
                $barangInventoryLama->stok -= $barangKeluar->jumlah_keluar;
                $barangInventoryLama->save();
            }
            return back()->withErrors(['jumlah_keluar' => 'Jumlah keluar baru (' . $validated['jumlah_keluar'] . ') melebihi stok yang tersedia pada barang yang dipilih (' . $barangInventoryTarget->stok . ').'])->withInput();
        }

        $barangInventoryTarget->stok -= $validated['jumlah_keluar'];
        $barangInventoryTarget->tanggal_keluar = $validated['tanggal_keluar'];
        $barangInventoryTarget->save();

        $keuntungan = 0;
        $kerugian = 0;
        if ($validated['detail_obat'] === 'terjual') {
            $keuntungan = ($barangInventoryTarget->harga_jual - $barangInventoryTarget->harga_beli) * $validated['jumlah_keluar'];
        } elseif ($validated['detail_obat'] === 'exp') {
            $kerugian = $barangInventoryTarget->harga_beli * $validated['jumlah_keluar'];
        }

        $barangKeluar->update([
            'id_barang' => $validated['id_barang'],
            'nama_barang' => $barangInventoryTarget->nama_barang,
            'kategori' => $barangInventoryTarget->kategori,
            'satuan' => $barangInventoryTarget->satuan,
            'tanggal_masuk' => $barangInventoryTarget->tanggal_masuk,
            'tanggal_keluar' => $validated['tanggal_keluar'],
            'jumlah_keluar' => $validated['jumlah_keluar'],
            'stok' => $barangInventoryTarget->stok,
            'harga_beli' => $barangInventoryTarget->harga_beli,
            'harga_jual' => $barangInventoryTarget->harga_jual,
            'detail_obat' => $validated['detail_obat'],
            'keterangan' => $validated['keterangan'],
            'keuntungan' => $keuntungan,
            'kerugian' => $kerugian,
        ]);

        return redirect()->route('barangkeluar')->with('success', 'Barang keluar berhasil diperbarui.');
    }

    public function deleteBarangKeluar($id_barang_keluar_pk)
    {
        try {
            $barangKeluar = BarangKeluar::findOrFail($id_barang_keluar_pk);
            $barangInventory = Inventory::find($barangKeluar->id_barang);
            if ($barangInventory) {
                $barangInventory->stok += $barangKeluar->jumlah_keluar;
                $barangInventory->save();
            }
            $barangKeluar->delete();
            return redirect()->route('barangkeluar')->with('success', 'Barang keluar berhasil dihapus dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            // Log::error("Error deleting item keluar: " . $e->getMessage());
            return redirect()->route('barangkeluar')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editBarangKeluar($id_barang_keluar_pk)
    {
        $barangKeluar = BarangKeluar::find($id_barang_keluar_pk);
        if (!$barangKeluar) {
            return redirect()->route('barangkeluar')->with('error', 'Data Barang Keluar tidak ditemukan.');
        }
        $barangMasuk = Inventory::where('stok', '>', 0)
            ->orWhere('id_barang', $barangKeluar->id_barang)
            ->select('id_barang', 'nama_barang', 'kategori', 'stok', 'satuan', 'harga_beli', 'harga_jual', 'tanggal_masuk', 'nama_supplier')
            ->orderBy('nama_barang')
            ->get();
        return view('inventory.tambahbarangkeluar', compact('barangKeluar', 'barangMasuk'));
    }

    public function laporan(Request $request)
    {
        $query = BarangKeluar::with('inventory')
            ->orderBy('tanggal_keluar', 'desc');

        if ($request->filled('kategori_laporan')) {
            $query->whereHas('inventory', function ($q) use ($request) {
                $q->where('kategori', $request->kategori_laporan);
            });
        }
        if ($request->filled('supplier_laporan')) {
            $query->whereHas('inventory', function ($q) use ($request) {
                $q->where('nama_supplier', 'like', '%' . $request->supplier_laporan . '%');
            });
        }
        if ($request->filled('tanggal_mulai_laporan')) {
            $query->whereDate('tanggal_keluar', '>=', $request->tanggal_mulai_laporan);
        }
        if ($request->filled('tanggal_akhir_laporan')) {
            $query->whereDate('tanggal_keluar', '<=', $request->tanggal_akhir_laporan);
        }

        $barangKeluar = $query->get();

        $suppliers = Inventory::select('nama_supplier')
            ->distinct()
            ->whereNotNull('nama_supplier')
            ->where('nama_supplier', '!=', '')
            ->orderBy('nama_supplier')
            ->pluck('nama_supplier');

        $kategori = Inventory::select('kategori')
            ->distinct()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('inventory.laporan', compact('barangKeluar', 'suppliers', 'kategori'));
    }
}
