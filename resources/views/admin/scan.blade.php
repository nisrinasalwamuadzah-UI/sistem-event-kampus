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

        <form id="scanForm" action="{{ url('/admin/scan') }}" method="POST">
            @csrf
            
            <!-- PILIH EVENT (DIPINDAH KE ATAS AGAR MUDAH & WAJIB DIISI) -->
            <div class="form-group" style="background: #f8fafc; border: 2px solid #cbd5e1; border-radius: 16px; padding: 20px; margin-bottom: 24px;">
                <label style="font-size: 16px; font-weight: 700; color: #0f172a; display: block; margin-bottom: 10px;">
                    <i class="ph-bold ph-calendar-check" style="color: #4f46e5;"></i> 1. Pilih Event Aktif (Wajib)
                </label>
                <select name="event_id" id="event_id" class="form-control" required style="cursor: pointer; font-size: 15px; padding: 12px 16px; border-radius: 12px; background: white; border: 1px solid #94a3b8; width: 100%; font-weight: 600; color: #334155;">
                    <option value="">-- Silakan Pilih Event Terlebih Dahulu --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->nama_event }}</option>
                    @endforeach
                </select>
                <p style="color: #64748b; font-size: 13px; margin-top: 8px; margin-bottom: 0;">Pilih event di atas sebelum menyalakan kamera atau mengunggah gambar.</p>
            </div>

            <!-- MODE TOGGLE -->
            <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                <button type="button" id="btnModeQR" onclick="setMode('qr')" class="btn btn-primary" style="flex: 1; padding: 12px; border-radius: 12px;">
                    <i class="ph-bold ph-qr-code"></i> Mode QR Code
                </button>
                <button type="button" id="btnModeBarcode" onclick="setMode('barcode')" class="btn btn-secondary" style="flex: 1; padding: 12px; border-radius: 12px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;">
                    <i class="ph-bold ph-barcode"></i> Mode Barcode
                </button>
            </div>

            <!-- KONTROL PEMILIHAN KAMERA -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 16px;">
                <div class="form-group" style="margin-bottom: 8px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <label style="font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 0;"><i class="ph-bold ph-video-camera"></i> 2. Pilih Kamera Perangkat</label>
                        <button type="button" id="btnRefreshCamera" onclick="refreshCameraList()" style="background: none; border: 1px solid #cbd5e1; border-radius: 8px; padding: 5px 10px; font-size: 12px; color: #4f46e5; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-weight: 600;">
                            <i class="ph-bold ph-arrows-clockwise"></i> Refresh Kamera
                        </button>
                    </div>
                    <select id="cameraSelect" class="form-control" style="width: 100%; padding: 10px 16px; border-radius: 12px; cursor: pointer; background: white; border: 1px solid #cbd5e1;">
                        <option value="">[ Meminta izin kamera... ]</option>
                    </select>
                    <div id="cameraStatus" style="font-size: 12px; color: #64748b; margin-top: 6px; display: none;"></div>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" id="btnStartScan" onclick="startCamera()" class="btn btn-primary" style="flex: 1; padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="ph-bold ph-play"></i> Mulai Scan
                    </button>
                    <button type="button" id="btnStopScan" onclick="stopCamera()" class="btn btn-secondary" style="flex: 1; padding: 12px; border-radius: 12px; font-size: 14px; font-weight: 600; display: none; align-items: center; justify-content: center; gap: 8px;">
                        <i class="ph-bold ph-stop"></i> Berhenti Scan
                    </button>
                </div>
            </div>

            <!-- QR SCANNER -->
            <div id="reader-container" style="display: none; width: 100%; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 16px; background: black;">
                <div id="reader" style="width: 100%;"></div>
                <div id="blur-tip" style="background: #1e293b; color: #f8fafc; padding: 12px 16px; font-size: 13px; text-align: center; border-top: 1px solid #334155; display: none;">
                    💡 <b>Tips QR Code:</b> Jauhkan sedikit QR Code (sekitar 15-25cm) dari kamera agar gambar fokus & tajam (tidak blur).
                </div>
            </div>

            <!-- HIDDEN DIV FOR FILE SCANNING -->
            <div id="hidden-reader" style="position: absolute; top: -9999px; left: -9999px; visibility: hidden;"></div>

            <!-- UPLOAD GAMBAR MANUAL -->
            <div style="background:#f8fafc; padding:20px; border-radius:12px; border:2px dashed #cbd5e1; margin-bottom:24px; text-align: center;">
                <label style="display: block; margin-bottom:12px; font-weight: 500; color: #334155;">Atau Upload Screenshot Barcode/QR</label>
                <input type="file" id="qr-input-file" accept="image/*" style="border:none; padding:0; background:transparent; max-width: 100%;">
                <div id="upload-status" style="color: #ef4444; font-size: 13px; margin-top: 10px; display: none;"></div>
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
let html5QrCode = null;
let currentMode = 'qr';
let currentCameraId = null;
let isScanning = false;

