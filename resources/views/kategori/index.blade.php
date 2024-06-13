@extends('layout.layouts') <!-- Assuming you have a layout named 'app.blade.php' -->

@section('content')
    <div class="container">
        <div class="row justify-content-center"> <!-- Tambahkan kelas justify-content-center di sini -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Daftar Kategori</div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('Gagal'))
                            <div class="alert alert-danger mt-3">
                                {{ session('Gagal') }}
                            </div>
                        @endif
                        
                        <form method="GET" action="{{ route('kategori.index') }}" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Cari kategori atau deskripsi..." value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">Cari</button>
                            </div>
                        </form>

                        <a href="{{ route('kategori.create') }}" class="btn btn-primary mb-2">Tambah Kategori</a>

                        <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DESKRIPSI</th>
                                <th>KATEGORI</th>
                                <th style="width: 15%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $kategoriMap = [
                                    'M' => 'Modal',
                                    'A' => 'Alat',
                                    'BHP' => 'Bahan Habis Pakai',
                                    'BTHP' => 'Bahan Tidak Habis Pakai',
                                ];
                            @endphp
                            @forelse ($rsetKategori as $rowkategori)
                                <tr>
                                    <td>{{ $rowkategori->id }}</td>
                                    <td>{{ $rowkategori->deskripsi }}</td>
                                    <td>{{ $kategoriMap[$rowkategori->kategori] ?? $rowkategori->kategori }}</td>
                                    <td class="text-center">
                                        <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('kategori.destroy', $rowkategori->id) }}" method="POST">
                                            <a href="{{ route('kategori.show', $rowkategori->id) }}" class="btn btn-sm btn-dark"><i class="fa fa-eye"></i></a>
                                            <a href="{{ route('kategori.edit', $rowkategori->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil-alt"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Data Kategori belum tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                        {{ $rsetKategori->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
