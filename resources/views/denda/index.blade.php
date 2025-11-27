@extends('layouts.app')

@section('content')
{{-- TAMBAHAN CSS KHUSUS UNTUK HALAMAN INI --}}
<style>
    :root {
        /* Palet warna mengikuti tema referensi */
        --denda-dark-1: #051F20;
        --denda-dark-2: #0B2B26;
        --denda-dark-3: #163832;
        --denda-mid:    #253547;
        --denda-soft:   #8EB69B;
        --denda-light:  #DAF1DE;
        --denda-text-light: #ffffff;
    }

    /* ============================
       WRAPPER DENGAN GRADIENT LINEAR
       ============================ */
    .denda-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        background: linear-gradient(
            180deg,
            var(--denda-dark-1) 0%,
            var(--denda-dark-3) 30%,
            var(--denda-soft)   70%,
            var(--denda-light)  100%
        );
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .denda-inner {
        width: 100%;
        max-width: 1150px;
    }

    @media (max-width: 767.98px) {
        .denda-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .denda-inner {
            max-width: 100%;
        }
    }

    /* ============================
       HEADER GELAP
       ============================ */
    .theme-header-block {
        background: linear-gradient(135deg, var(--denda-dark-1), var(--denda-dark-3));
        color: var(--denda-text-light);
        padding: 22px 26px;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.6);
    }

    .theme-header-block .text-muted {
        color: rgba(218, 241, 222, 0.9) !important;
    }

    .theme-header-block .text-white {
        color: var(--denda-light) !important;
    }

    .theme-header-block h1.h4 {
        font-weight: 650;
        letter-spacing: 0.04em;
    }

    /* ============================
       ALERT SUCCESS
       ============================ */
    .denda-alert {
        max-width: 1150px;
        margin: 12px auto 18px;
    }

    /* ============================
       CARD TABEL
       ============================ */
    .theme-table-card {
        border-radius: 0 0 20px 20px;
        border: none;
        margin-top: 0;
        box-shadow: 0 22px 44px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        background: #ffffff;
    }

    .theme-table-head {
        background-color: var(--denda-dark-2) !important;
        color: #f9fafb !important;
    }

    .theme-table-head th {
        border-bottom: none;
        font-weight: 500;
        letter-spacing: 0.05em;
        padding: 14px 16px;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .theme-table-card tbody td {
        border-color: #e1ebe4;
        font-size: 0.93rem;
        color: var(--denda-dark-3);
        vertical-align: middle;
    }

    .theme-table-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .theme-table-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    /* ============================
       BADGE & BUTTON SESUAI TEMA
       ============================ */
    .badge.bg-success {
        background-color: var(--denda-soft) !important;
        color: var(--denda-dark-1) !important;
    }

    .badge.bg-warning {
        background-color: var(--denda-light) !important;
        color: #051F20 !important;
    }

    .badge.bg-primary {
        background-color: var(--denda-mid) !important;
    }

    .btn-outline-primary.theme-btn {
        color: var(--denda-dark-3);
        border-color: var(--denda-dark-3);
        border-radius: 999px;
        font-size: 0.85rem;
    }
    .btn-outline-primary.theme-btn:hover {
        background-color: var(--denda-dark-3);
        color: #ffffff;
    }

    .btn-success.theme-btn {
        background: linear-gradient(135deg, var(--denda-soft), var(--denda-light));
        border: 1px solid rgba(142, 182, 155, 0.8);
        color: var(--denda-dark-1);
        border-radius: 999px;
        font-size: 0.85rem;
    }
    .btn-success.theme-btn:hover {
        filter: brightness(1.03);
    }
</style>

<div class="denda-wrapper">
    <div class="denda-inner">

        {{-- HEADER BLOCK --}}
        <div class="theme-header-block mb-0">
            <p class="text-muted small mb-1">
                Dashboard /
                <span class="text-white fw-semibold">Denda Peminjaman</span>
            </p>
            <h1 class="h4 mb-0 fw-bold text-white">Denda Peminjaman</h1>
            <small class="text-muted">
                Daftar pengguna yang terkena denda (terlambat, rusak, hilang)
            </small>
        </div>

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm denda-alert">
                <i class="bi bi-check-circle me-2" style="color: var(--denda-dark-3)"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- CARD TABEL --}}
        <div class="card shadow-sm theme-table-card">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="theme-table-head">
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Pengguna & Barang</th>
                            <th>Jenis</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Bukti Transfer</th>
                            <th style="width:180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($denda as $item)
                            @php
                                $jenis = $item->jenis;
                                $total = $item->total_denda ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $item->id_denda }}</td>

                                {{-- PENGGUNA & BARANG --}}
                                <td>
                                    <div class="fw-semibold" style="color: var(--denda-dark-3);">
                                        {{ $item->peminjaman->pengguna->nama ?? '-' }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ $item->peminjaman->barang->nama_barang ?? '-' }}
                                    </div>
                                </td>

                                {{-- JENIS --}}
                                <td>
                                    @if($jenis === 'terlambat')
                                        <span class="badge bg-warning text-dark">Terlambat</span>
                                    @elseif($jenis === 'rusak')
                                        <span class="badge bg-danger">Rusak</span>
                                    @elseif($jenis === 'hilang')
                                        <span class="badge bg-dark">Hilang</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($jenis) }}</span>
                                    @endif
                                </td>

                                {{-- NOMINAL --}}
                                <td class="fw-bold" style="color: var(--denda-mid);">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </td>

                                {{-- DETAIL NOMINAL --}}
                                <td class="small text-muted">
                                    @if($jenis === 'terlambat')
                                        @php
                                            $menit = (int) round($total / 1000);
                                        @endphp
                                        Terlambat {{ $menit }} menit × Rp 1.000
                                    @elseif($jenis === 'hilang')
                                        @php
                                            $hargaBarang = optional($item->peminjaman->barang)->harga;
                                        @endphp
                                        Harga barang:
                                        @if($hargaBarang)
                                            Rp {{ number_format($hargaBarang, 0, ',', '.') }}
                                        @else
                                            (harga barang belum diisi)
                                        @endif
                                    @else
                                        {{ $item->keterangan ?? '-' }}
                                    @endif
                                </td>

                                {{-- METODE --}}
                                <td>
                                    @if($item->metode_pembayaran === 'cash')
                                        <span class="badge bg-success">Cash</span>
                                    @elseif($item->metode_pembayaran === 'transfer')
                                        <span class="badge bg-primary">Transfer</span>
                                    @else
                                        <span class="badge bg-secondary badge-pale">Belum dipilih</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    @if($item->status_pembayaran === 'sudah')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
                                </td>

                                {{-- BUKTI TRANSFER --}}
                                <td class="small">
                                    @if($item->bukti_transfer_url)
                                        <a href="{{ $item->bukti_transfer_url }}"
                                           target="_blank"
                                           class="text-decoration-none fw-semibold"
                                           style="color: var(--denda-mid)">
                                            <i class="bi bi-file-earmark-image me-1"></i>Lihat bukti
                                        </a>
                                    @else
                                        <span class="text-muted opacity-50">-</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('petugas.denda.edit', $item->id_denda) }}"
                                           class="btn btn-sm btn-outline-primary theme-btn">
                                            Detail & Pembayaran
                                        </a>

                                        @if($item->status_pembayaran === 'belum')
                                            <form method="POST"
                                                  action="{{ route('petugas.denda.update', $item->id_denda) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status_pembayaran" value="sudah">
                                                <input type="hidden" name="metode_pembayaran" value="cash">
                                                <button class="btn btn-sm btn-success w-100 theme-btn"
                                                        onclick="return confirm('Tandai denda ini sebagai lunas (cash)?')">
                                                    <i class="bi bi-cash-stack me-1"></i>Verifikasi Lunas (Cash)
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox display-6 d-block mb-3 opacity-25"
                                       style="color: var(--denda-dark-3)"></i>
                                    Tidak ada data denda saat ini.
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
