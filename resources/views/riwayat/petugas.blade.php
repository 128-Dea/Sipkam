@extends('layouts.app')

@section('content')
@php
    $filters = $filters ?? ['kondisi' => null, 'search' => null];
@endphp

<<<<<<< HEAD
<style>
    :root {
        /* palet sesuai referensi */
        --hist-dark-1: #051F20;
        --hist-dark-2: #0B2B26;
        --hist-dark-3: #163832;
        --hist-mid:    #253547;
        --hist-soft:   #8EB69B;
        --hist-light:  #DAF1DE;
    }

    /* ============================
       WRAPPER DENGAN GRADIENT LINEAR
       ============================ */
    .histori-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        /* linear: atas gelap, bawah terang */
        background: linear-gradient(
            180deg,
            var(--hist-dark-1) 0%,
            var(--hist-dark-3) 30%,
            var(--hist-soft)   70%,
            var(--hist-light)  100%
        );
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .histori-inner {
        width: 100%;
        max-width: 1150px;
    }

    @media (max-width: 767.98px) {
        .histori-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .histori-inner {
            max-width: 100%;
        }
    }

    /* ============================
       HEADER ATAS (judul & tombol)
       ============================ */
    .histori-header {
        background: rgba(5, 31, 32, 0.96);
        border-radius: 20px 20px 0 0;
        padding: 20px 24px;
        color: #e9f7f0;
        box-shadow: 0 18px 34px rgba(0, 0, 0, 0.6);
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        justify-content: space-between;
    }

    .histori-header h1.h3 {
        color: var(--hist-light);
        font-weight: 650;
        letter-spacing: 0.03em;
        margin-bottom: 4px;
    }

    .histori-header small.text-muted {
        color: rgba(218, 241, 222, 0.9) !important;
    }

    .histori-header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-export-primary,
    .btn-export-secondary {
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 8px 16px;
        border-width: 1px;
        transition: all 0.22s ease;
    }

    .btn-export-primary {
        background: linear-gradient(135deg, var(--hist-soft), var(--hist-light));
        border-color: rgba(218, 241, 222, 0.9);
        color: var(--hist-dark-1);
        box-shadow:
            0 10px 24px rgba(0, 0, 0, 0.55),
            0 0 0 1px rgba(142, 182, 155, 0.8);
    }

    .btn-export-primary:hover {
        filter: brightness(1.03);
        transform: translateY(-1px);
    }

    .btn-export-secondary {
        background: transparent;
        border-color: rgba(226, 232, 240, 0.9);
        color: #e2e8f0;
    }

    .btn-export-secondary:hover {
        background: rgba(15, 23, 42, 0.5);
    }

    .histori-badge-total {
        background: rgba(218, 241, 222, 0.08);
        color: var(--hist-light);
        border-radius: 999px;
        border: 1px solid rgba(142, 182, 155, 0.7);
        font-weight: 500;
        font-size: 0.8rem;
        padding: 6px 14px;
    }

    @media (max-width: 767.98px) {
        .histori-header {
            padding: 18px 16px;
            border-radius: 16px 16px 0 0;
        }
    }

    /* ============================
       CARD FILTER
       ============================ */
    .histori-filter-card {
        border-radius: 0 0 18px 18px;
        margin-top: -1px; /* nempel ke header */
        border: none;
        background: rgba(250, 253, 252, 0.98);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.35);
    }

    .histori-filter-card .card-body {
        padding: 18px 22px 20px;
    }

    .form-label-modern {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #475569;
        margin-bottom: 6px;
    }

    /* ➜ MODE TERANG: background putih */
    .form-control-modern,
    .form-select.form-control-modern {
        border-radius: 14px;
        border-color: #e2ece5;
        box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.15);
        font-size: 0.9rem;
        padding: 10px 14px;
        background-color: #ffffff;
        color: #0f172a;
    }

    .form-control-modern::placeholder {
        color: #94a3b8;
    }

    .form-control-modern:focus,
    .form-select.form-control-modern:focus {
        border-color: var(--hist-soft);
        box-shadow:
            0 0 0 1px rgba(142, 182, 155, 0.45),
            0 0 0 4px rgba(142, 182, 155, 0.18);
    }

    /* ============================
       CARD & TABEL HISTORI
       ============================ */
    .histori-table-card {
        margin-top: 20px;
        border-radius: 18px;
        border: none;
        background: rgba(250, 253, 252, 0.97);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.45);
        overflow: hidden;
    }

    .histori-table-card .table {
        margin-bottom: 0;
    }

    .histori-table-card thead.table-light {
        background-color: var(--hist-dark-2) !important;
    }

    .histori-table-card thead.table-light th {
        background-color: transparent !important;
        color: #f9fafb;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: none;
        border-color: rgba(148, 163, 184, 0.35);
    }

    .histori-table-card tbody td {
        font-size: 0.92rem;
        color: var(--hist-dark-3);
        border-color: #e1ebe4;
        vertical-align: middle;
    }

    .histori-table-card tbody tr:nth-child(even) {
        background-color: #f6fbf8;
    }

    .histori-table-card tbody tr:hover {
        background-color: #e9f3ee;
    }

    .histori-table-card .text-muted {
        color: rgba(71, 85, 105, 0.8) !important;
    }

    /* ============================
       MODAL DETAIL
       ============================ */
    #detailModal .modal-header {
        background: linear-gradient(135deg, var(--hist-dark-1), var(--hist-mid));
        color: #e9f7f0;
    }

    #detailModal .modal-body {
        background: #f3faf6;
    }

    /* ============================
       MODE GELAP (body.sipkam-dark)
       ============================ */

    /* kartu filter ikut gelap */
    body.sipkam-dark .histori-filter-card {
        background: #020617;
    }

    /* label & teks form hijau neon */
    body.sipkam-dark .histori-filter-card .form-label-modern {
        color: #a7f3d0;
    }

    body.sipkam-dark .histori-filter-card .form-control-modern,
    body.sipkam-dark .histori-filter-card .form-select.form-control-modern {
        background-color: #020617;
        color: #a7f3d0;              /* teks hijau neon */
        border-color: #22c55e;
        box-shadow: inset 0 0 0 1px rgba(34, 197, 94, 0.55);
    }

    body.sipkam-dark .histori-filter-card .form-control-modern::placeholder {
        color: #4b5563;
    }

    body.sipkam-dark .histori-filter-card .form-select.form-control-modern option {
        background-color: #020617;
        color: #a7f3d0;
    }

    body.sipkam-dark .histori-filter-card .form-control-modern:focus,
    body.sipkam-dark .histori-filter-card .form-select.form-control-modern:focus {
        border-color: #22c55e;
        box-shadow:
            0 0 0 1px rgba(34, 197, 94, 0.85),
            0 0 0 4px rgba(34, 197, 94, 0.35);
    }
