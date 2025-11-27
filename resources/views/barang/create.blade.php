@extends('layouts.app')

@section('content')
<style>
    /* === BACKGROUND HALAMAN (AREA KONTEN) === */
    .sipkam-barang-page {
        min-height: 100vh;
        padding: 1.5rem 1.5rem 2rem;
        background: linear-gradient(180deg, #DAF1DE 0%, #E0ECDE 45%, #CDE0C9 100%);
    }

    /* === KARTU FORM: FULL WIDTH + GRADIENT === */
    .sipkam-barang-form-card {
        width: 100%;
        border: 1px solid rgba(205, 224, 201, 0.9);
        border-radius: 18px;
        overflow: hidden;
        /* gradasi hijau lembut di dalam form */
        background: linear-gradient(145deg, #DAF1DE 0%, #E0ECDE 40%, #CDE0C9 100%);
        box-shadow: 0 18px 40px rgba(44, 105, 117, 0.30);
    }

    /* HEADER FORM (dark → light hijau) */
    .sipkam-barang-form-header {
        background: linear-gradient(90deg, #051F20 0%, #0B2B26 40%, #163832 100%);
        color: #ffffff;
    }

    /* body form transparan tipis supaya gradasi kelihatan */
    .sipkam-barang-form-body {
        background-color: rgba(255, 255, 255, 0.12);
    }

    /* footer form */
    .sipkam-barang-form-footer {
        background-color: rgba(255, 255, 255, 0.16);
        border-top: 1px solid rgba(205, 224, 201, 0.7);
    }

    /* label & text kecil */
    .sipkam-label {
        font-weight: 600;
        font-size: 0.8rem;
        color: #234e52;
    }

    .sipkam-help {
        font-size: 0.75rem;
    }

    /* input kecil biar rapi */
    .sipkam-input-sm {
        height: 2.3rem;
        font-size: 0.9rem;
    }

    .sipkam-input-group-sm .input-group-text {
        font-size: 0.85rem;
    }

    /* tombol utama pakai warna tema gelap */
    .btn-sipkam-primary {
        background: linear-gradient(135deg, #051F20, #0B2B26);
        border: none;
        color: #ffffff;
    }
    .btn-sipkam-primary:hover {
        filter: brightness(1.05);
    }

    /* tombol reset soft hijau */
    .btn-sipkam-soft {
        background-color: #E0ECDE;
        border: 1px solid #CDE0C9;
        color: #234e52;
    }
    .btn-sipkam-soft:hover {
        background-color: #CDE0C9;
    }

    @media (max-width: 768px) {
        .sipkam-barang-page {
            padding: 1rem;
        }
    }
</style>

<div class="container-fluid sipkam-barang-page">

    {{-- FORM TAMBAH BARANG (FULL WIDTH) --}}
    <form method="POST"
          action="{{ route('petugas.barang.store') }}"
          enctype="multipart/form-data"
          class="sipkam-barang-form-card border-0">

        @csrf

        {{-- HEADER GRADIENT --}}
        <div class="card-header sipkam-barang-form-header border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                     style="width:40px;height:40px;">
                    <i class="fas fa-box-open"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-semibold">Tambah Barang</h5>
                    <small class="opacity-75">
                        Lengkapi informasi barang inventaris kampus
                    </small>
                </div>
            </div>

            <a href="{{ route('barang.index') }}"
               class="btn btn-outline-light btn-sm d-flex align-items-center">
                <i class="bi bi-arrow-left-short me-1"></i> Kembali
            </a>
        </div>

        {{-- BODY FORM (GRADIENT SOFT) --}}
        <div class="card-body sipkam-barang-form-body p-4">
            <div class="row g-4">

                {{-- NAMA BARANG --}}
                <div class="col-md-6">
                    <label class="sipkam-label mb-1">Nama Barang</label>
                    <input type="text"
                           name="nama_barang"
                           value="{{ old('nama_barang') }}"
                           class="form-control sipkam-input-sm @error('nama_barang') is-invalid @enderror"
                           required>
                    @error('nama_barang')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KATEGORI --}}
                <div class="col-md-6">
                    <label class="sipkam-label mb-1">Kategori</label>
                    <select name="id_kategori"
                            class="form-select sipkam-input-sm @error('id_kategori') is-invalid @enderror"
                            required>
                        <option value="">-- Pilih --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->id_kategori }}"
                                @selected(old('id_kategori') == $item->id_kategori)>
                                {{ $item->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KODE BARANG (INFO SAJA) --}}
                <div class="col-12">
                    <label class="sipkam-label mb-1">Kode Barang</label>
                    <input type="text"
                           class="form-control sipkam-input-sm"
                           value="Akan dibuat otomatis saat disimpan"
                           disabled
                           readonly>
                    <small class="text-muted fst-italic sipkam-help">
                        Kode barang digenerate otomatis dengan format BRG-XXXX.
                    </small>
                </div>

                {{-- HARGA --}}
                <div class="col-md-6">
                    <label class="sipkam-label mb-1">Harga</label>
                    <div class="input-group input-group-sm sipkam-input-group-sm">
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
                    <label class="sipkam-label mb-1">Stok</label>
                    <input type="number"
                           name="stok"
                           value="{{ old('stok', 0) }}"
                           class="form-control sipkam-input-sm @error('stok') is-invalid @enderror"
                           min="0">
                    @error('stok')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- FOTO BARANG --}}
                <div class="col-md-6">
                    <label class="sipkam-label mb-1">Foto Barang</label>
                    <input type="file"
                           name="foto_barang"
                           class="form-control sipkam-input-sm @error('foto_barang') is-invalid @enderror"
                           accept="image/*">
                    <small class="text-muted sipkam-help">
                        Format JPG, PNG, atau WEBP maksimal 2MB.
                    </small>
                    @error('foto_barang')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- FOOTER FORM --}}
        <div class="card-footer sipkam-barang-form-footer d-flex justify-content-end gap-2 py-3 px-4">
            <button type="reset" class="btn btn-sipkam-soft btn-sm">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
            </button>
            <button class="btn btn-sipkam-primary btn-sm" type="submit">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
        </div>

    </form>
</div>
@endsection
