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

            <!-- KONTROL SCANNER (OMNI-MODE & KONTRAS) -->
            <div style="display: flex; gap: 8px; margin-bottom: 16px;">
                <div style="flex: 2; padding: 12px; border-radius: 12px; background: #e0e7ff; color: #4f46e5; border: 1px solid #c7d2fe; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 14px;">
                    <i class="ph-bold ph-scan" style="margin-right: 8px; font-size: 18px;"></i> Omni-Scanner Aktif (QR & Barcode)
                </div>
                <button type="button" id="btnContrastToggle" class="btn btn-secondary" style="flex: 1; padding: 12px; border-radius: 12px; background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; font-weight: 600; font-size: 14px;">
                    <i class="ph-bold ph-aperture" style="margin-right: 4px;"></i> Hitam Putih
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
            <div id="reader-container" style="display: none; width: 100%; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; margin-bottom: 16px; background: black; position: relative;">
                
                <div id="video-wrapper" style="width: 100%; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <div id="reader" style="width: 100%; transition: transform 0.2s ease-in-out; transform-origin: center;"></div>
                </div>

                <div id="blur-tip" style="position: relative; z-index: 10; background: #1e293b; color: #f8fafc; padding: 12px 16px; font-size: 13px; text-align: center; border-top: 1px solid #334155; display: none;">
                    💡 <b>Tips:</b> Letakkan Barcode/QR tegak lurus di dalam kotak fokus, jarak 20-30cm.
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
class ScannerApp {
    constructor() {
        this.html5QrCode = null;
        this.currentMode = 'qr';
        this.currentCameraId = null;
        this.isScanning = false;
        this.isHighContrast = false;
        
        // DOM Elements
        this.els = {
            cameraSelect: document.getElementById('cameraSelect'),
            statusDiv: document.getElementById('cameraStatus'),
            refreshBtn: document.getElementById('btnRefreshCamera'),
            readerContainer: document.getElementById('reader-container'),
            reader: document.getElementById('reader'),
            btnStart: document.getElementById('btnStartScan'),
            btnStop: document.getElementById('btnStopScan'),
            eventSelect: document.getElementById('event_id'),
            blurTip: document.getElementById('blur-tip'),
            btnContrast: document.getElementById('btnContrastToggle'),
            nimInput: document.getElementById('nim'),
            scanForm: document.getElementById('scanForm'),
            uploadInput: document.getElementById('qr-input-file'),
            uploadStatus: document.getElementById('upload-status')
        };

        this.init();
    }

    init() {
        this.html5QrCode = new Html5Qrcode("reader");
        this.bindEvents();
        this.refreshCameraList();
        
        window.startCamera = () => this.startCamera();
        window.stopCamera = () => this.stopCamera();
        window.refreshCameraList = () => this.refreshCameraList();
    }

    bindEvents() {
        this.els.cameraSelect.addEventListener('change', (e) => {
            this.currentCameraId = e.target.value;
            if (this.isScanning) {
                this.stopCamera().then(() => this.startCamera());
            }
        });

        this.els.uploadInput.addEventListener('change', (e) => this.handleFileUpload(e));
        
        this.els.btnContrast.addEventListener('click', () => this.toggleContrast());
    }

    toggleContrast() {
        this.isHighContrast = !this.isHighContrast;
        const videoElement = document.querySelector('#reader video');
        
        if (this.isHighContrast) {
            this.els.btnContrast.className = 'btn btn-primary';
            this.els.btnContrast.style.background = '#4f46e5';
            this.els.btnContrast.style.color = '#fff';
            this.els.btnContrast.style.borderColor = 'transparent';
            if (videoElement) videoElement.style.filter = "grayscale(100%) contrast(150%)";
        } else {
            this.els.btnContrast.className = 'btn btn-secondary';
            this.els.btnContrast.style.background = '#f1f5f9';
            this.els.btnContrast.style.color = '#64748b';
            this.els.btnContrast.style.borderColor = '#e2e8f0';
            if (videoElement) videoElement.style.filter = "none";
        }
    }

    refreshCameraList() {
        this.els.cameraSelect.innerHTML = '<option value="">[ Meminta izin & mendeteksi kamera... ]</option>';
        if (this.els.statusDiv) this.els.statusDiv.style.display = 'none';
        if (this.els.refreshBtn) {
            this.els.refreshBtn.disabled = true;
            this.els.refreshBtn.style.opacity = '0.6';
        }

        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then((tempStream) => {
                tempStream.getTracks().forEach(track => track.stop());
                return Html5Qrcode.getCameras();
            })
            .then((devices) => {
                if (this.els.refreshBtn) {
                    this.els.refreshBtn.disabled = false;
                    this.els.refreshBtn.style.opacity = '1';
                }

                if (!devices || devices.length === 0) {
                    this.els.cameraSelect.innerHTML = '<option value="">Tidak ada kamera terdeteksi</option>';
                    this.showStatus('⚠️ Tidak ada kamera ditemukan. Pastikan kamera USB terpasang.', '#dc2626');
                    return;
                }

                this.els.cameraSelect.innerHTML = '';
                devices.forEach((device) => {
                    const option = document.createElement('option');
                    option.value = device.id;
                    option.text = device.label || ('Kamera ' + device.id.substring(0, 8));
                    this.els.cameraSelect.appendChild(option);
                });

                this.currentCameraId = devices[0].id;
                this.showStatus(`✅ ${devices.length} kamera terdeteksi.`, '#059669');
            })
            .catch((err) => {
                if (this.els.refreshBtn) {
                    this.els.refreshBtn.disabled = false;
                    this.els.refreshBtn.style.opacity = '1';
                }
                console.error('Error getting cameras: ', err);
                
                let msg = 'Akses kamera ditolak.';
                if (err.name === 'NotAllowedError') msg = '🔒 Izin kamera ditolak. Izinkan di address bar browser.';
                else if (err.name === 'NotFoundError') msg = '🔌 Kamera tidak ditemukan.';
                
                this.els.cameraSelect.innerHTML = '<option value="">Kamera tidak dapat diakses</option>';
                this.showStatus(msg, '#dc2626');
            });
    }

