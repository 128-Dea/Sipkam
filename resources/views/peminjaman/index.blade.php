@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
    $role = $user?->role;

    // Hanya dipakai petugas
    $activeList = $role === 'petugas'
        ? $peminjaman->filter(fn($p) => $p->status === 'berlangsung')
        : collect();
@endphp

@if($role === 'petugas')

    {{-- ============= STYLE KHUSUS VIEW PETUGAS: PEMINJAMAN AKTIF ============= --}}
    <style>
        :root {
        --sipkam-deep-1: #051F20;
        --sipkam-deep-2: #0B2B26;
        --sipkam-deep-3: #163832;
        --sipkam-deep-4: #235347;
        --sipkam-soft:  #8EB69B;
        --sipkam-mist:  #DAF1DE;
    }

        /* Latar halaman petugas (full 100%) */
        .peminjaman-petugas-page {
            margin: -1.5rem -1.5rem -1.5rem;
            padding: 1.5rem 1.5rem 2rem;
            min-height: calc(100vh - 70px);
            background: linear-gradient(180deg, #DAF1DE 0%, #235347 45%, #8EB69B 100%);
        }

        body.sipkam-dark .peminjaman-petugas-page {
            background: radial-gradient(circle at top, #020617 0%, #020617 45%, #020617 100%);
        }

        .peminjaman-header-title {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
        }

        .peminjaman-header-title::before {
            content: "";
            width: 4px;
            height: 26px;
            border-radius: 999px;
            background: linear-gradient(180deg, var(--pinj-dark-1), var(--pinj-soft-1));
        }

        .peminjaman-subtitle {
            font-size: .85rem;
            color: #4b5563;
        }

        /* --- CARD FILTER (gradien #163832 → #235347) --- */
        .peminjaman-filter-card {
            border-radius: 24px;
            border: none;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.35);
            background: linear-gradient(180deg, var(--pinj-dark-1) 0%, var(--pinj-soft-1) 100%);
            color: #E5F4E6;
        }

        .peminjaman-filter-card .card-body {
            background: transparent;
        }

        .peminjaman-filter-icon {
            width: 44px;
            height: 44px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 10px 26px rgba(0,0,0,0.35);
        }

        .peminjaman-filter-card .fw-semibold {
            color: #F9FAFB;
        }

        .peminjaman-filter-card small {
            color: rgba(226, 232, 240, 0.8);
        }

        .peminjaman-filter-card .form-label-modern {
            color: #F9FAFB;
            font-size: .8rem;
            font-weight: 600;
            margin-bottom: .25rem;
        }

        .peminjaman-filter-card .form-control-modern,
        .peminjaman-filter-card .form-select.form-control-modern {
            border-radius: 999px;
            border: none;
            padding: .55rem .9rem;
            background: #FFFFFF;
            color: #111827;
            box-shadow: 0 8px 20px rgba(15,23,42,0.25);
        }

        .peminjaman-filter-card .form-control-modern::placeholder {
            color: #9ca3af;
        }

        .peminjaman-filter-card .form-control-modern:focus,
        .peminjaman-filter-card .form-select.form-control-modern:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(248, 250, 252, 0.85);
        }

        /* --- CARD SCAN QR --- */
        .peminjaman-qr-card {
            border-radius: 24px;
            border: none;
            box-shadow: 0 18px 40px rgba(15,23,42,0.18);
        }

        .peminjaman-qr-card .form-control-modern,
        .peminjaman-qr-card .form-select.form-control-modern {
            border-radius: 999px;
        }

        .peminjaman-qr-card .btn-modern-primary {
            background: var(--pinj-teal-1);
            border: none;
        }

        .peminjaman-qr-card .btn-modern-primary:hover {
            filter: brightness(1.05);
        }

        /* --- CARD TABEL PEMINJAMAN AKTIF --- */
        .peminjaman-aktif-card {
            border-radius: 22px;
            border: none;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15,23,42,0.18);
            background: #ffffff;
        }

        /* Header card: TEAL GRADIENT */
        .peminjaman-aktif-card .card-header {
            background: linear-gradient(90deg, var(--pinj-teal-1), var(--pinj-teal-2));
            color: #ecfdf5;
            border-bottom: none;
        }

        .peminjaman-aktif-card .card-header h5 {
            font-weight: 600;
        }

        .peminjaman-aktif-card .card-header small {
            color: #ccfbf1;
        }

        .peminjaman-aktif-card .card-header .badge {
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
            color: #ecfdf5;
            font-size: .8rem;
            padding-inline: .8rem;
        }

        .peminjaman-aktif-card .table thead th {
            border-bottom: none;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            padding: .65rem .9rem;
            background: #F1F5F9;
        }

        .peminjaman-aktif-card .table tbody td {
            padding: .70rem .9rem;
            vertical-align: middle;
            font-size: .9rem;
        }

        .peminjaman-aktif-card .table tbody tr:nth-of-type(odd) {
            background: #ffffff;
        }

        .peminjaman-aktif-card .table tbody tr:nth-of-type(even) {
            background: #F8FAFC;
        }

        .peminjaman-aktif-card .table tbody tr:hover {
            background: var(--pinj-soft-bg);
        }

        .status-chip {
            border-radius: 999px;
            padding-inline: .9rem;
        }

        /* scrollbar di mobile / saat horizontal scroll */
        .peminjaman-aktif-card .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        .peminjaman-aktif-card .table-responsive::-webkit-scrollbar-track {
            background: #d1fae5;
            border-radius: 999px;
        }
        .peminjaman-aktif-card .table-responsive::-webkit-scrollbar-thumb {
            background: var(--pinj-teal-1);
            border-radius: 999px;
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 991.98px) {
            .peminjaman-petugas-page {
                margin: -1rem -1rem -1.5rem;
                padding: 1rem 1rem 2rem;
            }
        }

        @media (max-width: 767.98px) {
            /* filter field jadi 1 kolom */
            .peminjaman-filter-card .row > [class*="col-"] {
                margin-bottom: .75rem;
                flex: 0 0 100%;
                max-width: 100%;
            }

            .peminjaman-filter-card .card-body {
                padding: 1.1rem 1rem 1.4rem;
            }

            .peminjaman-aktif-card .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: .15rem;
            }

            .peminjaman-aktif-card .card-header h5 {
                font-size: 1rem;
            }

            .peminjaman-aktif-card .card-header small {
                font-size: .8rem;
            }

            .peminjaman-aktif-card .card-header .badge {
                margin-top: .35rem;
            }

            /* tabel dibuat lebar lalu di-scroll horizontal */
            .peminjaman-aktif-card .table {
                font-size: .85rem;
                min-width: 620px;
            }

            .peminjaman-aktif-card .table thead th,
            .peminjaman-aktif-card .table tbody td {
                padding: .5rem .7rem;
            }
        }
    </style>

    <div class="peminjaman-petugas-page">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 peminjaman-header-title">Peminjaman Aktif</h1>
                <div class="peminjaman-subtitle">
                    Validasi QR, lihat detail, dan pantau transaksi berjalan.
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            {{-- Card Filter --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100 peminjaman-filter-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="peminjaman-filter-icon d-flex align-items-center justify-content-center me-2">
                                <i class="fas fa-filter text-white"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Filter Peminjaman</div>
                                <small class="opacity-75">Tanggal pinjam, status, atau cari mahasiswa/barang</small>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label form-label-modern">Tanggal Pinjam</label>
                                <input type="date" id="filter-date" class="form-control form-control-modern" />
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-modern">Status</label>
                                <select id="filter-status" class="form-select form-control-modern">
                                    <option value="">Semua</option>
                                    <option value="berlangsung">Dipinjam</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label form-label-modern">Cari Mahasiswa / Barang</label>
                                <input type="text" id="filter-search" class="form-control form-control-modern" placeholder="Ketik nama atau barang" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Scan QR --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 peminjaman-qr-card">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                                🔍
                            </div>
                            <div>
                                <div class="fw-semibold">Scan QR Mahasiswa</div>
                                <small class="text-muted">Validasi sebelum serah terima</small>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label form-label-modern">Kode QR</label>
                            <div class="input-group">
                                <input type="text" id="scan-input" class="form-control form-control-modern" placeholder="Masukkan / scan kode QR" />
                                <button class="btn btn-modern btn-modern-primary" id="btn-scan">Proses</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label form-label-modern">Pilih Kamera</label>
                            <select id="camera-select" class="form-select form-control-modern">
                                <option value="" disabled selected>Sedang memuat kamera...</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label form-label-modern">Scan via Kamera</label>
                            <div id="qr-reader" class="border rounded p-2" style="display:none;"></div>
                        </div>

                        <small class="text-muted">Isi QR: ID Mahasiswa, ID Peminjaman, Kode Transaksi.</small>
                    </div>
                </div>
            </div>
        </div>

<<<<<<< HEAD
        {{-- TABEL PEMINJAMAN AKTIF --}}
        <div class="card border-0 shadow-sm peminjaman-aktif-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Peminjaman Aktif</h5>
                    <small>Transaksi yang sudah divalidasi QR</small>
=======
        {{-- Card Scan QR --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center me-2" style="width:44px;height:44px;">
                            🔍
                        </div>
                        <div>
                            <div class="fw-semibold">Scan QR Mahasiswa</div>
                            <small class="text-muted">Validasi sebelum aktivasi peminjaman</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-modern">Kode QR</label>
                        <div class="input-group">
                            <input type="text" id="scan-input" class="form-control form-control-modern" placeholder="Masukkan / scan kode QR" />
                            <button class="btn btn-modern btn-modern-primary" id="btn-scan">Proses</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-modern">Pilih Kamera</label>
                        <select id="camera-select" class="form-select form-control-modern">
                            <option value="" disabled selected>Sedang memuat kamera...</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label form-label-modern">Scan via Kamera</label>
                        <div id="qr-reader" class="border rounded p-2" style="display:none;"></div>
                    </div>

                    <small class="text-muted">Isi QR: ID Mahasiswa, ID Peminjaman, Kode Transaksi.</small>
>>>>>>> e17174a0545b2a0e04f164f6a92a3ca46fb26a70
                </div>
                <span class="badge">
                    {{ $activeList->count() }} aktif
                </span>
            </div>
<<<<<<< HEAD
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
=======
        </div>
    </div>

    {{-- TABEL PEMINJAMAN AKTIF --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Peminjaman Aktif</h5>
                <small class="text-muted">Transaksi yang sudah divalidasi QR</small>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success">
                {{ $activeList->count() }} aktif
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>Barang</th>
                        <th>Tanggal Pinjam</th>
                        <th>Estimasi Pengembalian</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="aktif-table">
                    @forelse($activeList as $item)
                        @php
                            $badge = $item->status === 'berlangsung' ? 'info' : ($item->status === 'selesai' ? 'success' : 'secondary');
                            $statusLabel = $item->status === 'berlangsung' ? 'Dipinjam ✔️' : ucfirst($item->status);
                        @endphp
                        <tr data-aktif-row
                            data-date="{{ \Carbon\Carbon::parse($item->waktu_awal)->toDateString() }}"
                            data-status="{{ $item->status }}"
                            data-search="{{ strtolower(($item->pengguna->nama ?? '') . ' ' . ($item->barang->nama_barang ?? '')) }}"
                            data-qr="{{ $item->qr->qr_code ?? '' }}"
                            data-qrpayload="{{ $item->qr->payload ?? '' }}">
                            <td>
                                {{ $item->pengguna->nama ?? '-' }}<br>
                                <small class="text-muted">{{ $item->pengguna->email ?? '' }}</small>
                            </td>
                            <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="text-nowrap">
                                {{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}
                            </td>
                            <td>
                                <span class="badge bg-{{ $badge }} status-chip">{{ $statusLabel }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-outline-primary btn-detail"
                                            data-nama="{{ $item->pengguna->nama ?? '-' }}"
                                            data-email="{{ $item->pengguna->email ?? '-' }}"
                                            data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                            data-kode="{{ $item->barang->kode_barang ?? '-' }}"
                                            data-mulai="{{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}"
                                            data-akhir="{{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}"
                                            data-status="{{ ucfirst($item->status) }}"
                                            data-qr="{{ $item->qr->qr_code ?? '-' }}"
                                            data-qrpayload="{{ $item->qr->payload ?? '' }}"
                                            data-riwayat="Peminjaman aktif. Riwayat perpanjangan: {{ $item->perpanjangan->count() }} kali. Keluhan: {{ $item->keluhan->count() }}.">
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
>>>>>>> e17174a0545b2a0e04f164f6a92a3ca46fb26a70
                        <tr>
                            <th>Nama Mahasiswa</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Estimasi Pengembalian</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="aktif-table">
                        @forelse($activeList as $item)
                            @php
                                $badge = $item->status === 'berlangsung' ? 'info' : ($item->status === 'selesai' ? 'success' : 'secondary');
                                $statusLabel = $item->status === 'berlangsung' ? 'Dipinjam ✔️' : ucfirst($item->status);
                            @endphp
                            <tr data-aktif-row
                                data-date="{{ \Carbon\Carbon::parse($item->waktu_awal)->toDateString() }}"
                                data-status="{{ $item->status }}"
                                data-search="{{ strtolower(($item->pengguna->nama ?? '') . ' ' . ($item->barang->nama_barang ?? '')) }}"
                                data-qr="{{ $item->qr->qr_code ?? '' }}">
                                <td>
                                    {{ $item->pengguna->nama ?? '-' }}<br>
                                    <small class="text-muted">{{ $item->pengguna->email ?? '' }}</small>
                                </td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}
                                </td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badge }} status-chip">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-primary btn-detail"
                                                data-nama="{{ $item->pengguna->nama ?? '-' }}"
                                                data-email="{{ $item->pengguna->email ?? '-' }}"
                                                data-barang="{{ $item->barang->nama_barang ?? '-' }}"
                                                data-kode="{{ $item->barang->kode_barang ?? '-' }}"
                                                data-mulai="{{ \Carbon\Carbon::parse($item->waktu_awal)->translatedFormat('d M Y H:i') }}"
                                                data-akhir="{{ \Carbon\Carbon::parse($item->waktu_akhir)->translatedFormat('d M Y H:i') }}"
                                                data-status="{{ ucfirst($item->status) }}"
                                                data-qr="{{ $item->qr->qr_code ?? '-' }}"
                                                data-riwayat="Peminjaman aktif. Riwayat perpanjangan: {{ $item->perpanjangan->count() }} kali. Keluhan: {{ $item->keluhan->count() }}.">
                                            Detail
                                        </button>
                                        @if($item->qr)
                                            <button class="btn btn-sm btn-modern btn-modern-success btn-scan-attach" data-qr="{{ $item->qr->qr_code }}">
                                                🔍 Scan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada peminjaman aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div> {{-- end .peminjaman-petugas-page --}}

    {{-- MODAL DETAIL TRANSAKSI (LOGIKA TETAP SAMA) --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded border bg-light">
                                <div class="fw-semibold mb-1">Mahasiswa</div>
                                <div id="detail-nama"></div>
                                <small class="text-muted" id="detail-email"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded border bg-light">
                                <div class="fw-semibold mb-1">Barang</div>
                                <div id="detail-barang"></div>
                                <small class="text-muted" id="detail-kode"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded border bg-white shadow-sm h-100">
                                <div class="fw-semibold mb-2">Periode</div>
                                <div class="text-muted small mb-1">Mulai</div>
                                <div id="detail-mulai" class="fw-semibold"></div>
                                <div class="text-muted small mt-2 mb-1">Estimasi Kembali</div>
                                <div id="detail-akhir" class="fw-semibold"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded border bg-white shadow-sm h-100">
                                <div class="fw-semibold mb-2">Status</div>
                                <span class="badge bg-primary" id="detail-status"></span>
                                <div class="mt-3">
                                    <div class="fw-semibold mb-1">QR / Kode Transaksi</div>
                                    <div id="detail-qr" class="text-monospace"></div>
                                    <div class="mt-2 text-center">
                                        <img id="detail-qr-img" src="" alt="QR Peminjaman" class="img-fluid rounded border" style="max-width: 180px; display:none;">
                                        <div id="detail-qr-empty" class="text-muted small" style="display:none;">QR belum tersedia.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded border bg-white shadow-sm">
                                <div class="fw-semibold mb-1">Riwayat Transaksi</div>
                                <p class="mb-0 text-muted" id="detail-riwayat"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT QR SCAN & FILTER (LOGIKA PERSIS SEBELUMNYA) --}}
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const aktifRows = document.querySelectorAll('[data-aktif-row]');
            const filterDate = document.getElementById('filter-date');
            const filterStatus = document.getElementById('filter-status');
            const filterSearch = document.getElementById('filter-search');
            const scanInput = document.getElementById('scan-input');
            const scanBtn = document.getElementById('btn-scan');
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            const cameraSelect = document.getElementById('camera-select');
            const qrReader = document.getElementById('qr-reader');
            let scanner = null;
            let isCameraActive = false;
            let isSubmitting = false;

            function showToast(message, variant = 'success') {
                const wrapper = document.createElement('div');
                wrapper.className = `alert alert-${variant} alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow`;
                wrapper.role = 'alert';
                wrapper.style.zIndex = 1080;
                wrapper.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(wrapper);
                setTimeout(() => wrapper.remove(), 3000);
            }

            function applyFilters() {
                const dateVal = filterDate.value;
                const statusVal = filterStatus.value;
                const searchVal = filterSearch.value.toLowerCase();

                [...aktifRows].forEach(row => {
                    const rowDate = row.getAttribute('data-date');
                    const rowStatus = row.getAttribute('data-status');
                    const rowSearch = row.getAttribute('data-search') ?? '';

                    const matchDate = !dateVal || rowDate === dateVal;
                    const matchStatus = !statusVal || rowStatus === statusVal;
                    const matchSearch = !searchVal || rowSearch.includes(searchVal);

                    row.style.display = (matchDate && matchStatus && matchSearch) ? '' : 'none';
                });
            }

            [filterDate, filterStatus, filterSearch].forEach(el => {
                if (el) el.addEventListener('input', applyFilters);
            });

            function fillModal(btn) {
                document.getElementById('detail-nama').textContent = btn.dataset.nama;
                document.getElementById('detail-email').textContent = btn.dataset.email;
                document.getElementById('detail-barang').textContent = btn.dataset.barang;
                document.getElementById('detail-kode').textContent = btn.dataset.kode;
                document.getElementById('detail-mulai').textContent = btn.dataset.mulai;
                document.getElementById('detail-akhir').textContent = btn.dataset.akhir;
                document.getElementById('detail-status').textContent = btn.dataset.status;
                document.getElementById('detail-qr').textContent = btn.dataset.qr;
                const img = document.getElementById('detail-qr-img');
                const empty = document.getElementById('detail-qr-empty');
                const payload = btn.dataset.qrpayload || '';
                if (payload) {
                    img.src = `https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(payload)}`;
                    img.style.display = 'block';
                    empty.style.display = 'none';
                } else {
                    img.style.display = 'none';
                    empty.style.display = 'block';
                }
                document.getElementById('detail-riwayat').textContent = btn.dataset.riwayat;
            }

            document.querySelectorAll('.btn-detail').forEach(btn => {
                btn.addEventListener('click', () => {
                    fillModal(btn);
                    detailModal.show();
                });
            });

            function startScanner(cameraId) {
                if (!window.Html5Qrcode) return;
                if (isCameraActive && scanner) {
                    scanner.stop();
                }
                qrReader.style.display = 'block';
                scanner = new Html5Qrcode('qr-reader');
                scanner.start(
                    cameraId,
                    { fps: 10, qrbox: 220 },
                    (decodedText) => {
                        scanInput.value = decodedText;
                        scanBtn.click();
                        showToast('QR terbaca: ' + decodedText, 'success');
                        scanner.stop();
                        isCameraActive = false;
                        qrReader.style.display = 'none';
                    }
                ).then(() => {
                    isCameraActive = true;
                }).catch(err => {
                    console.error('Gagal menghidupkan kamera:', err);
                    showToast('Kamera tidak bisa diakses.', 'danger');
                });
            }

            if (window.Html5Qrcode) {
                Html5Qrcode.getCameras().then(devices => {
                    cameraSelect.innerHTML = '';
                    devices.forEach((cam, i) => {
                        const opt = document.createElement('option');
                        opt.value = cam.id;
                        opt.textContent = cam.label || `Kamera ${i + 1}`;
                        cameraSelect.appendChild(opt);
                    });
                    if (devices.length > 0) {
                        startScanner(devices[0].id);
                    }
                    cameraSelect.addEventListener('change', function () {
                        if (this.value) startScanner(this.value);
                    });
                }).catch(err => {
                    console.error('Tidak bisa memuat kamera', err);
                });
            }

            function markAsScanned(qrCode) {
                if (!qrCode) return false;
                let kodeTransaksi = qrCode;
                try {
                    const parsed = JSON.parse(qrCode);
                    if (parsed?.kode_transaksi) {
                        kodeTransaksi = parsed.kode_transaksi;
                    }
                } catch (e) {
                    // plain string, keep as is
                }
                let found = false;
                document.querySelectorAll('[data-aktif-row]').forEach(row => {
                    if (row.getAttribute('data-qr') === kodeTransaksi) {
                        const chip = row.querySelector('.status-chip');
                        if (chip) {
                            chip.className = 'badge bg-success status-chip';
                            chip.textContent = 'Dipinjam ✔️';
                        }
                        found = true;
                    }
                });
                return found;
            }

            async function activateViaApi(qrCode) {
                if (isSubmitting) return;
                isSubmitting = true;
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                try {
                    const res = await fetch('{{ route('petugas.peminjaman.activate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ qr_code: qrCode }),
                    });

                    if (!res.ok) {
                        const data = await res.json().catch(() => ({}));
                        throw new Error(data.message || 'QR tidak valid atau peminjaman tidak ditemukan');
                    }

                    showToast('Peminjaman diaktifkan. Memuat ulang...', 'success');

                    // Segera ubah badge di tabel (jika ada) sambil menunggu reload.
                    markAsScanned(qrCode);

                    setTimeout(() => window.location.reload(), 600);
                } catch (err) {
                    console.error(err);
                    showToast(err.message, 'danger');
                } finally {
                    isSubmitting = false;
                }
            }

            scanBtn?.addEventListener('click', () => {
                const qrCode = scanInput.value.trim();
                if (!qrCode) {
                    showToast('Isi kode QR terlebih dahulu.', 'warning');
                    return;
                }

                activateViaApi(qrCode);
                scanInput.value = '';
            });

        });
    </script>

