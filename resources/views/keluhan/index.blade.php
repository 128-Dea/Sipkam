@extends('layouts.app')

@section('content')
@php
    $scope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
    $isPetugas = $scope === 'petugas';
@endphp

<style>
    :root {
        /* Palet tema referensi */
        --theme-dark:      #051F20;
        --theme-dark-soft: #0B2B26;
        --theme-mid:       #163832;
        --theme-edge:      #253547;
        --theme-accent:    #8EB69B;
        --theme-light:     #DAF1DE;
        --theme-text-light:#ffffff;
    }

    /* ============================
       WRAPPER DENGAN GRADIENT
       ============================ */
    .keluhan-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        background:
            radial-gradient(
                circle at 50% 120%,
                var(--theme-light) 0,
                var(--theme-accent) 30%,
                var(--theme-mid) 70%,
                var(--theme-dark) 100%
            );
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    /* container biar header & card selalu satu kolom rapi di tengah */
    .keluhan-inner {
        width: 100%;
        max-width: 1150px;
    }

    @media(max-width: 767.98px){
        .keluhan-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .keluhan-inner {
            max-width: 100%;
        }
    }

    /* ============================
       HEADER GELAP (judul + filter)
       ============================ */
    .keluhan-header-block {
        width: 100%;
        background: linear-gradient(135deg, var(--theme-dark), var(--theme-mid));
        color: var(--theme-text-light);
        padding: 22px 26px;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 18px 32px rgba(5, 31, 32, 0.55);
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }

    .keluhan-header-block h1 {
        color: var(--theme-light) !important;
        font-weight: 650;
        font-size: 1.8rem;
        letter-spacing: 0.04em;
        margin-bottom: 4px;
    }

    .keluhan-header-block .text-muted {
        color: rgba(218, 241, 222, 0.9) !important;
    }

    @media(max-width: 767.98px){
        .keluhan-header-block {
            padding: 18px 16px;
            border-radius: 16px 16px 0 0;
        }
    }

    /* ============================
       CARD TABEL PUTIH
       ============================ */
    .keluhan-card {
        width: 100%;
        border-radius: 0 0 20px 20px;
        border: none;
        box-shadow: 0 22px 44px rgba(5, 31, 32, 0.5);
        overflow: hidden;
        margin-top: -1px; /* nempel mulus ke header */
        background: #ffffff;
    }

    @media(max-width: 767.98px){
        .keluhan-card {
            border-radius: 0 0 16px 16px;
        }
    }

    .keluhan-table thead th {
        background-color: var(--theme-dark-soft);
        color: #f9fafb;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: none;
        padding: 14px 14px;
    }

    .keluhan-table tbody td {
        padding: 14px 14px;
        font-size: 0.95rem;
        color: var(--theme-mid);
    }

    .keluhan-table tbody tr:hover {
        background-color: #ecf4ee;
    }

    /* ============================
       FILTER & FORM DI HEADER
       ============================ */
    .form-control-dark {
        background-color: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.35);
        color: #e9f7f0;
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .form-control-dark:focus {
        background-color: rgba(255,255,255,0.16);
        border-color: rgba(255,255,255,0.75);
        color: #ffffff;
        box-shadow: none;
    }

    .form-control-dark option {
        background-color: var(--theme-dark);
        color: #f9fafb;
    }

    .btn-filter-dark {
        background: linear-gradient(135deg, var(--theme-mid), var(--theme-dark-soft));
        border: 1px solid rgba(255,255,255,0.35);
        color: #e9f7f0;
        border-radius: 10px;
        padding-inline: 16px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .btn-filter-dark:hover {
        background: linear-gradient(135deg, var(--theme-accent), var(--theme-light));
        color: var(--theme-dark);
        border-color: rgba(218,241,222,0.9);
    }

    /* ============================
       BADGE & MODAL
       ============================ */
    .badge {
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 500;
        font-size: 0.8rem;
    }

    #detailModal .modal-header {
        background: linear-gradient(135deg, var(--theme-dark), var(--theme-mid));
    }

    #detailModal .modal-body {
        background: #f3faf6;
    }
