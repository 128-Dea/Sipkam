@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body py-5">
                <div class="mb-3">
                    {{-- Ceklis sederhana pakai icon bootstrap --}}
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                         style="width:80px;height:80px;border:4px solid #22c55e;">
                        <span class="fs-1 text-success">&#10003;</span>
                    </div>
                </div>
                <h2 class="h4 mb-2">Pengembalian Berhasil Diproses</h2>
                <p class="text-muted mb-4">
                    Data pengembalian untuk peminjaman #{{ $peminjaman->id_peminjaman }}
                    telah disimpan di sistem.
                </p>

                <div class="text-start d-inline-block">
                    <p class="mb-1"><strong>Barang:</strong> {{ $peminjaman->barang->nama_barang ?? '-' }}</p>
                    <p class="mb-1"><strong>Peminjam:</strong> {{ $peminjaman->pengguna->nama ?? '-' }}</p>
                    <p class="mb-1">
                        <strong>Waktu Pengembalian:</strong>
                        {{ optional($peminjaman->pengembalian->waktu_pengembalian)->format('d M Y H:i') }}
                    </p>
                </div>

                <div class="mt-4">
                    <a href="{{ route('petugas.pengembalian.index') }}" class="btn btn-primary">
                        Kembali ke Daftar Pengembalian
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
