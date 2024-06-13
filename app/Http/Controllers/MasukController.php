<?php

namespace App\Http\Controllers;
use App\Models\barangmasuk;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasukController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data pencarian dari request
        $tgl_masuk = $request->input('tgl_masuk');
        $seri = $request->input('seri');

        // Query data barang masuk dengan eager loading relasi barang
        $query = Barangmasuk::with('barang');

        // Filter berdasarkan tanggal masuk jika ada input
        if ($tgl_masuk) {
            $query->where('tgl_masuk', $tgl_masuk);
        }

        // Filter berdasarkan seri barang jika ada input
        if ($seri) {
            $query->whereHas('barang', function ($query) use ($seri) {
                $query->where('seri', 'like', '%' . $seri . '%');
            });
        }

        // Pagination dengan 10 item per halaman
        $Barangmasuk = $query->paginate(10);

        // Kirim data ke view
        return view('barangmasuk.index', compact('Barangmasuk'));
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
    public function destroy(string $id)
    {
        // Memulai transaksi
        DB::beginTransaction();

        try {
            // Temukan barang masuk berdasarkan ID
            $barangMasuk = BarangMasuk::find($id);

            if (!$barangMasuk) {
                // Rollback transaksi jika barang masuk tidak ditemukan
                DB::rollBack();
                return redirect()->route('barangmasuk.index')->with(['error' => 'Data tidak ditemukan']);
            }

            // Hapus barang masuk
            $barangMasuk->delete();

            // Komit transaksi
            DB::commit();
            return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return redirect()->route('barangmasuk.index')->with(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}