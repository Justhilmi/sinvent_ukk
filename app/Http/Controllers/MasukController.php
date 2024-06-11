<?php

namespace App\Http\Controllers;
use App\Models\barangmasuk;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasukController extends Controller
{
    public function index()
    {
        #$rsetBarangmasuk = barangmasuk::latest()->paginate(10);
        $Barangmasuk = barangmasuk::with('barang')->paginate(10);
        return view('barangmasuk.index',compact('Barangmasuk'));
        //return view('barangmasuk.index');
    }

    public function create()
    {
        $barangOptions = Barang::all();
        #dd($barangOptions);

        return view('barangmasuk.create', compact('barangOptions'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate( [
            'tgl_masuk' => 'required',
            'qty_masuk' => 'required',
            'barang_id' => 'required',
        ]);

        // Simpan data barang masuk ke database
        Barangmasuk::create([
            'tgl_masuk'          => $request->tgl_masuk,
            'qty_masuk'          => $request->qty_masuk,
            'barang_id'          => $request->barang_id,
        ]);

        return redirect()->route('barangmasuk.index')->with('success', 'Data barang masuk berhasil dihapus');
    }

    public function edit($id)
    {
        // Mengambil data barang masuk berdasarkan ID
        $rsetBarangmasuk = Barangmasuk::findOrFail($id);
        $baranggOptions = Barang::all();

        return view('barangmasuk.edit', compact('rsetBarangmasuk', 'baranggOptions'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate( [
            'tgl_masuk' => 'required',
            'qty_masuk' => 'required',
            'barang_id' => 'required',
        ]);

        // Mengupdate data barang masuk berdasarkan ID
        $rsetBarangmasuk = Barangmasuk::findOrFail($id);
        $rsetBarangmasuk->update([
            'tgl_masuk' => $request->tgl_masuk,
            'qty_masuk' => $request->qty_masuk,
            'barang_id' => $request->barang_id,
        ]);

        return redirect()->route('barangmasuk.index')->with('success', 'Data barang masuk berhasil diupdate');
    }
    public function destroy($id)
    {
        // Cek apakah data barang masuk dengan ID yang diberikan ada di tabel barang
        // if (DB::table('barang')->where('id', $id)->exists()){
        //     return redirect()->route('barangmasuk.index')->with(['Gagal' => 'Data Gagal Dihapus!']);
        // } else {
        //     // Hapus data barang masuk berdasarkan ID
        //     Barangmasuk::findOrFail($id)->delete();
        //     return redirect()->route('barangmasuk.index')->with(['success' => 'Data Barang Masuk Berhasil Dihapus!']);
        // }
        // Hapus data barang masuk berdasarkan ID
        Barangmasuk::findOrFail($id)->delete();

        return redirect()->route('barangmasuk.index')->with('success', 'Data barang masuk berhasil dihapus');
    }
}