// ============================================================
// FASE 1: Saat halaman dibuka, minta izin kamera terlebih
// dahulu via getUserMedia() sebelum memanggil getCameras().
// Ini adalah SATU-SATUNYA cara agar Chrome mengungkap SEMUA
// perangkat kamera (termasuk USB Camera eksternal) secara penuh.
// ============================================================
document.addEventListener("DOMContentLoaded", function() {
    html5QrCode = new Html5Qrcode("reader");
    refreshCameraList();
});

function refreshCameraList() {
    const cameraSelect = document.getElementById('cameraSelect');
    const statusDiv = document.getElementById('cameraStatus');
    const refreshBtn = document.getElementById('btnRefreshCamera');

    cameraSelect.innerHTML = '<option value="">[ Meminta izin & mendeteksi kamera... ]</option>';
    if (statusDiv) { statusDiv.style.display = 'none'; }
    if (refreshBtn) { refreshBtn.disabled = true; refreshBtn.style.opacity = '0.6'; }

    // FASE 1: Minta izin kamera ke browser dulu
    // Ini memicu dialog "Izinkan kamera?" dan wajib dilakukan
    // agar enumerateDevices() mengembalikan daftar LENGKAP.
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function(tempStream) {
            // Izin diberikan! Langsung matikan stream sementara ini —
            // kita tidak butuh videonya, hanya izinnya.
            tempStream.getTracks().forEach(track => track.stop());

            // FASE 2: Setelah izin ada, enumerate semua kamera.
            // Sekarang Chrome akan mengungkap SEMUA kamera termasuk USB.
            return Html5Qrcode.getCameras();
        })
        .then(function(devices) {
            if (refreshBtn) { refreshBtn.disabled = false; refreshBtn.style.opacity = '1'; }

            if (!devices || devices.length === 0) {
                cameraSelect.innerHTML = '<option value="">Tidak ada kamera terdeteksi</option>';
                if (statusDiv) {
                    statusDiv.innerHTML = '⚠️ Tidak ada kamera ditemukan. Pastikan kamera USB sudah terpasang dengan benar.';
                    statusDiv.style.color = '#dc2626';
                    statusDiv.style.display = 'block';
                }
                return;
            }

            // Isi dropdown dengan SEMUA kamera yang ditemukan
            cameraSelect.innerHTML = '';
            devices.forEach(function(device) {
                const option = document.createElement('option');
                option.value = device.id;
                option.text = device.label || ('Kamera ' + device.id.substring(0, 8));
                cameraSelect.appendChild(option);
            });

            // Set kamera aktif ke pilihan pertama di dropdown
            currentCameraId = devices[0].id;

            // Tampilkan status
            if (statusDiv) {
                statusDiv.innerHTML = '✅ ' + devices.length + ' kamera terdeteksi. Pilih kamera yang ingin digunakan.';
                statusDiv.style.color = '#059669';
                statusDiv.style.display = 'block';
            }

            // Event listener: sinkronisasi saat user ganti pilihan kamera
            cameraSelect.onchange = function() {
                currentCameraId = cameraSelect.value;
                if (isScanning) {
                    stopCamera().then(() => startCamera());
                }
            };
        })
        .catch(function(err) {
            if (refreshBtn) { refreshBtn.disabled = false; refreshBtn.style.opacity = '1'; }
            console.error('Gagal mendapatkan izin kamera atau mendeteksi perangkat: ', err);

            let pesanError = 'Akses kamera ditolak.';
            if (err.name === 'NotAllowedError') {
                pesanError = '🔒 Izin kamera ditolak. Klik ikon kunci (🔒) di address bar browser Anda, lalu izinkan akses kamera.';
            } else if (err.name === 'NotFoundError') {
                pesanError = '🔌 Tidak ada kamera yang ditemukan. Pastikan kamera USB sudah dicolokkan.';
            }

            cameraSelect.innerHTML = '<option value="">Kamera tidak dapat diakses</option>';
            if (statusDiv) {
                statusDiv.innerHTML = pesanError;
                statusDiv.style.color = '#dc2626';
                statusDiv.style.display = 'block';
            }
        });
}


