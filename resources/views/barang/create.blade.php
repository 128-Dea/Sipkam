@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color:#f3f4f6;">

<<<<<<< Updated upstream
<form method="POST" action="{{ route('petugas.barang.store') }}" class="card border-0 shadow-sm" enctype="multipart/form-data">
    @csrf
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="form-control" required>
            @error('nama_barang')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
                <option value="">-- Pilih --</option>
                @foreach($kategori as $item)
                    <option value="{{ $item->id_kategori }}" @selected(old('id_kategori')==$item->id_kategori)>{{ $item->nama_kategori }}</option>
                @endforeach
            </select>
            @error('id_kategori')<small class="text-danger">{{ $message }}</small>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Kode Barang</label>
            <input type="text" class="form-control" value="Akan dibuat otomatis saat disimpan" disabled readonly>
            <small class="text-muted d-block mt-1">Kode barang digenerate otomatis dengan format BRG-XXXX.</small>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" value="{{ old('harga') }}" class="form-control" min="0" step="1000">
                @error('harga')<small class="text-danger">{{ $message }}</small>@enderror
=======
    {{-- FORM FULL WIDTH --}}
    <form method="POST"
          action="{{ route('barang.store') }}"
          class="card border-0 shadow-sm rounded-4 overflow-hidden w-100"
          enctype="multipart/form-data">
        @csrf

        {{-- HEADER GRADIENT --}}
        <div class="card-header border-0 py-3 px-4 d-flex justify-content-between align-items-center"
             style="background: linear-gradient(90deg,#4F46E5,#22C55E,#FACC15);">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;">
                    {{-- bisa isi icon kalau pakai Bootstrap Icons --}}
                    {{-- <i class="bi bi-box-seam fs-5 text-primary"></i> --}}
                </div>
                <div class="text-white">
                    <h5 class="mb-0 fw-semibold">Tambah Barang</h5>
                    <small class="opacity-75">
                        Lengkapi informasi barang inventaris kampus
                    </small>
                </div>
>>>>>>> Stashed changes
            </div>

            <a href="{{ route('barang.index') }}"
               class="btn btn-outline-light btn-sm d-flex align-items-center">
                <i class="bi bi-arrow-left-short me-1"></i> Kembali
            </a>
        </div>

        {{-- BODY FORM --}}
        <div class="card-body p-4" style="background-color:#f5f7fb;">
            <div class="row g-4">

                {{-- NAMA BARANG (tanpa placeholder contoh laptop) --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Nama Barang</label>
                    <input type="text"
                           name="nama_barang"
                           value="{{ old('nama_barang') }}"
                           class="form-control form-control-sm @error('nama_barang') is-invalid @enderror"
                           required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KATEGORI --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Kategori</label>
                    <select name="id_kategori"
                            class="form-select form-select-sm @error('id_kategori') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id_kategori }}"
                                @selected(old('id_kategori')==$item->id_kategori)>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KODE BARANG --}}
                <div class="col-12">
                    <label class="form-label fw-semibold small text-muted">Kode Barang</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           value="Akan dibuat otomatis saat disimpan"
                           disabled
                           readonly>
                    <small class="text-muted fst-italic">
                        Kode barang digenerate otomatis dengan format BRG-XXXX.
                    </small>
                </div>

                {{-- HARGA --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Harga</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number"
                               name="harga"
                               value="{{ old('harga') }}"
                               class="form-control @error('harga') is-invalid @enderror"
                               min="0"
                               step="1000">
                    </div>
                    @error('harga')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- STOK --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Stok</label>
                    <input type="number"
                           name="stok"
                           value="{{ old('stok', 0) }}"
                           class="form-control form-control-sm @error('stok') is-invalid @enderror"
                           min="0">
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FOTO BARANG --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold small text-muted">Foto Barang</label>
                    <input type="file"
                           name="foto_barang"
                           class="form-control form-control-sm @error('foto_barang') is-invalid @enderror"
                           accept="image/*">
                    <small class="text-muted">
                        Format JPG, PNG, atau WEBP maksimal 2MB.
                    </small>
                    @error('foto_barang')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- FOOTER --}}
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-light border">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </button>
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
        </div>

    </form>
</div>
@endsection