</style>


<div class="histori-wrapper">
    <div class="histori-inner">

        {{-- HEADER GRADIENT --}}
        <div class="histori-header mb-0">
            <div>
                <h1 class="h3 mb-1">Histori Transaksi 📁</h1>
                <small class="text-muted">Arsip peminjaman yang sudah selesai</small>
=======
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h1 class="h3 mb-1">Histori Transaksi</h1>
        <small class="text-muted">Arsip peminjaman yang sudah selesai</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('petugas.riwayat.export.csv', $filters) }}" class="btn btn-outline-primary">Download Excel (CSV)</a>
        <a href="{{ route('petugas.riwayat.export.html', $filters) }}" class="btn btn-outline-secondary">Download PDF (HTML)</a>
    </div>
    <span class="badge bg-primary bg-opacity-10 text-primary">Total denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <label class="form-label form-label-modern">Kondisi</label>
                <select id="filter-kondisi" class="form-select form-control-modern">
                    <option value="">Semua</option>
                    <option value="tersedia" {{ $filters['kondisi']==='tersedia' ? 'selected' : '' }}>Baik</option>
                    <option value="dalam_service" {{ $filters['kondisi']==='dalam_service' ? 'selected' : '' }}>Service / Rusak</option>
                    <option value="hilang" {{ $filters['kondisi']==='hilang' ? 'selected' : '' }}>Hilang</option>
                </select>
