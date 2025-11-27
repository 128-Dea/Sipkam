@extends('layouts.app')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h1 class="h4 mb-3">Hapus Kategori</h1>
        <p class="mb-2">Kategori <strong>{{ $kategori->nama_kategori }}</strong> masih memiliki barang terkait.</p>
        <p class="mb-3">Jika dilanjutkan, <strong>semua barang di kategori ini juga akan terhapus</strong>.</p>

        <div class="mb-3">
            <h6 class="mb-2">Daftar barang yang akan dihapus:</h6>
            <ul class="list-group list-group-flush">
                @foreach($barang as $item)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>{{ $item->nama_barang }}</span>
                        <span class="text-muted small">ID: {{ $item->id_barang }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <form action="{{ route('petugas.kategori.destroy', $kategori->id_kategori) }}" method="POST" class="d-flex gap-2">
            @csrf
            @method('DELETE')
            <input type="hidden" name="confirm_delete" value="1">

            <a href="{{ route('petugas.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
            <button type="submit" class="btn btn-danger">Hapus kategori & barang</button>
        </form>
    </div>
</div>
@endsection
