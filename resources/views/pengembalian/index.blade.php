@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Pengembalian Barang</h1>
        <small class="text-muted">Daftar seluruh pengembalian barang oleh mahasiswa</small>
    </div>
    <a href="{{ route('petugas.pengembalian.scan') }}" class="btn btn-primary">
        Scan QR Pengembalian
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Barang</th>
                        <th>Peminjam</th>
                        <th>Waktu Pengembalian</th>
                        <th>Denda</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $item)
                        @php
                            $peminjaman = $item->peminjaman;
                            $totalDenda = $peminjaman?->denda?->sum('total_denda') ?? 0;
                        @endphp
                        <tr
                            data-row
                            data-nama="{{ $peminjaman->pengguna->nama ?? '-' }}"
                            data-email="{{ $peminjaman->pengguna->email ?? '-' }}"
                            data-barang="{{ $peminjaman->barang->nama_barang ?? '-' }}"
                            data-kode="{{ $peminjaman->barang->kode_barang ?? '-' }}"
                            data-pinjam="{{ optional($peminjaman?->waktu_awal ? \Carbon\Carbon::parse($peminjaman->waktu_awal) : null)?->translatedFormat('d M Y H:i') ?? '-' }}"
                            data-kembali="{{ optional($item->waktu_pengembalian)?->translatedFormat('d M Y H:i') ?? '-' }}"
                            data-denda="{{ $totalDenda > 0 ? 'Rp ' . number_format($totalDenda, 0, ',', '.') : 'Tidak ada' }}"
                            data-catatan="{{ $item->catatan ?? '-' }}"
                        >
                            <td>#{{ $item->id_pengembalian }}</td>
                            <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                            <td>
                                {{ $peminjaman->pengguna->nama ?? '-' }}<br>
                                @if(!empty($peminjaman->pengguna?->email))
                                    <small class="text-muted">{{ $peminjaman->pengguna->email }}</small>
                                @endif
                            </td>
                            <td>{{ optional($item->waktu_pengembalian)->format('d M Y H:i') }}</td>
                            <td>
                                @if($totalDenda > 0)
                                    <span class="badge bg-danger">Ada (Rp {{ number_format($totalDenda, 0, ',', '.') }})</span>
                                @else
                                    <span class="badge bg-success">Tidak Ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary btn-detail">Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada pengembalian.
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
                <h5 class="modal-title">Detail Pengembalian</h5>
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
                            <div class="fw-semibold text-muted">Total Denda</div>
                            <div id="d-denda" class="fw-semibold text-danger"></div>
                            <div class="fw-semibold text-muted mt-3">Catatan</div>
                            <div id="d-catatan" class="text-muted"></div>
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
                if (!row) return;
                document.getElementById('d-nama').textContent = row.dataset.nama || '-';
                document.getElementById('d-email').textContent = row.dataset.email || '-';
                document.getElementById('d-barang').textContent = row.dataset.barang || '-';
                document.getElementById('d-kode').textContent = row.dataset.kode || '-';
                document.getElementById('d-pinjam').textContent = row.dataset.pinjam || '-';
                document.getElementById('d-kembali').textContent = row.dataset.kembali || '-';
                document.getElementById('d-denda').textContent = row.dataset.denda || '-';
                document.getElementById('d-catatan').textContent = row.dataset.catatan || '-';
                detailModal.show();
            });
        });
    });
</script>
@endsection
