@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">Form Peminjaman Barang</h1>

<form method="POST" action="{{ route('mahasiswa.peminjaman.store') }}" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Pilih Barang</label>
            <select name="id_barang" class="form-select @error('id_barang') is-invalid @enderror" required>
                <option value="">-- Pilih Barang --</option>
                @foreach($barang as $item)
                    <option value="{{ $item->id_barang }}" @selected(old('id_barang') == $item->id_barang)>
                        {{ $item->nama_barang }} (Stok: {{ $item->stok ?? '-' }} | Status: {{ ucfirst($item->status) }})
                    </option>
                @endforeach
            </select>
            @error('id_barang')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Waktu Mulai</label>
                <input
                    type="datetime-local"
                    name="waktu_awal"
                    value="{{ old('waktu_awal') }}"
                    class="form-control @error('waktu_awal') is-invalid @enderror"
                    required
                >
                @error('waktu_awal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Waktu Selesai</label>
                <input
                    type="datetime-local"
                    name="waktu_akhir"
                    value="{{ old('waktu_akhir') }}"
                    class="form-control @error('waktu_akhir') is-invalid @enderror"
                    required
                >
                @error('waktu_akhir')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Alasan Peminjaman</label>
            <textarea
                name="alasan"
                rows="3"
                class="form-control @error('alasan') is-invalid @enderror"
                placeholder="Tuliskan kebutuhan peminjaman"
            >{{ old('alasan') }}</textarea>
            @error('alasan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="card-footer text-end bg-white">
        <a href="{{ route('mahasiswa.peminjaman.index') }}" class="btn btn-light">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan &amp; Generate QR</button>
    </div>
</form>
@endsection
