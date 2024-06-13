@extends('layout.layouts')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Daftar Barang Masuk</div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('barangmasuk.index') }}" method="GET" class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="date" name="tgl_masuk" class="form-control" value="{{ request('tgl_masuk') }}" placeholder="Tanggal Masuk">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="seri" class="form-control" value="{{ request('seri') }}" placeholder="Seri Barang">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('barangmasuk.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>

                        <a href="{{ route('barangmasuk.create') }}" class="btn btn-primary mb-2">Tambah Barang Masuk</a>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Quantity Masuk</th>
                                    <th>Barang</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Barangmasuk as $barangmasuk)
                                    <tr>
                                        <td>{{ $barangmasuk->id }}</td>
                                        <td>{{ $barangmasuk->tgl_masuk }}</td>
                                        <td>{{ $barangmasuk->qty_masuk }}</td>
                                        <td>{{ $barangmasuk->barang->seri }}</td>
                                        <td>
                                            <a href="{{ route('barangmasuk.edit', $barangmasuk->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                            <!-- Form untuk handle delete -->
                                            <form action="{{ route('barangmasuk.destroy', $barangmasuk->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $Barangmasuk->appends(request()->input())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
