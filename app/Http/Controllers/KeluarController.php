<?php

namespace App\Http\Controllers;
use App\Models\barangkeluar;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeluarController extends Controller
{
    public function index()
    {
        #$rsetBarangkeluar = barangkeluar::latest()->paginate(10);
        $Barangkeluar = barangkeluar::with('barang')->paginate(10);
        return view('barangkeluar.index',compact('Barangkeluar'));
    //     return view('barangkeluar.index');
    }

    public function create()
    {
        $barangOptions = Barang::all();       
        return view('barangkeluar.create', compact('barangOptions'));
    }

    public function store(Request $request)
    {
        $barang = Barang::find($request->barang_id);
        $barangMasukTerakhir = $barang->barangmasuk()->latest('tgl_masuk')->first();

        if ($barangMasukTerakhir && $request->tgl_keluar < $barangMasukTerakhir->tgl_masuk) {
            return redirect()->back()->withErrors(['tgl_keluar' => 'Tanggal barang keluar tidak boleh mendahului tanggal barang masuk terakhir.'])->withInput();
        }
        // Validasi data
        $request->validate( [
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required',
            'barang_id' => 'required',
        ]);

        // Simpan data barang masuk ke database
        Barangkeluar::create([
            'tgl_keluar'          => $request->tgl_keluar,
            'qty_keluar'          => $request->qty_keluar,
            'barang_id'          => $request->barang_id,
        ]);

        return redirect()->route('barangkeluar.index');
    }

    public function edit($id)
    {
        $barangkeluar = Barangkeluar::findorfail($id);
        $barangOptions = Barang::all();
        
        return view('barangkeluar.edit', compact('barangkeluar', 'barangOptions'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required',
            'barang_id' => 'required',
        ]);

        // Temukan dan perbarui data barang keluar berdasarkan ID
        $barangkeluar = Barangkeluar::findorfail($id);
        $barangkeluar->update([
            'tgl_keluar' => $request->tgl_keluar,
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id,
        ]);

        return redirect()->route('barangkeluar.index')->with('success', 'Data barang keluar berhasil diupdate');
    }

    public function destroy($id)
    {
       
        // if (DB::table('barang')->where('id', $id)->exists()){
        //     return redirect()->route('barangkeluar.index')->with(['Gagal' => 'Data Gagal Dihapus!']);
        // } else {
        //     // Hapus data barang masuk berdasarkan ID
        //     Barangkeluar::findOrFail($id)->delete();
        //     return redirect()->route('barangkeluar.index')->with(['success' => 'Data Barang Masuk Berhasil Dihapus!']);
        // }
        // Hapus data barang masuk berdasarkan ID
        Barangkeluar::findOrFail($id)->delete();

        return redirect()->route('barangkeluar.index')->with('success', 'Data barang masuk berhasil dihapus');
    }
}
