@extends('layouts.app')

@section('content')
<style>
    :root {
        --notif-dark-1: #051F20;
        --notif-dark-2: #0B2B26;
        --notif-dark-3: #163832;
        --notif-soft:   #8EB69B;
        --notif-light:  #DAF1DE;
    }

    /* ============================
       WRAPPER BACKGROUND GRADIENT
       ============================ */
    .notif-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        background: linear-gradient(
            180deg,
            var(--notif-dark-1) 0%,
            var(--notif-dark-3) 30%,
            var(--notif-soft)   70%,
            var(--notif-light)  100%
        );
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .notif-inner {
        width: 100%;
        max-width: 900px;
    }

    @media (max-width: 767.98px) {
        .notif-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .notif-inner {
            max-width: 100%;
        }
    }

    /* ============================
       HEADER BLOK
       ============================ */
    .notif-header-block {
        background: linear-gradient(135deg, var(--notif-dark-1), var(--notif-dark-3));
        border-radius: 20px 20px 0 0;
        padding: 18px 22px;
        color: #e9f7f0;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .notif-header-icon {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        background: rgba(218, 241, 222, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .notif-header-title h1 {
        font-size: 1.2rem;
        font-weight: 650;
        letter-spacing: .04em;
        margin: 0;
    }

    .notif-header-title small {
        font-size: 0.8rem;
        color: rgba(218, 241, 222, 0.9);
    }

    /* ============================
       CARD & LIST ITEM
       ============================ */
    .notif-card {
        border-radius: 0 0 20px 20px;
        border: none;
        margin-top: 0;
        background: rgba(250, 253, 252, 0.98);
        box-shadow: 0 22px 44px rgba(0, 0, 0, 0.55);
        overflow: hidden;
    }

    .notif-list .list-group-item {
        border-color: #e1ebe4;
        font-size: 0.94rem;
        padding: 14px 18px;
    }

    .notif-item {
        background-color: #f6fbf8;
        position: relative;
    }

    .notif-item::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 0 4px 4px 0;
        background: linear-gradient(180deg, var(--notif-soft), var(--notif-light));
    }

    .notif-item h6 {
        font-weight: 600;
        color: var(--notif-dark-2);
    }

    .notif-item p {
        margin-bottom: 4px;
        color: #475569;
    }

    .notif-item small {
        color: #94a3b8 !important;
    }

    .notif-item:hover {
        background-color: #e9f3ee;
    }

    .notif-empty {
        background-color: #f9fafb;
        font-size: 0.9rem;
    }

    .btn-outline-danger.btn-sm {
        border-radius: 999px;
        font-size: 0.75rem;
        padding-inline: 12px;
    }
</style>

<div class="notif-wrapper">
    <div class="notif-inner">

        {{-- HEADER NOTIFIKASI --}}
        <div class="notif-header-block mb-0">
            <div class="notif-header-icon">
                <i class="bi bi-bell"></i>
            </div>
            <div class="notif-header-title">
                <h1>Notifikasi Sistem</h1>
                <small>Pemberitahuan penting terkait peminjaman, pengembalian, dan denda.</small>
            </div>
        </div>

        {{-- CARD LIST NOTIFIKASI --}}
        <div class="card notif-card">
            <div class="list-group list-group-flush notif-list">
                @forelse($notifikasi as $item)
                    <div class="list-group-item notif-item d-flex justify-content-between align-items-start">
                        <div class="me-3">
                            <h6 class="mb-1">{{ $item->judul ?? 'Notifikasi' }}</h6>
                            <p class="mb-1">{{ $item->pesan ?? '-' }}</p>
                            <small class="text-muted">
                                Barang: {{ $item->barang->nama_barang ?? '-' }}
                                &nbsp;|&nbsp;
                                Pengguna: {{ $item->pengguna->nama ?? '-' }}
                            </small>
                        </div>
                        <form
                            action="{{ route((auth()->user()?->role === 'petugas' ? 'petugas.notifikasi.destroy' : 'mahasiswa.notifikasi.destroy'), $item->id_notifikasi) }}"
                            method="POST"
                            onsubmit="return confirm('Hapus notifikasi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="list-group-item text-center text-muted py-4 notif-empty">
                        Belum ada notifikasi.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
