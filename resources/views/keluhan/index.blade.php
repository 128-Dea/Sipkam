@extends('layouts.app')

@section('content')
@php
    $keluhanRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp

{{-- ===== STYLE KHUSUS HALAMAN KELUHAN ===== --}}
<style>
    :root {
        --sipkam-complaint-accent: #3b82f6; /* biru tombol */
        --sipkam-complaint-accent-soft: rgba(59,130,246,0.35);
    }

    /* Background full mengikuti tema (light / dark) */
    .sipkam-complaint-page {
        min-height: 100vh;
        margin: -24px -32px -40px -32px; /* tarik keluar padding layout */
        padding: 24px 32px 40px;
        display: block;                  /* FULL width, bukan flex center lagi */
    }

    body.sipkam-light .sipkam-complaint-page {
        background: linear-gradient(135deg,#e0f2fe 0%,#f9fafb 40%,#dcfce7 100%);
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-complaint-page {
        background: radial-gradient(circle at top,#020617 0%,#020617 40%,#020617 100%);
        color: #e5e7eb;
    }

    .sipkam-complaint-shell {
        width: 100%;
        max-width: 100%;   /* << ini yang bikin wrapper mentok kanan kiri 100% */
    }

    /* HEADER */
    .sipkam-complaint-header {
        margin-bottom: 1.5rem;
    }

    .sipkam-complaint-title {
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .sipkam-complaint-subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .sipkam-complaint-btn {
        border-radius: 999px;
        padding: 0.45rem 1.7rem;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    body.sipkam-light .sipkam-complaint-btn {
        background: linear-gradient(135deg,#3b82f6,#06b6d4);
        color: #eff6ff;
        box-shadow: 0 12px 28px rgba(37,99,235,0.55);
    }

    body.sipkam-dark .sipkam-complaint-btn {
        background: linear-gradient(135deg,#3b82f6,#22c55e);
        color: #e5f2ff;
        box-shadow: 0 14px 36px rgba(37,99,235,0.8);
    }

    .sipkam-complaint-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 44px rgba(37,99,235,0.85);
    }

    /* CARD + GLOW */
    .sipkam-complaint-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
    }

    body.sipkam-light .sipkam-complaint-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(148,163,184,0.35);
        box-shadow: 0 22px 50px rgba(148,163,184,0.55);
    }

    body.sipkam-dark .sipkam-complaint-card {
        background: radial-gradient(circle at top left,#020617,#020617 55%,#020617 100%);
        border: 1px solid rgba(31,41,55,0.9);
        box-shadow: 0 26px 70px rgba(0,0,0,0.95);
    }

    .sipkam-complaint-card::before {
        content: "";
        position: absolute;
        right: -80px;
        top: -40px;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        background: radial-gradient(circle at center,var(--sipkam-complaint-accent-soft),transparent 70%);
        filter: blur(2px);
        opacity: 0.9;
        pointer-events: none;
    }

    .sipkam-complaint-card .table-responsive {
        position: relative;
        z-index: 1;
    }

    /* TABLE */
    .sipkam-complaint-table {
        margin-bottom: 0;
    }

    .sipkam-complaint-table thead th {
        border-bottom: none;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding-top: 0.75rem;
        padding-bottom: 0.55rem;
        background: transparent;
    }

    body.sipkam-light .sipkam-complaint-table thead th {
        color: #64748b;
    }

    body.sipkam-dark .sipkam-complaint-table thead th {
        color: #a7f3d0;
    }

    .sipkam-complaint-table tbody td {
        vertical-align: middle;
        font-size: 0.9rem;
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
    }

    .sipkam-complaint-table tbody tr + tr {
        border-top: 1px solid rgba(148,163,184,0.3);
    }

    body.sipkam-dark .sipkam-complaint-table tbody tr + tr {
        border-color: rgba(30,64,175,0.6);
    }

    .sipkam-complaint-table tbody tr:hover {
        background: rgba(15,23,42,0.02);
    }

    body.sipkam-dark .sipkam-complaint-table tbody tr:hover {
        background: rgba(15,23,42,0.8);
    }

    /* Foto keluhan */
    .sipkam-complaint-photo {
        max-height: 60px;
        border-radius: 0.6rem;
        object-fit: cover;
    }

    /* Tombol detail */
    .sipkam-complaint-table .btn-outline-primary {
        border-radius: 999px;
        font-size: 0.78rem;
        padding: 0.25rem 0.85rem;
    }

    /* Baris kosong */
    .sipkam-complaint-empty {
        padding: 2.3rem 0;
        font-size: 0.9rem;
    }

    @media (max-width: 767.98px) {
        .sipkam-complaint-page {
            margin: -16px -16px -24px -16px;
            padding: 16px 16px 24px;
        }
        .sipkam-complaint-shell {
            max-width: 100%;
        }
    }
</style>

<div class="sipkam-complaint-page">
    <div class="sipkam-complaint-shell">

        {{-- HEADER (logika tetap) --}}
        <div class="d-flex justify-content-between align-items-center mb-4 sipkam-complaint-header">
            <div>
                <h1 class="h3 mb-1 sipkam-complaint-title">Keluhan Mahasiswa</h1>
                <small class="sipkam-complaint-subtitle">Kelola laporan gangguan selama peminjaman</small>
            </div>
            @if($keluhanRouteScope === 'mahasiswa')
                <a href="{{ route($keluhanRouteScope . '.keluhan.create') }}"
                   class="btn btn-primary sipkam-complaint-btn">
                    Laporkan Keluhan
                </a>
            @endif
        </div>

        {{-- CARD + TABLE (LOGIKA TIDAK DIUBAH) --}}
        <div class="card border-0 shadow-sm sipkam-complaint-card">
            <div class="table-responsive">
                <table class="table table-striped mb-0 sipkam-complaint-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Barang</th>
                            <th>Keluhan</th>
                            <th>Pelapor</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keluhan as $item)
                            <tr>
                                <td>{{ $item->id_keluhan }}</td>
                                <td style="width: 100px;">
                                    @if($item->foto_url)
                                        <img src="{{ $item->foto_url }}" alt="Foto keluhan"
                                             class="img-thumbnail sipkam-complaint-photo">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ Str::limit($item->keluhan, 50) }}</td>
                                <td>{{ $item->pengguna->nama ?? '-' }}</td>
                                <td>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route($keluhanRouteScope . '.keluhan.show', $item->id_keluhan) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted sipkam-complaint-empty">
                                    Belum ada keluhan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
