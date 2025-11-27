@extends('layouts.app')

@section('content')

{{-- ==== STYLE TEMA KATEGORI (PALET #051F20, #0B2B26, #163832, #253547, #8EB69B, #DAF1DE) ==== --}}
<style>
    :root {
        --kat-deep-1: #051F20;
        --kat-deep-2: #0B2B26;
        --kat-deep-3: #163832;
        --kat-deep-4: #253547;
        --kat-soft-1: #8EB69B;
        --kat-soft-2: #DAF1DE;
    }

    /* BACKGROUND FULL 100% (MENTOK KANAN–KIRI) */
    .sipkam-kategori-create-shell {
        position: relative;
        width: 100vw;              /* penuh selebar viewport */
        left: 50%;
        right: 50%;
        margin-left: -50vw;        /* trik biar mentok kiri */
        margin-right: -50vw;       /* dan kanan */
        background: linear-gradient(
            180deg,
            var(--kat-soft-2) 0%,
            #E2F3E4 35%,
            var(--kat-soft-1) 90%
        );
    }

    body.sipkam-dark .sipkam-kategori-create-shell {
        background: radial-gradient(
            circle at top,
            var(--kat-deep-1) 0%,
            var(--kat-deep-2) 40%,
            var(--kat-deep-3) 100%
        );
    }

    .sipkam-kategori-create-inner {
        min-height: calc(100vh - 64px);      /* biar gradasi tetap sampai bawah */
        padding: 1.75rem 3.5rem 2.75rem;     /* ruang dalam konten */
    }

    @media (max-width: 991.98px) {
        .sipkam-kategori-create-inner {
            padding: 1.25rem 1.25rem 2rem;
        }
    }

    /* HEADER HALAMAN */
    .sipkam-kategori-create-title-line {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: .15rem;
    }

    .sipkam-kategori-create-title-line::before {
        content: "";
        width: 4px;
        height: 26px;
        border-radius: 999px;
        background: linear-gradient(180deg, var(--kat-deep-3), var(--kat-soft-1));
    }

    .sipkam-kategori-create-subtitle {
        font-size: .85rem;
        color: #64748b;
    }

    body.sipkam-dark .sipkam-kategori-create-subtitle {
        color: #8EB69B;
    }

    /* CARD / FORM WRAPPER (PENUH 100% DI DALAM AREA KONTEN) */
    .sipkam-kategori-form-card {
        border-radius: 18px;
        border: 1px solid rgba(218,241,222,0.9);
        background: linear-gradient(
            180deg,
            rgba(255,255,255,0.96) 0%,
            rgba(252,252,252,0.94) 55%,
            rgba(218,241,222,0.94) 100%
        );
        box-shadow: 0 18px 40px rgba(5,31,32,0.22);
        overflow: hidden;
        width: 100%;              /* mentok kanan–kiri di area konten */
    }

    body.sipkam-dark .sipkam-kategori-form-card {
        background: #020617;
        border-color: #163832;
        box-shadow: 0 22px 60px rgba(0,0,0,0.9);
    }

    .sipkam-kategori-form-header {
        background: linear-gradient(90deg, var(--kat-deep-1), var(--kat-deep-3));
        color: #FFFFFF;
        padding: 1rem 1.75rem;
    }

    .sipkam-kategori-form-header h2 {
        font-size: 1rem;
        margin-bottom: .15rem;
        font-weight: 600;
    }

    .sipkam-kategori-form-header p {
        font-size: .8rem;
        margin: 0;
        opacity: .85;
    }

    .sipkam-kategori-form-body {
        padding: 1.5rem 1.75rem 1.75rem;
    }

    .sipkam-kategori-form-body .form-label {
        font-size: .85rem;
        font-weight: 600;
        color: #334155;
    }

    .sipkam-kategori-form-body .form-control {
        border-radius: 999px;
        border-color: rgba(148,163,184,0.5);
        padding-inline: 1.1rem;
        background-color: #ffffff;
    }

    .sipkam-kategori-form-body .form-control:focus {
        border-color: var(--kat-deep-3);
        box-shadow: 0 0 0 1px rgba(37,53,71,0.25);
    }

    .sipkam-kategori-form-footer {
        padding: 0.9rem 1.75rem 1.15rem;
        border-top: none;
        background: transparent;
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
    }

    .sipkam-btn-ghost {
        border-radius: 999px;
        padding: .4rem 1.1rem;
        font-size: .85rem;
        border: 1px solid rgba(148,163,184,0.5);
        background-color: #ffffff;
    }

    .sipkam-btn-ghost:hover {
        background-color: #f9fafb;
    }

    .sipkam-btn-solid {
        border-radius: 999px;
        padding: .4rem 1.4rem;
        font-size: .85rem;
        border: none;
        background: linear-gradient(135deg, var(--kat-deep-1), var(--kat-deep-3));
        color: #ffffff;
        box-shadow: 0 12px 30px rgba(5,31,32,0.55);
    }

    .sipkam-btn-solid:hover {
        background: linear-gradient(135deg, var(--kat-deep-2), var(--kat-deep-4));
        color: #ffffff;
        box-shadow: 0 16px 40px rgba(5,31,32,0.75);
    }

    @media (max-width: 575.98px) {
        .sipkam-kategori-form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .sipkam-kategori-form-footer a,
        .sipkam-kategori-form-footer button {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="sipkam-kategori-create-shell">
    <div class="sipkam-kategori-create-inner">

        {{-- HEADER HALAMAN --}}
        <div class="mb-4">
            <p class="text-muted small mb-1">
                Dashboard /
                <span class="fw-semibold">Kategori Barang</span> /
                <span class="fw-semibold">Tambah</span>
            </p>

            <div class="sipkam-kategori-create-title-line">
                <h1 class="h4 mb-0 fw-bold text-dark">Tambah Kategori</h1>
            </div>

            <div class="sipkam-kategori-create-subtitle">
                Buat kategori baru agar data barang lebih rapi dan mudah dikelompokkan.
            </div>
        </div>

        {{-- FORM (LOGIKA TIDAK DIUBAH) --}}
        <form method="POST"
              action="{{ route('petugas.kategori.store') }}"
              class="card border-0 shadow-sm sipkam-kategori-form-card">
            @csrf

            <div class="sipkam-kategori-form-header">
                <h2>Data Kategori</h2>
                <p>Isi nama kategori yang akan digunakan pada data barang.</p>
            </div>

            <div class="card-body sipkam-kategori-form-body">
                <div class="mb-3">
                    <label class="form-label">Nama Kategori</label>
                    <input type="text"
                           name="nama_kategori"
                           value="{{ old('nama_kategori') }}"
                           class="form-control"
                           required>
                    @error('nama_kategori')
                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="card-footer sipkam-kategori-form-footer">
                <a href="{{ route('petugas.kategori.index') }}" class="btn sipkam-btn-ghost">
                    Batal
                </a>
                <button class="btn sipkam-btn-solid" type="submit">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

@endsection
