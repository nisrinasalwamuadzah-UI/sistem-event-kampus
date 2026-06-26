@extends('layouts.admin')

@section('title', 'Scan Absensi')

@section('content')

    <div class="header-section">
        <h2>Scan Absensi Mahasiswa</h2>
        <p>Upload foto KTM atau arahkan kamera langsung ke Barcode/QR Code.</p>
    </div>

    <div class="form-container" style="max-width: 640px; margin: 0 auto;">

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

        <form id="scanForm" action="{{ url('/admin/scan') }}" method="POST">
            @csrf

            <!-- STEP 1: PILIH EVENT -->
            <div class="form-group" style="background: #f8fafc; border: 2px solid #cbd5e1; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 16px; font-weight: 700; color: #0f172a; display: block; margin-bottom: 10px;">
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

            <!-- STEP 2: TAB SELECTOR -->
            <div style="display: flex; gap: 8px; margin-bottom: 0;">
                <button type="button" id="tabUpload" onclick="switchTab('upload')"
                    style="flex: 1; padding: 12px 16px; border-radius: 12px 12px 0 0; font-size: 14px; font-weight: 700; border: 2px solid #4f46e5; background: #4f46e5; color: white; cursor: pointer;">
                    <i class="ph-bold ph-camera"></i> 2a. Foto dari HP (Direkomendasikan)
                </button>
                <button type="button" id="tabCamera" onclick="switchTab('camera')"
                    style="flex: 1; padding: 12px 16px; border-radius: 12px 12px 0 0; font-size: 14px; font-weight: 700; border: 2px solid #e2e8f0; background: #f8fafc; color: #64748b; cursor: pointer;">
                    <i class="ph-bold ph-video-camera"></i> 2b. Kamera Langsung
                </button>
            </div>

            <!-- PANEL UPLOAD (DEFAULT/AKTIF) -->
            <div id="panelUpload" style="background: #f8fafc; border: 2px solid #4f46e5; border-top: none; border-radius: 0 0 16px 16px; padding: 24px; margin-bottom: 24px;">
                <p style="color: #334155; font-size: 14px; margin-bottom: 16px; line-height: 1.6;">
                    <strong>Cara terbaik:</strong> Foto Barcode/QR Code KTM Anda menggunakan kamera HP, lalu pilih fotonya di bawah. Sistem akan membaca NIM dari foto tersebut secara otomatis.
                </p>

                <!-- DROPZONE UPLOAD -->
                <label for="uploadFile" id="dropzone"
                    style="display: block; border: 2px dashed #a5b4fc; border-radius: 12px; padding: 32px 16px; text-align: center; cursor: pointer; background: white; transition: all 0.2s;">
                    <i class="ph-bold ph-image-square" style="font-size: 40px; color: #a5b4fc; display: block; margin-bottom: 8px;"></i>
                    <span style="font-weight: 600; color: #4f46e5; font-size: 15px;">Klik di sini untuk memilih foto KTM</span>
                    <p style="color: #94a3b8; font-size: 13px; margin: 4px 0 0;">Atau drag & drop gambar ke sini. Mendukung JPG, PNG, HEIC</p>
                    <input type="file" id="uploadFile" accept="image/*" style="display: none;">
                </label>

                <!-- PREVIEW & RESULT -->
                <div id="uploadPreviewArea" style="display: none; margin-top: 16px;">
                    <img id="uploadPreviewImg" src="" alt="Preview" style="max-width: 100%; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 12px;">
                    <div id="uploadStatus" style="padding: 12px 16px; border-radius: 8px; font-weight: 600; font-size: 14px; text-align: center;"></div>
                </div>

                <!-- HIDDEN READER for file scanning -->
                <div id="hidden-reader" style="position: absolute; top: -9999px; left: -9999px; visibility: hidden; width: 1px; height: 1px;"></div>
            </div>

            <!-- PANEL KAMERA (TERSEMBUNYI) -->
            <div id="panelCamera" style="display: none; background: #f8fafc; border: 2px solid #e2e8f0; border-top: none; border-radius: 0 0 16px 16px; padding: 20px; margin-bottom: 24px;">
                <p style="color: #64748b; font-size: 13px; margin-bottom: 16px;">
                    Arahkan kamera langsung ke Barcode/QR Code. Pastikan barcode berada di tengah kotak fokus dan terlihat tajam.
                </p>
                <div id="reader" style="width: 100%; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #cbd5e1;"></div>
                <p id="camera-tip" style="color: #64748b; font-size: 12px; margin-top: 8px; text-align: center;">
                    💡 Tips: Jaga jarak 15-25cm dan pastikan cahaya cukup.
                </p>
            </div>

            <!-- NIM RESULT -->
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
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    let html5QrcodeScanner = null;
    let scannerStarted = false;

    // ==========================================
    // TAB SWITCHING
    // ==========================================
    window.switchTab = function(tab) {
        const uploadPanel = document.getElementById('panelUpload');
        const cameraPanel = document.getElementById('panelCamera');
        const tabUploadBtn = document.getElementById('tabUpload');
        const tabCameraBtn = document.getElementById('tabCamera');

        if (tab === 'upload') {
            uploadPanel.style.display = 'block';
            uploadPanel.style.borderColor = '#4f46e5';
            cameraPanel.style.display = 'none';
            tabUploadBtn.style.background = '#4f46e5';
            tabUploadBtn.style.color = 'white';
            tabUploadBtn.style.borderColor = '#4f46e5';
            tabCameraBtn.style.background = '#f8fafc';
            tabCameraBtn.style.color = '#64748b';
            tabCameraBtn.style.borderColor = '#e2e8f0';
            // Stop camera if running
            stopCamera();
        } else {
            uploadPanel.style.display = 'none';
            cameraPanel.style.display = 'block';
            cameraPanel.style.borderColor = '#4f46e5';
            tabCameraBtn.style.background = '#4f46e5';
            tabCameraBtn.style.color = 'white';
            tabCameraBtn.style.borderColor = '#4f46e5';
            tabUploadBtn.style.background = '#f8fafc';
            tabUploadBtn.style.color = '#64748b';
            tabUploadBtn.style.borderColor = '#e2e8f0';
            // Start camera
            startCamera();
        }
    };

    // ==========================================
    // SUBMIT RESULT (SHARED)
    // ==========================================
    function processResult(decodedText) {
        const eventSelect = document.getElementById('event_id');
        if (!eventSelect.value) {
            alert("⚠️ Silakan Pilih Event Aktif terlebih dahulu!");
            eventSelect.focus();
            return;
        }

        const nimField = document.getElementById('nim');
        nimField.value = decodedText.trim();
        nimField.style.background = '#ecfdf5';
        nimField.style.color = '#059669';

        stopCamera();
        document.getElementById('scanForm').submit();
    }

    // ==========================================
    // ALUR 1: FILE UPLOAD (RELIABLE)
    // ==========================================
    const uploadInput = document.getElementById('uploadFile');
    const dropzone = document.getElementById('dropzone');

    uploadInput.addEventListener('change', function(e) {
        if (e.target.files.length === 0) return;
        handleFile(e.target.files[0]);
    });

    // Drag & Drop support
    dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.style.borderColor = '#4f46e5'; dropzone.style.background = '#eef2ff'; });
    dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = '#a5b4fc'; dropzone.style.background = 'white'; });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = '#a5b4fc';
        dropzone.style.background = 'white';
        if (e.dataTransfer.files.length > 0) handleFile(e.dataTransfer.files[0]);
    });

    function handleFile(file) {
        const previewArea = document.getElementById('uploadPreviewArea');
        const previewImg = document.getElementById('uploadPreviewImg');
        const statusDiv = document.getElementById('uploadStatus');

        // Show preview
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewArea.style.display = 'block';
        };
        reader.readAsDataURL(file);

        // Show loading
        statusDiv.innerHTML = '⏳ Sedang membaca barcode dari gambar...';
        statusDiv.style.background = '#fefce8';
        statusDiv.style.color = '#a16207';

        // Scan the file with ZXing
        const fileScanner = new Html5Qrcode("hidden-reader");
        fileScanner.scanFile(file, /* showImage= */ false)
            .then(decodedText => {
                statusDiv.innerHTML = '✅ Berhasil! NIM: <strong>' + decodedText + '</strong> — Menyimpan kehadiran...';
                statusDiv.style.background = '#ecfdf5';
                statusDiv.style.color = '#059669';
                setTimeout(() => processResult(decodedText), 800);
            })
            .catch(err => {
                statusDiv.innerHTML = '❌ Barcode/QR tidak terdeteksi di foto ini. Pastikan foto jelas dan barcode tidak terpotong.';
                statusDiv.style.background = '#fef2f2';
                statusDiv.style.color = '#dc2626';
                uploadInput.value = '';
                console.error('Scan file error:', err);
            });
    }

    // ==========================================
    // ALUR 2: KAMERA LANGSUNG
    // ==========================================
    function startCamera() {
        if (scannerStarted) return;
        if (!document.getElementById('event_id').value) {
            // Don't force alert on tab click, just start scanner
        }

        html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            {
                fps: 10,
                useBarCodeDetectorIfSupported: false, // HTTP mode — paksa ZXing murni
                rememberLastUsedCamera: true,
                // qrbox dinamis — menghindari error "qrbox lebih besar dari video"
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                    const boxWidth = Math.min(Math.round(viewfinderWidth * 0.85), 500);
                    const boxHeight = Math.min(Math.round(minEdge * 0.4), 160);
                    return { width: boxWidth, height: boxHeight };
                },
                videoConstraints: { width: { ideal: 640 }, height: { ideal: 480 } }
            },
            /* verbose= */ false
        );

        html5QrcodeScanner.render(
            (decodedText) => processResult(decodedText),
            (err) => { /* abaikan error per frame */ }
        );
        scannerStarted = true;
    }

    function stopCamera() {
        if (html5QrcodeScanner && scannerStarted) {
            html5QrcodeScanner.clear().catch(() => {});
            scannerStarted = false;
        }
    }

});
</script>
@endsection