@else
    {{-- =================== VIEW MAHASISWA: PEMINJAMAN SAYA (TIDAK DIUBAH) =================== --}}
<style>
    /* PALET WARNA SIPKAM (HIJAU) */
    :root {
        --sipkam-deep-1: #051F20;
        --sipkam-deep-2: #0B2B26;
        --sipkam-deep-3: #163832;
        --sipkam-deep-4: #235347;
        --sipkam-soft:  #8EB69B;
        --sipkam-mist:  #DAF1DE;
    }

    /* ============ BACKGROUND HALAMAN (BUKAN WRAPPER) ============ */
    /* dipakai ketika body kamu pakai class sipkam-light (sama seperti dashboard) */
    body.sipkam-light {
        background:
            radial-gradient(circle at 50% 20%, rgba(255,255,255,0.30) 0%, transparent 55%),
            linear-gradient(
                180deg,
                var(--sipkam-mist) 0%,   /* #DAF1DE – paling terang di atas */
                #cfe7d6       25%,
                var(--sipkam-soft) 55%, /* #8EB69B – tengah */
                #2b5d4f       80%,
                var(--sipkam-deep-2) 100%  /* #0B2B26 – bagian bawah */
            );
        background-attachment: fixed;
        color: #0f172a;
    }

    /* kalau nanti kamu pakai mode gelap */
    body.sipkam-dark {
        background:
            radial-gradient(circle at 50% 0%, rgba(148,163,184,0.20) 0%, transparent 55%),
            linear-gradient(180deg, #020617 0%, #020617 55%, #020617 100%);
        background-attachment: fixed;
        color: #e5e7eb;
    }

    /* WRAPPER KONTEN – dibuat transparan supaya gradasi body kelihatan */
    .peminjaman-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1.75rem 0 2.5rem;
        background: transparent;
    }

    /* ============ BLOK BESAR "DAFTAR PEMINJAMAN" ============ */
    .peminjaman-card {
        border-radius: 24px;
        border: none;
        padding: 18px;
        background: transparent; /* biar yang terlihat kartu putih di dalamnya */
        box-shadow: 0 26px 60px rgba(5, 31, 32, 0.25);
        position: relative;
        overflow: visible;
    }

    /* garis tipis gradasi di atas blok */
    .peminjaman-card::before {
        content: "";
        position: absolute;
        left: 18px;
        right: 18px;
        top: 0;
        height: 4px;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--sipkam-deep-3), var(--sipkam-soft), #facc15);
    }

    body.sipkam-dark .peminjaman-card {
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.9);
    }

    /* KARTU PUTIH YANG BERISI TABEL (DI DALAM BLOK HIJAU) */
    .peminjaman-card .table-responsive {
        border-radius: 18px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
    }

    body.sipkam-dark .peminjaman-card .table-responsive {
        background: #020617;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9);
    }

    /* ============ JUDUL & SUBTITLE ============ */
    .peminjaman-header-title h1 {
        color: var(--sipkam-deep-2);
    }

    .peminjaman-header-title small {
        color: rgba(15, 23, 42, 0.7);
        font-size: 0.85rem;
    }

    body.sipkam-dark .peminjaman-header-title h1 {
        color: var(--sipkam-soft);
    }

    body.sipkam-dark .peminjaman-header-title small {
        color: #9ca3af;
    }

    /* ============ TOMBOL "TAMBAH PEMINJAMAN" ============ */
    .btn-peminjaman-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--sipkam-deep-3), var(--sipkam-soft));
        border: none;
        color: #ffffff;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding-inline: 1.1rem;
        padding-block: 0.55rem;
        font-size: 0.9rem;
        box-shadow: 0 18px 35px rgba(22, 101, 52, 0.45);
    }

    .btn-peminjaman-primary:hover {
        filter: brightness(1.05);
        color: #ffffff;
        box-shadow: 0 20px 40px rgba(22, 101, 52, 0.6);
        transform: translateY(-1px);
    }

    body.sipkam-dark .btn-peminjaman-primary {
        box-shadow: 0 22px 50px rgba(34, 197, 94, 0.6);
    }

    /* ============ TABEL ============ */
    .peminjaman-table {
        margin-bottom: 0;
    }

    .peminjaman-table thead th {
        border-bottom: none;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.70rem 0.9rem;
        background: linear-gradient(90deg, var(--sipkam-deep-3), var(--sipkam-deep-4));
        color: #e5f9f0;
        white-space: nowrap;
    }

    .peminjaman-table tbody td {
        padding: 0.70rem 0.9rem;
        vertical-align: middle;
        border-top: 1px solid rgba(148, 163, 184, 0.25);
        font-size: 0.9rem;
    }

    /* zebra + hover */
    .peminjaman-table tbody tr:nth-child(even) {
        background: rgba(250, 252, 251, 0.9);
    }

    .peminjaman-table tbody tr:hover {
        background: rgba(222, 247, 236, 0.9);
    }

    .peminjaman-table th.col-kode,
    .peminjaman-table td.col-kode {
        width: 80px;
        white-space: nowrap;
    }

    .peminjaman-table td small {
        font-size: 0.78rem;
    }

   /* ============ BACKGROUND HALAMAN (BUKAN WRAPPER) ============ */
    /* dipakai ketika body kamu pakai class sipkam-light (sama seperti dashboard) */
    body.sipkam-light {
        background:
            radial-gradient(circle at 50% 20%, rgba(255,255,255,0.30) 0%, transparent 55%),
            linear-gradient(
                180deg,
                var(--sipkam-mist) 0%,   /* #DAF1DE – paling terang di atas */
                #cfe7d6       25%,
                var(--sipkam-soft) 55%, /* #8EB69B – tengah */
                #2b5d4f       80%,
                var(--sipkam-deep-2) 100%  /* #0B2B26 – bagian bawah */
            );
        background-attachment: fixed;
        color: #0f172a;
    }

    /* kalau nanti kamu pakai mode gelap */
    body.sipkam-dark {
        background:
            radial-gradient(circle at 50% 0%, rgba(148,163,184,0.20) 0%, transparent 55%),
            linear-gradient(180deg, #020617 0%, #020617 55%, #020617 100%);
        background-attachment: fixed;
        color: #e5e7eb;
    }

    /* WRAPPER KONTEN – dibuat transparan supaya gradasi body kelihatan */
    .peminjaman-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 1.75rem 0 2.5rem;
        background: transparent;
    }

    /* ============ BLOK BESAR "DAFTAR PEMINJAMAN" ============ */
    .peminjaman-card {
        border-radius: 24px;
        border: none;
        padding: 18px;
        background: transparent; /* biar yang terlihat kartu putih di dalamnya */
        box-shadow: 0 26px 60px rgba(5, 31, 32, 0.25);
        position: relative;
        overflow: visible;
    }

    /* garis tipis gradasi di atas blok */
    .peminjaman-card::before {
        content: "";
        position: absolute;
        left: 18px;
        right: 18px;
        top: 0;
        height: 4px;
        border-radius: 999px;
        background: linear-gradient(90deg, var(--sipkam-deep-3), var(--sipkam-soft), #facc15);
    }

    body.sipkam-dark .peminjaman-card {
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.9);
    }

    /* KARTU PUTIH YANG BERISI TABEL (DI DALAM BLOK HIJAU) */
    .peminjaman-card .table-responsive {
        border-radius: 18px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.10);
    }

    body.sipkam-dark .peminjaman-card .table-responsive {
        background: #020617;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9);
    }

    /* ============ JUDUL & SUBTITLE ============ */
    .peminjaman-header-title h1 {
        color: var(--sipkam-deep-2);
    }

    .peminjaman-header-title small {
        color: rgba(15, 23, 42, 0.7);
        font-size: 0.85rem;
    }

    body.sipkam-dark .peminjaman-header-title h1 {
        color: var(--sipkam-soft);
    }

    body.sipkam-dark .peminjaman-header-title small {
        color: #9ca3af;
    }

    /* ============ TOMBOL "TAMBAH PEMINJAMAN" ============ */
    .btn-peminjaman-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--sipkam-deep-3), var(--sipkam-soft));
        border: none;
        color: #ffffff;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding-inline: 1.1rem;
        padding-block: 0.55rem;
        font-size: 0.9rem;
        box-shadow: 0 18px 35px rgba(22, 101, 52, 0.45);
    }

    .btn-peminjaman-primary:hover {
        filter: brightness(1.05);
        color: #ffffff;
        box-shadow: 0 20px 40px rgba(22, 101, 52, 0.6);
        transform: translateY(-1px);
    }

    body.sipkam-dark .btn-peminjaman-primary {
        box-shadow: 0 22px 50px rgba(34, 197, 94, 0.6);
    }

    /* ============ TABEL ============ */
    .peminjaman-table {
        margin-bottom: 0;
    }

    .peminjaman-table thead th {
        border-bottom: none;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.70rem 0.9rem;
        background: linear-gradient(90deg, var(--sipkam-deep-3), var(--sipkam-deep-4));
        color: #e5f9f0;
        white-space: nowrap;
    }

    .peminjaman-table tbody td {
        padding: 0.70rem 0.9rem;
        vertical-align: middle;
        border-top: 1px solid rgba(148, 163, 184, 0.25);
        font-size: 0.9rem;
    }

    /* zebra + hover */
    .peminjaman-table tbody tr:nth-child(even) {
        background: rgba(250, 252, 251, 0.9);
    }

    .peminjaman-table tbody tr:hover {
        background: rgba(222, 247, 236, 0.9);
    }

    .peminjaman-table th.col-kode,
    .peminjaman-table td.col-kode {
        width: 80px;
        white-space: nowrap;
    }

    .peminjaman-table td small {
        font-size: 0.78rem;
    }

    /* MODE GELAP UNTUK TABEL */
    body.sipkam-dark .peminjaman-table thead th {
        background: linear-gradient(90deg, var(--sipkam-deep-1), var(--sipkam-deep-3));
        color: #bbf7d0;
        border-bottom: 1px solid rgba(31, 41, 55, 0.9);
    }

    body.sipkam-dark .peminjaman-table tbody td {
        color: #e5e7eb;
        border-top-color: rgba(31, 41, 55, 0.9);
    }

    body.sipkam-dark .peminjaman-table tbody tr:nth-child(even) {
        background: #020617;
    }

    body.sipkam-dark .peminjaman-table tbody tr:hover {
        background: rgba(15, 23, 42, 0.9);
    }

    body.sipkam-dark .peminjaman-table tbody td small {
        color: #9ca3af;
    }

    body.sipkam-dark .peminjaman-table .badge {
        box-shadow: 0 0 12px rgba(34, 197, 94, 0.45);
    }

    /* STATUS BADGE */
    .peminjaman-table .badge.bg-info,
    .peminjaman-table .badge.bg-success,
    .peminjaman-table .badge.bg-danger {
        border-radius: 999px;
        padding-inline: 0.9rem;
        padding-block: 0.3rem;
        font-size: 0.7rem;
    }

    .peminjaman-table .badge.bg-info {
        background-color: #0ea5e9 !important;
        color: #e0f2fe;
    }

    .peminjaman-table .badge.bg-success {
        background-color: #16a34a !important;
        color: #dcfce7;
    }

    .peminjaman-table .badge.bg-danger {
        background-color: #ef4444 !important;
        color: #fee2e2;
    }

    /* TOMBOL DETAIL */
    .peminjaman-table .btn-outline-primary {
        border-radius: 999px;
        padding-inline: 0.9rem;
        padding-block: 0.25rem;
        font-size: 0.78rem;
        border-color: var(--sipkam-deep-3);
        color: var(--sipkam-deep-3);
    }

    .peminjaman-table .btn-outline-primary:hover {
        background: var(--sipkam-deep-3);
        color: #ffffff;
    }

    body.sipkam-dark .peminjaman-table .btn-outline-primary {
        border-color: var(--sipkam-soft);
        color: var(--sipkam-soft);
    }

    body.sipkam-dark .peminjaman-table .btn-outline-primary:hover {
        background: var(--sipkam-soft);
        color: #020617;
    }

    /* RESPONSIVE */
    @media (max-width: 767.98px) {
        .peminjaman-page {
            margin: -16px -16px -24px -16px;
            padding: 16px 16px 24px;
        }

        .peminjaman-header-title h1 {
            font-size: 1.25rem;
        }
    }

    /* TEKS BODY DI DALAM CARD (MODE TERANG) */
    .peminjaman-card .peminjaman-table tbody td,
    .peminjaman-card .peminjaman-table tbody td small {
        color: #000000ff;
        font-weight: 500;
    }

    /* TEKS BODY DI DALAM CARD (MODE GELAP) */
    body.sipkam-dark .peminjaman-card .peminjaman-table tbody td,
    body.sipkam-dark .peminjaman-card .peminjaman-table tbody td small {
        color: #e5e7eb !important;
        font-weight: 400;
    }

    /* TOMBOL DETAIL */
    .peminjaman-table .btn-outline-primary {
        border-radius: 999px;
        padding-inline: 0.9rem;
        padding-block: 0.25rem;
        font-size: 0.78rem;
        border-color: var(--sipkam-deep-3);
        color: var(--sipkam-deep-3);
    }

    .peminjaman-table .btn-outline-primary:hover {
        background: var(--sipkam-deep-3);
        color: #ffffff;
    }

    body.sipkam-dark .peminjaman-table .btn-outline-primary {
        border-color: var(--sipkam-soft);
        color: var(--sipkam-soft);
    }

    body.sipkam-dark .peminjaman-table .btn-outline-primary:hover {
        background: var(--sipkam-soft);
        color: #020617;
    }

    /* RESPONSIVE */
    @media (max-width: 767.98px) {
        .peminjaman-page {
            margin: -16px -16px -24px -16px;
            padding: 16px 16px 24px;
        }

        .peminjaman-header-title h1 {
            font-size: 1.25rem;
        }
    }

    /* TEKS BODY DI DALAM CARD (MODE TERANG) */
    .peminjaman-card .peminjaman-table tbody td,
    .peminjaman-card .peminjaman-table tbody td small {
        color: #0f172a;
        font-weight: 500;
    }

    /* TEKS BODY DI DALAM CARD (MODE GELAP) */
    body.sipkam-dark .peminjaman-card .peminjaman-table tbody td,
    body.sipkam-dark .peminjaman-card .peminjaman-table tbody td small {
        color: #e5e7eb !important;
        font-weight: 400;
    }
    /* ========== DARK MODE: tabel full hitam + teks hijau neon ========== */

