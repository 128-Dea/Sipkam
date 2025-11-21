@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Pengembalian Barang</h1>
        <small class="text-muted">Daftar seluruh pengembalian barang oleh mahasiswa</small>
    </div>
    <a href="{{ route('petugas.pengembalian.scan') }}" class="btn btn-primary">
        Scan QR Pengembalian
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>Peminjam</th>
                        <th>Waktu Pengembalian</th>
                        <th>Denda</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $item)
                        @php
                            $peminjaman = $item->peminjaman;
                            $totalDenda = $peminjaman?->denda?->sum('total_denda') ?? 0;
                        @endphp
                        <tr>
                            <td>#{{ $item->id_pengembalian }}</td>
                            <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                            <td>
                                {{ $peminjaman->pengguna->nama ?? '-' }}<br>
                                @if(!empty($peminjaman->pengguna?->email))
                                    <small class="text-muted">{{ $peminjaman->pengguna->email }}</small>
                                @endif
                            </td>
                            <td>{{ optional($item->waktu_pengembalian)->format('d M Y H:i') }}</td>
                            <td>
                                @if($totalDenda > 0)
                                    <span class="badge bg-danger">Ada (Rp {{ number_format($totalDenda, 0, ',', '.') }})</span>
                                @else
                                    <span class="badge bg-success">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Selesai</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada pengembalian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