>>>>>>> e17174a0545b2a0e04f164f6a92a3ca46fb26a70
            </div>
            <div class="histori-header-right">
                <span class="histori-badge-total">
                    Total denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}
                </span>
                <a href="{{ route('petugas.riwayat.export.csv', $filters) }}" class="btn btn-export-primary">
                    Download Excel (CSV)
                </a>
                <a href="{{ route('petugas.riwayat.export.html', $filters) }}" class="btn btn-export-secondary">
                    Download PDF (HTML)
                </a>
            </div>
        </div>

        {{-- CARD FILTER --}}
        <div class="card histori-filter-card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label form-label-modern">Kondisi</label>
                        <select id="filter-kondisi" class="form-select form-control-modern">
                            <option value="">Semua</option>
                            <option value="tersedia" {{ $filters['kondisi']==='tersedia' ? 'selected' : '' }}>🟢 Baik</option>
                            <option value="service" {{ $filters['kondisi']==='service' ? 'selected' : '' }}>🟡 Service / Rusak</option>
                            <option value="hilang" {{ $filters['kondisi']==='hilang' ? 'selected' : '' }}>🔴 Hilang</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label form-label-modern">Cari Mahasiswa / Barang</label>
                        <input type="text" id="filter-search" class="form-control form-control-modern"
                               value="{{ $filters['search'] }}" placeholder="ketik nama atau barang">
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD TABEL HISTORI --}}
        <div class="card histori-table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="histori-table">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Kondisi Barang</th>
                            <th>Total Denda</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalian as $item)
                            @php
                                $p = $item->peminjaman;
                                $kondisi = $p?->barang?->status ?? 'tersedia';
                                $kondisiLabel = '🟢 Baik';
                                $badge = 'success';
                                if ($kondisi === 'service') { $kondisiLabel = '🟡 Service / Rusak'; $badge = 'warning'; }
                                if ($kondisi === 'hilang') { $kondisiLabel = '🔴 Hilang'; $badge = 'danger'; }
                                $denda = $p?->denda?->sum('total_denda') ?? 0;
                            @endphp
                            <tr data-row
                                data-nama="{{ $p?->pengguna?->nama ?? '-' }}"
                                data-email="{{ $p?->pengguna?->email ?? '-' }}"
                                data-barang="{{ $p?->barang?->nama_barang ?? '-' }}"
                                data-kode="{{ $p?->barang?->kode_barang ?? '-' }}"
                                data-pinjam="{{ \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y H:i') }}"
                                data-kembali="{{ \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y H:i') }}"
                                data-kondisi="{{ $kondisiLabel }}"
                                data-denda="Rp {{ number_format($denda, 0, ',', '.') }}"
                                data-catatan="{{ $item->catatan ?? '-' }}"
                            >
                                <td>{{ $p?->pengguna?->nama ?? '-' }}</td>
                                <td>{{ $p?->barang?->nama_barang ?? '-' }}</td>
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y') }}</td>
                                <td class="text-nowrap">{{ \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y') }}</td>
                                <td><span class="badge bg-{{ $badge }}">{{ $kondisiLabel }}</span></td>
                                <td>Rp {{ number_format($denda, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary btn-detail">Detail</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada histori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<<<<<<< HEAD
{{-- MODAL DETAIL (logika sama) --}}
=======
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="histori-table">
            <thead class="table-light">
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Kondisi Barang</th>
                    <th>Total Denda</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalian as $item)
                    @php
                        $p = $item->peminjaman;
                        $kondisi = $p?->barang?->status ?? 'tersedia';
                        $kondisiLabel = 'Baik';
                        $badge = 'success';
                        if ($kondisi === 'dalam_service') { $kondisiLabel = 'Service / Rusak'; $badge = 'warning'; }
                        if ($kondisi === 'hilang') { $kondisiLabel = 'Hilang'; $badge = 'danger'; }
                        $denda = $p?->denda?->sum('total_denda') ?? 0;
                        $isCanceled = $p?->status === 'dibatalkan';
                        $pinjamDisplay = $isCanceled ? '-' : \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y H:i');
                        $kembaliDisplay = $isCanceled ? '-' : \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y H:i');
                    @endphp
                    <tr data-row
                        data-nama="{{ $p?->pengguna?->nama ?? '-' }}"
                        data-email="{{ $p?->pengguna?->email ?? '-' }}"
                        data-barang="{{ $p?->barang?->nama_barang ?? '-' }}"
                        data-kode="{{ $p?->barang?->kode_barang ?? '-' }}"
                        data-pinjam="{{ $pinjamDisplay }}"
                        data-kembali="{{ $kembaliDisplay }}"
                        data-kondisi="{{ $kondisiLabel }}"
                        data-denda="Rp {{ number_format($denda, 0, ',', '.') }}"
                        data-catatan="{{ $item->catatan ?? '-' }}"
                    >
                        <td>{{ $p?->pengguna?->nama ?? '-' }}</td>
                        <td>{{ $p?->barang?->nama_barang ?? '-' }}</td>
                        <td class="text-nowrap">{{ $isCanceled ? '-' : \Carbon\Carbon::parse($p?->waktu_awal)->translatedFormat('d M Y') }}</td>
                        <td class="text-nowrap">{{ $isCanceled ? '-' : \Carbon\Carbon::parse($item->waktu_pengembalian)->translatedFormat('d M Y') }}</td>
                        <td><span class="badge bg-{{ $badge }}">{{ $kondisiLabel }}</span></td>
                        <td>Rp {{ number_format($denda, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary btn-detail">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada histori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

>>>>>>> e17174a0545b2a0e04f164f6a92a3ca46fb26a70
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted">Mahasiswa</div>
                            <div id="d-nama" class="fw-semibold"></div>
                            <small class="text-muted" id="d-email"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold text-muted">Barang</div>
                            <div id="d-barang" class="fw-semibold"></div>
                            <small class="text-muted" id="d-kode"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100">
                            <div class="fw-semibold text-muted">Tanggal Pinjam</div>
                            <div id="d-pinjam" class="fw-semibold"></div>
                            <div class="fw-semibold text-muted mt-3">Tanggal Pengembalian</div>
                            <div id="d-kembali" class="fw-semibold"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100">
                            <div class="fw-semibold text-muted">Kondisi Barang</div>
                            <div id="d-kondisi" class="fw-semibold"></div>
                            <div class="fw-semibold text-muted mt-3">Total Denda</div>
                            <div id="d-denda" class="fw-semibold text-danger"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded bg-white shadow-sm">
                            <div class="fw-semibold text-muted">Catatan / Riwayat</div>
                            <p class="mb-0" id="d-catatan"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.body.dataset.detailContext = 'riwayat';

        const params = new URLSearchParams(window.location.search);
        const inputs = {
            kondisi: document.getElementById('filter-kondisi'),
            search: document.getElementById('filter-search'),
        };

        Object.values(inputs).forEach(el => {
            el?.addEventListener('change', applyFilters);
            el?.addEventListener('input', applyFilters);
        });

        function applyFilters() {
            params.set('kondisi', inputs.kondisi.value.trim());
            params.set('search', inputs.search.value.trim());

            // Hapus parameter kosong supaya query string bersih
            Array.from(params.keys()).forEach((key) => {
                if (!params.get(key)) {
                    params.delete(key);
                }
            });

            const query = params.toString();
            window.location = '{{ route('petugas.riwayat.index') }}' + (query ? '?' + query : '');
        }

        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.querySelectorAll('[data-row] .btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                document.getElementById('d-nama').textContent = row.dataset.nama;
                document.getElementById('d-email').textContent = row.dataset.email;
                document.getElementById('d-barang').textContent = row.dataset.barang;
                document.getElementById('d-kode').textContent = row.dataset.kode;
                document.getElementById('d-pinjam').textContent = row.dataset.pinjam;
                document.getElementById('d-kembali').textContent = row.dataset.kembali;
                document.getElementById('d-kondisi').textContent = row.dataset.kondisi;
                document.getElementById('d-denda').textContent = row.dataset.denda;
                document.getElementById('d-catatan').textContent = row.dataset.catatan;
                detailModal.show();
            });
        });
    });
</script>
@endsection
