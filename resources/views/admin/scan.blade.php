@extends('layouts.admin')

@section('title', 'Scan Absensi')

@section('content')

    <div class="header-section">
        <h2>Scan Absensi Mahasiswa</h2>
        <p>Arahkan kamera ke Barcode/QR Code atau Upload Foto KTM.</p>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto;">

        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 16px; border-radius: 12px; border: 1px solid #a7f3d0; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-check-circle" style="font-size: 20px;"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-warning-circle" style="font-size: 20px;"></i> {{ session('error') }}
            </div>
        @endif

        <form id="scanForm" action="{{ url('/admin/scan') }}" method="POST">
            @csrf

            <!-- STEP 1: PILIH EVENT -->
            <div class="form-group" style="background: #f8fafc; border: 2px solid #cbd5e1; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 15px; font-weight: 700; color: #0f172a; display: block; margin-bottom: 10px;">
                    <i class="ph-bold ph-calendar-check" style="color: #4f46e5;"></i> 1. Pilih Event Aktif (Wajib)
                </label>
                <select name="event_id" id="event_id" class="form-control" required
                    style="cursor: pointer; font-size: 15px; padding: 12px 16px; border-radius: 12px; background: white; border: 1px solid #94a3b8; width: 100%; font-weight: 600; color: #334155;">
                    <option value="">-- Silakan Pilih Event Terlebih Dahulu --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->nama_event }}</option>
                    @endforeach
                </select>
            </div>

            <!-- STEP 2: KAMERA SCANNER (FULL FRAME) -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 12px; display: block;">
                    <i class="ph-bold ph-video-camera"></i> 2. Arahkan Kamera (QR / Barcode)
                </label>
                
                <div id="reader" style="width: 100%; min-height: 250px; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1;"></div>
                <p style="color: #64748b; font-size: 12px; margin-top: 10px; text-align: center;">
                    💡 Seluruh area video aktif. Pastikan barcode terlihat jelas dan tidak silau.
                </p>
            </div>

            <!-- STEP 3: UPLOAD FOTO (FALLBACK) -->
            <div style="background: #fffbeb; border: 1px dashed #fcd34d; border-radius: 16px; padding: 20px; margin-bottom: 24px; text-align: center;">
                <label style="font-size: 14px; font-weight: 600; color: #b45309; margin-bottom: 12px; display: block;">
                    <i class="ph-bold ph-image"></i> Kamera Sulit Fokus? Upload Foto Saja
                </label>
                <input type="file" id="uploadFile" accept="image/*" class="form-control" style="padding: 10px; background: white;">
                <div id="uploadStatus" style="margin-top: 10px; font-weight: 600; font-size: 13px;"></div>
                <div id="hidden-reader" style="display: none;"></div>
            </div>

            <!-- NIM RESULT -->
            <div class="form-group">
                <label>NIM Mahasiswa (terisi otomatis)</label>
                <input type="text" name="nim" id="nim"
                    placeholder="[ Menunggu hasil scan... ]"
                    required readonly class="form-control"
                    style="background: #f1f5f9; color: #64748b; font-weight: 700; text-align: center; font-family: monospace; font-size: 18px; letter-spacing: 1px;">
            </div>

            <button type="button" class="btn btn-secondary" disabled style="width: 100%; opacity: 0.6;">
                <i class="ph-bold ph-lock-key"></i> Otomatis submit setelah berhasil
            </button>
        </form>

    </div>

@endsection

@section('extra_js')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    function processResult(decodedText) {
        const eventSelect = document.getElementById('event_id');
        if (!eventSelect.value) {
            alert("⚠️ Pilih Event Aktif terlebih dahulu sebelum scan!");
            eventSelect.focus();
            return;
        }

        const nimField = document.getElementById('nim');
        nimField.value = decodedText.trim();
        nimField.style.background = '#ecfdf5';
        nimField.style.color = '#059669';

        // Hentikan scanner dan submit
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                document.getElementById('scanForm').submit();
            });
        } else {
            document.getElementById('scanForm').submit();
        }
    }

    // INISIALISASI KAMERA (FULL FRAME, BEBAS FORMAT)
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            useBarCodeDetectorIfSupported: false, // Wajib false untuk HTTP
            rememberLastUsedCamera: true,
            // HAPUS qrbox: Kamera men-scan seluruh layar secara brutal (Full-Frame)
            // HAPUS formatsToSupport: Bebas membaca semua format barcode di dunia
            videoConstraints: {
                facingMode: "environment" // Prioritaskan kamera belakang di HP
            }
        },
        /* verbose= */ false
    );

    html5QrcodeScanner.render(
        (decodedText) => processResult(decodedText),
        () => {} // Abaikan error frame (sangat penting agar tidak macet)
    );

    // FITUR UPLOAD FOTO
    const uploadInput = document.getElementById('uploadFile');
    const statusDiv = document.getElementById('uploadStatus');

    uploadInput.addEventListener('change', (e) => {
        if (e.target.files.length === 0) return;
        const file = e.target.files[0];

        statusDiv.innerHTML = '<span style="color: #b45309;">⏳ Sedang membaca barcode dari gambar...</span>';

        const fileScanner = new Html5Qrcode("hidden-reader");
        fileScanner.scanFile(file, false)
            .then(decodedText => {
                statusDiv.innerHTML = '<span style="color: #059669;">✅ Berhasil! NIM: ' + decodedText + '</span>';
                setTimeout(() => processResult(decodedText), 500);
            })
            .catch(err => {
                statusDiv.innerHTML = '<span style="color: #dc2626;">❌ Barcode/QR tidak terdeteksi di foto ini.</span>';
                uploadInput.value = '';
            })
            .finally(() => {
                fileScanner.clear().catch(()=>{});
            });
    });
});
</script>
@endsection