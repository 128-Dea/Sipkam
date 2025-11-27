@extends('layouts.app')

@section('content')
@php
    $scope = auth()->user()?->role === 'petugas' ? 'petugas' : 'mahasiswa';
    $isPetugas = $scope === 'petugas';
@endphp

<style>
    :root {
        --keluhan-accent: #3b82f6;
        --keluhan-accent-soft: rgba(59,130,246,0.18);
    }
    .btn-keluhan-primary {
        border-radius: 999px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%) !important;
        border: none !important;
        color: #ffffff !important;
        font-weight: 600;
        box-shadow: 0 12px 30px rgba(79,70,229,0.25);
    }
    .btn-keluhan-primary:hover {
        color: #ffffff !important;
        filter: brightness(1.05);
    }
    body.sipkam-dark .btn-keluhan-primary {
        box-shadow: 0 12px 32px rgba(99,102,241,0.35);
    }
    .keluhan-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 20px 32px 32px;
        background: #ffffff;
    }
    .keluhan-card {
        border-radius: 16px;
        border: 1px solid rgba(148,163,184,0.3);
        box-shadow: 0 12px 30px rgba(148,163,184,0.35);
        overflow: hidden;
    }
    .keluhan-table thead th {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }
    .keluhan-empty {
        padding: 2rem 0;
    }
    .keluhan-thumb {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }
    .keluhan-thumb .thumb-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(180deg, rgba(0,0,0,0.05), rgba(0,0,0,0.25));
    }
    .keluhan-thumb .thumb-badge {
        position: absolute;
        right: 6px;
        bottom: 6px;
        background: rgba(0,0,0,0.65);
        color: #fff;
        font-size: 11px;
        padding: 4px 6px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        line-height: 1;
        letter-spacing: 0.04em;
    }
    @media(max-width: 767.98px){
        .keluhan-wrapper { margin: -16px -16px -16px -16px; padding: 16px; }
    }
</style>

<div class="keluhan-wrapper">
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
            <a href="{{ route($scope . '.keluhan.create') }}"
               class="btn btn-keluhan-primary"
               style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); border: none; color: #ffffff;">
                Laporkan Keluhan
            </a>
        @endif
    </div>

    <div class="card keluhan-card border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 keluhan-table">
                <thead class="table-light">
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
                        @php
                            $isVideo = $item->foto_url
                                && \Illuminate\Support\Str::of($item->foto_url)->lower()->contains(['.mp4', '.mov', '.avi', '.webm']);
                        @endphp
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
                                data-is-video="{{ $isVideo ? '1' : '0' }}"
                            >
                                <td>{{ $item->pengguna->nama ?? '-' }}</td>
                                <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td class="text-nowrap">{{ optional($item->created_at)->translatedFormat('d M Y') ?? '-' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($item->keluhan, 80) }}</td>
                                <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <button class="btn btn-sm btn-outline-primary btn-detail">Detail</button>
                                        @if($status === 'pending')
                                            <form method="POST" action="{{ route('petugas.keluhan.service', $item->id_keluhan) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">📩 Kirim ke Service</button>
                                            </form>
                                        @endif
                                        @if(in_array($status, ['pending','ditangani']))
                                            <form method="POST" action="{{ route('petugas.keluhan.selesai', $item->id_keluhan) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">✔️ Tandai Selesai</button>
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
                                        @if($isVideo)
                                            <div class="keluhan-thumb" style="height:60px;">
                                                <video src="{{ $item->foto_url }}"
                                                       class="w-100 h-100"
                                                       muted
                                                       playsinline
                                                       preload="metadata"
                                                       style="object-fit:cover;">
                                                </video>
                                                <div class="thumb-overlay"></div>
                                                <div class="thumb-badge">▶ Video</div>
                                            </div>
                                        @else
                                            <img src="{{ $item->foto_url }}" alt="Foto keluhan"
                                                 class="img-thumbnail" style="max-height:60px;object-fit:cover;">
                                        @endif
                                    @else
                                        <span class="text-muted small">Tidak ada lampiran</span>
                                    @endif
                                </td>
                                <td>{{ $item->peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ Str::limit($item->keluhan, 50) }}</td>
                                <td>{{ $item->pengguna->nama ?? '-' }}</td>
                                <td>{{ optional($item->created_at)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <a href="{{ route($scope . '.keluhan.show', $item->id_keluhan) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ $isPetugas ? 6 : 7 }}" class="text-center text-muted keluhan-empty">
                                Belum ada keluhan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Keluhan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold mb-1">Mahasiswa</div>
                            <div id="d-nama"></div>
                            <small class="text-muted" id="d-email"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-light h-100">
                            <div class="fw-semibold mb-1">Barang</div>
                            <div id="d-barang"></div>
                            <small class="text-muted" id="d-status"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded bg-white shadow-sm">
                            <div class="fw-semibold mb-1">Deskripsi Keluhan</div>
                            <p class="mb-0" id="d-keluhan"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white shadow-sm h-100">
                            <div class="fw-semibold mb-1">Tanggal Keluhan</div>
                            <div id="d-tanggal"></div>
                            <div class="fw-semibold mt-3 mb-1">Tindak Lanjut</div>
                            <p class="text-muted mb-0" id="d-tindak"></p>
                        </div>
                    </div>
                    <div class="col-md-6" id="d-lampiran-wrap" style="display:none;">
                        <div class="p-3 rounded bg-white shadow-sm h-100 text-center">
                            <div class="fw-semibold mb-2">Lampiran Keluhan</div>
                            <video id="d-video" class="img-fluid rounded mb-2" style="max-height:320px; display:none;" controls></video>
                            <img id="d-foto" src="" alt="Lampiran keluhan" class="img-fluid rounded" style="display:none;">
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
                const wrap = document.getElementById('d-lampiran-wrap');
                const isVideo = row.dataset.isVideo === '1';
                const videoEl = document.getElementById('d-video');
                const imgEl = document.getElementById('d-foto');
                if (foto) {
                    if (isVideo) {
                        videoEl.src = foto;
                        videoEl.style.display = '';
                        imgEl.style.display = 'none';
                        videoEl.load();
                    } else {
                        imgEl.src = foto;
                        imgEl.style.display = '';
                        videoEl.pause();
                        videoEl.removeAttribute('src');
                        videoEl.style.display = 'none';
                    }
                    wrap.style.display = '';
                } else {
                    wrap.style.display = 'none';
                    videoEl.pause();
                }
                detailModal.show();
            });
        });
    });
</script>
@endsection
