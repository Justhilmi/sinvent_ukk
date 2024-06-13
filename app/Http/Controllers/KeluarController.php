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
        $Barangkeluar = barangkeluar::with('barang')->paginate(10);
        return view('barangkeluar.index',compact('Barangkeluar'));
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
        $request->validate( [
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required',
            'barang_id' => 'required',
        ]);

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
        $request->validate([
            'tgl_keluar' => 'required',
            'qty_keluar' => 'required',
            'barang_id' => 'required',
        ]);

        $barangkeluar = Barangkeluar::findorfail($id);
        $barangkeluar->update([
            'tgl_keluar' => $request->tgl_keluar,
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id,
        ]);

        return redirect()->route('barangkeluar.index')->with('success', 'Data barang keluar berhasil diupdate');
    }

    public function destroy(string $id)
    {
        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Temukan barang keluar berdasarkan ID
            $barangKeluar = BarangKeluar::find($id);

            if (!$barangKeluar) {
                // Rollback transaksi jika barang keluar tidak ditemukan
                DB::rollBack();
                return redirect()->route('barangkeluar.index')->with(['error' => 'Data tidak ditemukan']);
            }

            // Hapus barang keluar
            $barangKeluar->delete();

            // Komit transaksi
            DB::commit();
            return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return redirect()->route('barangkeluar.index')->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
