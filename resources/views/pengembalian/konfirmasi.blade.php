@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Konfirmasi Pengembalian</h1>
                <small class="text-muted">
                    Peminjaman #{{ $peminjaman->id_peminjaman }} &mdash;
                    {{ $peminjaman->barang->nama_barang ?? '-' }}
                </small>
            </div>
            <a href="{{ route('petugas.pengembalian.scan') }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Detail Peminjaman</h5>
                <p class="mb-1"><strong>Barang:</strong> {{ $peminjaman->barang->nama_barang ?? '-' }}</p>
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
                <h5 class="card-title mb-3">
                    Apakah barang berfungsi dengan baik dan tidak ada kerusakan?
                </h5>

                <p class="text-muted">
                    Jika barang berfungsi dengan baik dan tidak ada kerusakan, pengembalian
                    akan diproses tanpa denda kerusakan (hanya denda keterlambatan jika ada).
                    Jika ada kerusakan, silakan isi catatan dan denda pada langkah berikutnya.
                </p>

                <div class="d-flex flex-column flex-md-row gap-2 mt-3">
                    {{-- Barang baik --}}
                    <form
                        method="POST"
                        action="{{ route('petugas.pengembalian.tanpaKerusakan', $peminjaman->id_peminjaman) }}"
                    >
                        @csrf
                        <button type="submit" class="btn btn-success">
                            Ya, barang berfungsi baik
                        </button>
                    </form>

                    {{-- Ada kerusakan --}}
                    <a
                        href="{{ route('petugas.pengembalian.formKerusakan', $peminjaman->id_peminjaman) }}"
                        class="btn btn-outline-danger"
                    >
                        Tidak, ada kerusakan / gangguan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
