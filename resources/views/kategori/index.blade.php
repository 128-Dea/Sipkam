@extends('layouts.app')

@section('content')

{{-- === STYLE KHUSUS HALAMAN KATEGORI (PALET #051F20 dst) === --}}
<style>
    :root {
        --kat-deep-1: #051F20;
        --kat-deep-2: #0B2B26;
        --kat-deep-3: #163832;
        --kat-deep-4: #253547;
        --kat-soft-1: #8EB69B;
        --kat-soft-2: #DAF1DE;
    }

    /* BACKGROUND MENTOK FULL 100% DI DALAM <main> */
    .sipkam-kategori-page {
        /* makan padding main (.p-4) supaya background nempel ke tepi */
        margin: -1.5rem -1.5rem -2rem;
        padding: 1.5rem 2.5rem 2.5rem;

        min-height: 100vh;
        width: auto;
        background: linear-gradient(
            180deg,
            var(--kat-soft-2) 0%,
            #E2F3E4 35%,
            var(--kat-soft-1) 85%
        );
    }

    body.sipkam-dark .sipkam-kategori-page {
        background: radial-gradient(
            circle at top,
            var(--kat-deep-1) 0%,
            var(--kat-deep-2) 40%,
            var(--kat-deep-3) 100%
        );
    }

    .sipkam-kategori-header-title {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }

    .sipkam-kategori-header-title::before {
        content: "";
        width: 4px;
        height: 26px;
        border-radius: 999px;
        background: linear-gradient(180deg, var(--kat-deep-3), var(--kat-soft-1));
    }

    .sipkam-kategori-subtitle {
        font-size: .85rem;
        color: #64748b;
    }

    body.sipkam-dark .sipkam-kategori-subtitle {
        color: #8EB69B;
    }

    /* Tombol tambah kategori kapsul hijau gelap */
    .sipkam-btn-gradient {
        border-radius: 999px;
        padding: 0.55rem 1.5rem;
        border: none;
        font-weight: 600;
        font-size: .9rem;
        color: var(--kat-soft-2);
        background: linear-gradient(135deg, var(--kat-deep-1), var(--kat-deep-3));
        box-shadow: 0 10px 28px rgba(5, 31, 32, 0.55);
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        text-decoration: none;
    }

    .sipkam-btn-gradient i {
        font-size: .85rem;
    }

    .sipkam-btn-gradient:hover {
        color: #ffffff;
        background: linear-gradient(135deg, var(--kat-deep-2), var(--kat-deep-4));
        box-shadow: 0 14px 36px rgba(5, 31, 32, 0.75);
    }

    /* KARTU TABEL */
    .sipkam-kategori-card {
        border-radius: 18px;
        background: rgba(255,255,255,0.96);
        box-shadow: 0 18px 40px rgba(5,31,32,0.18);
        border: 1px solid rgba(218,241,222,0.9);
        overflow: hidden;
    }

    body.sipkam-dark .sipkam-kategori-card {
        background: #051F20;
        border-color: #163832;
        box-shadow: 0 22px 60px rgba(0,0,0,0.9);
    }

    .sipkam-kategori-card .table {
        margin-bottom: 0;
    }

    .sipkam-kategori-table thead th {
        border-bottom: none;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--kat-soft-2);
        padding-top: .85rem;
        padding-bottom: .7rem;
        background: linear-gradient(90deg, var(--kat-deep-1), var(--kat-deep-3));
    }

    body.sipkam-dark .sipkam-kategori-table thead th {
        background: linear-gradient(90deg, #020617, var(--kat-deep-2));
    }

    /* striping body */
    .sipkam-kategori-table tbody tr:nth-of-type(odd) {
        background: rgba(218,241,222,0.7);
    }

    .sipkam-kategori-table tbody tr:nth-of-type(even) {
        background: rgba(255,255,255,0.98);
    }

    body.sipkam-dark .sipkam-kategori-table tbody tr:nth-of-type(odd),
    body.sipkam-dark .sipkam-kategori-table tbody tr:nth-of-type(even) {
        background: #020617;
    }

    .sipkam-kategori-table tbody tr:hover {
        background: rgba(142,182,155,0.6) !important;
        box-shadow: 0 6px 18px rgba(5,31,32,0.25);
        transform: translateY(-1px);
    }

    body.sipkam-dark .sipkam-kategori-table tbody tr:hover {
        background: #0B2B26 !important;
    }

    .sipkam-kategori-table td {
        vertical-align: middle;
        font-size: .9rem;
        border-top-color: rgba(148,163,184,0.25);
    }

    /* Tombol Edit / Hapus */
    .sipkam-kategori-card .btn-outline-primary {
        border-radius: 999px;
        border-color: var(--kat-deep-3);
        color: var(--kat-deep-3);
        font-size: .8rem;
        padding-inline: .9rem;
        padding-block: .25rem;
    }

    .sipkam-kategori-card .btn-outline-primary:hover {
        background: var(--kat-deep-3);
        color: var(--kat-soft-2);
    }

    .sipkam-kategori-card .btn-outline-danger {
        border-radius: 999px;
        font-size: .8rem;
        padding-inline: .9rem;
        padding-block: .25rem;
    }

    .sipkam-kategori-empty {
        padding: 2.5rem 1rem;
        color: #94a3b8;
        font-style: italic;
    }
</style>

<div class="sipkam-kategori-page">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted small mb-1">
                Dashboard /
                <span class="fw-semibold text-dark">Kategori Barang</span>
            </p>
            <h1 class="h4 mb-1 fw-bold text-dark sipkam-kategori-header-title">
                Kategori Barang
            </h1>
            <div class="sipkam-kategori-subtitle">
                Kelola kelompok kategori agar data barang lebih rapi dan mudah dicari.
            </div>
        </div>

        <a href="{{ route('petugas.kategori.create') }}" class="sipkam-btn-gradient">
            <i class="fas fa-plus"></i>
            <span>Tambah Kategori</span>
        </a>
    </div>

    {{-- CARD TABEL --}}
    <div class="card border-0 sipkam-kategori-card">
        <div class="table-responsive">
            <table class="table sipkam-kategori-table">
                <thead>
                    <tr>
                        <th style="width:80px;">ID</th>
                        <th>Nama Kategori</th>
                        <th style="width:160px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $item)
                        <tr>
                            <td class="fw-semibold">{{ $item->id_kategori }}</td>
                            <td>{{ $item->nama_kategori }}</td>
                            <td class="text-end">
                                <a href="{{ route('petugas.kategori.edit', $item->id_kategori) }}"
                                   class="btn btn-sm btn-outline-primary me-1">
                                    Edit
                                </a>
                                <form action="{{ route('petugas.kategori.destroy', $item->id_kategori) }}"
                                      method="POST"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus kategori ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center sipkam-kategori-empty">
                                Belum ada kategori. Tambahkan kategori pertama untuk mulai mengelompokkan barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