function onScanSuccess(decodedText, decodedResult) {
    if (isScanning && html5QrCode) {
        html5QrCode.stop().then(() => {
            isScanning = false;
            updateUIStopped();
            processResult(decodedText);
        }).catch(err => console.error(err));
    } else {
        processResult(decodedText);
    }
}

function processResult(decodedText) {
    const eventSelect = document.getElementById('event_id');
    if (!eventSelect.value) {
        alert("⚠️ PERHATIAN: Silakan Pilih Event Aktif terlebih dahulu di bagian atas!");
        eventSelect.focus();
        return;
    }
    
    document.getElementById('nim').value = decodedText.trim();
    document.getElementById('nim').style.background = '#ecfdf5';
    document.getElementById('nim').style.color = '#059669';
    
    document.getElementById('scanForm').submit();
}

function startCamera() {
    const eventSelect = document.getElementById('event_id');
    if (!eventSelect.value) {
        alert("⚠️ PERHATIAN: Silakan Pilih Event Aktif terlebih dahulu di bagian atas sebelum menyalakan kamera!");
        eventSelect.focus();
        return;
    }

    // Pastikan currentCameraId mengambil value terbaru dari dropdown
    const cameraSelect = document.getElementById('cameraSelect');
    if (cameraSelect && cameraSelect.value) {
        currentCameraId = cameraSelect.value;
    }

    if (!currentCameraId) {
        alert("Pilih kamera terlebih dahulu!");
        return;
    }

    // Tampilkan container DULU agar offsetWidth bisa dibaca dengan benar
    document.getElementById('reader-container').style.display = 'block';
    let blurTip = document.getElementById('blur-tip');
    blurTip.style.display = 'block';
    if (currentMode === 'qr') {
        blurTip.innerHTML = '💡 <b>Tips QR Code:</b> Posisikan QR Code tegak lurus di depan kamera, jarak 30-50cm. Tangan jangan bergetar.';
    } else {
        blurTip.innerHTML = '💡 <b>Tips Barcode:</b> Posisikan barcode tegak lurus, jarak 30-50cm. Jangan terlalu dekat atau jauh.';
    }

    // Baca lebar SETELAH container visible agar nilai benar
    let containerWidth = document.getElementById('reader').offsetWidth;
    // Gunakan nilai fixed yang aman jika belum ter-render (fallback)
    let boxWidth = currentMode === 'qr' ? 250 : (containerWidth > 100 ? Math.min(Math.floor(containerWidth * 0.9), 420) : 300);
    let boxHeight = currentMode === 'qr' ? 250 : 150;

    // KONFIGURASI BERSIH: Hanya gunakan cameraId sebagai parameter pertama.
    // Tanpa videoConstraints (menyebabkan blur & error).
    // Tanpa formatsToSupport (membatasi format & gagal baca).
    // Tanpa useBarCodeDetectorIfSupported — gunakan ZXing yang jauh lebih robust
    // untuk kondisi gambar blur/low-light dibanding Chrome BarcodeDetector.
    html5QrCode.start(
        currentCameraId,
        {
            fps: 15,
            qrbox: { width: boxWidth, height: boxHeight }
        },
        onScanSuccess,
        (errorMessage) => {
            // Abaikan error background pemindaian normal (bukan error fatal)
        }
    ).then(() => {
        isScanning = true;
        document.getElementById('btnStartScan').style.display = 'none';
        document.getElementById('btnStopScan').style.display = 'inline-flex';
        document.getElementById('cameraSelect').disabled = true;
        eventSelect.disabled = true;
    }).catch(err => {
        console.error("Error starting camera: ", err);
        alert("⚠️ Gagal memulai kamera.\n\nTips:\n1. Cabut dan colokkan kembali kamera USB.\n2. Pastikan tidak ada aplikasi lain (Zoom/Meet) yang memakai kamera.\n3. Error: " + err);
        document.getElementById('reader-container').style.display = 'none';
        document.getElementById('cameraSelect').disabled = false;
        eventSelect.disabled = false;
    });
}