    showStatus(msg, color) {
        if (!this.els.statusDiv) return;
        this.els.statusDiv.innerHTML = msg;
        this.els.statusDiv.style.color = color;
        this.els.statusDiv.style.display = 'block';
    }

    startCamera() {
        if (!this.els.eventSelect.value) {
            alert("⚠️ PERHATIAN: Silakan Pilih Event Aktif terlebih dahulu di bagian atas!");
            this.els.eventSelect.focus();
            return;
        }

        if (this.els.cameraSelect.value) {
            this.currentCameraId = this.els.cameraSelect.value;
        }

        if (!this.currentCameraId) {
            alert("Pilih kamera terlebih dahulu!");
            return;
        }

        // Tampilkan container DULU
        this.els.readerContainer.style.display = 'block';
        this.els.blurTip.style.display = 'block';

        this.html5QrCode.start(
            this.currentCameraId,
            {
                fps: 20,
                useBarCodeDetectorIfSupported: false, // Wajib false untuk Windows
                disableFlip: false,
                // SOLUSI PAMUNGKAS: Paksa resolusi kamera ke VGA (640x480).
                // Ini mencegah bug "Fat Bar" di mana ZXing gagal membaca garis yang terlalu tebal akibat kamera HD/1080p.
                videoConstraints: {
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                }
                // HAPUS qrbox: Anda bebas men-scan dari jarak mana saja.
                // HAPUS formatsToSupport: Mendeteksi semua jenis barcode secara otomatis.
            },
            (decodedText) => this.onScanSuccess(decodedText),
            (errorMessage) => { /* Abaikan error tiap frame */ }
        ).then(() => {
            this.isScanning = true;
            this.updateUIStarted();
            
            // Re-apply filter jika aktif
            if (this.isHighContrast) {
                const videoElement = document.querySelector('#reader video');
                if (videoElement) videoElement.style.filter = "grayscale(100%) contrast(150%)";
            }
        }).catch((err) => {
            console.error("Error starting camera: ", err);
            alert("⚠️ Gagal memulai kamera. Pastikan tidak dipakai aplikasi lain.\nError: " + err);
            this.els.readerContainer.style.display = 'none';
            this.updateUIStopped();
        });
    }

    stopCamera() {
        return new Promise((resolve) => {
            if (this.html5QrCode && this.isScanning) {
                this.html5QrCode.stop().then(() => {
                    this.isScanning = false;
                    this.updateUIStopped();
                    resolve();
                }).catch((err) => {
                    console.error("Error stopping camera: ", err);
                    resolve();
                });
            } else {
                resolve();
            }
        });
    }

    onScanSuccess(decodedText) {
        if (this.isScanning) {
            this.stopCamera().then(() => this.processResult(decodedText));
        } else {
            this.processResult(decodedText);
        }
    }

    processResult(decodedText) {
        if (!this.els.eventSelect.value) {
            alert("⚠️ PERHATIAN: Silakan Pilih Event Aktif terlebih dahulu!");
            this.els.eventSelect.focus();
            return;
        }
        
        this.els.nimInput.value = decodedText.trim();
        this.els.nimInput.style.background = '#ecfdf5';
        this.els.nimInput.style.color = '#059669';
        this.els.scanForm.submit();
    }

    handleFileUpload(e) {
        if (e.target.files.length === 0) return;
        
        if (!this.els.eventSelect.value) {
            alert("⚠️ PERHATIAN: Silakan Pilih Event Aktif terlebih dahulu sebelum upload!");
            this.els.eventSelect.focus();
            e.target.value = '';
            return;
        }

        const imageFile = e.target.files[0];
        this.els.uploadStatus.style.display = 'none';

        if (this.isScanning) {
            this.stopCamera();
        }

        const fileScanner = new Html5Qrcode("hidden-reader");
        fileScanner.scanFile(imageFile, true)
            .then(decodedText => this.processResult(decodedText))
            .catch(() => {
                this.els.uploadStatus.style.display = 'block';
                this.els.uploadStatus.innerHTML = '<i class="ph-bold ph-warning-circle"></i> Barcode/QR tidak terdeteksi pada gambar ini.';
                e.target.value = '';
            });
    }

    updateUIStarted() {
        this.els.btnStart.style.display = 'none';
        this.els.btnStop.style.display = 'inline-flex';
        this.els.cameraSelect.disabled = true;
        this.els.eventSelect.disabled = true;
    }

    updateUIStopped() {
        this.els.btnStart.style.display = 'inline-flex';
        this.els.btnStop.style.display = 'none';
        this.els.readerContainer.style.display = 'none';
        this.els.cameraSelect.disabled = false;
        this.els.eventSelect.disabled = false;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    window.app = new ScannerApp();
});
</script>
@endsection