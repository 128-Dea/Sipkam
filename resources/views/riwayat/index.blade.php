@extends('layouts.app')

@section('content')

<style>
    :root {
        --sipkam-deep-1: #051F20;
        --sipkam-deep-2: #0B2B26;
        --sipkam-deep-3: #163832;
        --sipkam-soft:  #8EB69B;
        --sipkam-mist:  #DAF1DE;
        --sipkam-neon:  #a7f3d0;
    }

    /* ===== LIGHT MODE (mahasiswa) – pakai palet hijau referensi ===== */
    h1.h3.mb-4 {
        color: var(--sipkam-deep-2);
        font-weight: 700;
    }

    h1.h3.mb-4 + .card {
        border-radius: 18px;
        overflow: hidden;
        background: #f9fafb;
        box-shadow: 0 18px 35px rgba(15, 23, 42, 0.18);
        border: 1px solid rgba(148, 163, 184, 0.35);
    }

    h1.h3.mb-4 + .card .table thead th {
        background: linear-gradient(135deg, var(--sipkam-deep-2), var(--sipkam-deep-3));
        color: #e5f9ee;
        text-transform: uppercase;
        letter-spacing: .06em;
        font-size: 0.78rem;
        border: none;
    }

    h1.h3.mb-4 + .card .table tbody td {
        border-color: rgba(203, 213, 225, 0.7);
        font-size: 0.9rem;
    }

    /* ===== DARK MODE (mahasiswa) – background hitam, teks neon ===== */
    body.sipkam-dark h1.h3.mb-4 {
        color: var(--sipkam-neon);
        text-shadow: 0 0 18px rgba(34, 197, 94, 0.9);
    }

    /* kartu tabel */
    body.sipkam-dark h1.h3.mb-4 + .card {
        background: #020617;
        border-color: #111827;
        box-shadow: 0 26px 70px rgba(0,0,0,.95);
    }

    /* header tabel */
    body.sipkam-dark h1.h3.mb-4 + .card .table thead th {
        background: #020617 !important;
        color: var(--sipkam-neon) !important;
        border-color: #1f2937 !important;
    }

    /* seluruh sel body tabel (termasuk row "Riwayat masih kosong") */
    body.sipkam-dark h1.h3.mb-4 + .card .table,
    body.sipkam-dark h1.h3.mb-4 + .card .table > :not(caption) > * > * {
        background-color: #020617 !important;
        color: var(--sipkam-neon) !important;
        border-color: #111827 !important;
    }

    /* teks muted di dalam tabel (kalimat "Riwayat masih kosong.") */
    body.sipkam-dark h1.h3.mb-4 + .card .text-muted {
        color: var(--sipkam-neon) !important;
        opacity: 0.8;
    }

    /* efek hover di dark mode: tetap gelap, sedikit highlight */
    body.sipkam-dark h1.h3.mb-4 + .card .table-hover tbody tr:hover {
        background-color: #020617 !important;
        box-shadow: none;
    }

    /* badge di dark mode kasih sedikit glow */
    body.sipkam-dark h1.h3.mb-4 + .card .badge.bg-success {
        background-color: #16a34a !important;
        color: #022c22 !important;
        box-shadow: 0 0 16px rgba(34, 197, 94, 0.75);
    }

    body.sipkam-dark h1.h3.mb-4 + .card .badge.bg-danger {
        background-color: #ef4444 !important;
        color: #fee2e2 !important;
        box-shadow: 0 0 14px rgba(248, 113, 113, 0.6);
    }

</style>

<div class="sipkam-history-page">
    <div class="sipkam-history-inner">

        {{-- JUDUL (logika tidak diubah, hanya ditambah class) --}}
        <h1 class="h3 mb-4 sipkam-history-title">Riwayat Peminjaman</h1>

        {{-- CARD TABEL (logika & isi sama persis, hanya tambah class) --}}
        <div class="card border-0 shadow-sm sipkam-history-card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Total Denda</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                            @php
                                $peminjaman = $item->pengembalian->peminjaman ?? null;
                                $denda = $peminjaman?->denda?->sum('total_denda') ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $peminjaman->pengguna->nama ?? '-' }}</td>
                                <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ optional($peminjaman?->waktu_awal ? \Carbon\Carbon::parse($peminjaman->waktu_awal) : null)->format('d M Y') }}</td>
                                <td>{{ optional($item->pengembalian?->waktu_pengembalian)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @if($denda > 0)
                                        <span class="badge bg-danger">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                                    @else
                                        <span class="badge bg-success">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('mahasiswa.riwayat.show', $item->id_riwayat) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Riwayat masih kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
