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
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <label style="font-size: 14px; font-weight: 600; color: #334155; margin: 0;">
                        <i class="ph-bold ph-video-camera"></i> 2. Arahkan Kamera (QR / Barcode)
                    </label>
                    <span id="scanIndicator" style="display: inline-flex; align-items: center; font-size: 12px; font-weight: 600; color: #059669; background: #dcfce7; padding: 4px 8px; border-radius: 12px;">
                        <span class="pulsing-dot" style="width: 8px; height: 8px; background: #059669; border-radius: 50%; display: inline-block; margin-right: 6px;"></span>
                        Kamera Aktif
                    </span>
                </div>
                
                <div style="position: relative; border-radius: 12px; overflow: hidden; border: 2px solid #3b82f6; background: black;">
                    <div id="reader" style="width: 100%; min-height: 250px;"></div>
                    <!-- CSS Laser Animation -->
                    <div id="laser-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; display: block;">
                        <div class="laser-line"></div>
                    </div>
                </div>
                <p style="color: #64748b; font-size: 12px; margin-top: 10px; text-align: center;">
                    💡 Seluruh area video aktif. Pastikan barcode terlihat jelas dan tidak silau.
                </p>
            </div>

            <!-- NIM RESULT -->
            <div class="form-group">
                <label style="display: flex; justify-content: space-between; align-items: center;">
                    <span>NIM Mahasiswa</span>
                    <span style="font-size: 11px; color: #b45309; background: #fef3c7; padding: 2px 8px; border-radius: 8px;">Bisa diketik manual</span>
                </label>
                <input type="text" name="nim" id="nim"
                    placeholder="Scan barcode, atau ketik NIM di sini..."
                    required class="form-control"
                    style="background: white; color: #0f172a; font-weight: 700; text-align: center; font-family: monospace; font-size: 18px; letter-spacing: 1px; border: 2px solid #cbd5e1;">
            </div>

            <button id="submitBtn" type="submit" class="btn btn-primary" style="width: 100%; font-weight: bold; background: #4f46e5; border-color: #4f46e5; padding: 12px; font-size: 16px;">
                <i class="ph-bold ph-check-circle"></i> Submit Kehadiran
            </button>
        </form>

    </div>

@endsection

@section('extra_js')
<style>
/* Animasi Laser Pemindai */
.laser-line {
    width: 100%;
    height: 3px;
    background: rgba(239, 68, 68, 0.9);
    box-shadow: 0 0 15px 3px rgba(239, 68, 68, 0.7);
    position: absolute;
    animation: scan 2.5s infinite ease-in-out;
}
@keyframes scan {
    0% { top: 5%; opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { top: 95%; opacity: 0; }
}
/* Animasi Dot Aktif */
.pulsing-dot {
    animation: pulse 1s infinite alternate;
}
@keyframes pulse {
    0% { opacity: 0.3; }
    100% { opacity: 1; }
}
</style>
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

        // Ubah UI untuk indikasi sukses
        const nimField = document.getElementById('nim');
        nimField.value = decodedText.trim();
        nimField.style.background = '#ecfdf5';
        nimField.style.color = '#059669';
        nimField.style.borderColor = '#059669';

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="ph-bold ph-spinner ph-spin"></i> Menyimpan...';
        submitBtn.style.background = '#059669';
        submitBtn.style.borderColor = '#059669';

        document.getElementById('laser-overlay').style.display = 'none';
        
        const indicator = document.getElementById('scanIndicator');
        indicator.innerHTML = '✅ Berhasil!';
        indicator.style.background = '#ecfdf5';
        indicator.style.color = '#059669';

        // Hentikan scanner dan submit form
        if (html5QrcodeScanner) {
            html5QrcodeScanner.pause(true); 
            document.getElementById('scanForm').submit();
        } else {
            document.getElementById('scanForm').submit();
        }
    }

    // INISIALISASI KAMERA
    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            useBarCodeDetectorIfSupported: true, 
            rememberLastUsedCamera: true,
            formatsToSupport: [ 0, 3, 5 ], // HANYA QR (0), Code 39 (3), Code 128 (5). Menghemat 80% beban CPU!
            videoConstraints: {
                facingMode: "environment" // Hapus resolusi paksa (1280x720) agar webcam murah tidak pecah/blur akibat digital upscaling
            }
        },
        /* verbose= */ false
    );

    html5QrcodeScanner.render(
        (decodedText) => processResult(decodedText),
        () => {} // Abaikan error per frame
    );
});
</script>
@endsection