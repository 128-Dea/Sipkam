@extends('layouts.app')

@section('content')

<style>
    /* PALET TEMA SIPKAM */
    :root {
    --sipkam-deep-1: #051F20;
    --sipkam-deep-2: #0B2B26;
    --sipkam-deep-3: #163832;
    --sipkam-deep-4: #235347;
    --sipkam-soft:  #8EB69B;
    --sipkam-mist:  #DAF1DE;

    --sipkam-card-radius: 18px;
}

    /* WRAPPER HALAMAN DETAIL */
    .sipkam-detail-shell {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1.75rem 1.5rem 2.5rem;
    }

    /* HEADER DI ATAS */
    .sipkam-detail-header {
        margin-bottom: 1.5rem;
    }

    .sipkam-detail-header .breadcrumb-text {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .sipkam-detail-header h1 {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--sipkam-deep-2);
    }

    body.sipkam-dark .sipkam-detail-header h1 {
        color: #bbf7d0;
    }

    body.sipkam-dark .sipkam-detail-header .breadcrumb-text {
        color: #9ca3af;
    }

    /* GRID 2 KOLOM KONTEN */
    .sipkam-detail-layout {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(0, 1.1fr);
        gap: 1.5rem;
        align-items: flex-start;
    }

    @media (max-width: 991.98px) {
        .sipkam-detail-shell {
            padding-inline: 1rem;
        }

        .sipkam-detail-layout {
            grid-template-columns: 1fr;
        }
    }

    /* KARTU DASAR */
    .sipkam-card {
        border-radius: var(--sipkam-card-radius);
        border: 1px solid rgba(148, 163, 184, 0.25);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
        overflow: hidden;
    }

    .sipkam-card-header {
        padding: 0.85rem 1.25rem 0.2rem;
        border-bottom: none;
        background: transparent;
    }

    .sipkam-card-body {
        padding: 0.8rem 1.25rem 1.1rem;
    }

    body.sipkam-dark .sipkam-card {
        background: #020617;
        border-color: rgba(31, 41, 55, 0.9);
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.9);
        color: #e5e7eb;
    }

    /* FOTO BARANG – BINGKAI SAMA SEPERTI AWAL */
    .sipkam-detail-photo {
        margin-bottom: 1rem;
    }

    .sipkam-detail-photo img {
        max-height: 320px;
        width: 100%;
        object-fit: cover;
        border-radius: 16px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.30);
    }

    /* TABEL DETAIL */
    .sipkam-detail-table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .sipkam-detail-table th {
        width: 170px;
        font-weight: 600;
        color: #64748b;
        border-top: none;
        padding: 0.55rem 0.2rem;
    }

    .sipkam-detail-table td {
        border-top: none;
        padding: 0.55rem 0.2rem;
        color: #0f172a;
    }

    .sipkam-detail-table tr + tr th,
    .sipkam-detail-table tr + tr td {
        border-top: 1px solid rgba(226, 232, 240, 0.9);
    }

    body.sipkam-dark .sipkam-detail-table th {
        color: #9ca3af;
    }

    body.sipkam-dark .sipkam-detail-table td {
        color: #e5e7eb;
    }

    /* STRIP TIPIS EMAS DI ATAS KARTU DETAIL */
    .sipkam-card-detail {
        position: relative;
    }
    .sipkam-card-detail::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--sipkam-deep-3), var(--sipkam-soft), #facc15);
    }

    /* PANEL KANAN: RINGKASAN STOK & PINJAM */
    .sipkam-card-side + .sipkam-card-side {
        margin-top: 1rem;
    }

    .sipkam-card-side h5 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    body.sipkam-dark .sipkam-card-side h5 {
        color: #bbf7d0;
    }

    .sipkam-summary-table {
        margin-bottom: 0;
        font-size: 0.85rem;
    }

    .sipkam-summary-table th {
        border-top: none;
        padding: 0.4rem 0;
        font-weight: 500;
        color: #64748b;
    }

    .sipkam-summary-table td {
        border-top: none;
        padding: 0.4rem 0;
        color: #0f172a;
    }

    .sipkam-summary-table tr + tr th,
    .sipkam-summary-table tr + tr td {
        border-top: 1px solid rgba(226, 232, 240, 0.9);
    }

    body.sipkam-dark .sipkam-summary-table th {
        color: #9ca3af;
    }
    body.sipkam-dark .sipkam-summary-table td {
        color: #e5e7eb;
    }

    /* BADGE STATUS – BERI SEDIKIT GLOW DI DARK MODE */
    body.sipkam-dark .badge.bg-success,
    body.sipkam-dark .badge.bg-warning,
    body.sipkam-dark .badge.bg-info,
    body.sipkam-dark .badge.bg-danger {
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.45);
    }

    /* CTA "PINJAM SEKARANG" */
    .sipkam-cta-primary {
        border-radius: 999px;
        font-weight: 600;
        padding-block: 0.7rem;
        background: #16a34a;
        border: none;
        color: #f9fafb;
        box-shadow: 0 18px 40px rgba(22, 163, 74, 0.6);
        transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
    }

    .sipkam-cta-primary:hover {
        filter: brightness(1.05);
        transform: translateY(-1px);
        box-shadow: 0 22px 50px rgba(22, 163, 74, 0.75);
        color: #f9fafb;
    }

    body.sipkam-dark .sipkam-cta-primary {
        background: #22c55e;
        box-shadow: 0 0 25px rgba(34, 197, 94, 0.9);
        color: #020617;
    }

    body.sipkam-dark .sipkam-cta-primary:hover {
        filter: brightness(1.08);
        box-shadow: 0 0 32px rgba(34, 197, 94, 1);
    }

    /* TOMBOL SECONDARY DI PANEL PETUGAS */
    .sipkam-btn-outline {
        border-radius: 999px;
    }

    /* MOBILE: KARTU STACKED */
    @media (max-width: 575.98px) {
        .sipkam-detail-header h1 {
            font-size: 1.2rem;
        }
    }

    /* BACKGROUND GLOBAL SESUAI TEMA HIJAU–TEAL */
