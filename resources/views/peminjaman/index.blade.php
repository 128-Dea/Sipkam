@extends('layouts.app')

@section('content')
<<<<<<< Updated upstream
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
=======

{{-- ===== STYLE KHUSUS HALAMAN DAFTAR PEMINJAMAN ===== --}}
<style>
    /* Wrapper halaman: gradient biru–ungu, dibuat full nutup padding container */
    .peminjaman-page {
        min-height: 100vh;

        /* Tarik keluar dari container supaya birunya full tanpa space putih */
        margin: -24px -32px -40px -32px;

        /* Padding dalam tetap, biar konten nggak nempel ke pinggir */
        padding: 24px 32px 40px;

        background: linear-gradient(135deg,#2563eb 0%,#4f46e5 35%,#6366f1 70%,#22c1c3 100%);
    }

    /* Dark mode: background hitam */
    body.sipkam-dark .peminjaman-page {
        background: radial-gradient(circle at top,#020617 0%,#020617 45%,#020617 100%);
    }

    /* Header judul + deskripsi */
    .peminjaman-header-title h1,
    .peminjaman-header-title small {
        color: #ffffff;
    }

    /* Judul & deskripsi di mode gelap → hijau neon */
    body.sipkam-dark .peminjaman-header-title h1,
    body.sipkam-dark .peminjaman-header-title small {
        color: #22c55e;
    }

    /* Tombol Tambah Peminjaman */
    .btn-peminjaman-primary {
        border-radius: 999px;
        padding: 0.55rem 1.8rem;
        font-weight: 600;
        border: none;
        background: radial-gradient(circle at top left,#4ade80,#22c55e);
        color: #022c22;
        box-shadow: 0 14px 30px rgba(34,197,94,0.45);
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .btn-peminjaman-primary i {
        font-size: 0.9rem;
    }

    body.sipkam-dark .btn-peminjaman-primary {
        background: radial-gradient(circle at top left,#4ade80,#22c55e);
        color: #020617;
        box-shadow: 0 18px 40px rgba(34,197,94,0.7);
    }

    .btn-peminjaman-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 38px rgba(34,197,94,0.6);
    }

    /* Card / container tabel: full width, lembut */
    .peminjaman-card {
        border-radius: 18px;
        border: none;
        background: rgba(248,250,252,0.98);
        box-shadow: 0 18px 40px rgba(15,23,42,0.16);
        overflow: hidden;
    }

    body.sipkam-dark .peminjaman-card {
        background: #020617;
        border: 1px solid rgba(31,41,55,0.9);
        box-shadow: 0 22px 45px rgba(0,0,0,0.85);
    }

    /* Tabel peminjaman – dirapikan, jarak KODE–BARANG dipersempit */
    .peminjaman-table {
        width: 100%;
        margin-bottom: 0;
    }

    .peminjaman-table thead th {
        border-bottom: none;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        padding: 0.70rem 0.9rem;
        background: rgba(15,23,42,0.03);
    }

    .peminjaman-table tbody td {
        padding: 0.70rem 0.9rem;
        vertical-align: middle;
    }

    /* Kolom KODE dipersempit supaya lebih rapat dengan BARANG */
    .peminjaman-table th.col-kode,
    .peminjaman-table td.col-kode {
        width: 80px;
        white-space: nowrap;
    }

    /* Periode pakai font lebih kecil */
    .peminjaman-table td small {
        font-size: 0.78rem;
    }

    /* Header dan isi tabel di dark mode */
    body.sipkam-dark .peminjaman-table thead th {
        background: #020617;
        color: #a7f3d0;
        border-bottom: 1px solid rgba(31,41,55,0.9);
    }

    body.sipkam-dark .peminjaman-table tbody td {
        color: #e5e7eb;
    }

    body.sipkam-dark .peminjaman-table tbody td small {
        color: #9ca3af;
    }

    /* Badge status tetap, hanya sedikit glow di dark mode */
    body.sipkam-dark .peminjaman-table .badge {
        box-shadow: 0 0 12px rgba(34,197,94,0.45);
    }

    @media (max-width: 767.98px) {
        .peminjaman-page {
            /* Sesuaikan margin/padding di layar kecil */
            margin: -16px -16px -24px -16px;
            padding: 16px 16px 24px;
        }
    }

    /* >>> PAKSA TEKS DI DALAM TABEL JADI HITAM PEKAT <<< */
    .peminjaman-card .peminjaman-table tbody td,
    .peminjaman-card .peminjaman-table tbody td small {
        color: #000000 !important;   /* hitam tegas, tidak abu-abu */
        font-weight: 500;            /* agak ditebalkan biar jelas */
    }
</style>

<div class="peminjaman-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="peminjaman-header-title">
            <h1 class="h3 mb-1 fw-semibold">Daftar Peminjaman</h1>
            <small>Pantau semua permintaan peminjaman Anda</small>
        </div>

        <a href="{{ route('mahasiswa.peminjaman.create') }}" class="btn btn-peminjaman-primary">
            <i class="fas fa-plus"></i>
            <span>Tambah Peminjaman</span>
        </a>
    </div>

    {{-- CARD TABEL – FULL WIDTH --}}
    <div class="peminjaman-card">
        <div class="table-responsive">
            <table class="table peminjaman-table align-middle">
                <thead>
                    <tr>
                        <th class="col-kode">Kode</th>
                        <th>Barang</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Aksi</th>
>>>>>>> Stashed changes
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $item)
                        <tr>
<<<<<<< Updated upstream
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
=======
                            <td class="col-kode">{{ $item->id_peminjaman }}</td>
                            <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($item->waktu_awal)->format('d M Y H:i') }}<br>
                                <small>s/d {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->status === 'berlangsung' ? 'info' : ($item->status === 'selesai' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('mahasiswa.peminjaman.show', $item->id_peminjaman) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
>>>>>>> Stashed changes
                            </td>
                        </tr>
                    @empty
                        <tr>
<<<<<<< Updated upstream
                            <td colspan="5" class="text-center text-muted py-4">
=======
                            <td colspan="5" class="text-center text-white-50 py-4">
>>>>>>> Stashed changes
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
