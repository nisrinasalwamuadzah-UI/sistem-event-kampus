@extends('layouts.admin')

@section('title', 'Scan Absensi')

@section('content')

    <div class="header-section">
        <h2>Scan Absensi Mahasiswa</h2>
        <p>Arahkan kamera ke Barcode/QR Code KTM atau upload *screenshot*.</p>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="width: 64px; height: 64px; background: #eef2ff; color: #4f46e5; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 32px; margin-bottom: 16px;">
                <i class="ph-bold ph-qr-code"></i>
            </div>
            <h3 style="font-size: 18px; color: #0f172a; font-weight: 600;">Scanner Kehadiran</h3>
            <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Pastikan pencahayaan cukup agar kamera dapat mendeteksi dengan cepat.</p>
        </div>

        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 16px; border-radius: 12px; border: 1px solid #a7f3d0; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-check-circle" style="font-size: 20px;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <i class="ph-bold ph-warning-circle" style="font-size: 20px;"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 16px; border-radius: 12px; border: 1px solid #fecaca; margin-bottom: 24px; font-size: 14px;">
                <ul style="margin-left: 15px; margin-bottom: 0;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- MODE TOGGLE -->
        <div style="display: flex; gap: 8px; margin-bottom: 16px;">
            <button type="button" id="btnModeQR" onclick="setMode('qr')" class="btn btn-primary" style="flex: 1; padding: 12px; border-radius: 12px;">
                <i class="ph-bold ph-qr-code"></i> Mode QR Code
            </button>
            <button type="button" id="btnModeBarcode" onclick="setMode('barcode')" class="btn btn-secondary" style="flex: 1; padding: 12px; border-radius: 12px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">
                <i class="ph-bold ph-barcode"></i> Mode Barcode
            </button>
        </div>

        <!-- QR SCANNER -->
        <div id="reader" style="width: 100%; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 16px;"></div>

        <!-- HIDDEN DIV FOR FILE SCANNING -->
        <div id="hidden-reader" style="position: absolute; top: -9999px; left: -9999px; visibility: hidden;"></div>

        <!-- UPLOAD GAMBAR MANUAL -->
        <div style="background:#f8fafc; padding:20px; border-radius:12px; border:2px dashed #cbd5e1; margin-bottom:24px; text-align: center;">
            <label style="display: block; margin-bottom:12px; font-weight: 500; color: #334155;">Atau Upload Screenshot Barcode/QR</label>
            <input type="file" id="qr-input-file" accept="image/*" style="border:none; padding:0; background:transparent; max-width: 100%;">
            <div id="upload-status" style="color: #ef4444; font-size: 13px; margin-top: 10px; display: none;"></div>
        </div>

        <!-- FORM -->
        <form id="scanForm" action="{{ url('/admin/scan') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Pilih Event Aktif</label>
                <select name="event_id" class="form-control" required style="cursor: pointer;">
                    <option value="">-- Silakan Pilih Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->nama_event }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>NIM Mahasiswa</label>
                <input 
                    type="text"
                    name="nim"
                    id="nim"
                    placeholder="[ Menunggu hasil scan... ]"
                    required
                    readonly
                    class="form-control"
                    style="background: #f1f5f9; color: #64748b; font-weight: 600; text-align: center; font-family: monospace; font-size: 16px;"
                >
            </div>

            <button type="button" class="btn btn-secondary" disabled style="width: 100%; opacity: 0.7;">
                <i class="ph-bold ph-lock-key"></i> Otomatis submit setelah scan
            </button>
        </form>

    </div>
@endsection

@section('extra_js')
<!-- QR CODE SCRIPT -->
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrcodeScanner = null;
let currentMode = 'qr';

function onScanSuccess(decodedText, decodedResult) {
    if (html5QrcodeScanner && html5QrcodeScanner.getState() === Html5QrcodeScannerState.SCANNING) {
        html5QrcodeScanner.pause();
    }
    document.getElementById('nim').value = decodedText.trim();
    document.getElementById('scanForm').submit();
}

function initScanner(mode) {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear().then(() => {
            startScanner(mode);
        }).catch(err => console.error(err));
    } else {
        startScanner(mode);
    }
}

function startScanner(mode) {
    let formats = [];
    if (mode === 'qr') {
        formats = [0]; // 0 = QR_CODE in Html5QrcodeSupportedFormats enum
    } else if (mode === 'barcode') {
        formats = [3, 5, 9]; // CODE_39, CODE_128, EAN_13
    }

    html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 30,
            useBarCodeDetectorIfSupported: true,
            formatsToSupport: formats
        }
    );
    html5QrcodeScanner.render(onScanSuccess);
}

function setMode(mode) {
    currentMode = mode;
    
    let btnQR = document.getElementById('btnModeQR');
    let btnBarcode = document.getElementById('btnModeBarcode');
    
    if (mode === 'qr') {
        btnQR.className = 'btn btn-primary';
        btnQR.style.background = '#4f46e5';
        btnQR.style.color = '#fff';
        btnQR.style.border = '1px solid transparent';
        
        btnBarcode.className = 'btn btn-secondary';
        btnBarcode.style.background = '#f1f5f9';
        btnBarcode.style.color = '#64748b';
        btnBarcode.style.border = '1px solid #e2e8f0';
    } else {
        btnBarcode.className = 'btn btn-primary';
        btnBarcode.style.background = '#4f46e5';
        btnBarcode.style.color = '#fff';
        btnBarcode.style.border = '1px solid transparent';
        
        btnQR.className = 'btn btn-secondary';
        btnQR.style.background = '#f1f5f9';
        btnQR.style.color = '#64748b';
        btnQR.style.border = '1px solid #e2e8f0';
    }

    initScanner(mode);
}

// Init default mode on load
setMode('qr');

// KONTROL CAHAYA DIHAPUS SESUAI PERMINTAAN KARENA MENGGANGGU FOKUS KAMERA

// LOGIKA UPLOAD GAMBAR
document.getElementById('qr-input-file').addEventListener('change', function(e) {
    if (e.target.files.length === 0) return;
    
    const imageFile = e.target.files[0];
    const statusDiv = document.getElementById('upload-status');
    statusDiv.style.display = 'none';

    const fileScanner = new Html5Qrcode("hidden-reader");
    fileScanner.scanFile(imageFile, true)
        .then(decodedText => {
            onScanSuccess(decodedText, null);
        })
        .catch(err => {
            statusDiv.style.display = 'block';
            statusDiv.innerHTML = '<i class="ph-bold ph-warning-circle"></i> Barcode/QR tidak terdeteksi pada gambar ini.';
            e.target.value = '';
        });
});
</script>
@endsection