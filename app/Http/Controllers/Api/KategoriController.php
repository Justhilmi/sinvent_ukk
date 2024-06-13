<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rsetKategori = Kategori::all();
        return new PostResource(true, 'Data Kategori Berhasil Diambil', $rsetKategori);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|unique:kategori,deskripsi',
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = Kategori::create([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        return new PostResource(true, 'Data Berhasil Disimpan!', $kategori);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kategori = Kategori::find($id);

        if (is_null($kategori)) {
            return new PostResource(false, 'Data Tidak Ditemukan', null);
        }

        return new PostResource(true, 'Detail data Kategori', $kategori);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required|unique:kategori,deskripsi,'.$id,
            'kategori' => 'required|in:M,A,BHP,BTHP',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $kategori = Kategori::find($id);

        if (is_null($kategori)) {
            return new PostResource(false, 'Data Tidak Ditemukan', null);
        }

        $kategori->update([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        return new PostResource(true, 'Data Berhasil Diubah!', $kategori);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori = Kategori::find($id);

        if (is_null($kategori)) {
            return new PostResource(false, 'Data Tidak Ditemukan', null);
        }

        if ($kategori->barang()->exists()) {
            return new PostResource(false, 'Data Gagal Dihapus! Data sudah dipakai di tabel barang', null);
        }

        $kategori->delete();
        return new PostResource(true, 'Data Berhasil Dihapus!', null);
    }
}