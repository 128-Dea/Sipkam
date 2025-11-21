@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Scan QR Pengembalian</h1>
            <a href="{{ route('petugas.pengembalian.index') }}" class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted">
                    Gunakan scanner fisik atau kamera untuk membaca QR Code.
                    Kode hasil scan akan masuk otomatis ke kolom di bawah.
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ================= FORM =================== -->
                <form id="qrForm" method="POST" action="{{ route('petugas.pengembalian.handleScan') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Kode QR</label>
                        <input
                            type="text"
                            name="qr_code"
                            id="qr_code"
                            class="form-control @error('qr_code') is-invalid @enderror"
                            placeholder="Contoh: PINJ-1-ABCDEF"
                            value="{{ old('qr_code') }}"
                            required
                        >
                        @error('qr_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- ========== SCANNER QR CAMERA (FULL) ========== -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Scan QR via Kamera</label>

                        <!-- PILIH KAMERA -->
                        <div class="mb-3">
                            <label class="form-label">Pilih Kamera</label>
                            <select id="camera-select" class="form-select">
                                <option value="" disabled selected>Sedang memuat kamera...</option>
                            </select>
                        </div>

                        <!-- AREA SCANNER -->
                        <div id="qr-reader" class="border rounded p-2" style="display:none;"></div>
                    </div>
                    <!-- ========== END SCANNER ========== -->


                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Proses QR
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ======================= LIBRARY TAMBAHAN ======================= -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
    let scanner = null;
    let isCameraActive = false;

    // =======================================================
    // FUNGSI SAAT QR BERHASIL TERBACA
    // =======================================================
    function onScanSuccess(decodedText) {

        // Masukkan ke input
        document.getElementById("qr_code").value = decodedText;

        // Popup sukses
        Swal.fire({
            icon: 'success',
            title: 'QR Berhasil Dibaca!',
            text: decodedText,
            timer: 1500,
            showConfirmButton: false
        });

        // Matikan kamera setelah scan
        if (scanner) {
            scanner.stop();
            isCameraActive = false;
            document.getElementById("qr-reader").style.display = "none";
        }

        // Auto submit
        setTimeout(() => {
            document.getElementById("qrForm").submit();
        }, 1200);
    }

    // =======================================================
    // LOAD LIST KAMERA
    // =======================================================
    Html5Qrcode.getCameras().then(devices => {
        const select = document.getElementById("camera-select");

        select.innerHTML = ""; // kosongkan

        devices.forEach((cam, i) => {
            let option = document.createElement("option");
            option.value = cam.id;
            option.textContent = cam.label || `Kamera ${i + 1}`;
            select.appendChild(option);
        });

        // Auto start kamera pertama
        if (devices.length > 0) {
            startScanner(devices[0].id);
        }

        // Bila user ganti kamera
        select.addEventListener("change", function () {
            startScanner(this.value);
        });

    }).catch(err => {
        alert("Gagal memuat kamera: " + err);
    });


    // =======================================================
    // FUNGSI MULAI KAMERA
    // =======================================================
    function startScanner(cameraId) {
        if (isCameraActive && scanner) {
            scanner.stop();
        }

        document.getElementById("qr-reader").style.display = "block";

        scanner = new Html5Qrcode("qr-reader");

        scanner.start(
            cameraId,
            { fps: 10, qrbox: 250 },
            onScanSuccess
        ).then(() => {
            isCameraActive = true;
        }).catch(err => {
            console.error("Gagal menghidupkan kamera:", err);
        });
    }
</script>

@endsection
