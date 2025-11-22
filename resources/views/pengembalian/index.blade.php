@extends('layouts.app')

@section('content')
<<<<<<< Updated upstream
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
=======
@php
    $pengembalianRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp

{{-- ===== STYLE KHUSUS HALAMAN PENGEMBALIAN ===== --}}
<style>
    :root {
        --sipkam-return-accent: #22c55e;
        --sipkam-return-accent-soft: rgba(34, 197, 94, 0.22);
    }

    /* Wrapper halaman supaya background-nya full dan rapi */
    .sipkam-return-page {
        min-height: 100vh;

        /* tarik keluar padding container bawaan layout */
        margin: -24px -32px -40px -32px;
        padding: 24px 32px 40px;

        display: flex;
        justify-content: center;
    }

    body.sipkam-light .sipkam-return-page {
        background: linear-gradient(135deg,#e0f2fe 0%,#f9fafb 40%,#dcfce7 100%);
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-return-page {
        background: radial-gradient(circle at top,#020617 0%,#020617 40%,#020617 100%);
        color: #e5e7eb;
    }

    /* Kontainer tengah (biar tidak terlalu lebar) */
    .sipkam-return-shell {
        width: 100%;
        max-width: 1120px;
    }

    /* HEADER */
    .sipkam-return-header {
        align-items: center;
    }

    .sipkam-return-title {
        font-weight: 700;
        letter-spacing: 0.03em;
    }

    .sipkam-return-subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .sipkam-return-btn {
        border-radius: 999px;
        padding: 0.45rem 1.6rem;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 14px 28px rgba(37,99,235,0.38);
    }

    body.sipkam-light .sipkam-return-btn {
        background: linear-gradient(135deg,#22c55e,#16a34a);
        color: #022c22;
    }

    body.sipkam-dark .sipkam-return-btn {
        background: linear-gradient(135deg,#22c55e,#4ade80);
        color: #020617;
        box-shadow: 0 16px 40px rgba(34,197,94,0.6);
    }

    .sipkam-return-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 20px 46px rgba(34,197,94,0.65);
    }

    /* CARD UTAMA */
    .sipkam-return-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        padding: 1.75rem 1.75rem 1.5rem;
    }

    body.sipkam-light .sipkam-return-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(148,163,184,0.35);
        box-shadow: 0 22px 48px rgba(148,163,184,0.45);
    }

    body.sipkam-dark .sipkam-return-card {
        background: radial-gradient(circle at top left,#020617,#020617 55%,#020617 100%);
        border: 1px solid rgba(31,41,55,0.9);
        box-shadow: 0 26px 60px rgba(0,0,0,0.9);
    }

    /* Glow dekoratif di sisi kanan card (inspirasi daun/glow) */
    .sipkam-return-card::before {
        content: "";
        position: absolute;
        right: -80px;
        top: -40px;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        background: radial-gradient(circle at center, rgba(34,197,94,0.35), transparent 70%);
        filter: blur(2px);
        opacity: 0.95;
        pointer-events: none;
    }

    body.sipkam-light .sipkam-return-card::before {
        background: radial-gradient(circle at center, rgba(34,197,94,0.25), transparent 70%);
    }

    body.sipkam-dark .sipkam-return-card::before {
        background: radial-gradient(circle at center, rgba(22,163,74,0.45), transparent 70%);
    }

    /* Garis halus di atas tabel */
    .sipkam-return-card::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        height: 1px;
        background: linear-gradient(90deg,transparent,rgba(148,163,184,0.4),transparent);
        opacity: 0.7;
        pointer-events: none;
    }

    /* TABLE STYLE */
    .sipkam-return-table {
        margin-bottom: 0;
    }

    .sipkam-return-table thead th {
        border-bottom: none;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.09em;
        padding-top: 0.75rem;
        padding-bottom: 0.55rem;
        background: transparent;
    }

    body.sipkam-light .sipkam-return-table thead th {
        color: #64748b;
    }

    body.sipkam-dark .sipkam-return-table thead th {
        color: #a7f3d0;
    }

    .sipkam-return-table tbody td {
        vertical-align: middle;
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
        font-size: 0.9rem;
    }

    .sipkam-return-table tbody tr + tr {
        border-top: 1px solid rgba(148,163,184,0.3);
    }

    body.sipkam-dark .sipkam-return-table tbody tr + tr {
        border-color: rgba(30,64,175,0.6);
    }

    .sipkam-return-table tbody tr:hover {
        background: rgba(15,23,42,0.02);
    }

    body.sipkam-dark .sipkam-return-table tbody tr:hover {
        background: rgba(15,23,42,0.8);
    }

    /* Kolom status jadi lebih menonjol */
    .sipkam-return-table tbody td:nth-child(5) {
        text-transform: capitalize;
        font-weight: 600;
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-return-table tbody td:nth-child(5) {
        color: #bbf7d0;
    }

    /* Teks "Belum ada pengembalian" */
    .sipkam-return-empty {
        padding: 2.5rem 0;
        font-size: 0.95rem;
    }

    @media (max-width: 767.98px) {
        .sipkam-return-page {
            margin: -16px -16px -24px -16px;
            padding: 16px 16px 24px;
        }

        .sipkam-return-card {
            padding: 1.25rem 1.1rem;
        }

        .sipkam-return-card::before {
            right: -120px;
            top: 40%;
            width: 220px;
            height: 220px;
        }
    }
</style>

<div class="sipkam-return-page">
    <div class="sipkam-return-shell">

        {{-- HEADER (logika tetap, hanya ditambah class untuk styling) --}}
        <div class="d-flex justify-content-between mb-4 sipkam-return-header">
            <div>
                <h1 class="h3 mb-1 sipkam-return-title">Pengembalian Barang</h1>
                <div class="sipkam-return-subtitle">
                    <small>Riwayat pengembalian barang pada sistem SIPKAM</small>
                </div>
            </div>

            @if($pengembalianRouteScope === 'mahasiswa')
                <a href="{{ route($pengembalianRouteScope . '.pengembalian.create') }}"
                   class="btn btn-primary sipkam-return-btn">
                    Input Pengembalian
                </a>
            @endif
        </div>

        {{-- CARD TABEL (logika tabel tidak diubah) --}}
        <div class="card border-0 shadow-sm sipkam-return-card">
            <div class="table-responsive">
                <table class="table sipkam-return-table">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Barang</th>
                            <th>Peminjam</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalian as $item)
                            <tr>
                                <td>{{ $item->id_pengembalian }}</td>
                                <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $item->peminjaman->pengguna->nama ?? '-' }}</td>
                                <td>{{ optional($item->waktu_pengembalian)->format('d M Y H:i') }}</td>
                                <td>{{ ucfirst($item->status ?? 'selesai') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="text-center text-muted sipkam-return-empty">
                                    Belum ada pengembalian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

>>>>>>> Stashed changes
    </div>
</div>
@endsection
