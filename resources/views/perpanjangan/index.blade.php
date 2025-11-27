@extends('layouts.app')

@section('content')
@php
    $perpanjanganRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp

<style>
    .btn-perpanjangan-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 12px 30px rgba(79,70,229,0.25);
    }

    .btn-perpanjangan-primary:hover {
        color: #ffffff;
        filter: brightness(1.05);
    }

    body.sipkam-dark .btn-perpanjangan-primary {
        box-shadow: 0 12px 32px rgba(99,102,241,0.35);
    }
</style>

<div class="d-flex justify-content-between mb-4">
    <div>
        <h1 class="h3 mb-1">Perpanjangan Peminjaman</h1>
        <small class="text-muted">Pantau status pengajuan perpanjangan</small>
    </div>
    @if($perpanjanganRouteScope === 'mahasiswa')
        <a href="{{ route($perpanjanganRouteScope . '.perpanjangan.create') }}" class="btn btn-perpanjangan-primary">Ajukan Perpanjangan</a>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Peminjaman</th>
                    <th>Pengajuan</th>
                    <th>Perpanjangan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perpanjangan as $item)
                    <tr>
                        <td>{{ $item->id_perpanjangan }}</td>
                        <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->waktu_pengajuan)->format('d M Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->waktu_perpanjangan)->format('d M Y H:i') }}</td>
                        <td>
                            <span class="badge bg-{{ $item->status_persetujuan === 'disetujui' ? 'success' : ($item->status_persetujuan === 'ditolak' ? 'danger' : 'warning') }}">
                                {{ ucfirst($item->status_persetujuan) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada pengajuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