body.sipkam-light {
    background:
        radial-gradient(circle at 50% 20%, rgba(255,255,255,0.28) 0%, transparent 55%),
        linear-gradient(
            180deg,
            var(--sipkam-mist) 0%,   /* #DAF1DE – atas terang */
            #cfe7d6       25%,
            var(--sipkam-soft) 55%, /* #8EB69B – tengah hijau lembut */
            #2b5d4f       80%,
            var(--sipkam-deep-2) 100%  /* #0B2B26 – bawah lebih gelap */
        );
    background-attachment: fixed;
    color: #0f172a;
}

/* MODE GELAP: HITAM + TEKS NEON (sudah nyatu dengan styling kartu neon tadi) */
body.sipkam-dark {
    background:
        radial-gradient(circle at 50% 0%, rgba(148,163,184,0.20) 0%, transparent 55%),
        linear-gradient(180deg, #020617 0%, #020617 55%, #020617 100%);
    background-attachment: fixed;
    color: #e5e7eb;
}
    
</style>

<div class="sipkam-detail-shell">

    {{-- HEADER / BREADCRUMB --}}
    <div class="sipkam-detail-header">
        <p class="breadcrumb-text mb-1">
            <a href="{{ route('barang.index') }}" class="text-decoration-none text-muted">
                Barang
            </a>
            /
            <span class="fw-semibold">{{ $barang->nama_barang }}</span>
        </p>
        <h1 class="mb-0">{{ $barang->nama_barang }}</h1>
    </div>

    <div class="sipkam-detail-layout">

        {{-- KOLOM KIRI: FOTO + DETAIL BARANG --}}
        <div>

            @if($barang->foto_url)
                <div class="sipkam-detail-photo">
                    <img src="{{ $barang->foto_url }}"
                         alt="Foto {{ $barang->nama_barang }}"
                         class="img-fluid">
                </div>
            @endif

            <div class="sipkam-card sipkam-card-detail">
                <div class="sipkam-card-header">
                    <h5 class="mb-0">Detail Barang</h5>
                </div>
                <div class="sipkam-card-body">
                    <table class="table table-sm sipkam-detail-table">
                        <tr>
                            <th>Kode Barang</th>
                            <td>{{ $barang->kode_barang }}</td>
                        </tr>
                        <tr>
                            <th>Nama Barang</th>
                            <td>{{ $barang->nama_barang }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $barang->kategori->nama_kategori ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>
                                {{ $barang->deskripsi ?? 'Layanan peminjaman barang di kampus disediakan untuk mendukung kegiatan akademik dan operasional mahasiswa. Ketersediaan barang bersifat terbatas dan bergantung pada stok yang ada pada saat pengajuan peminjaman.

Setiap peminjam wajib menjaga barang yang digunakan agar tetap dalam kondisi baik. Barang tidak boleh dirusak, dan apabila terjadi kerusakan akibat kelalaian peminjam, maka akan dikenakan denda sesuai dengan ketentuan yang berlaku.

Peminjam juga diwajibkan mengembalikan barang tepat waktu. Keterlambatan pengembalian dapat dikenai sanksi administratif sesuai kebijakan kampus.' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>
                                @if($barang->harga)
                                    Rp {{ number_format($barang->harga, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Sistem</th>
                            <td>
                                @php $status = $barang->status_otomatis; @endphp
                                @if($status === 'tersedia')
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($status === 'dipinjam')
                                    <span class="badge bg-warning text-dark">Sedang Dipinjam</span>
                                @elseif($status === 'dalam_service')
                                    <span class="badge bg-info text-dark">Sedang Service</span>
                                @elseif($status === 'habis')
                                    <span class="badge bg-secondary">Stok Habis</span>
                                @else
                                    <span class="badge bg-danger">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Manual (DB)</th>
                            <td>{{ $barang->status ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: RINGKASAN STOK + AKSI --}}
        <div>

            <div class="sipkam-card sipkam-card-side mb-3">
                <div class="sipkam-card-body">
                    <h5 class="card-title mb-3">Ringkasan Stok</h5>
                    <table class="table table-sm sipkam-summary-table mb-0">
                        <tr>
                            <th>Stok Total</th>
                            <td class="text-end fw-semibold">{{ $barang->stok ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th>Sedang Dipinjam</th>
                            <td class="text-end">{{ $barang->stok_dipinjam }}</td>
                        </tr>
                        <tr>
                            <th>Sedang Service</th>
                            <td class="text-end">{{ $barang->stok_service }}</td>
                        </tr>
                        <tr>
                            <th>Stok Tersedia</th>
                            <td class="text-end fw-bold">{{ $barang->stok_tersedia }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            @auth
                @if(auth()->user()->role === 'petugas')
                    <div class="sipkam-card sipkam-card-side">
                        <div class="sipkam-card-body">
                            <h5 class="card-title mb-3">Manajemen Stok</h5>
                            <p class="small text-muted mb-3">
                                Atur stok total barang ini. Status otomatis akan mengikuti stok & transaksi.
                            </p>
                            <div class="d-flex gap-2 mb-2">
                                <form action="{{ route('petugas.barang.stok.kurang', $barang->id_barang) }}"
                                      method="POST" class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="jumlah" value="1">
                                    <button class="btn btn-outline-secondary w-100 sipkam-btn-outline" type="submit">
                                        − 1 Stok
                                    </button>
                                </form>
                                <form action="{{ route('petugas.barang.stok.tambah', $barang->id_barang) }}"
                                      method="POST" class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="jumlah" value="1">
                                    <button class="btn btn-outline-secondary w-100 sipkam-btn-outline" type="submit">
                                        + 1 Stok
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('petugas.barang.edit', $barang->id_barang) }}"
                               class="btn btn-primary w-100 sipkam-btn-outline">
                                Edit Detail Barang
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Mahasiswa: tombol pinjam --}}
                    <div class="sipkam-card sipkam-card-side">
                        <div class="sipkam-card-body">
                            <h5 class="card-title mb-3">Pinjam Barang</h5>
                            @if($barang->status_otomatis === 'tersedia')
                                <a href="{{ route('mahasiswa.peminjaman.create', ['barang_id' => $barang->id_barang]) }}"
                                   class="btn sipkam-cta-primary w-100">
                                    Pinjam Sekarang
                                </a>
                            @else
                                <div class="alert alert-warning mb-0">
                                    Barang ini tidak tersedia untuk dipinjam saat ini.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <div class="sipkam-card sipkam-card-side">
                    <div class="sipkam-card-body">
                        <h5 class="card-title mb-2">Login Diperlukan</h5>
                        <p class="small mb-3">
                            Silakan login sebagai mahasiswa untuk meminjam barang.
                        </p>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100 sipkam-btn-outline">
                            Login
                        </a>
                    </div>
                </div>
            @endauth

        </div>

    </div>
</div>
@endsection
