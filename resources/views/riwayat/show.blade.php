@extends('layouts.app')

@section('content')
@php
    $peminjaman  = $riwayat->pengembalian->peminjaman ?? null;
    $barang      = $peminjaman->barang ?? null;
    $waktuAwal   = $peminjaman->waktu_awal ?? null;
    $waktuAkhir  = $peminjaman->waktu_akhir ?? null;
    $statusPinjam = $peminjaman->status ?? '-';
@endphp
<div class="d-flex justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1">Detail Riwayat</h1>
        <small class="text-muted">Riwayat #{{ $riwayat->id_riwayat }}</small>
    </div>
    <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Informasi Barang</h5>
                <p class="mb-1"><strong>Nama:</strong> {{ $barang->nama_barang ?? '-' }}</p>
                <p class="mb-1"><strong>Kode:</strong> {{ $barang->kode_barang ?? '-' }}</p>
                <p class="mb-0"><strong>Status:</strong> {{ ucfirst($statusPinjam) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Waktu</h5>
                <p class="mb-1">Mulai: {{ optional($waktuAwal ? \Carbon\Carbon::parse($waktuAwal) : null)->format('d M Y H:i') ?? '-' }}</p>
                <p class="mb-0">Selesai: {{ optional($waktuAkhir ? \Carbon\Carbon::parse($waktuAkhir) : null)->format('d M Y H:i') ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
