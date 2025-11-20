@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Riwayat Peminjaman</h1>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                    @php
                        $peminjaman = $item->pengembalian->peminjaman ?? null;
                    @endphp
                    <tr>
                        <td>{{ $item->id_riwayat }}</td>
                        <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                        <td>{{ ucfirst($peminjaman->status ?? '-') }}</td>
                        <td>{{ optional($peminjaman?->waktu_awal ? \Carbon\Carbon::parse($peminjaman->waktu_awal) : null)->format('d M Y') }}</td>
                        <td><a href="{{ route('mahasiswa.riwayat.show', $item->id_riwayat) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Riwayat masih kosong.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