</style>

<div class="keluhan-wrapper">
<<<<<<< HEAD
    <div class="keluhan-inner">
=======
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h3 mb-1">{{ $isPetugas ? 'Keluhan Barang' : 'Keluhan Mahasiswa' }}</h1>
            <small class="text-muted">
                {{ $isPetugas ? 'Pantau laporan mahasiswa dan tindak lanjuti' : 'Kelola laporan gangguan selama peminjaman' }}
            </small>
        </div>
        @if($isPetugas)
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select form-control-modern">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="ditangani" {{ request('status') === 'ditangani' ? 'selected' : '' }}>Ditangani</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button class="btn btn-modern btn-modern-primary" type="submit">Filter</button>
                </form>
            </div>
        @else
            <a href="{{ route($scope . '.keluhan.create') }}" class="btn btn-primary">Laporkan Keluhan</a>
        @endif
    </div>
>>>>>>> e17174a0545b2a0e04f164f6a92a3ca46fb26a70

        {{-- HEADER --}}
        <div class="keluhan-header-block">
            <div>
                <h1 class="h3 mb-1">{{ $isPetugas ? 'Keluhan Barang' : 'Keluhan Mahasiswa' }}</h1>
                <small class="text-muted">
                    {{ $isPetugas ? 'Pantau laporan mahasiswa dan tindak lanjuti' : 'Kelola laporan gangguan selama peminjaman' }}
                </small>
            </div>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <select name="status" class="form-select form-control-dark">
                        <option value="">Semua Status</option>
                        <option value="pending"   {{ request('status') === 'pending' ? 'selected' : '' }}>🕓 Pending</option>
                        <option value="ditangani" {{ request('status') === 'ditangani' ? 'selected' : '' }}>🔧 Ditangani</option>
                        <option value="selesai"   {{ request('status') === 'selesai' ? 'selected' : '' }}>✔️ Selesai</option>
                    </select>
                    <button class="btn btn-filter-dark" type="submit">Filter</button>
                </form>
                @if(!$isPetugas)
                    <a href="{{ route($scope . '.keluhan.create') }}"
                       class="btn"
                       style="background: linear-gradient(135deg, var(--theme-accent), var(--theme-light)); color: var(--theme-dark); border-radius: 999px; border: 1px solid rgba(142,182,155,0.8); font-weight:600;">
                        Laporkan
                    </a>
                @endif
            </div>
        </div>

        {{-- CARD TABEL --}}
        <div class="card keluhan-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0 keluhan-table">
                    <thead>
                        <tr>
                            @if($isPetugas)
                                <th>Nama Mahasiswa</th>
                                <th>Barang</th>
                                <th>Tanggal Keluhan</th>
                                <th>Isi / Detail</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            @else
                                <th>ID</th>
                                <th>Foto</th>
                                <th>Barang</th>
                                <th>Keluhan</th>
                                <th>Pelapor</th>
                                <th>Tanggal</th>
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($keluhan as $item)
                            @if($isPetugas)
                                @php
                                    $status = $item->status ?? 'pending';
                                    $label = $status === 'ditangani' ? '🔧 Ditangani' : ($status === 'selesai' ? '✔️ Selesai' : '🕓 Pending');
                                    $badge = $status === 'ditangani' ? 'warning' : ($status === 'selesai' ? 'success' : 'secondary');
                                @endphp
                                <tr data-row
                                    data-nama="{{ $item->pengguna->nama ?? '-' }}"
                                    data-email="{{ $item->pengguna->email ?? '-' }}"
                                    data-barang="{{ $item->peminjaman->barang->nama_barang ?? '-' }}"
                                    data-keluhan="{{ $item->keluhan }}"
                                    data-tanggal="{{ optional($item->created_at)->translatedFormat('d M Y H:i') ?? '-' }}"
                                    data-status="{{ ucfirst($status) }}"
                                    data-foto="{{ $item->foto_url ?? '' }}"
                                    data-tindak="{{ $item->tindak_lanjut ?? '-' }}"
                                >
                                    <td class="fw-medium">{{ $item->pengguna->nama ?? '-' }}</td>
                                    <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                    <td class="text-nowrap text-muted small">{{ optional($item->created_at)->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($item->keluhan, 80) }}</td>
                                    <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                                            <button class="btn btn-sm btn-outline-success btn-detail">Detail</button>
                                            @if($status === 'pending')
                                                <form method="POST" action="{{ route('petugas.keluhan.service', $item->id_keluhan) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning">📩 Service</button>
                                                </form>
                                            @endif
                                            @if(in_array($status, ['pending','ditangani']))
                                                <form method="POST" action="{{ route('petugas.keluhan.selesai', $item->id_keluhan) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">✔️ Selesai</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $item->id_keluhan }}</td>
                                    <td style="width: 100px;">
                                        @if($item->foto_url)
                                            <img src="{{ $item->foto_url }}" alt="Foto keluhan"
                                                 class="img-thumbnail" style="max-height:60px;object-fit:cover;">
                                        @else
                                            <span class="text-muted small">No IMG</span>
                                        @endif
                                    </td>
                                    <td class="fw-medium">{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                    <td class="text-muted">{{ Str::limit($item->keluhan, 50) }}</td>
                                    <td>{{ $item->pengguna->nama ?? '-' }}</td>
                                    <td>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route($scope . '.keluhan.show', $item->id_keluhan) }}"
                                           class="btn btn-sm btn-outline-success">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ $isPetugas ? 6 : 7 }}" class="text-center py-5">
                                    <div class="text-muted" style="opacity: 0.7;">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Belum ada keluhan yang masuk.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- MODAL DETAIL (LOGIKA TIDAK DIUBAH) --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: var(--theme-dark);">
                <h5 class="modal-title">Detail Keluhan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100 border">
                            <div class="fw-semibold mb-1 text-success">Mahasiswa</div>
                            <div id="d-nama" class="fs-5 text-dark"></div>
                            <small class="text-muted" id="d-email"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100 border">
                            <div class="fw-semibold mb-1 text-success">Barang</div>
                            <div id="d-barang" class="fs-5 text-dark"></div>
                            <small class="text-muted" id="d-status"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-4 rounded bg-white shadow-sm border">
                            <div class="fw-semibold mb-2 text-success">Deskripsi Keluhan</div>
                            <p class="mb-0 text-secondary" id="d-keluhan" style="line-height: 1.6;"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100 border">
                            <div class="fw-semibold mb-1 text-success">Tanggal Keluhan</div>
                            <div id="d-tanggal" class="mb-3"></div>
                            <div class="fw-semibold mb-1 text-success">Tindak Lanjut</div>
                            <p class="text-muted mb-0" id="d-tindak"></p>
                        </div>
                    </div>
                    <div class="col-md-6" id="d-foto-wrap" style="display:none;">
                        <div class="p-3 rounded bg-white shadow-sm h-100 text-center border">
                            <div class="fw-semibold mb-2 text-success">Foto Keluhan</div>
                            <img id="d-foto" src="" alt="Foto keluhan" class="img-fluid rounded" style="max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.querySelectorAll('[data-row] .btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const row = btn.closest('tr');
                document.getElementById('d-nama').textContent = row.dataset.nama;
                document.getElementById('d-email').textContent = row.dataset.email;
                document.getElementById('d-barang').textContent = row.dataset.barang;
                document.getElementById('d-status').textContent = row.dataset.status;
                document.getElementById('d-keluhan').textContent = row.dataset.keluhan;
                document.getElementById('d-tanggal').textContent = row.dataset.tanggal;
                document.getElementById('d-tindak').textContent = row.dataset.tindak;
                const foto = row.dataset.foto;
                const wrap = document.getElementById('d-foto-wrap');
                if (foto) {
                    document.getElementById('d-foto').src = foto;
                    wrap.style.display = '';
                } else {
                    wrap.style.display = 'none';
                }
                detailModal.show();
            });
        });
    });
</script>
@endsection
