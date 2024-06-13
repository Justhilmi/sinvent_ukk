<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $rsetKategori = Kategori::select('id', 'deskripsi', 'kategori',
            DB::raw('(CASE
                WHEN kategori = "M" THEN "Modal"
                WHEN kategori = "A" THEN "Alat"
                WHEN kategori = "BHP" THEN "Bahan Habis Pakai"
                ELSE "Bahan Tidak Habis Pakai"
                END) AS ketKategori'))
            ->paginate(10);

        return view('kategori.index', compact('rsetKategori'));
    }

    public function create()
    {
        $aKategori = [
            'blank' => 'Pilih Kategori',
            'M' => 'Barang Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        ];
        return view('kategori.create', compact('aKategori'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'deskripsi' => 'required',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        // Create kategori
        Kategori::create([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori, // Pastikan untuk menyertakan kategori di sini
        ]);

        return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id)
    {
        $rsetKategori = Kategori::find($id);

        return view('kategori.show', compact('rsetKategori'));
    }

    public function edit(string $id)
    {
        $aKategori = [
            'blank' => 'Pilih Kategori',
            'M' => 'Barang Modal',
            'A' => 'Alat',
            'BHP' => 'Bahan Habis Pakai',
            'BTHP' => 'Bahan Tidak Habis Pakai'
        ];

        $rsetKategori = Kategori::find($id);

        return view('kategori.edit', compact('rsetKategori', 'aKategori'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'deskripsi' => 'required',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        $rsetKategori = Kategori::find($id);

        // Update kategori
        $rsetKategori->update([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        // Redirect to index with success message
        return redirect()->route('kategori.index')->with(['success' => 'Data berhasil diperbarui!']);
    }

    public function destroy(string $id)
    {
        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Cek apakah ada barang yang terkait dengan kategori yang akan dihapus
            if (DB::table('barang')->where('kategori_id', $id)->exists()) {

                DB::rollBack();
                return redirect()->route('kategori.index')->with(['Gagal' => 'Data Gagal Dihapus! Kategori masih memiliki barang terkait.']);
            } else {
                // Hapus kategori
                $rsetKategori = Kategori::find($id);
                $rsetKategori->delete();

                // Komit transaksi
                DB::commit();
                return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Dihapus!']);
            }
        } catch (\Exception $e) {
            // Rollback transaksi jika ada kesalahan
            DB::rollBack();
            return redirect()->route('kategori.index')->with(['Gagal' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()]);
        }
    }


}
