@extends('layouts.app')

@section('content')
@php
    $user = $user ?? auth()->user();
@endphp

<style>
    :root {
        --profil-dark-1: #051F20;
        --profil-dark-2: #0B2B26;
        --profil-dark-3: #163832;
        --profil-mid:    #253547;
        --profil-soft:   #8EB69B;
        --profil-light:  #DAF1DE;
    }

    /* ============================
       WRAPPER BACKGROUND GRADIENT
       ============================ */
    .editprofil-wrapper {
        margin: -24px -32px -24px -32px;
        padding: 32px 32px 48px;
        min-height: calc(100vh - 64px);
        background: linear-gradient(
            180deg,
            var(--profil-dark-1) 0%,
            var(--profil-dark-3) 30%,
            var(--profil-soft)   70%,
            var(--profil-light)  100%
        );
        display: flex;
        justify-content: center;
        align-items: flex-start;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .editprofil-inner {
        width: 100%;
        max-width: 950px;
    }

    @media (max-width: 767.98px) {
        .editprofil-wrapper {
            margin: -16px -16px -16px -16px;
            padding: 20px 16px 32px;
        }
        .editprofil-inner {
            max-width: 100%;
        }
    }

    /* ============================
       CARD & HEADING
       ============================ */
    .edit-card {
        border-radius: 20px;
        border: none;
        background: rgba(250, 253, 252, 0.98);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.55);
    }

    .edit-card h5 {
        color: var(--profil-dark-2);
        letter-spacing: 0.03em;
    }

    .edit-card small.text-muted {
        color: #64748b !important;
    }

    .alert-success {
        border-radius: 12px;
        border: none;
        background: rgba(134, 239, 172, 0.12);
        color: #166534;
    }

    /* ============================
       FORM CONTROL MODERN
       ============================ */
    .form-label-modern {
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6b7280;
    }

    .form-control-modern {
        border-radius: 14px;
        border-color: #d1e3d8;
        background-color: #f5fbf7;
        font-size: 0.92rem;
        padding: 10px 14px;
        box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.15);
    }

    .form-control-modern:focus {
        background-color: #ffffff;
        border-color: var(--profil-soft);
        box-shadow:
            0 0 0 1px rgba(142, 182, 155, 0.45),
            0 0 0 4px rgba(142, 182, 155, 0.18);
    }

    .form-text {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* ============================
       BUTTON MODERN
       ============================ */
    .btn-modern.btn-modern-primary {
        background: linear-gradient(135deg, var(--profil-soft), var(--profil-light));
        border: 1px solid rgba(142, 182, 155, 0.9);
        color: var(--profil-dark-1);
        font-weight: 600;
        border-radius: 999px;
        padding-inline: 20px;
    }

    .btn-modern.btn-modern-primary:hover {
        filter: brightness(1.03);
    }

    .btn-outline-secondary {
        border-radius: 999px;
    }
</style>

<div class="editprofil-wrapper">
    <div class="editprofil-inner">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 edit-card">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-1">Edit Profil</h5>
                        <small class="text-muted">Perbarui nama dan nomor telepon. Email tidak dapat diubah.</small>

                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success mt-3">Profil berhasil diperbarui.</div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" class="mt-3">
                            @csrf
                            @method('patch')

                            <div class="mb-3">
                                <label class="form-label form-label-modern" for="name">Nama</label>
                                <input
                                    type="text"
                                    class="form-control form-control-modern @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label form-label-modern" for="email">Email</label>
                                <input type="email" class="form-control form-control-modern" id="email" value="{{ $user->email }}" disabled>
                                <div class="form-text">Email terverifikasi dan tidak dapat diubah.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label form-label-modern" for="phone">Nomor Telepon</label>
                                <input
                                    type="tel"
                                    class="form-control form-control-modern @error('phone') is-invalid @enderror"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $user->phone ?? $user->nomor_hp) }}"
                                    required
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-modern btn-modern-primary">Simpan Perubahan</button>
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary ms-2">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 edit-card">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-1">Edit Password</h5>
                        <small class="text-muted">Gunakan password minimal 8 karakter</small>

                        @if (session('status') === 'password-updated')
                            <div class="alert alert-success mt-3">Password berhasil diperbarui.</div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}" class="mt-3">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label class="form-label form-label-modern" for="current_password">Password Lama</label>
                                <input
                                    type="password"
                                    class="form-control form-control-modern @error('current_password', 'updatePassword') is-invalid @enderror"
                                    id="current_password"
                                    name="current_password"
                                    autocomplete="current-password"
                                >
                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label form-label-modern" for="password">Password Baru</label>
                                <input
                                    type="password"
                                    class="form-control form-control-modern @error('password', 'updatePassword') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    autocomplete="new-password"
                                >
                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label form-label-modern" for="password_confirmation">Konfirmasi Password Baru</label>
                                <input
                                    type="password"
                                    class="form-control form-control-modern"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                >
                            </div>

                            <button type="submit" class="btn btn-modern btn-modern-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
