@extends('layouts.app')

@section('content')
@php
    $pengembalianRouteScope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
@endphp

<style>
    :root {
        /* Palet warna sesuai referensi */
        --color-dark-1: #051F20;   /* paling gelap */
        --color-dark-2: #0B2B26;
        --color-dark-3: #163832;
        --color-dark-4: #253547;
        --color-soft:   #8EB69B;   /* hijau sage */
        --color-light:  #DAF1DE;   /* hijau pucat */
        --text-white:   #ffffff;
    }

    /* ============================
       WRAPPER DENGAN GRADIENT TEMA
       ============================ */
    .pengembalian-wrapper {
        /* full-bleed: isi area konten penuh */
        margin: -24px -32px -24px -32px;
        padding: 40px 32px 56px;

        min-height: calc(100vh - 64px);

        /* gradasi: bawah terang, atas gelap, pakai palet referensi */
        background:
            radial-gradient(
                circle at 50% 120%,
                var(--color-light) 0,
                var(--color-soft) 32%,
                var(--color-dark-3) 70%,
                var(--color-dark-1) 100%
            );

        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        display: flex;
        align-items: flex-start;
    }

    /* frame biar konten nggak terlalu lebar tapi tetap lega */
    .pengembalian-inner {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    @media (max-width: 992px) {
        .pengembalian-wrapper {
            margin: -16px;
            padding: 24px 16px 40px;
        }
    }

    /* ============================
       HEADER BLOCK GELAP
       ============================ */
    .header-block {
        background: linear-gradient(135deg, var(--color-dark-1), var(--color-dark-3));
        color: var(--text-white);
        padding: 26px 32px;
        border-radius: 22px 22px 0 0;
        box-shadow: 0 18px 38px rgba(5, 31, 32, 0.55);
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(142, 182, 155, 0.35);
    }

    .header-block h1 {
        font-weight: 700;
        font-size: 1.9rem;
        letter-spacing: 0.04em;
        margin: 0 0 4px 0;
    }

    .header-block small {
        color: rgba(218, 241, 222, 0.9);
        font-weight: 300;
    }

    @media (max-width: 768px) {
        .header-block {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            padding: 20px 18px;
        }
    }

    /* Tombol “Input Pengembalian” */
    .btn-custom-light {
        background: linear-gradient(135deg, var(--color-dark-3), var(--color-dark-2));
        color: #e9f7f0;
        border-radius: 999px;
        border: 1px solid rgba(142, 182, 155, 0.7);
        padding: 9px 22px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        transition: all 0.25s ease;
        box-shadow:
            0 12px 26px rgba(5, 31, 32, 0.8),
            0 0 0 1px rgba(142, 182, 155, 0.5);
    }

    .btn-custom-light:hover {
        background: linear-gradient(135deg, var(--color-soft), var(--color-light));
        color: var(--color-dark-1);
        transform: translateY(-1px);
        box-shadow:
            0 18px 32px rgba(5, 31, 32, 0.9),
            0 0 0 1px rgba(218, 241, 222, 0.75);
    }

    .btn-custom-light:active {
        transform: translateY(0);
        box-shadow:
            0 8px 18px rgba(5, 31, 32, 0.9),
            0 0 0 1px rgba(142, 182, 155, 0.7);
    }

    /* ============================
       CARD TABEL PUTIH
       ============================ */
    .table-card {
        background: #ffffff;
        border-radius: 0 0 22px 22px;
        border: none;
        box-shadow: 0 22px 44px rgba(5, 31, 32, 0.45);
        overflow: hidden;
    }

    /* Header tabel */
    .custom-table thead th {
        background-color: var(--color-dark-2);
        color: #f8fafc;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.08em;
        padding: 16px 22px;
        border: none;
    }

    /* Body tabel */
    .custom-table tbody td {
        padding: 18px 22px;
        vertical-align: middle;
        color: var(--color-dark-3);
        border-bottom: 1px solid #e2ece5;
        font-size: 0.95rem;
    }

    .custom-table tbody tr:nth-child(even) {
        background-color: #f7faf8;
    }

    .custom-table tbody tr:hover {
        background-color: #ecf4ee;
    }

    /* Avatar kecil di kolom peminjam */
    .avatar-placeholder {
        background: #e5f2ea;
        color: var(--color-dark-3);
        font-size: 0.8rem;
    }

    /* Status Badge */
    .status-badge {
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }
    
    .status-selesai {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    /* Empty state supaya tetap kebaca di atas background terang */
    .pengembalian-wrapper .empty-state {
        opacity: 0.85 !important;
        color: rgba(22, 56, 50, 0.8) !important;
    }

    @media (max-width: 768px) {
        .pengembalian-wrapper { padding: 20px 14px 32px; }
    }
</style>

<div class="pengembalian-wrapper">
    <div class="pengembalian-inner">

        {{-- 1. Bagian Header Gelap --}}
        <div class="header-block">
            <div>
                <h1 class="h3 mb-1">Pengembalian Barang</h1>
                <small>Kelola data pengembalian aset kampus</small>
            </div>
            
            @if($pengembalianRouteScope === 'mahasiswa')
                <a href="{{ route($pengembalianRouteScope . '.pengembalian.create') }}" class="btn btn-custom-light">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span>Input Pengembalian</span>
                </a>
            @endif
        </div>

        {{-- 2. Bagian Tabel dalam Card Putih --}}
        <div class="card table-card">
            <div class="table-responsive">
                <table class="table mb-0 custom-table">
                    <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th width="25%">Barang</th>
                            <th width="25%">Peminjam</th>
                            <th width="20%">Waktu</th>
                            <th width="20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalian as $item)
                            <tr>
                                <td class="fw-bold text-primary">#{{ $item->id_pengembalian }}</td>
                                <td class="fw-semibold">{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder me-2 rounded-circle text-center fw-bold text-muted"
                                             style="width:30px; height:30px; line-height:30px;">
                                            {{ substr($item->peminjaman->pengguna->nama ?? 'U', 0, 1) }}
                                        </div>
                                        {{ $item->peminjaman->pengguna->nama ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <i class="bi bi-clock me-1 text-muted"></i>
                                    {{ optional($item->waktu_pengembalian)->format('d M Y H:i') }}
                                </td>
                                <td>
                                    @php
                                        $status = strtolower($item->status ?? 'selesai');
                                        $badgeClass = $status === 'selesai' ? 'status-selesai' : 'status-pending';
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted opacity-50 empty-state">
                                        <i class="bi bi-archive display-4 mb-3 d-block"></i>
                                        <p class="mb-0">Belum ada data pengembalian.</p>
                                    </div>
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
