<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    
	public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('merk', 'like', '%' . $search . '%')
                ->orWhere('seri', 'like', '%' . $search . '%')
                ->orWhere('spesifikasi', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function ($q) use ($search) {
                    $q->where('deskripsi', 'like', '%' . $search . '%')
                        ->orWhere('kategori', 'like', '%' . $search . '%');
                });
        }

        $rsetBarang = $query->latest()->paginate(5);

        if ($request->has('search') && $rsetBarang->count() == 1) {
            $barang = $rsetBarang->first();
            return redirect()->route('barang.show', $barang->id);
        }

        return view('barang.index', compact('rsetBarang'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $akategori = Kategori::all();
        return view('barang.create',compact('akategori'));
    }

    public function store(Request $request)
    {
    // Validate form
    $request->validate([
        'merk'          => 'required',
        'seri'          => 'required',
        'spesifikasi'   => 'required',
        'kategori_id'   => 'required|not_in:blank',
        'foto'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ]);

    // Upload image
    $foto = $request->file('foto');
    $foto->storeAs('public/foto', $foto->hashName());

    // Create post
    Barang::create([
        'merk'             => $request->merk,
        'seri'             => $request->seri,
        'spesifikasi'      => $request->spesifikasi,
        'kategori_id'      => $request->kategori_id,
        'foto'             => $foto->hashName()
    ]);

    // Redirect to index
    return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id)
    {
        $rsetBarang = Barang::find($id);


        return view('barang.show', compact('rsetBarang'));
    }

    public function edit(string $id)
    {
    $akategori = Kategori::all();
    $rsetBarang = Barang::find($id);
    $selectedKategori = Kategori::find($rsetBarang->kategori_id);

    return view('barang.edit', compact('rsetBarang', 'akategori', 'selectedKategori'));
    }

    public function update(Request $request, string $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'merk'         => 'required',
            'seri'         => 'required',
            'spesifikasi'  => 'required',
            'stok'         => 'integer|min:0',
            'kategori_id'  => 'required|not_in:blank',
            'foto'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $rsetBarang = Barang::find($id);
    
        // Set stok to 0 if not provided
        $stok = $request->has('stok') ? $request->stok : 0;
    
        // Check if image is uploaded
        if ($request->hasFile('foto')) {
            // Upload new image
            $foto = $request->file('foto');
            $foto->storeAs('public/foto', $foto->hashName());
    
            // Delete old image
            Storage::delete('storage/app/public/foto/' . $rsetBarang->foto);
    
            // Update post with new image
            $rsetBarang->update([
                'merk'          => $request->merk,
                'seri'          => $request->seri,
                'spesifikasi'   => $request->spesifikasi,
                'stok'          => $stok,
                'kategori_id'   => $request->kategori_id,
                'foto'          => $foto->hashName()
            ]);
        } else {
            // Update post without image
            $rsetBarang->update([
                'merk'          => $request->merk,
                'seri'          => $request->seri,
                'spesifikasi'   => $request->spesifikasi,
                'stok'          => $stok,
                'kategori_id'   => $request->kategori_id,
            ]);
        }
    
        // Redirect to the index page with a success message
        return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Diubah!']);
    }


    public function destroy(string $id)
    { {
            if (DB::table('barangmasuk')->where('barang_id', $id)->exists() || DB::table('barangkeluar')->where('barang_id', $id)->exists()) {
                return redirect()->back()->with(['error' => 'Data Gagal Dihapus!']);
            }

            $rsetBarang = Barang::find($id);
            $rsetBarang->delete();

            return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }

    }

}
