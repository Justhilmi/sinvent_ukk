@extends('layout.master')

@section('content')
@forelse ($rset_Category as $kategori)
{{ $kategori->id }}
{{ $kategori->deskripsi }}
{{ $kategori->ketKategori }}
{{ $kategori->kategori }}
<br>
@empty
 {{"kosong"}}
@endforelse
@endsection 