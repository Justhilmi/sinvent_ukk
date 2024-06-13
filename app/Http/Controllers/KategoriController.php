<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        // Provide a default value for the argument if not provided
        $arg = $search ?? '';
    
        // Call the stored procedure with the required argument
        $kategoriData = DB::select('CALL getKategori(?)', [$arg]);
    
        // Create query builder for kategori data
        $query = DB::table('kategori')->select('id', 'deskripsi', 'kategori');
    
        if ($search) {
            $query->where('deskripsi', 'like', '%' . $search . '%')
                ->orWhere('kategori', 'like', '%' . $search . '%');
        }
    
        $rsetKategori = $query->paginate(10);
    
        return view('kategori.index', compact('rsetKategori', 'search', 'kategoriData'));
    }
    


    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        //cek data
        // echo "data deskripsi";
        // echo $request->deskripsi;
        // die('asd');


        $request->validate([
            'deskripsi' => 'required',
            'kategori' => 'required',
        ]);

        // Create a new Kategori
        //Kategori::create([
        //    'deskripsi' => $request->deskripsi,
        //    'kategori' => $request->kategori,
       // ]);

        try {
            DB::beginTransaction(); // <= Starting the transaction

            // Insert a new order history
            DB::table('kategori')->insert([
                'deskripsi' => $request->deskripsi,
                'kategori' =>$request->kategori,
            ]);

            DB::commit(); // <= Commit the changes
        } catch (\Exception $e) {
            report($e);
            
            DB::rollBack(); // <= Rollback in case of an exception
        }

                // Redirect to index
                return redirect()->route('kategori.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id)
    {
        $rsetKategori = Kategori::find($id);

        return view('kategori.show', compact('rsetKategori'));
    }

    public function edit(string $id)
    {
        $rsetKategori = Kategori::find($id);

        return view('kategori.edit', compact('rsetKategori'));
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
