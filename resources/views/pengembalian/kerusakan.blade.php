@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Detail Kerusakan Barang</h1>
                <small class="text-muted">
                    Peminjaman #{{ $peminjaman->id_peminjaman }} &mdash;
                    {{ $peminjaman->barang->nama_barang ?? '-' }}
                </small>
            </div>
            <a href="{{ route('petugas.pengembalian.konfirmasi', $peminjaman->id_peminjaman) }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Ringkasan</h5>
                <p class="mb-1"><strong>Peminjam:</strong> {{ $peminjaman->pengguna->nama ?? '-' }}</p>
                <p class="mb-1">
                    <strong>Periode:</strong>
                    {{ \Carbon\Carbon::parse($peminjaman->waktu_awal)->format('d M Y H:i') }}
                    &mdash;
                    {{ \Carbon\Carbon::parse($peminjaman->waktu_akhir)->format('d M Y H:i') }}
                </p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Isi Catatan Kerusakan</h5>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('petugas.pengembalian.prosesKerusakan', $peminjaman->id_peminjaman) }}"
                >
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tanggal & Jam Pengembalian</label>
                        <input
                            type="datetime-local"
                            name="waktu_pengembalian"
                            value="{{ old('waktu_pengembalian', now()->format('Y-m-d\TH:i')) }}"
                            class="form-control @error('waktu_pengembalian') is-invalid @enderror"
                        >
                        @error('waktu_pengembalian')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Jika dikosongkan, akan otomatis memakai waktu saat ini.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Kerusakan</label>
                        <textarea
                            name="catatan"
                            rows="4"
                            class="form-control @error('catatan') is-invalid @enderror"
                            placeholder="Contoh: Layar retak, tombol power tidak berfungsi, dll."
                            required
                        >{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Denda Kerusakan (opsional)</label>
                            <input
                                type="number"
                                name="biaya_rusak"
                                value="{{ old('biaya_rusak') }}"
                                class="form-control @error('biaya_rusak') is-invalid @enderror"
                                min="0"
                                placeholder="Contoh: 50000"
                            >
                            @error('biaya_rusak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Isi jika kerusakan ditanggung peminjam.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Denda Kehilangan (opsional)</label>
                            <input
                                type="number"
                                name="biaya_hilang"
                                value="{{ old('biaya_hilang') }}"
                                class="form-control @error('biaya_hilang') is-invalid @enderror"
                                min="0"
                                placeholder="Contoh: 250000"
                            >
                            @error('biaya_hilang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Isi jika barang hilang total.</small>
                        </div>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary" type="submit">
                            Simpan & Selesaikan Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