/* semua sel body tabel jadi hitam pekat */
body.sipkam-dark .peminjaman-table tbody tr,
body.sipkam-dark .peminjaman-table tbody td {
    background-color: #020617 !important;
}

/* hilangkan efek zebra di mode gelap (semua sama hitam) */
body.sipkam-dark .peminjaman-table tbody tr:nth-child(even) td {
    background-color: #020617 !important;
}

/* hover: tetap hitam, tapi ada garis glow hijau di dalam baris */
body.sipkam-dark .peminjaman-table tbody tr:hover td {
    background-color: #020617 !important;
    box-shadow: 0 0 0 1px rgba(34, 197, 94, 0.5) inset;
}

/* teks isi tabel hijau neon */
body.sipkam-dark .peminjaman-card .peminjaman-table tbody td,
body.sipkam-dark .peminjaman-card .peminjaman-table tbody td small {
    color: #a7f3d0 !important;   /* hijau neon */
    font-weight: 500;
}

body.sipkam-dark .btn-peminjaman-primary {
    background: #22c55e;              /* hijau solid */
    color: #020617;
    border: none;
    box-shadow: 0 0 24px rgba(34, 197, 94, 0.9);
}

body.sipkam-dark .btn-peminjaman-primary:hover {
    background: #16a34a;
    color: #020617;
    box-shadow: 0 0 30px rgba(34, 197, 94, 1);
    transform: translateY(-1px);
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

        {{-- CARD TABEL --}}
        <div class="peminjaman-card">
            <div class="table-responsive">
                <table class="table peminjaman-table align-middle">
                    <thead>
                        <tr>
                            <th class="col-kode text-nowrap">Kode</th>
                            <th>Barang</th>
                            <th class="text-nowrap">Tanggal Pinjam</th>
                            <th class="text-nowrap">Estimasi Pengembalian</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $item)
                            @php
                                $status = $item->status;
                                $badge = match ($status) {
                                    'berlangsung' => 'info',
                                    'selesai'     => 'success',
                                    'booking'     => 'warning',
                                    'dibatalkan'  => 'secondary',
                                    default       => 'danger',
                                };
                            @endphp
                            <tr>
                                <td class="col-kode">#{{ $item->id_peminjaman }}</td>
                                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($item->waktu_awal)->format('d M Y H:i') }}
                                </td>
                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($item->waktu_akhir)->format('d M Y H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('mahasiswa.peminjaman.show', $item->id_peminjaman) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            Detail
                                        </a>
                                        @if($status === 'booking')
                                            <form method="POST"
                                                  action="{{ route('mahasiswa.peminjaman.cancel', $item->id_peminjaman) }}"
                                                  onsubmit="return confirm('Batalkan booking ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Batal
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada data peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endif
@endsection
