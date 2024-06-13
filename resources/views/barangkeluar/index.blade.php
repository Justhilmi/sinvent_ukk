@extends('layout.layouts') 
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Daftar Barang Keluar</div>
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
                        <form action="{{ route('barangkeluar.index') }}" method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="date" name="tgl_keluar" class="form-control" value="{{ request('tgl_keluar') }}" placeholder="Tanggal Keluar">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="seri" class="form-control" value="{{ request('seri') }}" placeholder="Seri Barang">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('barangkeluar.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                        <a href="{{ route('barangkeluar.create') }}" class="btn btn-primary mb-2">Tambah Barang Keluar</a>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal Keluar</th>
                                    <th>Qty Keluar</th>
                                    <th>Nama Barang</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Barangkeluar as $barangkeluar)
                                    <tr>
                                        <td>{{ $barangkeluar->id }}</td>
                                        <td>{{ $barangkeluar->tgl_keluar }}</td>
                                        <td>{{ $barangkeluar->qty_keluar }}</td>
                                        <td>{{ $barangkeluar->barang->seri }}</td>
                                        <td>
                                            <a href="{{ route('barangkeluar.edit', $barangkeluar->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                            <!-- Form untuk handle delete -->
                                            <form action="{{ route('barangkeluar.destroy', $barangkeluar->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $Barangkeluar->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection