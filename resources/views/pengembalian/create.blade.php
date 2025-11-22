@extends('layouts.app')

@section('content')

{{-- ===== STYLE KHUSUS HALAMAN FORM PENGEMBALIAN ===== --}}
<style>
    :root {
        --sipkam-return-accent: #22c55e;
        --sipkam-return-accent-soft: rgba(34,197,94,0.35);
    }

    /* Latar full screen, ikut tema global */
    .sipkam-return-form-page {
        min-height: 100vh;
        margin: -24px -32px -40px -32px; /* tarik keluar padding layout */
        padding: 32px 32px 40px;          /* ada ruang 32px dari kiri/kanan */
        display: block;                   /* bukan flex lagi, biar full width */
    }

    body.sipkam-light .sipkam-return-form-page {
        background: linear-gradient(135deg,#e0f2fe 0%,#f9fafb 40%,#dcfce7 100%);
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-return-form-page {
        background: radial-gradient(circle at top,#020617 0%,#020617 40%,#020617 100%);
        color: #e5e7eb;
    }

    .sipkam-return-form-shell {
        width: 100%;
        max-width: 100%;   /* << ini yang bikin form mentok full kiri–kanan */
        padding: 0;
    }

    /* HEADER */
    .sipkam-return-form-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .sipkam-return-form-back {
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    body.sipkam-light .sipkam-return-form-back {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 8px 18px rgba(148,163,184,0.45);
    }

    body.sipkam-dark .sipkam-return-form-back {
        background: #020617;
        color: #e5e7eb;
        box-shadow: 0 10px 24px rgba(0,0,0,0.9);
        border: 1px solid rgba(31,41,55,0.9);
    }

    .sipkam-return-form-back:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 32px rgba(15,23,42,0.55);
    }

    .sipkam-return-form-title {
        font-weight: 700;
        letter-spacing: 0.03em;
        margin-bottom: 0.1rem;
    }

    .sipkam-return-form-subtitle {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    /* CARD FORM (inspirasi mockup) */
    .sipkam-return-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
    }

    body.sipkam-light .sipkam-return-card {
        background: rgba(255,255,255,0.96);
        border: 1px solid rgba(148,163,184,0.35);
        box-shadow: 0 24px 56px rgba(148,163,184,0.55);
    }

    body.sipkam-dark .sipkam-return-card {
        background: radial-gradient(circle at top left,#020617,#020617 55%,#020617 100%);
        border: 1px solid rgba(31,41,55,0.9);
        box-shadow: 0 26px 70px rgba(0,0,0,0.95);
    }

    /* Glow hijau di sisi kanan card */
    .sipkam-return-card::before {
        content: "";
        position: absolute;
        right: -80px;
        top: -40px;
        width: 260px;
        height: 260px;
        border-radius: 999px;
        background: radial-gradient(circle at center,var(--sipkam-return-accent-soft),transparent 70%);
        filter: blur(2px);
        opacity: 0.9;
        pointer-events: none;
    }

    body.sipkam-light .sipkam-return-card::before { opacity: 0.65; }
    body.sipkam-dark  .sipkam-return-card::before { opacity: 0.95; }

    .sipkam-return-card .card-body {
        padding: 1.75rem 1.9rem 1.4rem;
        position: relative;
        z-index: 1;
    }

    .sipkam-return-card .card-footer {
        padding: 0.9rem 1.9rem 1.3rem;
        border-top: 1px solid rgba(148,163,184,0.25);
        position: relative;
        z-index: 1;
    }

    body.sipkam-dark .sipkam-return-card .card-footer {
        border-color: rgba(31,41,55,0.85);
        background: transparent;
    }

    /* Label & input */
    .sipkam-return-card .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.3rem;
    }

    .sipkam-return-card .form-control,
    .sipkam-return-card .form-select,
    .sipkam-return-card textarea.form-control {
        border-radius: 0.75rem;
        border-width: 1px;
        padding: 0.55rem 0.9rem;
        font-size: 0.9rem;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease, color 0.15s ease;
    }

    body.sipkam-light .sipkam-return-card .form-control,
    body.sipkam-light .sipkam-return-card .form-select,
    body.sipkam-light .sipkam-return-card textarea.form-control {
        background: #f9fafb;
        border-color: #d1d5db;
        color: #0f172a;
    }

    body.sipkam-dark .sipkam-return-card .form-control,
    body.sipkam-dark .sipkam-return-card .form-select,
    body.sipkam-dark .sipkam-return-card textarea.form-control {
        background: rgba(15,23,42,0.9);
        border-color: rgba(31,41,55,0.9);
        color: #e5e7eb;
    }

    .sipkam-return-card .form-control:focus,
    .sipkam-return-card .form-select:focus,
    .sipkam-return-card textarea.form-control:focus {
        outline: none;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.4), 0 0 0 4px rgba(34,197,94,0.20);
        border-color: var(--sipkam-return-accent);
    }

    body.sipkam-light .sipkam-return-card .form-control::placeholder,
    body.sipkam-light .sipkam-return-card textarea.form-control::placeholder {
        color: #9ca3af;
    }

    body.sipkam-dark .sipkam-return-card .form-control::placeholder,
    body.sipkam-dark .sipkam-return-card textarea.form-control::placeholder {
        color: #6b7280;
    }

    /* Alert error di dalam card */
    .sipkam-return-card .alert {
        border-radius: 0.75rem;
        font-size: 0.85rem;
    }

    /* Tombol footer */
    .sipkam-return-card .btn {
        border-radius: 999px;
        padding-inline: 1.4rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .sipkam-return-card .btn-primary { border: none; }

    body.sipkam-light .sipkam-return-card .btn-primary {
        background: linear-gradient(135deg,#22c55e,#16a34a);
        color: #022c22;
        box-shadow: 0 10px 28px rgba(34,197,94,0.55);
    }

    body.sipkam-dark .sipkam-return-card .btn-primary {
        background: linear-gradient(135deg,#22c55e,#4ade80);
        color: #020617;
        box-shadow: 0 12px 32px rgba(34,197,94,0.7);
    }

    .sipkam-return-card .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 42px rgba(34,197,94,0.75);
    }

    body.sipkam-light .sipkam-return-card .btn-light {
        border-color: #e5e7eb;
    }

    body.sipkam-dark .sipkam-return-card .btn-light {
        background: transparent;
        color: #e5e7eb;
        border-color: rgba(148,163,184,0.6);
    }

    .sipkam-return-card .btn-light:hover {
        background: rgba(148,163,184,0.15);
    }

    @media (max-width: 767.98px) {
        .sipkam-return-form-page {
            margin: -16px -16px -24px -16px;
            padding: 20px 16px 28px;
        }
        .sipkam-return-form-shell {
            padding: 0;
        }
        .sipkam-return-card .card-body,
        .sipkam-return-card .card-footer {
            padding-inline: 1.1rem;
        }
    }
</style>

<div class="sipkam-return-form-page">
    <div class="sipkam-return-form-shell">

        {{-- HEADER (judul + tombol back) --}}
        <div class="sipkam-return-form-header">
            <button type="button" class="sipkam-return-form-back" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="sipkam-return-form-title h4 mb-0">Form Pengembalian</h1>
                <div class="sipkam-return-form-subtitle">
                    Lengkapi detail pengembalian barang yang Anda kembalikan ke petugas.
                </div>
            </div>
        </div>

        {{-- FORM: LOGIKA TIDAK DIUBAH --}}
        <form method="POST"
              action="{{ route('mahasiswa.pengembalian.store') }}"
              class="sipkam-return-card card border-0 shadow-sm">
            @csrf
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label class="form-label">Peminjaman</label>
                    <select name="id_peminjaman" class="form-select" required>
                        <option value="">-- Pilih peminjaman yang ingin dikembalikan --</option>
                        @foreach($peminjaman as $item)
                            <option value="{{ $item->id_peminjaman }}"
                                {{ old('id_peminjaman') == $item->id_peminjaman ? 'selected' : '' }}>
                                {{ $item->barang->nama_barang ?? 'Barang' }}
                                @if(!empty($item->pengguna?->nama))
                                    - {{ $item->pengguna->nama }}
                                @endif
                                (Jatuh tempo: {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal & Jam Pengembalian</label>
                    <input
                        type="datetime-local"
                        name="waktu_pengembalian"
                        value="{{ old('waktu_pengembalian', now()->format('Y-m-d\TH:i')) }}"
                        class="form-control"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Catatan (opsional)</label>
                    <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="card-footer text-end bg-white">
                <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-light">Batal</a>
                <button class="btn btn-primary" type="submit">Kirim Pengembalian</button>
            </div>
        </form>

    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Form Pengembalian</h1>

<form method="POST" action="{{ route('mahasiswa.pengembalian.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label">Peminjaman</label>
            <select name="id_peminjaman" class="form-select" required>
                <option value="">-- Pilih peminjaman yang ingin dikembalikan --</option>
                @foreach($peminjaman as $item)
                    <option value="{{ $item->id_peminjaman }}"
                        {{ old('id_peminjaman') == $item->id_peminjaman ? 'selected' : '' }}>
                        {{ $item->barang->nama_barang ?? 'Barang' }}
                        @if(!empty($item->pengguna?->nama))
                            - {{ $item->pengguna->nama }}
                        @endif
                        (Jatuh tempo: {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal & Jam Pengembalian</label>
            <input
                type="datetime-local"
                name="waktu_pengembalian"
                value="{{ old('waktu_pengembalian', now()->format('Y-m-d\TH:i')) }}"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan (opsional)</label>
            <textarea name="catatan" rows="3" class="form-control">{{ old('catatan') }}</textarea>
        </div>
    </div>

    <div class="card-footer text-end bg-white">
        <a href="{{ route('mahasiswa.riwayat.index') }}" class="btn btn-light">Batal</a>
        <button class="btn btn-primary" type="submit">Kirim Pengembalian</button>
    </div>
</form>
@endsection
