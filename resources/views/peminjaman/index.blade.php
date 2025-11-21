@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $role = $user?->role;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">
            @if($role === 'mahasiswa')
                Peminjaman Saya
            @else
                Daftar Peminjaman Mahasiswa
            @endif
        </h1>
        <small class="text-muted">
            @if($role === 'mahasiswa')
                Pantau semua permintaan peminjaman Anda.
            @else
                Pantau seluruh peminjaman barang yang dilakukan mahasiswa.
            @endif
        </small>
    </div>

    {{-- Tombol "Tambah Peminjaman" HANYA untuk mahasiswa --}}
    @if($role === 'mahasiswa')
        <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-primary">
            Tambah Peminjaman
        </a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">Kode Peminjaman</th>
                        @if($role === 'petugas')
                            <th>Peminjam</th>
                        @else
                            <th>Barang</th>
                        @endif
                        <th class="text-nowrap">Periode</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                        <tr>
                            {{-- Kode Peminjaman --}}
                            <td>#{{ $item->id_peminjaman }}</td>

                            {{-- Kolom kedua: Peminjam (petugas) / Barang (mahasiswa) --}}
                            @if($role === 'petugas')
                                <td>
                                    {{ $item->pengguna->nama ?? '-' }}<br>
                                    @if($item->pengguna?->email)
                                        <small class="text-muted">{{ $item->pengguna->email }}</small>
                                    @endif
                                </td>
                            @else
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                            @endif

                            {{-- Periode pinjam --}}
                            <td>
                                {{ \Carbon\Carbon::parse($item->waktu_awal)->format('d M Y H:i') }}<br>
                                <small class="text-muted">
                                    s/d {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }}
                                </small>
                            </td>

                            {{-- Status --}}
                            <td>
                                @php
                                    $status = $item->status;
                                    $badge = 'secondary';
                                    if ($status === 'berlangsung') {
                                        $badge = 'info';
                                    } elseif ($status === 'selesai') {
                                        $badge = 'success';
                                    } elseif ($status === 'ditolak') {
                                        $badge = 'danger';
                                    }
                                @endphp
                                <span class="badge bg-{{ $badge }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            {{-- Aksi: Detail (route beda untuk mahasiswa & petugas) --}}
                            <td class="text-center">
                                @if($role === 'mahasiswa')
                                    <a href="{{ route('mahasiswa.peminjaman.show', $item->id_peminjaman) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                @elseif($role === 'petugas')
                                    <a href="{{ route('petugas.peminjaman.show', $item->id_peminjaman) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Belum ada data peminjaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