function stopCamera() {
    return new Promise((resolve, reject) => {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                updateUIStopped();
                resolve();
            }).catch(err => {
                console.error("Error stopping camera: ", err);
                reject(err);
            });
        } else {
            resolve();
        }
    });
}

function updateUIStopped() {
    document.getElementById('btnStartScan').style.display = 'inline-flex';
    document.getElementById('btnStopScan').style.display = 'none';
    document.getElementById('reader-container').style.display = 'none';
    document.getElementById('cameraSelect').disabled = false;
    document.getElementById('event_id').disabled = false; // Unlock event selection
}

function setMode(mode) {
    currentMode = mode;
    
    let btnQR = document.getElementById('btnModeQR');
    let btnBarcode = document.getElementById('btnModeBarcode');
    let blurTip = document.getElementById('blur-tip');
    
    if (mode === 'qr') {
        btnQR.className = 'btn btn-primary';
        btnQR.style.background = '#4f46e5';
        btnQR.style.color = '#fff';
        btnQR.style.border = '1px solid transparent';
        
        btnBarcode.className = 'btn btn-secondary';
        btnBarcode.style.background = '#f1f5f9';
        btnBarcode.style.color = '#64748b';
        btnBarcode.style.border = '1px solid #e2e8f0';
        if (blurTip) blurTip.innerHTML = '💡 <b>Tips QR Code:</b> Jauhkan sedikit QR Code (sekitar 15-25cm) dari kamera agar gambar fokus & tajam (tidak blur).';
    } else {
        btnBarcode.className = 'btn btn-primary';
        btnBarcode.style.background = '#4f46e5';
        btnBarcode.style.color = '#fff';
        btnBarcode.style.border = '1px solid transparent';
        
        btnQR.className = 'btn btn-secondary';
        btnQR.style.background = '#f1f5f9';
        btnQR.style.color = '#64748b';
        btnQR.style.border = '1px solid #e2e8f0';
        if (blurTip) blurTip.innerHTML = '💡 <b>Tips Barcode:</b> Jauhkan sedikit Barcode KTM (sekitar 15-25cm) dari kamera agar gambar fokus & tajam (tidak blur).';
    }

    if (isScanning) {
        stopCamera().then(() => {
            startCamera();
        });
    }
}

// LOGIKA UPLOAD GAMBAR
document.getElementById('qr-input-file').addEventListener('change', function(e) {
    if (e.target.files.length === 0) return;
    
    const eventSelect = document.getElementById('event_id');
    if (!eventSelect.value) {
        alert("⚠️ PERHATIAN: Silakan Pilih Event Aktif terlebih dahulu di bagian atas sebelum upload file!");
        eventSelect.focus();
        e.target.value = '';
        return;
    }

    const imageFile = e.target.files[0];
    const statusDiv = document.getElementById('upload-status');
    statusDiv.style.display = 'none';

    if (isScanning) {
        stopCamera();
    }

    const fileScanner = new Html5Qrcode("hidden-reader");
    fileScanner.scanFile(imageFile, true)
        .then(decodedText => {
            processResult(decodedText);
        })
        .catch(err => {
            statusDiv.style.display = 'block';
            statusDiv.innerHTML = '<i class="ph-bold ph-warning-circle"></i> Barcode/QR tidak terdeteksi pada gambar ini.';
            e.target.value = '';
        });
});
</script>
@endsection